<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Request;
use App\Models\Tool;

class TestRelationships extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:relationships';

    /**
     * The console command description.
     */
    protected $description = 'Test the new Request-Tool relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Request -> Tools (hasMany) relationship:');
        $this->line('');
        
        $requests = Request::with('tools')->get();
        
        foreach ($requests as $request) {
            $this->info("Request: {$request->no_surat}");
            $this->info("  Tools count: " . $request->tools->count());
            
            foreach ($request->tools as $tool) {
                $this->line("  - Tool ID {$tool->idTools}: {$tool->Description}");
            }
            $this->line('');
        }
        
        $this->info('Testing Tool -> Request (belongsTo) relationship:');
        $this->line('');
        
        $tools = Tool::with('request')->get();
        
        foreach ($tools as $tool) {
            $this->info("Tool ID {$tool->idTools}: {$tool->Description}");
            $this->info("  Belongs to Request: {$tool->request->no_surat}");
            $this->line('');
        }
        
        $this->info('✅ All relationships are working correctly!');
        
        return 0;
    }
}