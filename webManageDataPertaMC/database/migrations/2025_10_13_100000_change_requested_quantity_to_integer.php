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
        Schema::table('request_details', function (Blueprint $table) {
            // Ubah requested_quantity dari decimal ke integer
            $table->integer('requested_quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_details', function (Blueprint $table) {
            // Kembalikan ke decimal jika perlu rollback
            $table->decimal('requested_quantity', 15, 2)->change();
        });
    }
};