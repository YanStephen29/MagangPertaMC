<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Boq;

class UpdateBoqStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boq:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update BOQ status based on existing sections and details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating BOQ status...');
        
        $boqs = Boq::with('sections')->get();
        $updated = 0;
        
        foreach ($boqs as $boq) {
            if ($boq->status === 'Pending' && $boq->sections->count() > 0) {
                $boq->update(['status' => 'Open']);
                $updated++;
                $this->line("Updated BOQ {$boq->nomorBoq} from Pending to Open");
            }
        }
        
        $this->info("Successfully updated {$updated} BOQ(s) status.");
        
        return 0;
    }
}
