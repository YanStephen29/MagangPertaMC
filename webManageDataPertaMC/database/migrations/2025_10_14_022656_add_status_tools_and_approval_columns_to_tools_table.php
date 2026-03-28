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
        Schema::table('tools', function (Blueprint $table) {
            // Add status_tools enum column
            $table->enum('status_tools', ['On Process', 'Hold', 'Rejected', 'Closed'])->default('On Process')->after('request_id');
            
            // Add approval tracking columns
            $table->unsignedBigInteger('approved_by')->nullable()->after('status_tools');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('approved_at');
            
            // Add rejection tracking columns
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approval_notes');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->text('rejection_notes')->nullable()->after('rejected_at');
            
            // Add foreign key constraints
            $table->foreign('approved_by')->references('admin_id')->on('admins')->onDelete('set null');
            $table->foreign('rejected_by')->references('admin_id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            
            // Drop columns
            $table->dropColumn([
                'status_tools',
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
