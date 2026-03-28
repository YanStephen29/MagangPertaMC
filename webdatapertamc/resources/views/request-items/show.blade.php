@extends('layouts.management')

@section('title', 'Detail Request Item')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Detail Request Item</h2>
            <small class="text-muted">{{ $requestItem->description }}</small>
        </div>
        <div>
            <a href="{{ route('request-items.edit', $requestItem) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('request-items.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Event No I/O:</dt>
                                <dd class="col-sm-8">
                                    <a href="{{ route('events.show', $requestItem->event_no_io) }}" class="text-decoration-none">
                                        {{ $requestItem->event_no_io }}
                                    </a>
                                </dd>
                                
                                <dt class="col-sm-4">Deskripsi:</dt>
                                <dd class="col-sm-8">{{ $requestItem->description }}</dd>
                                
                                <dt class="col-sm-4">Quantity:</dt>
                                <dd class="col-sm-8">{{ number_format($requestItem->quantity) }} {{ $requestItem->unit }}</dd>
                                
                                <dt class="col-sm-4">Bidang Request:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-info">{{ $requestItem->bidang_req_type_name }}</span>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge 
                                        @if($requestItem->status === 'completed') bg-success
                                        @elseif($requestItem->status === 'in_progress') bg-warning
                                        @elseif($requestItem->status === 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ $requestItem->status_name }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-4">Dibuat:</dt>
                                <dd class="col-sm-8">{{ $requestItem->created_at->format('d/m/Y H:i') }}</dd>
                                
                                <dt class="col-sm-4">Diupdate:</dt>
                                <dd class="col-sm-8">{{ $requestItem->updated_at->format('d/m/Y H:i') }}</dd>
                                
                                @if($requestItem->remarks)
                                    <dt class="col-sm-4">Catatan:</dt>
                                    <dd class="col-sm-8">{{ $requestItem->remarks }}</dd>
                                @endif
                            </dl>
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
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6">Nomor Dokumen:</dt>
                                <dd class="col-sm-6">{{ $requestItem->document_number ?: '-' }}</dd>
                                
                                <dt class="col-sm-6">Tanggal Dokumen:</dt>
                                <dd class="col-sm-6">
                                    {{ $requestItem->document_date ? $requestItem->document_date->format('d/m/Y') : '-' }}
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-6">Request Type:</dt>
                                <dd class="col-sm-6">
                                    @if($requestItem->request_type)
                                        <span class="badge bg-primary">{{ $requestItem->request_type }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-6">Nomor Dok. Type:</dt>
                                <dd class="col-sm-6">{{ $requestItem->request_type_doc_number ?: '-' }}</dd>
                                
                                <dt class="col-sm-6">Tanggal Dok. Type:</dt>
                                <dd class="col-sm-6">
                                    {{ $requestItem->request_type_doc_date ? $requestItem->request_type_doc_date->format('d/m/Y') : '-' }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workflow Progress -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks me-2"></i>Progress Workflow
                    </h5>
                    <div class="fw-bold">{{ number_format($requestItem->workflow_progress, 0) }}% Complete</div>
                </div>
                <div class="card-body">
                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 25px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ $requestItem->workflow_progress }}%"
                             aria-valuenow="{{ $requestItem->workflow_progress }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($requestItem->workflow_progress, 0) }}%
                        </div>
                    </div>
                    
                    <!-- Workflow Stages -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded 
                                {{ $requestItem->project_to_epc_date ? 'bg-success text-white' : 'bg-light' }}">
                                <i class="fas fa-arrow-right fa-2x mb-2"></i>
                                <h6 class="mb-1">Project to EPC</h6>
                                <small>
                                    @if($requestItem->project_to_epc_date)
                                        {{ $requestItem->project_to_epc_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Belum dilakukan</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded 
                                {{ $requestItem->pmo_to_epc_date ? 'bg-success text-white' : 'bg-light' }}">
                                <i class="fas fa-arrow-right fa-2x mb-2"></i>
                                <h6 class="mb-1">PMO to EPC</h6>
                                <small>
                                    @if($requestItem->pmo_to_epc_date)
                                        {{ $requestItem->pmo_to_epc_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Belum dilakukan</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded 
                                {{ $requestItem->epc_to_procurement_date ? 'bg-success text-white' : 'bg-light' }}">
                                <i class="fas fa-arrow-right fa-2x mb-2"></i>
                                <h6 class="mb-1">EPC to Procurement</h6>
                                <small>
                                    @if($requestItem->epc_to_procurement_date)
                                        {{ $requestItem->epc_to_procurement_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Belum dilakukan</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Update Workflow Form -->
                    <div class="mt-4">
                        <h6>Update Workflow Stage</h6>
                        <form action="{{ route('request-items.workflow.update', $requestItem) }}" method="POST" class="row g-3">
                            @csrf
                            @method('PATCH')
                            <div class="col-md-4">
                                <select class="form-select" name="stage" required>
                                    <option value="">Pilih Stage</option>
                                    <option value="project_to_epc">Project to EPC</option>
                                    <option value="pmo_to_epc">PMO to EPC</option>
                                    <option value="epc_to_procurement">EPC to Procurement</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="date" required>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Information -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-store me-2"></i>Informasi Vendor
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Nama Vendor:</dt>
                        <dd class="col-sm-7">{{ $requestItem->vendor_name ?: '-' }}</dd>
                        
                        <dt class="col-sm-5">Harga Satuan:</dt>
                        <dd class="col-sm-7">
                            @if($requestItem->price)
                                Rp {{ number_format($requestItem->price, 0, ',', '.') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-5">Total Harga:</dt>
                        <dd class="col-sm-7">
                            @if($requestItem->total_price)
                                <strong>Rp {{ number_format($requestItem->total_price, 0, ',', '.') }}</strong>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-5">Payment PO/PCM:</dt>
                        <dd class="col-sm-7">{{ $requestItem->payment_po_pcm ?: '-' }}</dd>
                        
                        <dt class="col-sm-5">Status Bayar:</dt>
                        <dd class="col-sm-7">
                            <span class="badge 
                                @if($requestItem->payment_status === 'paid') bg-success
                                @elseif($requestItem->payment_status === 'partial') bg-warning
                                @elseif($requestItem->payment_status === 'cancelled') bg-danger
                                @else bg-secondary @endif">
                                {{ $requestItem->payment_status_name }}
                            </span>
                        </dd>
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('request-items.edit', $requestItem) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Request Item
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Hapus Request Item
                        </button>
                        <a href="{{ route('events.show', $requestItem->event_no_io) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Lihat Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus request item "<strong>{{ $requestItem->description }}</strong>"?</p>
                <p class="text-muted">Action ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('request-items.destroy', $requestItem) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
