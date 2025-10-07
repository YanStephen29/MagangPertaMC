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
        Schema::create('requests', function (Blueprint $table) {
            $table->id('id_req');
            $table->enum('type_surat', ['SPS', 'SPMP', 'PCM']);
            $table->enum('jenis_req', ['PO', 'Kontrak', 'PCM']);
            $table->string('no_surat', 45);
            $table->date('date_req');
            $table->enum('status_req', ['Closed', 'On Proses'])->default('On Proses');
            
            // Foreign key to tools table (one-to-one relationship)
            // idTools is int unsigned, so we use unsignedInteger
            $table->unsignedInteger('tool_id');
            $table->foreign('tool_id')->references('idTools')->on('tools')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
