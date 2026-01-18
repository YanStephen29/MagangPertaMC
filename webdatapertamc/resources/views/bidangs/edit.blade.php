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
                <li class="breadcrumb-item active text-secondary-custom">Edit Bidang</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Header Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #f8d7da 0%, #cfe2ff 100%);">
            <div class="card-body text-center py-4">
                <i class="fas fa-edit fa-3x text-warning mb-3"></i>
                <h2 class="text-warning mb-2">Edit Bidang</h2>
                <p class="text-muted mb-1">Perbarui informasi untuk bidang: <strong>{{ $bidang->nama_Bidang }}</strong></p>
                <small class="text-muted">Kode GL: {{ $bidang->kodeGl }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Bidang Info Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-code fa-2x mb-2"></i>
                <h6>Kode GL</h6>
                <h5>{{ $bidang->kodeGl }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-2x mb-2"></i>
                <h6>Tools Terkait</h6>
                <h5>{{ $bidang->tools()->count() }} Items</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-2x mb-2"></i>
                <h6>Dibuat</h6>
                <h5>{{ $bidang->created_at->format('d/m/Y') }}</h5>
            </div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-lg">
            <div class="card-header card-header-secondary position-relative">
                <div class="position-absolute top-0 end-0 opacity-25 pe-3 pt-2">
                    <i class="fas fa-edit" style="font-size: 3rem;"></i>
                </div>
                <div class="position-relative">
                    <h4 class="mb-1">
                        <i class="fas fa-edit me-2"></i>Form Edit Bidang
                    </h4>
                    <p class="mb-0 opacity-75">Perbarui informasi bidang {{ $bidang->nama_Bidang }}</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('bidangs.update', $bidang->kodeGl) }}" id="bidangEditForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Current Info Alert -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Informasi Saat Ini</h6>
                                <p class="mb-0">
                                    <strong>Kode GL:</strong> {{ $bidang->kodeGl }} | 
                                    <strong>Nama:</strong> {{ $bidang->nama_Bidang }}<br>
                                    <small class="text-muted">
                                        Terakhir diupdate: {{ $bidang->updated_at->format('d/m/Y H:i') }}
                                    </small>
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
                                   value="{{ old('kodeGl', $bidang->kodeGl) }}" 
                                   placeholder="Masukkan Kode GL" 
                                   required>
                            @error('kodeGl')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Kode GL unik untuk identifikasi bidang (harus berupa angka)
                        </div>
                        @if($bidang->tools()->count() > 0)
                            <div class="alert alert-warning mt-2 py-2">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Perhatian:</strong> Bidang ini memiliki {{ $bidang->tools()->count() }} tools terkait. Perubahan Kode GL akan mempengaruhi semua relasi.
                                </small>
                            </div>
                        @endif
                    </div>

                    <!-- Nama Bidang Field -->
                    <div class="mb-4">
                        <label for="nama_Bidang" class="form-label fw-bold">
                            <i class="fas fa-tag me-1 text-secondary-custom"></i>
                            Nama Bidang <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-sitemap text-muted"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-lg @error('nama_Bidang') is-invalid @enderror" 
                                   id="nama_Bidang" 
                                   name="nama_Bidang" 
                                   value="{{ old('nama_Bidang', $bidang->nama_Bidang) }}" 
                                   placeholder="Masukkan nama bidang" 
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
                            Nama lengkap bidang untuk identifikasi (maksimal 255 karakter)
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Karakter tersisa: <span id="namaCounter">{{ 255 - strlen($bidang->nama_Bidang) }}</span></small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('bidangs.index') }}" class="btn btn-outline-secondary-custom btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <div>
                            <a href="{{ route('bidangs.show', $bidang->kodeGl) }}" class="btn btn-outline-info btn-lg me-2">
                                <i class="fas fa-eye me-2"></i>Lihat Detail
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg shadow">
                                <i class="fas fa-save me-2"></i>Update Bidang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- History Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-history me-1"></i>Riwayat Bidang
                </h6>
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="border-end">
                            <h6 class="text-success">Dibuat</h6>
                            <p class="mb-0">{{ $bidang->created_at->format('d/m/Y H:i') }}</p>
                            <small class="text-muted">{{ $bidang->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Terakhir Update</h6>
                        <p class="mb-0">{{ $bidang->updated_at->format('d/m/Y H:i') }}</p>
                        <small class="text-muted">{{ $bidang->updated_at->diffForHumans() }}</small>
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
        const originalLength = namaInput.value.length;
        
        namaInput.addEventListener('input', function() {
            const remaining = 255 - this.value.length;
            namaCounter.textContent = remaining;
            namaCounter.className = remaining < 20 ? 'text-danger' : 'text-muted';
        });
        
        // Form validation enhancement
        const form = document.getElementById('bidangEditForm');
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
            submitBtn.disabled = true;
        });
        
        // Auto-format Kode GL (optional)
        const kodeGlInput = document.getElementById('kodeGl');
        kodeGlInput.addEventListener('blur', function() {
            this.value = this.value.trim();
        });
        
        // Highlight changes
        const inputs = ['kodeGl', 'nama_Bidang'];
        const originalValues = {
            'kodeGl': '{{ $bidang->kodeGl }}',
            'nama_Bidang': '{{ $bidang->nama_Bidang }}'
        };
        
        inputs.forEach(fieldName => {
            const input = document.getElementById(fieldName);
            input.addEventListener('input', function() {
                if (this.value !== originalValues[fieldName]) {
                    this.classList.add('border-warning');
                    this.style.backgroundColor = '#fff3cd';
                } else {
                    this.classList.remove('border-warning');
                    this.style.backgroundColor = '';
                }
            });
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
    
    .border-warning {
        animation: pulse-warning 2s infinite;
    }
    
    @keyframes pulse-warning {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        70% { box-shadow: 0 0 0 5px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
</style>
@endpush
@endsection
