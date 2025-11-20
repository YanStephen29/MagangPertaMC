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
        Schema::table('boqs', function (Blueprint $table) {
            $table->foreignId('locked_by_admin_id')
                  ->nullable()
                  ->after('status')
                  ->constrained(
                        table: 'admins',
                        column: 'admin_id'
                  )
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('boqs', function (Blueprint $table) {
            $table->dropForeign(['locked_by_admin_id']);
            $table->dropColumn('locked_by_admin_id');
        });
    }
};
