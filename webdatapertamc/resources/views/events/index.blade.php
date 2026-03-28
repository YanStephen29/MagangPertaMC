@extends('layouts.management')

@section('content')
<!-- Hero Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-custom">
            <div class="card-header card-header-primary position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-15">
                    <i class="fas fa-building" style="font-size: 8rem;"></i>
                </div>
                <div class="position-relative">
                    <h2 class="mb-2 fw-light d-none d-md-block">
                        <i class="fas fa-industry me-3"></i>
                        Request Data Proyek & Equipment
                    </h2>
                    <h4 class="mb-2 fw-light d-block d-md-none">
                        <i class="fas fa-industry me-2"></i>
                        Request Data Proyek
                    </h4>
                </div>
            </div>
            <div class="card-body gradient-background">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h4 class="text-primary-custom fw-bold mb-4 text-center text-md-start d-none d-sm-block">ANGGARAN PELAKSANAAN PROYEK</h4>
                        <h6 class="text-primary-custom fw-bold mb-3 text-center d-block d-sm-none">ANGGARAN PROYEK</h6>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="bg-white p-3 p-md-4 rounded shadow-sm border-start border-success border-4 h-100">
                                    <h6 class="text-muted mb-2 small text-uppercase fw-semibold">NILAI KONTRAK</h6>
                                    <h4 class="text-success fw-bold mb-0 d-block d-md-none">Rp 25.7M</h4>
                                    <h3 class="text-success fw-bold mb-0 d-none d-md-block">Rp 25.731.869.000</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="bg-white p-3 p-md-4 rounded shadow-sm border-start border-primary-custom border-4 h-100">
                                    <h6 class="text-muted mb-2 small text-uppercase fw-semibold">PERIODE PROYEK</h6>
                                    <h3 class="text-primary-custom fw-bold mb-0">24 Bulan</h3>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4">
                                <div class="bg-white p-3 p-md-4 rounded shadow-sm border-start border-info border-4 h-100">
                                    <h6 class="text-muted mb-2 small text-uppercase fw-semibold">TOTAL EVENTS</h6>
                                    <h3 class="text-info fw-bold mb-0">{{ $events->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4 g-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-calendar-alt fa-lg fa-md-2x text-primary-custom"></i>
                </div>
                <h4 class="h5 h-md-3 text-primary-custom fw-bold">{{ $events->count() }}</h4>
                <p class="mb-0 text-muted small">Total Events</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-tools fa-lg fa-md-2x text-success"></i>
                </div>
                <h4 class="h5 h-md-3 text-success fw-bold">{{ $events->sum('tools_count') }}</h4>
                <p class="mb-0 text-muted small">Total Tools</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-clock fa-lg fa-md-2x text-info"></i>
                </div>
                <h4 class="h5 h-md-3 text-info fw-bold">{{ $events->where('created_at', '>=', now()->startOfMonth())->count() }}</h4>
                <p class="mb-0 text-muted small d-none d-sm-block">Event Bulan Ini</p>
                <p class="mb-0 text-muted small d-block d-sm-none">Bulan Ini</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-chart-line fa-lg fa-md-2x text-warning"></i>
                </div>
                <h4 class="h5 h-md-3 text-warning fw-bold">{{ $events->where('updated_at', '>=', now()->subDays(7))->count() }}</h4>
                <p class="mb-0 text-muted small d-none d-sm-block">Updated 7 Hari</p>
                <p class="mb-0 text-muted small d-block d-sm-none">7 Hari</p>
            </div>
        </div>
    </div>
</div>

<!-- Search & Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-12 col-lg-7 mb-3 mb-lg-0">
                        <h6 class="text-secondary-custom mb-3 fw-semibold">
                            <i class="fas fa-search me-2"></i>Pencarian Events
                        </h6>
                        <form method="GET" action="{{ route('events.index') }}">
                            <div class="input-group">
                                <span class="input-group-text bg-light-custom border-primary-custom d-none d-sm-block">
                                    <i class="fas fa-search text-primary-custom"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control border-primary-custom" 
                                       placeholder="Cari event atau No I/O..." 
                                       value="{{ $search }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search d-block d-sm-none"></i>
                                    <span class="d-none d-sm-inline">
                                        <i class="fas fa-search me-1"></i>Cari
                                    </span>
                                </button>
                                @if($search)
                                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                        @if($search)
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Hasil untuk: <strong>"{{ $search }}"</strong>
                            </small>
                        @endif
                    </div>
                    <div class="col-12 col-lg-5">
                        <h6 class="text-secondary-custom mb-3 fw-semibold text-start text-lg-end">
                            <i class="fas fa-cogs me-2"></i>Aksi Cepat
                        </h6>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-start justify-content-lg-end">
                            <a href="{{ route('events.export.all') }}" class="btn btn-success shadow-sm">
                                <i class="fas fa-file-excel me-2"></i>
                                <span class="d-none d-sm-inline">Download All </span>Excel
                            </a>
                            <a href="{{ route('events.create') }}" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus me-2"></i>
                                <span class="d-none d-sm-inline">Tambah </span>Event<span class="d-none d-sm-inline"> Baru</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Events List -->
<div class="card border-0 shadow-custom">
    <div class="card-header card-header-secondary">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 text-secondary-custom fw-semibold">
                    <i class="fas fa-list-ul me-2"></i>Daftar Events Proyek
                </h4>
                <p class="mb-0 text-muted small">Kelola semua events dalam proyek PT Pertamina MC</p>
            </div>
            @if($events->count() > 0)
                <div class="text-end">
                    <span class="badge badge-primary-custom fs-6">{{ $events->count() }} Events</span>
                    @if($search)
                        <br><small class="text-muted">Hasil pencarian</small>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($events->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">
                                <i class="fas fa-hashtag me-1"></i>No I/O
                            </th>
                            <th>
                                <i class="fas fa-calendar-alt me-1"></i>Judul Event
                            </th>
                            <th class="text-center">
                                <i class="fas fa-tools me-1"></i>Tools
                            </th>
                            <th class="text-center">
                                <i class="fas fa-clock me-1"></i>Status
                            </th>
                            <th class="text-center">
                                <i class="fas fa-calendar-day me-1"></i>Tanggal
                            </th>
                            <th class="text-center pe-4">
                                <i class="fas fa-cogs me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $index => $event)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-size: 14px;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <strong class="text-primary-custom">{{ $event->{'no_I/O'} }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $event->{'no_I/O'} }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="{{ route('events.show', $event->{'no_I/O'}) }}" 
                                       class="text-decoration-none text-dark fw-bold fs-6 d-block mb-1">
                                        {{ $event->title }}
                                    </a>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Dibuat: {{ $event->created_at->format('d/m/Y') }}
                                    </small>
                                    @if($event->updated_at != $event->created_at)
                                        <br>
                                        <small class="text-info">
                                            <i class="fas fa-edit me-1"></i>
                                            Update: {{ $event->updated_at->format('d/m/Y') }}
                                        </small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    @if($event->tools_count > 0)
                                        <span class="badge bg-success fs-6 mb-1">
                                            {{ $event->tools_count }} Items
                                        </span>
                                        <small class="text-muted">Tools terdaftar</small>
                                    @else
                                        <span class="badge bg-secondary fs-6 mb-1">
                                            0 Items
                                        </span>
                                        <small class="text-muted">Belum ada tools</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $isRecent = $event->updated_at->diffInDays() <= 7;
                                    $hasTools = $event->tools_count > 0;
                                @endphp
                                @if($hasTools && $isRecent)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                    <br><small class="text-success">Terkini</small>
                                @elseif($hasTools)
                                    <span class="badge bg-info">
                                        <i class="fas fa-pause-circle me-1"></i>Stabil
                                    </span>
                                    <br><small class="text-info">Ada tools</small>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-circle me-1"></i>Perlu Tools
                                    </span>
                                    <br><small class="text-warning">Kosong</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div>
                                    <strong class="text-primary-custom">{{ $event->created_at->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $event->created_at->format('H:i') }}</small>
                                    <br>
                                    <small class="text-secondary-custom">{{ $event->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group-vertical" role="group">
                                    <div class="btn-group mb-1" role="group">
                                        <a href="{{ route('events.show', $event->{'no_I/O'}) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Lihat Detail Event"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('events.edit', $event->{'no_I/O'}) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit Event"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('events.export.single', $event->{'no_I/O'}) }}" 
                                           class="btn btn-sm btn-outline-success" 
                                           title="Download Excel Event"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form method="POST" action="{{ route('events.destroy', $event->{'no_I/O'}) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus event: {{ $event->title }}?\n\nSemua tools yang terkait juga akan dihapus!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Hapus Event"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Events Summary Footer -->
            <div class="card-footer bg-light">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">{{ $events->count() }}</h5>
                            <small class="text-muted">Total Events</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-end">
                            <h5 class="text-success mb-0">{{ $events->where('tools_count', '>', 0)->count() }}</h5>
                            <small class="text-muted">Events dengan Tools</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-warning mb-0">{{ $events->where('tools_count', 0)->count() }}</h5>
                        <small class="text-muted">Events Kosong</small>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    @if($search)
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">Tidak ada hasil pencarian</h4>
                        <p class="text-muted mb-4">
                            Tidak ditemukan event yang sesuai dengan pencarian <strong>"{{ $search }}"</strong><br>
                            Coba gunakan kata kunci yang berbeda atau buat event baru.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Reset Pencarian
                            </a>
                            <a href="{{ route('events.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Buat Event Baru
                            </a>
                        </div>
                    @else
                        <i class="fas fa-calendar-plus fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">Belum ada events dalam proyek</h4>
                        <p class="text-muted mb-4">
                            Proyek ini belum memiliki event yang terdaftar.<br>
                            Mulai dengan membuat event pertama untuk PT Pertamina MC.
                        </p>
                        <a href="{{ route('events.create') }}" class="btn btn-primary btn-lg shadow">
                            <i class="fas fa-plus me-2"></i>Buat Event Pertama
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .bg-blue {
        background-color: var(--primary-blue) !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.005);
        transition: all 0.2s ease;
    }
    
    .btn-group .btn, .btn-group-vertical .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover, .btn-group-vertical .btn:hover {
        transform: scale(1.05);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .input-group:focus-within {
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    /* Responsive Enhancements */
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem 0.75rem;
        }
        
        .btn-group-vertical .btn {
            margin-bottom: 2px;
            font-size: 0.75rem;
        }
        
        .table td, .table th {
            padding: 0.5rem 0.25rem;
            font-size: 0.85rem;
        }
        
        .badge {
            font-size: 0.65rem;
        }
        
        .input-group:focus-within {
            transform: none;
        }
        
        .card-header h2 {
            font-size: 1.25rem;
        }
        
        .position-absolute {
            display: none !important;
        }
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.9rem;
        }
        
        .btn-group-vertical {
            width: 100%;
        }
        
        .btn-group-vertical .btn {
            border-radius: 0.25rem !important;
            margin-bottom: 1px;
        }
        
        .table thead th {
            font-size: 0.8rem;
            padding: 0.5rem 0.25rem;
        }
        
        .hover-effects:hover {
            transform: none !important;
        }
    }
    
    @media (max-width: 992px) {
        .d-flex.gap-2 {
            gap: 0.5rem !important;
        }
        
        .text-end {
            text-align: start !important;
        }
        
        .text-lg-end {
            text-align: end !important;
        }
    }
    
    /* Mobile-first icon sizing */
    .fa-lg { font-size: 1.2em; }
    .fa-md-2x { font-size: 1.5em; }
    
    @media (min-width: 768px) {
        .fa-md-2x { font-size: 2em; }
    }
    
    /* Mobile table improvements */
    @media (max-width: 576px) {
        .table-responsive table {
            min-width: 100%;
        }
        
        .table td:first-child,
        .table th:first-child {
            position: sticky;
            left: 0;
            background: white;
            z-index: 10;
        }
        
        .table thead th:first-child {
            background: #212529;
        }
    }
    
    /* Improved mobile card spacing */
    .g-3 > * {
        padding: 0.75rem;
    }
    
    @media (max-width: 576px) {
        .g-3 > * {
            padding: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto-focus search input if there's a search parameter
        @if($search)
            document.querySelector('input[name="search"]').focus();
        @endif
    });
</script>
@endpush
@endsection
