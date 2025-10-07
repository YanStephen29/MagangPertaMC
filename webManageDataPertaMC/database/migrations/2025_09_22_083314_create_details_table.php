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
        Schema::create('details', function (Blueprint $table) {
            $table->id('no'); // Primary key as 'no'
            $table->string('nama_detail', 45);
            $table->unsignedBigInteger('parent_no')->nullable(); // Self-referencing foreign key
            $table->string('spesification', 45);
            $table->integer('quantity');
            $table->string('unit', 5);
            $table->string('unit_waktu', 5);
            $table->integer('quantity_waktu');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('harga_total'); // Will be calculated as quantity * harga_satuan
            
            // Foreign key to sections table (one-to-many from sections to details)
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            
            // Self-referencing foreign key
            $table->foreign('parent_no')->references('no')->on('details')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
    }
};
