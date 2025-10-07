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
        Schema::create('tahapans', function (Blueprint $table) {
            $table->id('idTahapan');
            $table->string('no_request', 100); // Foreign key to documents table
            $table->enum('namaTahapan', [
                'BELUM DI PROSES',
                'PROJECT TO EPC (EPC PROCESS)',
                'PMO TO EPC',
                'EPC TO PROCUREMENT'
            ]);
            $table->date('Date_Tahapan')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('no_request')->references('no_request')->on('documents')->onDelete('cascade');
            
            // Index for better performance
            $table->index(['no_request', 'Date_Tahapan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahapans');
    }
};
