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
            // Update status_req enum to include 'hold', 'approved', 'rejected'
            $table->enum('status_req', ['Closed', 'On Proses', 'hold', 'approved', 'rejected'])->default('On Proses')->change();
            
            // Add approval tracking columns
            $table->string('approved_by')->nullable()->after('status_req');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('approved_at');
            
            // Add rejection tracking columns
            $table->string('rejected_by')->nullable()->after('approval_notes');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_notes')->nullable()->after('rejected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Revert status_req enum to original values
            $table->enum('status_req', ['Closed', 'On Proses'])->default('On Proses')->change();
            
            // Drop added columns
            $table->dropColumn([
                'approved_by',
                'approved_at', 
                'approval_notes',
                'rejected_by',
                'rejected_at',
                'rejection_notes'
            ]);
        });
    }
};
