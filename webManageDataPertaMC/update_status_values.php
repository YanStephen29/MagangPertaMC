<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING CURRENT STATUS VALUES ===\n\n";

try {
    $statuses = DB::table('requests')->select('status_req')->distinct()->get();
    
    echo "Current status_req values:\n";
    foreach ($statuses as $status) {
        echo "- " . $status->status_req . "\n";
    }
    
    echo "\n=== MAPPING TO NEW STATUS VALUES ===\n";
    
    // Map old values to new values
    $statusMapping = [
        'hold' => 'Pending',
        'approved' => 'On Process', 
        'rejected' => 'Closed',
        'On Proses' => 'On Process',
        'Closed' => 'Closed'
    ];
    
    foreach ($statusMapping as $oldStatus => $newStatus) {
        $count = DB::table('requests')->where('status_req', $oldStatus)->count();
        if ($count > 0) {
            echo "Will change '$oldStatus' ($count records) → '$newStatus'\n";
        }
    }
    
    echo "\n=== UPDATING STATUS VALUES ===\n";
    
    foreach ($statusMapping as $oldStatus => $newStatus) {
        $updated = DB::table('requests')
            ->where('status_req', $oldStatus)
            ->update(['status_req' => $newStatus]);
            
        if ($updated > 0) {
            echo "✅ Updated $updated records from '$oldStatus' to '$newStatus'\n";
        }
    }
    
    echo "\n✅ Status mapping completed!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}