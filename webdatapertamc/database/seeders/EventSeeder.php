<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'no_I/O' => 'IO-2024-001',
                'title' => 'Maintenance Pump Station A',
            ],
            [
                'no_I/O' => 'IO-2024-002',
                'title' => 'Installation New Pipeline B',
            ],
            [
                'no_I/O' => 'IO-2024-003',
                'title' => 'Refinery Tank Inspection',
            ],
        ];

        foreach ($events as $eventData) {
            event::create($eventData);
        }
    }
}
