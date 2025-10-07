<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Tahapan;

class AddDefaultTahapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua dokumen yang belum memiliki tahapan sama sekali
        $documentsWithoutTahapan = Document::whereDoesntHave('tahapans')->get();
        
        echo "Found " . $documentsWithoutTahapan->count() . " documents without tahapan.\n";
        
        foreach ($documentsWithoutTahapan as $document) {
            // Buat tahapan default "BELUM DI PROSES" dengan Date_Tahapan = null (0% progress)
            Tahapan::create([
                'no_request' => $document->no_request,
                'namaTahapan' => 'BELUM DI PROSES',
                'Date_Tahapan' => null // null berarti belum selesai, jadi progress 0%
            ]);
            
            echo "Added default tahapan for document: " . $document->no_request . "\n";
        }
        
        echo "Completed adding default tahapan to existing documents.\n";
    }
}