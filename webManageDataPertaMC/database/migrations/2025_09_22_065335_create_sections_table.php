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
        Schema::create('sections', function (Blueprint $table) {
            $table->id(); // Auto increment primary key
            $table->string('nama', 45); // Nama section
            $table->integer('total_harga'); // Total harga
            $table->string('boq_nomorBoq', 10); // Foreign key ke table boqs
            $table->timestamps();
            
            // Foreign key constraint untuk one-to-many dengan BOQ
            $table->foreign('boq_nomorBoq')->references('nomorBoq')->on('boqs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
