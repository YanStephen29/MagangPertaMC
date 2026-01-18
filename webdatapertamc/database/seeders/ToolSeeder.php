<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\tools;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toolsData = [
            [
                'description' => 'Centrifugal Pump 50HP',
                'quantity' => 2,
                'unit' => 'pcs',
                'deliveryDate' => '2024-03-15',
                'remarks' => 'For pump station maintenance',
                'event_no_I/O' => 'IO-2024-001',
                'Document_no_request' => 'MR-2024-001',
                'Bidang_kodeGl' => 1003,
            ],
            [
                'description' => 'Steel Pipe 12 inch',
                'quantity' => 100,
                'unit' => 'm',
                'deliveryDate' => '2024-04-01',
                'remarks' => 'For new pipeline installation',
                'event_no_I/O' => 'IO-2024-002',
                'Document_no_request' => 'MR-2024-002',
                'Bidang_kodeGl' => 1001,
            ],
            [
                'description' => 'Inspection Camera',
                'quantity' => 1,
                'unit' => 'pcs',
                'deliveryDate' => '2024-04-10',
                'remarks' => 'For tank internal inspection',
                'event_no_I/O' => 'IO-2024-003',
                'Document_no_request' => 'AST-2024-001',
                'Bidang_kodeGl' => 1002,
            ],
            [
                'description' => 'Safety Harness',
                'quantity' => 5,
                'unit' => 'pcs',
                'deliveryDate' => '2024-03-20',
                'remarks' => 'For worker safety during maintenance',
                'event_no_I/O' => 'IO-2024-001',
                'Document_no_request' => 'SR-2024-001',
                'Bidang_kodeGl' => 1004,
            ],
            [
                'description' => 'Welding Machine',
                'quantity' => 1,
                'unit' => 'pcs',
                'deliveryDate' => '2024-03-25',
                'remarks' => 'For pipeline welding work',
                'event_no_I/O' => 'IO-2024-002',
                'Document_no_request' => 'FR-2024-001',
                'Bidang_kodeGl' => 1001,
            ],
        ];

        foreach ($toolsData as $toolData) {
            tools::create($toolData);
        }
    }
}
