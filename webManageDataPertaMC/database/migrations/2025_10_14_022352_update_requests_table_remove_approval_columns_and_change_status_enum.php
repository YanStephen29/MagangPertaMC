<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Remove approval/rejection columns if they exist
            if (Schema::hasColumn('requests', 'approved_by')) {
                $table->dropColumn(['approved_by', 'approved_at', 'approval_notes']);
            }
            if (Schema::hasColumn('requests', 'rejected_by')) {
                $table->dropColumn(['rejected_by', 'rejected_at', 'rejection_notes']);
            }
            
            // Change status_req enum to new values
            DB::statement("ALTER TABLE requests MODIFY COLUMN status_req ENUM('Pending', 'On Process', 'Closed') NOT NULL DEFAULT 'Pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Add back approval/rejection columns
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_notes')->nullable();
            
            // Revert status_req to original enum
            DB::statement("ALTER TABLE requests MODIFY COLUMN status_req ENUM('Closed','On Proses','hold','approved','rejected') NOT NULL DEFAULT 'hold'");
        });
    }
};
