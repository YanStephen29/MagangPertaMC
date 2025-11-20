<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tool;

echo "=== TESTING HOLD REQUESTS CONTROLLER LOGIC ===\n\n";

try {
    // Test holdRequests method logic
    echo "1. Testing holdRequests method logic:\n";
    
    $holdTools = Tool::where('status_tools', 'Hold')
        ->with(['project', 'request.requestDetails.detail'])
        ->orderBy('created_at', 'desc')
        ->get();
        
    echo "   Found {$holdTools->count()} tools with Hold status\n";
    
    if ($holdTools->count() > 0) {
        foreach ($holdTools as $tool) {
            echo "   - Tool {$tool->idTools}: {$tool->Description} (Status: {$tool->status_tools})\n";
            if ($tool->project) {
                echo "     Project: {$tool->project->title_project}\n";
            }
            if ($tool->request) {
                echo "     Request: {$tool->request->no_surat}\n";
            }
        }
    } else {
        echo "   No hold tools found. Let's create one for testing...\n";
        
        // Find a tool to set as Hold
        $testTool = Tool::first();
        if ($testTool) {
            $originalStatus = $testTool->status_tools;
            $testTool->update(['status_tools' => 'Hold']);
            
            echo "   Set Tool {$testTool->idTools} to Hold status\n";
            
            // Test again
            $holdTools = Tool::where('status_tools', 'Hold')
                ->with(['project', 'request.requestDetails.detail'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            echo "   Now found {$holdTools->count()} tools with Hold status\n";
            
            // Restore original status
            $testTool->update(['status_tools' => $originalStatus]);
            echo "   Restored original status: {$originalStatus}\n";
        }
    }
    
    echo "\n2. Testing Tool approval methods:\n";
    $testTool = Tool::first();
    if ($testTool) {
        echo "   Testing Tool ID: {$testTool->idTools}\n";
        echo "   Original Status: {$testTool->status_tools}\n";
        echo "   Needs Approval: " . ($testTool->needsApproval() ? 'Yes' : 'No') . "\n";
        
        // Test setting to Hold
        $testTool->update(['status_tools' => 'Hold']);
        echo "   After setting to Hold - Needs Approval: " . ($testTool->needsApproval() ? 'Yes' : 'No') . "\n";
        
        // Test approve method (using admin_id = 1 if exists)
        $adminId = \App\Models\Admin::first()->admin_id ?? 1;
        echo "   Testing approve with admin ID: {$adminId}\n";
        
        try {
            $testTool->approve($adminId, 'Test approval');
            echo "   ✅ Approve method works - Status: {$testTool->status_tools}\n";
        } catch (Exception $e) {
            echo "   ❌ Approve method error: " . $e->getMessage() . "\n";
        }
        
        // Test reject method
        $testTool->update(['status_tools' => 'Hold']);
        try {
            $testTool->reject($adminId, 'Test rejection');
            echo "   ✅ Reject method works - Status: {$testTool->status_tools}\n";
        } catch (Exception $e) {
            echo "   ❌ Reject method error: " . $e->getMessage() . "\n";
        }
        
        // Restore to On Process
        $testTool->update(['status_tools' => 'On Process']);
        echo "   Restored to On Process\n";
    }
    
    echo "\n✅ Hold requests controller logic test completed!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}