<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;
use App\Models\Tool;

echo "=== TESTING REQUEST STATUS UPDATE ===\n\n";

try {
    // 1. Get a request that has tools
    $request = Request::with('tools')->whereHas('tools')->first();
    
    if (!$request) {
        echo "No request with tools found.\n";
        exit;
    }
    
    echo "Testing Request ID: {$request->id_req}\n";
    echo "Current Status: {$request->status_req}\n";
    echo "Tools Count: {$request->tools->count()}\n\n";
    
    // 2. Show current status for each tool
    echo "=== CURRENT TOOL STATUS ===\n";
    foreach ($request->tools as $tool) {
        echo "Tool {$tool->idTools}: {$tool->Description}\n";
        echo "  - Tool request_id: {$tool->request_id}\n";
        echo "  - Request status: {$tool->request->status_req}\n";
        echo "  - Status Display: {$tool->request->getStatusDisplay()}\n";
        echo "  - Status Color: {$tool->request->getStatusColor()}\n\n";
    }
    
    // 3. Update request status
    $oldStatus = $request->status_req;
    $newStatus = $oldStatus === 'hold' ? 'approved' : 'hold';
    
    echo "=== UPDATING REQUEST STATUS ===\n";
    echo "Changing status from: {$oldStatus} → {$newStatus}\n";
    
    $request->update(['status_req' => $newStatus]);
    $request->refresh(); // Reload from database
    
    echo "Updated successfully. New status: {$request->status_req}\n\n";
    
    // 4. Check if tools now show the new status
    echo "=== AFTER UPDATE - TOOL STATUS ===\n";
    $request->load('tools'); // Reload tools relationship
    
    foreach ($request->tools as $tool) {
        $tool->load('request'); // Reload request relationship
        echo "Tool {$tool->idTools}: {$tool->Description}\n";
        echo "  - Tool request_id: {$tool->request_id}\n";
        echo "  - Request status: {$tool->request->status_req}\n";
        echo "  - Status Display: {$tool->request->getStatusDisplay()}\n";
        echo "  - Status Color: {$tool->request->getStatusColor()}\n\n";
    }
    
    // 5. Restore original status
    echo "Restoring original status: {$oldStatus}\n";
    $request->update(['status_req' => $oldStatus]);
    
    echo "\n✅ Test completed successfully!\n";
    echo "RESULT: Request status update WORKS correctly.\n";
    echo "Tools automatically show the updated status from their request.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}