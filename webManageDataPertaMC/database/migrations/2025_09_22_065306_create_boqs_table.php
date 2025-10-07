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
        Schema::create('boqs', function (Blueprint $table) {
            $table->string('nomorBoq', 10)->primary(); // Primary key varchar(10)
            $table->integer('total_harga'); // Total harga
            $table->date('date'); // Tanggal
            $table->string('project_no_io')->unique(); // Foreign key untuk one-to-one dengan projects
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('project_no_io')->references('no_IO')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boqs');
    }
};
