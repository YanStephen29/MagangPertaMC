<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class ConvertPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:convert-passwords {--default=123456}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert hashed passwords to plaintext with default password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultPassword = $this->option('default');
        
        $admins = Admin::all();
        
        $this->info("Converting hashed passwords to plaintext...");
        $this->info("Default password: {$defaultPassword}");
        $this->info(str_repeat('-', 50));
        
        foreach ($admins as $admin) {
            $isHashed = strlen($admin->password) === 60 && str_starts_with($admin->password, '$2y$');
            
            if ($isHashed) {
                $admin->password = $defaultPassword;
                $admin->save();
                
                $this->line("✓ {$admin->username}: Converted from hashed to plaintext");
            } else {
                $this->line("- {$admin->username}: Already plaintext");
            }
        }
        
        $this->info("\n✅ Password conversion completed!");
        
        return 0;
    }
}
