@extends('layouts.management')

@section('content')
<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('events.index') }}" class="text-decoration-none text-primary-custom">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('bidangs.index') }}" class="text-decoration-none text-primary-custom">
                        <i class="fas fa-sitemap me-1"></i>Bidang
                    </a>
                </li>
                <li class="breadcrumb-item active text-secondary-custom">{{ $bidang->nama_Bidang }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-custom">
            <div class="card-header card-header-primary position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-15">
                    <i class="fas fa-sitemap" style="font-size: 8rem;"></i>
                </div>
                <div class="position-relative">
                    <h2 class="mb-2 fw-light d-none d-md-block">
                        <i class="fas fa-sitemap me-3"></i>
                        Detail Bidang: {{ $bidang->nama_Bidang }}
                    </h2>
                    <h4 class="mb-2 fw-light d-block d-md-none">
                        <i class="fas fa-sitemap me-2"></i>
                        {{ $bidang->nama_Bidang }}
                    </h4>
                    <p class="mb-0 opacity-75">Kode GL: {{ $bidang->kodeGl }}</p>
                </div>
            </div>
            <div class="card-body gradient-background">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi lengkap tentang bidang dan tools yang terkait dalam sistem PT Pertamina MC.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Dibuat: {{ $bidang->created_at->format('d/m/Y') }}
                            </span>
                            <span class="badge bg-info fs-6 px-3 py-2">
                                <i class="fas fa-edit me-1"></i>
                                Update: {{ $bidang->updated_at->format('d/m/Y') }}
                            </span>
                            @if($bidang->tools->count() > 0)
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Aktif Digunakan
                                </span>
                            @else
                                <span class="badge bg-warning fs-6 px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>
                                    Standby
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <a href="{{ route('bidangs.edit', $bidang->kodeGl) }}" class="btn btn-warning shadow-sm">
                                <i class="fas fa-edit me-2"></i>Edit Bidang
                            </a>
                            <a href="{{ route('bidangs.index') }}" class="btn btn-outline-secondary-custom shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="row mb-4 g-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-code fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $bidang->kodeGl }}</h3>
                <p class="mb-0">Kode GL</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-tools fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $bidang->tools->count() }}</h3>
                <p class="mb-0">Total Tools</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $bidang->tools->pluck('event')->unique()->count() }}</h3>
                <p class="mb-0">Events Terkait</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h3 class="fw-bold">{{ $bidang->created_at->diffInDays() }}</h3>
                <p class="mb-0">Hari Aktif</p>
            </div>
        </div>
    </div>
</div>

<!-- Tools List -->
<div class="card border-0 shadow-custom">
    <div class="card-header card-header-secondary">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 text-secondary-custom fw-semibold">
                    <i class="fas fa-tools me-2"></i>Tools Terkait dengan Bidang {{ $bidang->nama_Bidang }}
                </h4>
                <p class="mb-0 text-muted small">Daftar semua tools yang menggunakan bidang ini</p>
            </div>
            @if($bidang->tools->count() > 0)
                <div class="text-end">
                    <span class="badge badge-primary-custom fs-6">{{ $bidang->tools->count() }} Tools</span>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($bidang->tools->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">
                                <i class="fas fa-hashtag me-1"></i>No
                            </th>
                            <th>
                                <i class="fas fa-wrench me-1"></i>Description
                            </th>
                            <th>
                                <i class="fas fa-calendar-alt me-1"></i>Event
                            </th>
                            <th class="text-center">
                                <i class="fas fa-sort-amount-up me-1"></i>Quantity
                            </th>
                            <th class="text-center">
                                <i class="fas fa-truck me-1"></i>Delivery
                            </th>
                            <th class="text-center">
                                <i class="fas fa-sticky-note me-1"></i>Remarks
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bidang->tools as $index => $tool)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-size: 14px;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">{{ $tool->description }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-hashtag me-1"></i>
                                        ID: {{ $tool->idTools }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($tool->event)
                                        <a href="{{ route('events.show', $tool->event->{'no_I/O'}) }}" 
                                           class="text-decoration-none text-primary-custom fw-bold">
                                            {{ $tool->event->title }}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-key me-1"></i>
                                            {{ $tool->event->{'no_I/O'} }}
                                        </small>
                                    @else
                                        <span class="text-muted">Event tidak ditemukan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div>
                                    <span class="badge bg-info fs-6 mb-1">{{ $tool->quantity }}</span>
                                    <br>
                                    <small class="text-muted">{{ $tool->unit }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div>
                                    <strong class="text-primary-custom">{{ \Carbon\Carbon::parse($tool->deliveryDate)->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($tool->deliveryDate)->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="text-muted small">{{ $tool->remarks }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-tools fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Belum ada tools yang menggunakan bidang ini</h4>
                    <p class="text-muted mb-4">
                        Bidang <strong>{{ $bidang->nama_Bidang }}</strong> belum memiliki tools yang terkait.<br>
                        Tools akan muncul di sini ketika ada yang menggunakan bidang ini.
                    </p>
                    <a href="{{ route('events.index') }}" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-plus me-2"></i>Lihat Events & Tools
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Information Card -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-secondary-custom mb-3 fw-semibold">
                    <i class="fas fa-info-circle me-2"></i>Informasi Bidang
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Kode GL:</td>
                                <td>{{ $bidang->kodeGl }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Nama Bidang:</td>
                                <td>{{ $bidang->nama_Bidang }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Status:</td>
                                <td>
                                    @if($bidang->tools->count() > 0)
                                        <span class="badge bg-success">Aktif Digunakan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Standby</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Dibuat:</td>
                                <td>{{ $bidang->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Terakhir Update:</td>
                                <td>{{ $bidang->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Total Tools:</td>
                                <td>
                                    <span class="badge bg-info">{{ $bidang->tools->count() }} Tools</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transition: all 0.2s ease;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .badge {
        transition: all 0.2s ease;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
