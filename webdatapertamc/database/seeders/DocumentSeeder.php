<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'no_request' => 'MR-2024-001',
                'jenis_request' => 'Material Request',
                'date_issue' => '2024-01-15'
            ],
            [
                'no_request' => 'SR-2024-001',
                'jenis_request' => 'Service Request',
                'date_issue' => '2024-01-20'
            ],
            [
                'no_request' => 'FR-2024-001',
                'jenis_request' => 'Facility Request',
                'date_issue' => '2024-02-10'
            ],
            [
                'no_request' => 'AST-2024-001',
                'jenis_request' => 'Aset',
                'date_issue' => '2024-02-15'
            ],
            [
                'no_request' => 'MR-2024-002',
                'jenis_request' => 'Material Request',
                'date_issue' => '2024-03-01'
            ],
        ];

        foreach ($documents as $documentData) {
            document::create($documentData);
        }
    }
}
