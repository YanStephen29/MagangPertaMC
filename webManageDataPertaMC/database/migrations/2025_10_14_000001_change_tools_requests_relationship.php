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
        // Step 1: Add request_id column to tools table
        Schema::table('tools', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id')->nullable()->after('no_document');
            $table->foreign('request_id')->references('id_req')->on('requests')->onDelete('set null');
        });

        // Step 2: Migrate existing data from requests.tool_id to tools.request_id
        DB::statement('UPDATE tools t JOIN requests r ON t.idTools = r.tool_id SET t.request_id = r.id_req');

        // Step 3: Remove tool_id column from requests table
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['tool_id']);
            $table->dropColumn('tool_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add tool_id column back to requests table
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('tool_id')->nullable()->after('status_req');
            $table->foreign('tool_id')->references('idTools')->on('tools')->onDelete('cascade');
        });

        // Step 2: Migrate data back from tools.request_id to requests.tool_id
        DB::statement('UPDATE requests r JOIN tools t ON r.id_req = t.request_id SET r.tool_id = t.idTools');

        // Step 3: Remove request_id column from tools table
        Schema::table('tools', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn('request_id');
        });
    }
};