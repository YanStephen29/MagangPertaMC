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
            // Drop existing foreign key constraints
            $table->dropForeign(['event_no_I/O']);
            
            // Add new foreign key constraint with CASCADE on UPDATE and DELETE
            $table->foreign('event_no_I/O')->references('no_I/O')->on('events')
                  ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['event_no_I/O']);
            
            // Restore original foreign key constraint (only CASCADE on DELETE)
            $table->foreign('event_no_I/O')->references('no_I/O')->on('events')
                  ->onDelete('cascade');
        });
    }
};
