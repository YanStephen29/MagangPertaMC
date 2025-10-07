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
        // Fix sections table
        Schema::table('sections', function (Blueprint $table) {
            $table->bigInteger('total_harga')->change();
        });
        
        // Fix boqs table  
        Schema::table('boqs', function (Blueprint $table) {
            $table->bigInteger('total_harga')->change();
        });
        
        // Fix details table harga_satuan and harga_total
        Schema::table('details', function (Blueprint $table) {
            $table->bigInteger('harga_satuan')->change();
            $table->bigInteger('harga_total')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->integer('total_harga')->change();
        });
        
        Schema::table('boqs', function (Blueprint $table) {
            $table->integer('total_harga')->change();
        });
        
        Schema::table('details', function (Blueprint $table) {
            $table->integer('harga_satuan')->change();
            $table->integer('harga_total')->change();
        });
    }
};
