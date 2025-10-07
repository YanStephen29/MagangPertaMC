<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin with full privileges
        $superAdmin = Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'password' => '123456',
                'role' => 'Admin',
                'privilege' => [
                    'full_access',
                    'manage_admin',
                    'project_create', 'project_read', 'project_update', 'project_delete',
                    'document_create', 'document_read', 'document_update', 'document_delete',
                    'bidang_create', 'bidang_read', 'bidang_update', 'bidang_delete',
                    'tahapan_create', 'tahapan_read', 'tahapan_update', 'tahapan_delete',
                    'tools_create', 'tools_read', 'tools_update', 'tools_delete',
                    'account_create', 'account_read', 'account_update', 'account_delete'
                ]
            ]
        );

        $this->command->info('Super Admin created: ' . $superAdmin->username . ' (Role: ' . $superAdmin->role . ')');
        
        // Create test PM user with limited privileges
        $pmUser = Admin::updateOrCreate(
            ['username' => 'testpm'],
            [
                'password' => '123456',
                'role' => 'Project Manager',
                'privilege' => [
                    'project_read', 'project_update',
                    'document_read', 'document_update',
                    'tools_create', 'tools_read', 'tools_update', 'tools_delete',
                    'tahapan_create', 'tahapan_read', 'tahapan_update'
                ]
            ]
        );

        $this->command->info('Test PM created: ' . $pmUser->username . ' (Role: ' . $pmUser->role . ')');
        
        // Create test User with minimal privileges
        $testUser = Admin::updateOrCreate(
            ['username' => 'testuser'],
            [
                'password' => '123456',
                'role' => 'User',
                'privilege' => [
                    'project_read',
                    'document_read'
                ]
            ]
        );

        $this->command->info('Test User created: ' . $testUser->username . ' (Role: ' . $testUser->role . ')');
    }
}