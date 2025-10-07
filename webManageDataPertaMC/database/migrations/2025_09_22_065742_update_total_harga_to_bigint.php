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
        Schema::table('boqs', function (Blueprint $table) {
            $table->bigInteger('total_harga')->change();
        });
        
        Schema::table('sections', function (Blueprint $table) {
            $table->bigInteger('total_harga')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boqs', function (Blueprint $table) {
            $table->integer('total_harga')->change();
        });
        
        Schema::table('sections', function (Blueprint $table) {
            $table->integer('total_harga')->change();
        });
    }
};
