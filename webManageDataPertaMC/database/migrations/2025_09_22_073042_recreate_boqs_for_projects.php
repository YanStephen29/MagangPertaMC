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
        // Drop foreign key constraint dari sections terlebih dahulu
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['boq_nomorBoq']);
        });
        
        // Clear data sections yang lama
        DB::table('sections')->delete();
        
        // Drop existing BOQs table
        Schema::dropIfExists('boqs');
        
        // Recreate BOQs table dengan relasi ke projects
        Schema::create('boqs', function (Blueprint $table) {
            $table->string('nomorBoq', 10)->primary(); // Primary key varchar(10)
            $table->bigInteger('total_harga'); // Total harga
            $table->date('date'); // Tanggal
            $table->string('project_no_io', 20)->unique(); // Foreign key ke projects table
            $table->timestamps();
            
            // Foreign key constraint ke projects
            $table->foreign('project_no_io')->references('no_IO')->on('projects')->onDelete('cascade');
        });
        
        // Recreate foreign key constraint dari sections ke boqs
        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('boq_nomorBoq')->references('nomorBoq')->on('boqs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boqs');
        
        // Recreate original BOQs table dengan relasi ke events
        Schema::create('boqs', function (Blueprint $table) {
            $table->string('nomorBoq', 10)->primary(); // Primary key varchar(10)
            $table->bigInteger('total_harga'); // Total harga
            $table->date('date'); // Tanggal
            $table->unsignedBigInteger('event_id')->unique(); // Foreign key untuk one-to-one dengan events
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }
};
