@extends('layouts.management')

@section('content')
<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary-custom btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('events.index') }}" class="text-decoration-none text-blue">
                        <i class="fas fa-home me-1"></i>Events
                    </a>
                </li>
                <li class="breadcrumb-item active text-red">Tambah Event Baru</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Header Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm gradient-background">
            <div class="card-body text-center py-4">
                <i class="fas fa-plus-circle fa-3x text-primary-custom mb-3"></i>
                <h2 class="text-primary-custom mb-2 fw-light">Buat Event Baru</h2>
                <p class="text-muted mb-0">Tambahkan event baru untuk PT Pertamina Maintenance & Construction</p>
            </div>
        </div>
    </div>
</div>

<!-- Form -->
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-custom">
            <div class="card-header card-header-primary position-relative">
                <div class="position-absolute top-0 end-0 opacity-15 pe-3 pt-2">
                    <i class="fas fa-calendar-plus" style="font-size: 3rem;"></i>
                </div>
                <div class="position-relative">
                    <h4 class="mb-1 fw-light">
                        <i class="fas fa-plus me-2"></i>Form Event Baru
                    </h4>
                    <p class="mb-0 opacity-80">Isi informasi dasar untuk event baru</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('events.store') }}" id="eventForm">
                    @csrf
                    
                    <!-- No I/O Field -->
                    <div class="mb-4">
                        <label for="no_I_O" class="form-label fw-semibold">
                            <i class="fas fa-hashtag me-1 text-primary-custom"></i>
                            No I/O <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light-custom border-primary-custom">
                                <i class="fas fa-key text-primary-custom"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-lg @error('no_I_O') is-invalid @enderror" 
                                   id="no_I_O" 
                                   name="no_I_O" 
                                   value="{{ old('no_I_O') }}" 
                                   placeholder="Contoh: PMC/2025/001" 
                                   maxlength="20" 
                                   required>
                            @error('no_I_O')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Nomor identifikasi unik untuk event (maksimal 20 karakter)
                        </div>
                    </div>

                    <!-- Title Field -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold">
                            <i class="fas fa-tag me-1 text-secondary-custom"></i>
                            Judul Event <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light-custom border-primary-custom">
                                <i class="fas fa-edit text-secondary-custom"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="Contoh: Maintenance Kompressor A1" 
                                   maxlength="50" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Nama atau judul yang mendeskripsikan event (maksimal 50 karakter)
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Karakter tersisa: <span id="titleCounter">50</span></small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg shadow">
                            <i class="fas fa-save me-2"></i>Simpan Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-lightbulb me-1"></i>Tips Pengisian
                </h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>No I/O:</strong> Gunakan format yang konsisten, contoh: PMC/2025/001
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Judul:</strong> Buat judul yang deskriptif dan mudah dipahami
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Setelah dibuat:</strong> Anda dapat menambahkan tools untuk event ini
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter for title
        const titleInput = document.getElementById('title');
        const titleCounter = document.getElementById('titleCounter');
        
        titleInput.addEventListener('input', function() {
            const remaining = 50 - this.value.length;
            titleCounter.textContent = remaining;
            titleCounter.className = remaining < 10 ? 'text-danger' : 'text-muted';
        });
        
        // Auto-focus first input
        document.getElementById('no_I_O').focus();
        
        // Form validation enhancement
        const form = document.getElementById('eventForm');
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
        // Auto-format No I/O (optional)
        const noIOInput = document.getElementById('no_I_O');
        noIOInput.addEventListener('blur', function() {
            this.value = this.value.toUpperCase().trim();
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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
