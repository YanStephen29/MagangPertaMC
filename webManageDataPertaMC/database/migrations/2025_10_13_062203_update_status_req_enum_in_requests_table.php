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
        Schema::table('requests', function (Blueprint $table) {
            // Update status_req enum to include all new status values
            $table->enum('status_req', [
                'approved',
                'pending', 
                'hold',
                'rejected',
                'Closed',        // Legacy status
                'On Proses'      // Legacy status
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Revert to original enum values
            $table->enum('status_req', ['Closed', 'On Proses', 'hold', 'approved', 'rejected'])->default('On Proses')->change();
        });
    }
};
