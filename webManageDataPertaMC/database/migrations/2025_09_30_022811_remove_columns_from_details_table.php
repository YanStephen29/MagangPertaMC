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
        Schema::table('details', function (Blueprint $table) {
            $table->dropColumn(['spesification', 'unit_waktu', 'quantity_waktu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('details', function (Blueprint $table) {
            $table->string('spesification', 45)->after('parent_no');
            $table->string('unit_waktu', 5)->after('unit');
            $table->integer('quantity_waktu')->after('unit_waktu');
        });
    }
};
