<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;

class FixPmPrivileges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pm:fix-privileges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix PM privileges to ensure all necessary permissions are granted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing PM privileges...');
        
        // Define complete PM privileges
        $pmPrivileges = [
            // Project Management
            'project_view',
            'project_add', 
            'project_edit',
            'project_delete',
            'project_assign',
            
            // Tools Management  
            'tools_view',
            'tools_add',
            'tools_edit',
            'tools_delete',
            
            // Kode Bidang Management
            'kode_bidang_view',
            
            // Document Management
            'document_view',
            'document_edit',
            
            // BOQ Management
            'boq_view', 
            'boq_add',
            'boq_edit',
            'boq_delete',
            'boq_assign',
            
            // BOQ Section Management
            'boq_section_view',
            'boq_section_add',
            'boq_section_edit',
            'boq_section_delete',
            
            // BOQ Detail Management
            'boq_detail_view',
            'boq_detail_add', 
            'boq_detail_edit',
            'boq_detail_delete',
        ];
        
        $pms = Admin::where('role', 'Project Manager')->get();
        
        foreach ($pms as $pm) {
            $pm->update(['privilege' => $pmPrivileges]);
            $this->info("Updated privileges for PM: {$pm->username}");
        }
        
        $this->info('PM privileges fixed successfully!');
        return 0;
    }
}
