<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php'; 
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;

echo "=== TESTING REQUEST UPDATE ENDPOINT ===\n\n";

try {
    // Get a request to test
    $request = Request::first();
    if (!$request) {
        echo "No request found to test.\n";
        exit;
    }
    
    echo "Testing Request ID: {$request->id_req}\n";
    echo "Current Status: {$request->status_req}\n";
    
    // Prepare test data
    $testData = [
        'type_surat' => $request->type_surat,
        'jenis_req' => $request->jenis_req,
        'no_surat' => $request->no_surat,
        'date_req' => $request->date_req->format('Y-m-d'),
        'status_req' => 'approved' // Change to approved
    ];
    
    echo "\nTest Update Data:\n";
    foreach ($testData as $key => $value) {
        echo "  {$key}: {$value}\n";
    }
    
    // Create a fake HTTP request to test validation
    $httpRequest = new \Illuminate\Http\Request();
    $httpRequest->merge($testData);
    $httpRequest->headers->set('Accept', 'application/json');
    $httpRequest->headers->set('Content-Type', 'application/json');
    
    // Test controller method directly
    $controller = new \App\Http\Controllers\RequestController();
    
    echo "\nTesting controller update method...\n";
    
    $originalStatus = $request->status_req;
    
    try {
        $response = $controller->update($httpRequest, $request->id_req);
        
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);
            echo "✅ JSON Response received:\n";
            echo "  Success: " . ($data['success'] ?? 'null') . "\n";
            
            // Check if request was actually updated
            $request->refresh();
            echo "  New Status: {$request->status_req}\n";
            
            if ($request->status_req === 'approved') {
                echo "✅ Status update SUCCESSFUL!\n";
            } else {
                echo "❌ Status NOT updated in database.\n";
            }
            
        } else {
            echo "❌ Non-JSON response received.\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Controller error: " . $e->getMessage() . "\n";
    }
    
    // Restore original status
    $request->update(['status_req' => $originalStatus]);
    echo "\nRestored original status: {$originalStatus}\n";
    
    echo "\n✅ Update endpoint test completed!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}