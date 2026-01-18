<?php
/*
 * Test script for No I/O update functionality
 * Run this script to test if the EventController update method works correctly
 */

// Include Laravel's bootstrap
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\event;
use App\Models\tools;
use Illuminate\Support\Facades\DB;

try {
    echo "Testing No I/O update functionality...\n";
    
    // First, create a test event
    $testEvent = event::create([
        'no_I/O' => 'TEST-001',
        'title' => 'Test Event for No I/O Update'
    ]);
    
    echo "Created test event: " . $testEvent->{'no_I/O'} . "\n";
    
    // Create a test tool linked to this event
    $testTool = tools::create([
        'description' => 'Test Tool',
        'quantity' => 1,
        'unit' => 'pcs',
        'deliveryDate' => '2025-12-31',
        'remarks' => 'Test remarks',
        'event_no_I/O' => 'TEST-001',
        'Document_no_request' => 'DOC-001',
        'Bidang_kodeGl' => 1
    ]);
    
    echo "Created test tool linked to event\n";
    
    // Now test the update functionality
    DB::transaction(function () use ($testEvent) {
        $oldNoIO = 'TEST-001';
        $newNoIO = 'TEST-002';
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Update the event record with new No I/O
        DB::table('events')
            ->where('no_I/O', $oldNoIO)
            ->update([
                'no_I/O' => $newNoIO,
                'title' => 'Updated Test Event',
                'updated_at' => now(),
            ]);
        
        // Update all related tools to use the new event No I/O
        DB::table('tools')
            ->where('event_no_I/O', $oldNoIO)
            ->update(['event_no_I/O' => $newNoIO]);
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "No I/O update test completed successfully!\n";
    });
    
    // Verify the update worked
    $updatedEvent = event::where('no_I/O', 'TEST-002')->first();
    $updatedTool = tools::where('event_no_I/O', 'TEST-002')->first();
    
    if ($updatedEvent && $updatedTool) {
        echo "SUCCESS: Event and tools updated correctly\n";
        echo "Event No I/O: " . $updatedEvent->{'no_I/O'} . "\n";
        echo "Tool linked to: " . $updatedTool->{'event_no_I/O'} . "\n";
    } else {
        echo "ERROR: Update failed\n";
    }
    
    // Clean up test data
    if ($updatedEvent) $updatedEvent->delete();
    if ($updatedTool) $updatedTool->delete();
    
    echo "Test completed and cleaned up.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    
    // Clean up in case of error
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    event::where('no_I/O', 'like', 'TEST-%')->delete();
    tools::where('event_no_I/O', 'like', 'TEST-%')->delete();
}
