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
        Schema::create('workflow_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Project to EPC, PMO to EPC, EPC to Procurement
            $table->string('description', 200)->nullable();
            $table->integer('sequence'); // Order of stages
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default workflow stages
        DB::table('workflow_stages')->insert([
            ['name' => 'Project to EPC', 'description' => 'Project team to EPC handover', 'sequence' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PMO to EPC', 'description' => 'PMO to EPC handover', 'sequence' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'EPC to Procurement', 'description' => 'EPC to Procurement handover', 'sequence' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_stages');
    }
};
