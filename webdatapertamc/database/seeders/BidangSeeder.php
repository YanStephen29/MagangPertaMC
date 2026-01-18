<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\bidang;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangs = [
            ['kodeGl' => 1001, 'nama_Bidang' => 'Engineering'],
            ['kodeGl' => 1002, 'nama_Bidang' => 'Operations'],
            ['kodeGl' => 1003, 'nama_Bidang' => 'Maintenance'],
            ['kodeGl' => 1004, 'nama_Bidang' => 'HSE'],
            ['kodeGl' => 1005, 'nama_Bidang' => 'HR & GA'],
        ];

        foreach ($bidangs as $bidangData) {
            bidang::create($bidangData);
        }
    }
}
