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
        Schema::create('request_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('detail_id'); // ID dari table details (BOQ items)
            $table->decimal('requested_quantity', 15, 2); // Quantity yang di-request
            $table->decimal('unit_price', 15, 2); // Harga per unit saat request dibuat
            $table->decimal('total_price', 15, 2); // Total harga (requested_quantity * unit_price)
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('request_id')->references('id_req')->on('requests')->onDelete('cascade');
            $table->foreign('detail_id')->references('no')->on('details')->onDelete('cascade');
            
            // Index for performance
            $table->index(['request_id', 'detail_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_details');
    }
};
