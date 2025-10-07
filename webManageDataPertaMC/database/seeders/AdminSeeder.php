<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'username' => 'admin',
                'password' => bcrypt('admin123'),
                'role' => 'Admin',
                'privilege' => array_keys(Admin::PRIVILEGES), // All privileges
            ],
            [
                'username' => 'vp001',
                'password' => bcrypt('vp123456'),
                'role' => 'VP',
                'privilege' => [
                    'view_project', 'view_document', 'view_tool', 'view_request', 'view_reports'
                ],
            ],
            [
                'username' => 'manager01',
                'password' => bcrypt('mgr12345'),
                'role' => 'Manager Construction',
                'privilege' => [
                    'create_project', 'edit_project', 'view_project',
                    'create_document', 'edit_document', 'view_document',
                    'create_tool', 'edit_tool', 'view_tool',
                    'view_request', 'view_reports'
                ],
            ],
            [
                'username' => 'pm001',
                'password' => bcrypt('pm123456'),
                'role' => 'Project Manager',
                'privilege' => [
                    'create_project', 'edit_project', 'view_project',
                    'create_document', 'edit_document', 'view_document',
                    'create_tool', 'edit_tool', 'view_tool',
                    'create_request', 'edit_request', 'view_request'
                ],
            ],
            [
                'username' => 'pc001',
                'password' => bcrypt('pc123456'),
                'role' => 'Project Control',
                'privilege' => [
                    'view_project', 'edit_document', 'view_document',
                    'view_tool', 'view_request', 'view_reports'
                ],
            ],
            [
                'username' => 'cc001',
                'password' => bcrypt('cc123456'),
                'role' => 'Cost Control',
                'privilege' => [
                    'view_project', 'view_document', 'view_tool',
                    'view_request', 'view_reports'
                ],
            ],
            [
                'username' => 'user001',
                'password' => bcrypt('user1234'),
                'role' => 'User',
                'privilege' => [
                    'view_project', 'view_document', 'view_tool', 'view_request'
                ],
            ],
        ];

        foreach ($admins as $adminData) {
            Admin::create($adminData);
        }
    }
}
