<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('username', 20)->unique();
            $table->char('password', 8);
            $table->enum('role', [
                'Admin',
                'VP', 
                'Manager Construction',
                'Project Manager',
                'Project Control',
                'Cost Control',
                'User'
            ]);
            $table->json('privilege'); // Store privileges as JSON
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
