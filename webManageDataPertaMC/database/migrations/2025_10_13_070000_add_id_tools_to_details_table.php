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
        Schema::table('details', function (Blueprint $table) {
            // Add idTools column as foreign key to tools table
            $table->unsignedInteger('idTools')->nullable()->after('section_id');
            
            // Add foreign key constraint
            $table->foreign('idTools')->references('idTools')->on('tools')->onDelete('set null');
            
            // Add index for better performance
            $table->index('idTools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['idTools']);
            
            // Drop the column
            $table->dropColumn('idTools');
        });
    }
};