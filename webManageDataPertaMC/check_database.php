<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CHECKING DATABASE DIRECTLY ===\n\n";

try {
    // 1. Check all requests and their current status
    echo "=== ALL REQUESTS STATUS ===\n";
    $requests = DB::table('requests')->select('id_req', 'status_req', 'no_surat', 'date_req')->get();
    
    foreach ($requests as $request) {
        echo "Request {$request->id_req}: {$request->no_surat} - Status: {$request->status_req} ({$request->date_req})\n";
    }
    
    // 2. Check tools and their requests
    echo "\n=== TOOLS WITH REQUEST STATUS ===\n";
    $tools = DB::table('tools')
        ->leftJoin('requests', 'tools.request_id', '=', 'requests.id_req')
        ->select('tools.idTools', 'tools.Description', 'tools.request_id', 'requests.status_req', 'requests.no_surat')
        ->get();
        
    foreach ($tools as $tool) {
        if ($tool->request_id) {
            echo "Tool {$tool->idTools}: {$tool->Description}\n";
            echo "  → Request {$tool->request_id} ({$tool->no_surat}): {$tool->status_req}\n\n";
        } else {
            echo "Tool {$tool->idTools}: {$tool->Description} → No Request\n\n";
        }
    }
    
    // 3. Check specific request that might be causing issues
    echo "=== RECENT REQUEST UPDATES ===\n";
    $recentRequests = DB::table('requests')
        ->where('updated_at', '>=', now()->subHours(2))
        ->select('id_req', 'status_req', 'no_surat', 'updated_at')
        ->get();
        
    if ($recentRequests->count() > 0) {
        foreach ($recentRequests as $request) {
            echo "Recently Updated Request {$request->id_req}: {$request->status_req} at {$request->updated_at}\n";
        }
    } else {
        echo "No requests updated in the last 2 hours.\n";
    }
    
    echo "\n✅ Database check completed!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}