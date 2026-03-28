@extends('layouts.management')

@section('content')
<!-- Breadcrumb -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('events.index') }}" class="text-decoration-none text-primary-custom">
                        <i class="fas fa-home me-1"></i>Events
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('events.show', $event->{'no_I/O'}) }}" class="text-decoration-none text-primary-custom">
                        {{ Str::limit($event->title, 30) }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-secondary-custom">Edit Event</li>
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
                <h2 class="text-warning mb-2">Edit Event</h2>
                <p class="text-muted mb-1">Perbarui informasi untuk event: <strong>{{ $event->title }}</strong></p>
                <small class="text-muted">No I/O: {{ $event->{'no_I/O'} }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Event Info Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-hashtag fa-2x mb-2"></i>
                <h6>No I/O</h6>
                <h5>{{ $event->{'no_I/O'} }}</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-2x mb-2"></i>
                <h6>Tools Terdaftar</h6>
                <h5>{{ $event->tools->count() }} Items</h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-2x mb-2"></i>
                <h6>Dibuat</h6>
                <h5>{{ $event->created_at->format('d/m/Y') }}</h5>
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
                    <i class="fas fa-calendar-edit" style="font-size: 3rem;"></i>
                </div>
                <div class="position-relative">
                    <h4 class="mb-1">
                        <i class="fas fa-edit me-2"></i>Form Edit Event
                    </h4>
                    <p class="mb-0 opacity-75">Perbarui informasi event {{ $event->title }}</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('events.update', $event->{'no_I/O'}) }}" id="eventEditForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Current Info Alert -->
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Informasi Saat Ini</h6>
                                <p class="mb-0">
                                    <strong>No I/O:</strong> {{ $event->{'no_I/O'} }} | 
                                    <strong>Judul:</strong> {{ $event->title }}<br>
                                    <small class="text-muted">
                                        Terakhir diupdate: {{ $event->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- No I/O Field -->
                    <div class="mb-4">
                        <label for="no_I_O" class="form-label fw-bold">
                            <i class="fas fa-hashtag me-1 text-primary-custom"></i>
                            No I/O <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-key text-muted"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-lg @error('no_I_O') is-invalid @enderror" 
                                   id="no_I_O" 
                                   name="no_I_O" 
                                   value="{{ old('no_I_O', $event->{'no_I/O'}) }}" 
                                   placeholder="Masukkan No I/O" 
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
                        @if($event->tools->count() > 0)
                            <div class="alert alert-warning mt-2 py-2">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Perhatian:</strong> Event ini memiliki {{ $event->tools->count() }} tools terkait. Perubahan No I/O akan mempengaruhi semua relasi.
                                </small>
                            </div>
                        @endif
                    </div>

                    <!-- Title Field -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">
                            <i class="fas fa-tag me-1 text-secondary-custom"></i>
                            Judul Event <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-edit text-muted"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $event->title) }}" 
                                   placeholder="Masukkan judul event" 
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
                            <small class="text-muted">Karakter tersisa: <span id="titleCounter">{{ 50 - strlen($event->title) }}</span></small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('events.show', $event->{'no_I/O'}) }}" class="btn btn-outline-secondary-custom btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                        <div>
                            <a href="{{ route('events.show', $event->{'no_I/O'}) }}" class="btn btn-outline-info btn-lg me-2">
                                <i class="fas fa-eye me-2"></i>Lihat Detail
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg shadow">
                                <i class="fas fa-save me-2"></i>Update Event
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
                    <i class="fas fa-history me-1"></i>Riwayat Event
                </h6>
                <div class="row text-center">
                    <div class="col-md-6">
                        <div class="border-end">
                            <h6 class="text-success">Dibuat</h6>
                            <p class="mb-0">{{ $event->created_at->format('d/m/Y H:i') }}</p>
                            <small class="text-muted">{{ $event->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning">Terakhir Update</h6>
                        <p class="mb-0">{{ $event->updated_at->format('d/m/Y H:i') }}</p>
                        <small class="text-muted">{{ $event->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
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
        const originalLength = titleInput.value.length;
        
        titleInput.addEventListener('input', function() {
            const remaining = 50 - this.value.length;
            titleCounter.textContent = remaining;
            titleCounter.className = remaining < 10 ? 'text-danger' : 'text-muted';
        });
        
        // Form validation enhancement
        const form = document.getElementById('eventEditForm');
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengupdate...';
            submitBtn.disabled = true;
        });
        
        // Auto-format No I/O (optional)
        const noIOInput = document.getElementById('no_I_O');
        noIOInput.addEventListener('blur', function() {
            this.value = this.value.toUpperCase().trim();
        });
        
        // Highlight changes
        const inputs = ['no_I_O', 'title'];
        const originalValues = {
            'no_I_O': '{{ $event->{"no_I/O"} }}',
            'title': '{{ $event->title }}'
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
