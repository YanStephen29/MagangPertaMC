<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;

echo "Checking admin data:\n";
$admins = Admin::all();
foreach($admins as $admin) {
    echo "Username: {$admin->username} | Password: {$admin->password} | Role: {$admin->role}\n";
}

echo "\nTesting authentication manually:\n";
$testCredentials = [
    'username' => 'admin',
    'password' => 'admin123'
];

// Test manual authentication
use Illuminate\Support\Facades\Auth;

$admin = Admin::where('username', $testCredentials['username'])->first();
if ($admin) {
    echo "Admin found: {$admin->username}\n";
    echo "Stored password: {$admin->password}\n";
    echo "Input password: {$testCredentials['password']}\n";
    echo "Password match: " . ($admin->password === $testCredentials['password'] ? 'YES' : 'NO') . "\n";
} else {
    echo "Admin not found!\n";
}