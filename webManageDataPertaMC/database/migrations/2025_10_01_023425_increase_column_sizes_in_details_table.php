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
            // Increase nama_detail from varchar(45) to varchar(255) for longer descriptions
            $table->string('nama_detail', 255)->change();
            
            // Increase unit from varchar(5) to varchar(20) for longer unit names
            $table->string('unit', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details', function (Blueprint $table) {
            // Revert back to original sizes
            $table->string('nama_detail', 45)->change();
            $table->string('unit', 5)->change();
        });
    }
};
