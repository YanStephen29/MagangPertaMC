<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class ResetPmPrivileges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pm:reset-privileges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset PM privileges to full BOQ management (for testing)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting PM privileges...');
        
        $pms = Admin::where('role', 'Project Manager')->get();
        $updated = 0;
        
        foreach ($pms as $pm) {
            // Full PM privileges
            $fullPrivileges = [
                'project_view', 'project_edit', 'project_assign',
                'tools_view', 'tools_add', 'tools_edit', 'tools_delete',
                'document_view', 'document_edit',
                'boq_view', 'boq_add', 'boq_edit', 'boq_delete', 'boq_assign',
                'boq_section_view', 'boq_section_add', 'boq_section_edit', 'boq_section_delete',
                'boq_detail_view', 'boq_detail_add', 'boq_detail_edit', 'boq_detail_delete'
            ];
            
            $pm->update(['privilege' => $fullPrivileges]);
            $updated++;
            $this->line("Reset privileges for PM: {$pm->username}");
        }
        
        $this->info("Successfully reset privileges for {$updated} PM(s).");
        
        return 0;
    }
}
