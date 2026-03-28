<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10); // SPS, PO, PCM
            $table->string('name', 50); // Full name of request type
            $table->string('description', 200)->nullable();
            $table->string('document_prefix', 10)->nullable(); // Prefix for document numbers
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default request types
        DB::table('request_types')->insert([
            ['code' => 'SPS', 'name' => 'Surat Permintaan Spesifikasi', 'description' => 'Surat permintaan spesifikasi barang/jasa', 'document_prefix' => 'SPS', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PO', 'name' => 'Purchase Order', 'description' => 'Purchase Order untuk pembelian barang/jasa', 'document_prefix' => 'PO', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PCM', 'name' => 'Procurement Contract Management', 'description' => 'Manajemen kontrak pengadaan', 'document_prefix' => 'PCM', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_types');
    }
};
