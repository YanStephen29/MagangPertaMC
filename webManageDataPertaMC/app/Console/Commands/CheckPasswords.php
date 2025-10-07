<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class CheckPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:check-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check admin passwords in database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = Admin::select('admin_id', 'username', 'password')->get();
        
        $this->info('Current admin passwords in database:');
        $this->info(str_repeat('-', 50));
        
        foreach ($admins as $admin) {
            $password = $admin->password;
            $isHashed = strlen($password) === 60 && str_starts_with($password, '$2y$');
            
            $this->line("Username: {$admin->username}");
            $this->line("Password: {$password}");
            $this->line("Is Hashed: " . ($isHashed ? 'YES (bcrypt)' : 'NO (plaintext)'));
            $this->line(str_repeat('-', 30));
        }
        
        return 0;
    }
}
