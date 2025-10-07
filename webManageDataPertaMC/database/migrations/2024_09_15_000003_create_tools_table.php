<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->increments('idTools'); // Primary key INT auto-increment
            $table->string('Description', 45);
            $table->integer('quantity');
            $table->string('unit', 20);
            $table->date('delivery_date');
            $table->string('remarks', 45)->nullable();
            
            // Foreign keys
            $table->string('no_IO', 10); // Foreign key to projects table
            $table->string('kode_GL', 20); // Foreign key to bidangs table
            
            $table->timestamps();
            
            // Define foreign key constraints
            $table->foreign('no_IO')->references('no_IO')->on('projects')->onDelete('cascade');
            $table->foreign('kode_GL')->references('kode_GL')->on('bidangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
