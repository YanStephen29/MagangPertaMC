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
            // Check if foreign key exists before dropping
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'requests' 
                AND COLUMN_NAME = 'tool_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            // Drop foreign key if exists
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }
            
            // Drop the column
            if (Schema::hasColumn('requests', 'tool_id')) {
                $table->dropColumn('tool_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Add tool_id column back
            $table->unsignedBigInteger('tool_id')->nullable()->after('status_req');
            $table->foreign('tool_id')->references('idTools')->on('tools')->onDelete('cascade');
        });
    }
};
