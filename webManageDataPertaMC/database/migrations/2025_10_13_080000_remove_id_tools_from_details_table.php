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
            // Drop foreign key constraint first
            $table->dropForeign(['idTools']);
            
            // Drop the column
            $table->dropColumn('idTools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details', function (Blueprint $table) {
            // Add back the column and foreign key
            $table->unsignedInteger('idTools')->nullable()->after('section_id');
            $table->foreign('idTools')->references('idTools')->on('tools')->onDelete('set null');
            $table->index('idTools');
        });
    }
};