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
        Schema::table('request_items', function (Blueprint $table) {
            // Basic item information
            $table->string('description', 100)->after('id');
            $table->integer('quantity')->after('description');
            $table->string('unit', 20)->after('quantity');
            
            // Project reference
            $table->string('event_no_io', 20)->after('unit');
            $table->foreign('event_no_io')->references('no_I/O')->on('events')->onDelete('cascade');
            
            // Bidang pilihan req (material, service, facility, aset)
            $table->enum('bidang_req_type', ['material_req', 'service_req', 'facility_req', 'aset_req'])->after('event_no_io');
            
            // Document information
            $table->string('document_number', 50)->nullable()->after('bidang_req_type');
            $table->date('document_date')->nullable()->after('document_number');
            
            // Workflow stages with dates
            $table->date('project_to_epc_date')->nullable()->after('document_date');
            $table->date('pmo_to_epc_date')->nullable()->after('project_to_epc_date');
            $table->date('epc_to_procurement_date')->nullable()->after('pmo_to_epc_date');
            
            // Request type (SPS, PO, PCM)
            $table->enum('request_type', ['SPS', 'PO', 'PCM'])->nullable()->after('epc_to_procurement_date');
            $table->string('request_type_doc_number', 50)->nullable()->after('request_type');
            $table->date('request_type_doc_date')->nullable()->after('request_type_doc_number');
            
            // Vendor information
            $table->string('vendor_name', 100)->nullable()->after('request_type_doc_date');
            $table->decimal('price', 15, 2)->nullable()->after('vendor_name');
            $table->decimal('total_price', 15, 2)->nullable()->after('price');
            $table->integer('payment_po_pcm')->nullable()->after('total_price');
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'cancelled'])->default('pending')->after('payment_po_pcm');
            
            // Additional fields
            $table->text('remarks')->nullable()->after('payment_status');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft')->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_items', function (Blueprint $table) {
            $table->dropForeign(['event_no_io']);
            $table->dropColumn([
                'description',
                'quantity',
                'unit',
                'event_no_io',
                'bidang_req_type',
                'document_number',
                'document_date',
                'project_to_epc_date',
                'pmo_to_epc_date',
                'epc_to_procurement_date',
                'request_type',
                'request_type_doc_number',
                'request_type_doc_date',
                'vendor_name',
                'price',
                'total_price',
                'payment_po_pcm',
                'payment_status',
                'remarks',
                'status',
            ]);
        });
    }
};
