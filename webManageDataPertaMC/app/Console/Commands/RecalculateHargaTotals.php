<?php

namespace App\Console\Commands;

use App\Models\Detail;
use App\Models\Section;
use App\Models\Boq;
use Illuminate\Console\Command;

class RecalculateHargaTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boq:recalculate-totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all harga_total based on hierarchical children';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting recalculation of harga_total for all details...');
        
        // Get all details
        $allDetails = Detail::all();
        $this->info("Found {$allDetails->count()} details to process");
        
        // First, update all leaf details (no children)
        $leafDetails = Detail::whereDoesntHave('children')->get();
        $this->info("Updating {$leafDetails->count()} leaf details...");
        
        foreach ($leafDetails as $detail) {
            $newTotal = $detail->quantity * $detail->harga_satuan;
            if ($detail->harga_total != $newTotal) {
                $detail->timestamps = false;
                $detail->harga_total = $newTotal;
                $detail->save();
                $detail->timestamps = true;
            }
        }
        
        // Then, update parent details from bottom to top
        $this->info("Updating parent details...");
        $processedCount = 0;
        
        do {
            $updated = false;
            $parentDetails = Detail::whereHas('children')->get();
            
            foreach ($parentDetails as $detail) {
                $calculatedTotal = $detail->getTotalHargaWithChildren();
                
                if ($detail->harga_total != $calculatedTotal) {
                    $detail->timestamps = false;
                    $detail->harga_total = $calculatedTotal;
                    $detail->save();
                    $detail->timestamps = true;
                    $updated = true;
                    $processedCount++;
                }
            }
        } while ($updated);
        
        $this->info("Updated $processedCount parent details");
        
        // Update section totals
        $sections = Section::all();
        $this->info("Updating {$sections->count()} sections...");
        
        foreach ($sections as $section) {
            $section->updateTotalHarga();
        }
        
        // Update BOQ totals
        $boqs = Boq::all();
        $this->info("Updating {$boqs->count()} BOQs...");
        
        foreach ($boqs as $boq) {
            $boq->updateTotalHarga();
        }
        
        $this->info('✅ Recalculation completed successfully!');
        
        return Command::SUCCESS;
    }
}