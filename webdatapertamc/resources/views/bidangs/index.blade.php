@extends('layouts.management')

@section('content')
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
                        Daftar Bidang - PT Pertamina MC
                    </h2>
                    <h4 class="mb-2 fw-light d-block d-md-none">
                        <i class="fas fa-sitemap me-2"></i>
                        Daftar Bidang
                    </h4>
                </div>
            </div>
            <div class="card-body gradient-background">
                <div class="row align-items-center">
                    <div class="col-12">
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Kelola kode bidang untuk mengelompokkan tools dan equipment dalam sistem manajemen proyek.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4 g-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-sitemap fa-lg fa-md-2x text-primary-custom"></i>
                </div>
                <h4 class="h5 h-md-3 text-primary-custom fw-bold">{{ $bidangs->count() }}</h4>
                <p class="mb-0 text-muted small">Total Bidang</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-tools fa-lg fa-md-2x text-success"></i>
                </div>
                <h4 class="h5 h-md-3 text-success fw-bold">{{ $bidangs->sum('tools_count') }}</h4>
                <p class="mb-0 text-muted small">Tools Terkait</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-check-circle fa-lg fa-md-2x text-info"></i>
                </div>
                <h4 class="h5 h-md-3 text-info fw-bold">{{ $bidangs->where('tools_count', '>', 0)->count() }}</h4>
                <p class="mb-0 text-muted small d-none d-sm-block">Bidang Aktif</p>
                <p class="mb-0 text-muted small d-block d-sm-none">Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body text-center py-3 py-md-4">
                <div class="mb-2 mb-md-3">
                    <i class="fas fa-plus-circle fa-lg fa-md-2x text-warning"></i>
                </div>
                <h4 class="h5 h-md-3 text-warning fw-bold">{{ $bidangs->where('tools_count', 0)->count() }}</h4>
                <p class="mb-0 text-muted small d-none d-sm-block">Belum Digunakan</p>
                <p class="mb-0 text-muted small d-block d-sm-none">Kosong</p>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-8 mb-3 mb-lg-0">
                        <h6 class="text-secondary-custom mb-2 fw-semibold">
                            <i class="fas fa-cogs me-2"></i>Manajemen Bidang
                        </h6>
                        <p class="text-muted mb-0 small">
                            Kelola daftar bidang untuk pengelompokan tools dan equipment dalam proyek PT Pertamina MC.
                        </p>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-start justify-content-lg-end">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary-custom shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i>
                                <span class="d-none d-sm-inline">Kembali ke </span>Events
                            </a>
                            <a href="{{ route('bidangs.create') }}" class="btn btn-primary shadow-sm">
                                <i class="fas fa-plus me-2"></i>
                                <span class="d-none d-sm-inline">Tambah </span>Bidang<span class="d-none d-sm-inline"> Baru</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bidang List -->
<div class="card border-0 shadow-custom">
    <div class="card-header card-header-secondary">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 text-secondary-custom fw-semibold">
                    <i class="fas fa-list-ul me-2"></i>Daftar Bidang Terdaftar
                </h4>
                <p class="mb-0 text-muted small">Kelola semua bidang dalam sistem PT Pertamina MC</p>
            </div>
            @if($bidangs->count() > 0)
                <div class="text-end">
                    <span class="badge badge-primary-custom fs-6">{{ $bidangs->count() }} Bidang</span>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($bidangs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">
                                <i class="fas fa-hashtag me-1"></i>No
                            </th>
                            <th>
                                <i class="fas fa-code me-1"></i>Kode GL
                            </th>
                            <th>
                                <i class="fas fa-tag me-1"></i>Nama Bidang
                            </th>
                            <th class="text-center">
                                <i class="fas fa-tools me-1"></i>Tools Terkait
                            </th>
                            <th class="text-center">
                                <i class="fas fa-clock me-1"></i>Status
                            </th>
                            <th class="text-center pe-4">
                                <i class="fas fa-cogs me-1"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bidangs as $index => $bidang)
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
                                    <strong class="text-primary-custom fs-5">{{ $bidang->kodeGl }}</strong>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">{{ $bidang->nama_Bidang }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Dibuat: {{ $bidang->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    @if($bidang->tools_count > 0)
                                        <span class="badge bg-success fs-6 mb-1">
                                            {{ $bidang->tools_count }} Items
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
                                @if($bidang->tools_count > 0)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                    <br><small class="text-success">Digunakan</small>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-exclamation-circle me-1"></i>Standby
                                    </span>
                                    <br><small class="text-warning">Menunggu</small>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group-vertical" role="group">
                                    <div class="btn-group mb-1" role="group">
                                        <a href="{{ route('bidangs.show', $bidang->kodeGl) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Lihat Detail Bidang"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('bidangs.edit', $bidang->kodeGl) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit Bidang"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                    <div class="btn-group" role="group">
                                        @if($bidang->tools_count == 0)
                                            <form method="POST" action="{{ route('bidangs.destroy', $bidang->kodeGl) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus bidang: {{ $bidang->nama_Bidang }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Hapus Bidang"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-danger disabled" 
                                                    title="Tidak dapat dihapus (digunakan oleh tools)"
                                                    data-bs-toggle="tooltip">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-sitemap fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Belum ada bidang terdaftar</h4>
                    <p class="text-muted mb-4">
                        Sistem belum memiliki bidang yang terdaftar.<br>
                        Mulai dengan membuat bidang pertama untuk mengelompokkan tools.
                    </p>
                    <a href="{{ route('bidangs.create') }}" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-plus me-2"></i>Buat Bidang Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
@endsection
