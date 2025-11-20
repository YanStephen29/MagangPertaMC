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
        // Step 1: Change column to varchar to allow data migration
        DB::statement("ALTER TABLE requests MODIFY COLUMN status_req VARCHAR(20) NOT NULL");
        
        // Step 2: Update data to new values
        DB::table('requests')->where('status_req', 'hold')->update(['status_req' => 'Pending']);
        DB::table('requests')->where('status_req', 'approved')->update(['status_req' => 'On Process']);
        DB::table('requests')->where('status_req', 'rejected')->update(['status_req' => 'Closed']);
        DB::table('requests')->where('status_req', 'On Proses')->update(['status_req' => 'On Process']);
        
        // Step 3: Change back to enum with new values
        DB::statement("ALTER TABLE requests MODIFY COLUMN status_req ENUM('Pending', 'On Process', 'Closed') NOT NULL DEFAULT 'Pending'");
        
        // Step 4: Remove approval columns if they exist
        Schema::table('requests', function (Blueprint $table) {
            if (Schema::hasColumn('requests', 'approved_by')) {
                $table->dropColumn(['approved_by', 'approved_at', 'approval_notes']);
            }
            if (Schema::hasColumn('requests', 'rejected_by')) {
                $table->dropColumn(['rejected_by', 'rejected_at', 'rejection_notes']);
            }
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
        });
        
        // Revert enum values
        DB::statement("ALTER TABLE requests MODIFY COLUMN status_req VARCHAR(20) NOT NULL");
        DB::table('requests')->where('status_req', 'Pending')->update(['status_req' => 'hold']);
        DB::table('requests')->where('status_req', 'On Process')->update(['status_req' => 'approved']);
        DB::table('requests')->where('status_req', 'Closed')->update(['status_req' => 'rejected']);
        DB::statement("ALTER TABLE requests MODIFY COLUMN status_req ENUM('Closed','On Proses','hold','approved','rejected') NOT NULL DEFAULT 'hold'");
    }
};
