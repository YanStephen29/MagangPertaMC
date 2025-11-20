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
        Schema::create('tool_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('idTools'); // Foreign key to tools table
            $table->unsignedBigInteger('no_detail'); // Foreign key to details table (primary key 'no')
            $table->decimal('requested_quantity', 15, 2)->default(0); // Quantity yang di-request untuk detail ini
            $table->decimal('unit_price', 15, 2)->default(0); // Harga per unit saat request dibuat
            $table->decimal('total_price', 15, 2)->default(0); // Total harga (requested_quantity * unit_price)
            $table->string('status')->default('pending'); // pending, approved, rejected, hold
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('idTools')->references('idTools')->on('tools')->onDelete('cascade');
            $table->foreign('no_detail')->references('no')->on('details')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate tool-detail pairs
            $table->unique(['idTools', 'no_detail'], 'unique_tool_detail');
            
            // Indexes for performance
            $table->index('idTools');
            $table->index('no_detail');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_details');
    }
};