<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request;
use App\Models\Tool;
use Carbon\Carbon;

class OrphanedToolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get orphaned tools (tools without request_id)
        $orphanedTools = Tool::whereNull('request_id')->get();
        
        if ($orphanedTools->isEmpty()) {
            $this->command->info('No orphaned tools found.');
            return;
        }
        
        $this->command->info("Found {$orphanedTools->count()} orphaned tools. Creating requests for them...");
        
        foreach ($orphanedTools as $tool) {
            // Create a new request for each orphaned tool
            $request = Request::create([
                'type_surat' => 'SPS',
                'jenis_req' => 'PO',
                'no_surat' => 'REQ-' . Carbon::now()->format('YmdHis') . '-' . $tool->idTools,
                'date_req' => Carbon::now()->toDateString(),
                'status_req' => 'approved',
                'approved_by' => 'System Migration',
                'approved_at' => Carbon::now(),
                'approval_notes' => 'Auto-created for orphaned tool: ' . $tool->Description
            ]);
            
            // Assign the tool to the new request
            $tool->request_id = $request->id_req;
            $tool->save();
            
            $this->command->info("Created request {$request->no_surat} for tool: {$tool->Description}");
        }
        
        $this->command->info('Successfully migrated all orphaned tools.');
    }
}