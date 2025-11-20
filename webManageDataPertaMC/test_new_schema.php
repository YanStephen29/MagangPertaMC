<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;
use App\Models\Tool;

echo "=== TESTING NEW DATABASE SCHEMA ===\n\n";

try {
    // Test Request model with new enum
    echo "1. Testing Request model with new status enum:\n";
    $request = Request::first();
    if ($request) {
        echo "   Request ID: {$request->id_req}\n";
        echo "   Status: {$request->status_req}\n";
        echo "   Status Display: {$request->getStatusDisplay()}\n";
        echo "   Status Color: {$request->getStatusColor()}\n\n";
    }
    
    // Test Tool model with new status_tools column
    echo "2. Testing Tool model with new status_tools column:\n";
    $tool = Tool::first();
    if ($tool) {
        echo "   Tool ID: {$tool->idTools}\n";
        echo "   Description: {$tool->Description}\n";
        echo "   Status Tools: {$tool->status_tools}\n";
        echo "   Status Tools Display: {$tool->getStatusToolsDisplay()}\n";
        echo "   Status Tools Color: {$tool->getStatusToolsColor()}\n";
        echo "   Needs Approval: " . ($tool->needsApproval() ? 'Yes' : 'No') . "\n\n";
    }
    
    // Test updating existing tool status
    echo "3. Testing updating tool status to Hold:\n";
    $existingTool = Tool::first();
    if ($existingTool) {
        $originalStatus = $existingTool->status_tools;
        $existingTool->update(['status_tools' => 'Hold']);
        $existingTool->refresh();
        
        echo "   Tool ID: {$existingTool->idTools}\n";
        echo "   Updated Status: {$existingTool->status_tools}\n";
        echo "   Needs Approval: " . ($existingTool->needsApproval() ? 'Yes' : 'No') . "\n";
        
        // Restore original status
        $existingTool->update(['status_tools' => $originalStatus]);
        echo "   Status restored to: {$originalStatus}\n\n";
    }
    
    // Test Request status constants
    echo "4. Testing Request status constants:\n";
    foreach (Request::STATUS_REQ_OPTIONS as $status) {
        echo "   - {$status}\n";
    }
    
    echo "\n5. Testing Tool status constants:\n";
    foreach (Tool::STATUS_TOOLS_OPTIONS as $status) {
        echo "   - {$status}\n";
    }
    
    echo "\n✅ All tests passed! New schema is working correctly.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}