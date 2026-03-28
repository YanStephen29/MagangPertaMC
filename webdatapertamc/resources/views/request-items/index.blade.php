@extends('layouts.management')

@section('title', 'Request Items Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Manajemen Request Items</h2>
            <small class="text-muted">Kelola semua request items dalam sistem</small>
        </div>
        <a href="{{ route('request-items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Request Item
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('request-items.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ $search }}" placeholder="Cari description, No I/O, document, vendor...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="bidang_req_type" class="form-label">Bidang Req</label>
                    <select class="form-select" id="bidang_req_type" name="bidang_req_type">
                        <option value="">Semua Bidang</option>
                        <option value="material_req" {{ $bidang_req_type === 'material_req' ? 'selected' : '' }}>Material Request</option>
                        <option value="service_req" {{ $bidang_req_type === 'service_req' ? 'selected' : '' }}>Service Request</option>
                        <option value="facility_req" {{ $bidang_req_type === 'facility_req' ? 'selected' : '' }}>Facility Request</option>
                        <option value="aset_req" {{ $bidang_req_type === 'aset_req' ? 'selected' : '' }}>Asset Request</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="request_type" class="form-label">Request Type</label>
                    <select class="form-select" id="request_type" name="request_type">
                        <option value="">Semua Type</option>
                        <option value="SPS" {{ $request_type === 'SPS' ? 'selected' : '' }}>SPS</option>
                        <option value="PO" {{ $request_type === 'PO' ? 'selected' : '' }}>PO</option>
                        <option value="PCM" {{ $request_type === 'PCM' ? 'selected' : '' }}>PCM</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('request-items.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Request Items Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($requestItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Description</th>
                                <th>Event No I/O</th>
                                <th>Qty</th>
                                <th>Bidang Request</th>
                                <th>Workflow Progress</th>
                                <th>Request Type</th>
                                <th>Vendor</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requestItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->description }}</div>
                                        <small class="text-muted">{{ $item->unit }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('events.show', $item->event_no_io) }}" 
                                           class="text-decoration-none">
                                            {{ $item->event_no_io }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($item->quantity) }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $item->bidang_req_type_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $item->workflow_progress }}%"
                                                 aria-valuenow="{{ $item->workflow_progress }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($item->workflow_progress, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->request_type)
                                            <span class="badge bg-primary">{{ $item->request_type }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->vendor_name ?? '-' }}
                                    </td>
                                    <td>
                                        @if($item->total_price)
                                            Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($item->status === 'completed') bg-success
                                            @elseif($item->status === 'in_progress') bg-warning
                                            @elseif($item->status === 'cancelled') bg-danger
                                            @else bg-secondary @endif">
                                            {{ $item->status_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('request-items.show', $item) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('request-items.edit', $item) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('request-items.destroy', $item) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus request item ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $requestItems->firstItem() }} to {{ $requestItems->lastItem() }} 
                        of {{ $requestItems->total() }} results
                    </div>
                    {{ $requestItems->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada request items</h5>
                    <p class="text-muted mb-4">Belum ada request items yang dibuat dalam sistem.</p>
                    <a href="{{ route('request-items.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Request Item Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
