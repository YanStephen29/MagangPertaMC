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
        Schema::table('documents', function (Blueprint $table) {
            // First drop foreign key constraint if it exists
            try {
                $table->dropForeign(['project_id']);
            } catch (Exception $e) {
                // Foreign key doesn't exist, continue
            }
            
            // Drop unused columns that are not referenced anywhere in the codebase
            $table->dropColumn(['name_document', 'location', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Add back the columns if rollback is needed
            $table->string('name_document', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('project_id', 10)->nullable();
            
            // Re-add foreign key constraint if needed
            $table->foreign('project_id')->references('no_IO')->on('projects')->onDelete('set null');
        });
    }
};
