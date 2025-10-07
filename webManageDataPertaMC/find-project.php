<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tool;

echo "Finding project with completed document tools:\n";
$tool = Tool::where('no_document', '003/JAE56015/VIII/2023')->first();
if($tool) {
    echo "Project no_IO: " . $tool->no_IO . "\n";
    echo "Tool ID: " . $tool->idTools . "\n";
    echo "Tool Description: " . $tool->Description . "\n";
}