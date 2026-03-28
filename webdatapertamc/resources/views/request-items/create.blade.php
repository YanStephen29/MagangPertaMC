@extends('layouts.management')

@section('title', 'Tambah Request Item')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Tambah Request Item</h2>
            <small class="text-muted">Buat request item baru untuk project</small>
        </div>
        <a href="{{ route('request-items.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Ada kesalahan:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('request-items.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="event_no_io" class="form-label">Event No I/O <span class="text-danger">*</span></label>
                                <select class="form-select" id="event_no_io" name="event_no_io" required>
                                    <option value="">Pilih Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->{'no_I/O'} }}" 
                                                {{ $eventNoIo === $event->{'no_I/O'} || old('event_no_io') === $event->{'no_I/O'} ? 'selected' : '' }}>
                                            {{ $event->{'no_I/O'} }} - {{ $event->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="bidang_req_type" class="form-label">Bidang Request <span class="text-danger">*</span></label>
                                <select class="form-select" id="bidang_req_type" name="bidang_req_type" required>
                                    <option value="">Pilih Bidang Request</option>
                                    <option value="material_req" {{ old('bidang_req_type') === 'material_req' ? 'selected' : '' }}>
                                        Material Request
                                    </option>
                                    <option value="service_req" {{ old('bidang_req_type') === 'service_req' ? 'selected' : '' }}>
                                        Service Request
                                    </option>
                                    <option value="facility_req" {{ old('bidang_req_type') === 'facility_req' ? 'selected' : '' }}>
                                        Facility Request
                                    </option>
                                    <option value="aset_req" {{ old('bidang_req_type') === 'aset_req' ? 'selected' : '' }}>
                                        Asset Request
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      required placeholder="Masukkan deskripsi request item">{{ old('description') }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" 
                                       value="{{ old('quantity') }}" min="1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="unit" name="unit" 
                                       value="{{ old('unit') }}" required placeholder="pcs, kg, liter, etc.">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>Informasi Dokumen
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="document_number" class="form-label">Nomor Dokumen</label>
                                <input type="text" class="form-control" id="document_number" name="document_number" 
                                       value="{{ old('document_number') }}" placeholder="Nomor dokumen">
                            </div>
                            <div class="col-md-6">
                                <label for="document_date" class="form-label">Tanggal Dokumen</label>
                                <input type="date" class="form-control" id="document_date" name="document_date" 
                                       value="{{ old('document_date') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Workflow Stages -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tasks me-2"></i>Tahapan Workflow
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="project_to_epc_date" class="form-label">Project to EPC</label>
                                <input type="date" class="form-control" id="project_to_epc_date" name="project_to_epc_date" 
                                       value="{{ old('project_to_epc_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="pmo_to_epc_date" class="form-label">PMO to EPC</label>
                                <input type="date" class="form-control" id="pmo_to_epc_date" name="pmo_to_epc_date" 
                                       value="{{ old('pmo_to_epc_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="epc_to_procurement_date" class="form-label">EPC to Procurement</label>
                                <input type="date" class="form-control" id="epc_to_procurement_date" name="epc_to_procurement_date" 
                                       value="{{ old('epc_to_procurement_date') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="col-lg-4">
                <!-- Request Type -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tag me-2"></i>Request Type
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="request_type" class="form-label">Type</label>
                            <select class="form-select" id="request_type" name="request_type">
                                <option value="">Pilih Request Type</option>
                                <option value="SPS" {{ old('request_type') === 'SPS' ? 'selected' : '' }}>SPS</option>
                                <option value="PO" {{ old('request_type') === 'PO' ? 'selected' : '' }}>PO</option>
                                <option value="PCM" {{ old('request_type') === 'PCM' ? 'selected' : '' }}>PCM</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="request_type_doc_number" class="form-label">Nomor Dokumen Type</label>
                            <input type="text" class="form-control" id="request_type_doc_number" name="request_type_doc_number" 
                                   value="{{ old('request_type_doc_number') }}" placeholder="Nomor dokumen type">
                        </div>
                        <div class="mb-3">
                            <label for="request_type_doc_date" class="form-label">Tanggal Dokumen Type</label>
                            <input type="date" class="form-control" id="request_type_doc_date" name="request_type_doc_date" 
                                   value="{{ old('request_type_doc_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Vendor Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-store me-2"></i>Informasi Vendor
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="vendor_name" class="form-label">Nama Vendor/Toko</label>
                            <input type="text" class="form-control" id="vendor_name" name="vendor_name" 
                                   value="{{ old('vendor_name') }}" placeholder="Nama vendor/toko">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control" id="price" name="price" 
                                   value="{{ old('price') }}" step="0.01" min="0" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="total_price" class="form-label">Total Harga</label>
                            <input type="number" class="form-control" id="total_price" name="total_price" 
                                   value="{{ old('total_price') }}" step="0.01" min="0" placeholder="0" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="payment_po_pcm" class="form-label">Payment PO/PCM</label>
                            <input type="number" class="form-control" id="payment_po_pcm" name="payment_po_pcm" 
                                   value="{{ old('payment_po_pcm') }}" min="0" placeholder="0">
                        </div>
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending" {{ old('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ old('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="cancelled" {{ old('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Status & Remarks -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>Status & Catatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Catatan</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3" 
                                      placeholder="Catatan tambahan...">{{ old('remarks') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Simpan Request Item
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Auto calculate total price
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const quantityInput = document.getElementById('quantity');
    const totalPriceInput = document.getElementById('total_price');
    
    function calculateTotal() {
        const price = parseFloat(priceInput.value) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        const total = price * quantity;
        totalPriceInput.value = total.toFixed(2);
    }
    
    priceInput.addEventListener('input', calculateTotal);
    quantityInput.addEventListener('input', calculateTotal);
    
    // Calculate on load if values exist
    calculateTotal();
});
</script>
@endsection
