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
        Schema::create('tools', function (Blueprint $table) {
            $table->increments('idTools');
            $table->string('description', 50);
            $table->integer('quantity');
            $table->string('unit', 20);
            $table->date('deliveryDate');
            $table->string('remarks',50);

            // Foreign key to documents table
            $table->string('event_no_I/O', 20);
            $table->string('Document_no_request', 40);
            $table->integer('Bidang_kodeGl');

            $table->foreign('event_no_I/O')->references('no_I/O')->on('events')->onDelete('cascade');
            $table->foreign('Document_no_request')->references('no_request')->on('documents')->onDelete('cascade');
            $table->foreign('Bidang_kodeGl')->references('kodeGl')->on('bidangs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
