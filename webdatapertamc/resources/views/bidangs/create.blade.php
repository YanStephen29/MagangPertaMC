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
                        <i class="fas fa-sitemap me-1"></i>Daftar Bidang
                    </a>
                </li>
                <li class="breadcrumb-item active text-secondary-custom">Tambah Bidang</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Header Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);">
            <div class="card-body text-center py-4">
                <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                <h2 class="text-primary mb-2">Tambah Bidang Baru</h2>
                <p class="text-muted mb-0">Buat bidang baru untuk mengelompokkan tools dalam sistem PT Pertamina MC</p>
            </div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-lg">
            <div class="card-header card-header-primary position-relative">
                <div class="position-absolute top-0 end-0 opacity-25 pe-3 pt-2">
                    <i class="fas fa-sitemap" style="font-size: 3rem;"></i>
                </div>
                <div class="position-relative">
                    <h4 class="mb-1">
                        <i class="fas fa-plus me-2"></i>Form Tambah Bidang
                    </h4>
                    <p class="mb-0 opacity-75">Masukkan informasi bidang baru</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('bidangs.store') }}" id="bidangCreateForm">
                    @csrf
                    
                    <!-- Info Alert -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Informasi Penting</h6>
                                <p class="mb-0">
                                    Bidang akan digunakan untuk mengelompokkan tools dalam sistem.<br>
                                    <small class="text-muted">Pastikan kode GL dan nama bidang unik.</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kode GL Field -->
                    <div class="mb-4">
                        <label for="kodeGl" class="form-label fw-bold">
                            <i class="fas fa-code me-1 text-primary-custom"></i>
                            Kode GL <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-hashtag text-muted"></i>
                            </span>
                            <input type="number" 
                                   class="form-control form-control-lg @error('kodeGl') is-invalid @enderror" 
                                   id="kodeGl" 
                                   name="kodeGl" 
                                   value="{{ old('kodeGl') }}" 
                                   placeholder="Masukkan kode GL (contoh: 12345)" 
                                   required>
                            @error('kodeGl')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Kode GL berupa angka unik untuk identifikasi bidang
                        </div>
                    </div>

                    <!-- Nama Bidang Field -->
                    <div class="mb-4">
                        <label for="nama_Bidang" class="form-label fw-bold">
                            <i class="fas fa-tag me-1 text-secondary-custom"></i>
                            Nama Bidang <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-building text-muted"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-lg @error('nama_Bidang') is-invalid @enderror" 
                                   id="nama_Bidang" 
                                   name="nama_Bidang" 
                                   value="{{ old('nama_Bidang') }}" 
                                   placeholder="Masukkan nama bidang (contoh: Engineering & Construction)" 
                                   maxlength="255" 
                                   required>
                            @error('nama_Bidang')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Nama lengkap bidang (maksimal 255 karakter)
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Karakter tersisa: <span id="namaCounter">255</span></small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('bidangs.index') }}" class="btn btn-outline-secondary-custom btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg shadow">
                            <i class="fas fa-save me-2"></i>Simpan Bidang
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-question-circle me-1"></i>Bantuan
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-primary">Kode GL:</strong>
                            <ul class="mt-1 small text-muted mb-0">
                                <li>Harus berupa angka</li>
                                <li>Harus unik (tidak boleh sama)</li>
                                <li>Digunakan sebagai kunci utama</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-success">Nama Bidang:</strong>
                            <ul class="mt-1 small text-muted mb-0">
                                <li>Maksimal 255 karakter</li>
                                <li>Harus unik dan deskriptif</li>
                                <li>Akan muncul dalam dropdown</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter for nama bidang
        const namaInput = document.getElementById('nama_Bidang');
        const namaCounter = document.getElementById('namaCounter');
        
        namaInput.addEventListener('input', function() {
            const remaining = 255 - this.value.length;
            namaCounter.textContent = remaining;
            namaCounter.className = remaining < 25 ? 'text-danger' : 'text-muted';
        });
        
        // Form validation enhancement
        const form = document.getElementById('bidangCreateForm');
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
        // Auto-format kode GL
        const kodeInput = document.getElementById('kodeGl');
        kodeInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseInt(this.value) || '';
            }
        });
        
        // Auto-capitalize nama bidang
        namaInput.addEventListener('blur', function() {
            this.value = this.value.trim();
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-control:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        transform: scale(1.02);
        transition: all 0.2s ease;
    }
    
    .input-group:focus-within {
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
@endsection
