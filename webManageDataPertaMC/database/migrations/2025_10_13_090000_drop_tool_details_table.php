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
        Schema::dropIfExists('tool_details');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tool_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('idTools');
            $table->unsignedBigInteger('no_detail');
            $table->decimal('requested_quantity', 15, 2)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('idTools')->references('idTools')->on('tools')->onDelete('cascade');
            $table->foreign('no_detail')->references('no')->on('details')->onDelete('cascade');
            $table->unique(['idTools', 'no_detail'], 'unique_tool_detail');
            $table->index('idTools');
            $table->index('no_detail');
            $table->index('status');
        });
    }
};