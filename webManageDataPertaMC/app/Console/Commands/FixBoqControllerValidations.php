<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixBoqControllerValidations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:boq-validations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix all BoqController validations to use new canAccessBOQ method';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing BoqController validations...');
        
        $filePath = app_path('Http/Controllers/BoqController.php');
        $content = file_get_contents($filePath);
        
        // Replace old validation with new method
        $oldPattern = "if (\$admin->role === 'Project Manager' && \$project->assigned_to !== \$admin->username) {";
        $newPattern = "if (!\$project->canAccessBOQ(\$admin)) {";
        
        $newContent = str_replace($oldPattern, $newPattern, $content);
        
        file_put_contents($filePath, $newContent);
        
        $this->info('BoqController validations fixed successfully!');
        return 0;
    }
}
