<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Request;
use App\Models\Tool;
use App\Models\Document;

echo "Testing problematic queries...\n";

try {
    // Test the query that was failing before
    echo "1. Testing Request with tools...\n";
    $requests = Request::with('tools')->get();
    echo "   Found " . $requests->count() . " requests\n";
    
    foreach ($requests->take(3) as $request) {
        echo "   Request {$request->id_req}: {$request->tools->count()} tools\n";
    }
    
    echo "\n2. Testing Tool with request...\n";
    $tools = Tool::with('request')->get();
    echo "   Found " . $tools->count() . " tools\n";
    
    foreach ($tools->take(3) as $tool) {
        if ($tool->request) {
            echo "   Tool {$tool->idTools}: belongs to request {$tool->request->id_req}\n";
        } else {
            echo "   Tool {$tool->idTools}: no request\n";
        }
    }
    
    echo "\n3. Testing Document relationship (the problematic one)...\n";
    $documents = Document::with('request')->get();
    echo "   Found " . $documents->count() . " documents\n";
    
    foreach ($documents->take(3) as $document) {
        if ($document->request) {
            echo "   Document {$document->no_request}: has request {$document->request->id_req}\n";
        } else {
            echo "   Document {$document->no_request}: no request\n";
        }
    }
    
    echo "\n4. Testing the query that might cause error...\n";
    // This might be the problematic query from dashboard
    $projects = \App\Models\Project::with(['tools.request'])->get();
    echo "   Found " . $projects->count() . " projects\n";
    
    foreach ($projects->take(3) as $project) {
        echo "   Project {$project->nama_project}: {$project->tools->count()} tools\n";
    }
    
    echo "\nAll tests passed! The relationships are working correctly.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}