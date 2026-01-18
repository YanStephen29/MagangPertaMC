@extends('layouts.management')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.show', $event->{'no_I/O'}) }}">{{ $event->title }}</a></li>
                <li class="breadcrumb-item active">Edit Tool</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header card-header-secondary">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Tool: {{ $tool->description }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tools.update', [$event->{'no_I/O'}, $tool->idTools]) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                       id="description" name="description" value="{{ old('description', $tool->description) }}" 
                                       placeholder="Masukkan deskripsi tool" maxlength="50" required>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity', $tool->quantity) }}" 
                                       placeholder="0" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                                       id="unit" name="unit" value="{{ old('unit', $tool->unit) }}" 
                                       placeholder="pcs, kg, m, dll" maxlength="20" required>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="delivery_date" class="form-label">Delivery Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" 
                                       id="delivery_date" name="delivery_date" value="{{ old('delivery_date', \Carbon\Carbon::parse($tool->deliveryDate)->format('Y-m-d')) }}" required>
                                @error('delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('remarks') is-invalid @enderror" 
                                       id="remarks" name="remarks" value="{{ old('remarks', $tool->remarks) }}" 
                                       placeholder="Catatan atau keterangan" maxlength="50" required>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Bidang_kodeGl" class="form-label">Bidang <span class="text-danger">*</span></label>
                                <select class="form-select @error('Bidang_kodeGl') is-invalid @enderror" 
                                        id="Bidang_kodeGl" name="Bidang_kodeGl" required>
                                    <option value="">Pilih Bidang</option>
                                    @foreach($bidangs as $bidang)
                                        <option value="{{ $bidang->kodeGl }}" {{ old('Bidang_kodeGl', $tool->Bidang_kodeGl) == $bidang->kodeGl ? 'selected' : '' }}>
                                            {{ $bidang->nama_Bidang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Bidang_kodeGl')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="Document_no_request" class="form-label">Document <span class="text-danger">*</span></label>
                                <select class="form-select @error('Document_no_request') is-invalid @enderror" 
                                        id="Document_no_request" name="Document_no_request" required>
                                    <option value="">Pilih Document</option>
                                    @foreach($documents as $document)
                                        <option value="{{ $document->no_request }}" {{ old('Document_no_request', $tool->Document_no_request) == $document->no_request ? 'selected' : '' }}>
                                            {{ $document->no_request }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Document_no_request')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('events.show', $event->{'no_I/O'}) }}" class="btn btn-outline-secondary-custom">
                            <i class="fas fa-arrow-left me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Tool
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
