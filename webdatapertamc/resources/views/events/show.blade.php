@extends('layouts.management')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light-custom p-3 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}" class="text-decoration-none text-primary-custom"><i class="fas fa-home me-1"></i>Events</a></li>
                <li class="breadcrumb-item active text-secondary-custom">{{ $event->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Event Header Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-custom">
            <div class="card-header card-header-primary position-relative">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1 fw-light">
                            <i class="fas fa-calendar-check me-2"></i>
                            {{ $event->title }}
                        </h3>
                        <p class="mb-0 opacity-80">Detail Lengkap Event dan Tools</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('events.edit', $event->{'no_I/O'}) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit Event
                        </a>
                        <a href="{{ route('events.export.single', $event->{'no_I/O'}) }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-download me-1"></i>Download Excel
                        </a>
                        <form method="POST" action="{{ route('events.destroy', $event->{'no_I/O'}) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus event ini beserta semua tools?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-trash me-1"></i>Hapus Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body gradient-background">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="info-box text-center p-3 bg-white rounded shadow-sm border-start border-primary-custom border-3">
                            <i class="fas fa-hashtag fa-2x text-primary-custom mb-2"></i>
                            <h6 class="text-muted mb-1 small">No I/O</h6>
                            <h5 class="text-primary-custom fw-bold">{{ $event->{'no_I/O'} }}</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center p-3 bg-white rounded shadow-sm border-start border-success border-3">
                            <i class="fas fa-tools fa-2x text-success mb-2"></i>
                            <h6 class="text-muted mb-1 small">Total Tools</h6>
                            <h5 class="text-success fw-bold">{{ $event->tools->count() }} Items</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center p-3 bg-white rounded shadow-sm border-start border-info border-3">
                            <i class="fas fa-calendar-plus fa-2x text-info mb-2"></i>
                            <h6 class="text-muted mb-1 small">Dibuat</h6>
                            <h5 class="text-info fw-bold">{{ $event->created_at->format('d/m/Y') }}</h5>
                            <small class="text-muted">{{ $event->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box text-center p-3 bg-white rounded shadow-sm border-start border-warning border-3">
                            <i class="fas fa-calendar-edit fa-2x text-warning mb-2"></i>
                            <h6 class="text-muted mb-1 small">Diupdate</h6>
                            <h5 class="text-warning fw-bold">{{ $event->updated_at->format('d/m/Y') }}</h5>
                            <small class="text-muted">{{ $event->updated_at->format('H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comprehensive Event Data Variables Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-custom">
            <div class="card-header card-header-secondary">
                <h4 class="mb-1 fw-semibold">
                    <i class="fas fa-database me-2"></i>Data Variables Event
                </h4>
                <p class="mb-0 small opacity-75">Semua variabel data yang tersimpan untuk event ini</p>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Basic Event Information -->
                    <div class="col-md-6">
                        <h6 class="text-primary-custom fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informasi Dasar Event
                        </h6>
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-muted" style="width: 40%;">Primary Key:</td>
                                    <td><code class="bg-light px-2 py-1 rounded">{{ $event->{'no_I/O'} }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Event Title:</td>
                                    <td><span class="badge bg-primary">{{ $event->title }}</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Table Name:</td>
                                    <td><code class="bg-light px-2 py-1 rounded">events</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Model Class:</td>
                                    <td><code class="bg-light px-2 py-1 rounded">App\Models\event</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Timestamps -->
                    <div class="col-md-6">
                        <h6 class="text-success fw-bold mb-3">
                            <i class="fas fa-clock me-2"></i>Timestamp Information
                        </h6>
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-muted" style="width: 40%;">Created At:</td>
                                    <td>
                                        <div>{{ $event->created_at->format('d/m/Y H:i:s') }}</div>
                                        <small class="text-muted">{{ $event->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Updated At:</td>
                                    <td>
                                        <div>{{ $event->updated_at->format('d/m/Y H:i:s') }}</div>
                                        <small class="text-muted">{{ $event->updated_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Age:</td>
                                    <td>
                                        <span class="badge bg-info">{{ $event->created_at->diffInDays() }} hari</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Last Modified:</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">{{ $event->updated_at->diffInHours() }} jam lalu</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Related Data Summary -->
                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="text-info fw-bold mb-3">
                            <i class="fas fa-tools me-2"></i>Tools Statistics
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Tools:</span>
                                <span class="fw-bold">{{ $event->tools->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Quantity:</span>
                                <span class="fw-bold">{{ $event->tools->sum('quantity') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Unique Units:</span>
                                <span class="fw-bold">{{ $event->tools->pluck('unit')->unique()->count() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Avg Quantity:</span>
                                <span class="fw-bold">{{ $event->tools->count() > 0 ? number_format($event->tools->avg('quantity'), 2) : '0' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h6 class="text-warning fw-bold mb-3">
                            <i class="fas fa-building me-2"></i>Bidang Distribution
                        </h6>
                        <div class="bg-light p-3 rounded">
                            @php
                                $bidangStats = $event->tools->groupBy('Bidang_kodeGl');
                            @endphp
                            @if($bidangStats->count() > 0)
                                @foreach($bidangStats->take(4) as $bidangId => $tools)
                                    @php
                                        $bidang = $tools->first()->bidang ?? null;
                                    @endphp
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">{{ $bidang ? $bidang->nama_Bidang : 'Unknown' }}:</span>
                                        <span class="fw-bold">{{ $tools->count() }} tools</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-muted text-center py-2">No bidang data</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h6 class="text-danger fw-bold mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>Delivery Timeline
                        </h6>
                        <div class="bg-light p-3 rounded">
                            @php
                                $upcomingDeliveries = $event->tools->where('deliveryDate', '>=', now())->count();
                                $pastDeliveries = $event->tools->where('deliveryDate', '<', now())->count();
                                $nearestDelivery = $event->tools->where('deliveryDate', '>=', now())->sortBy('deliveryDate')->first();
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Upcoming:</span>
                                <span class="fw-bold text-success">{{ $upcomingDeliveries }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Past Due:</span>
                                <span class="fw-bold text-danger">{{ $pastDeliveries }}</span>
                            </div>
                            @if($nearestDelivery)
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Next Delivery:</span>
                                    <span class="fw-bold text-info">{{ \Carbon\Carbon::parse($nearestDelivery->deliveryDate)->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Raw Data Debug -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-secondary fw-bold mb-3">
                            <i class="fas fa-code me-2"></i>Raw Event Data (Debug)
                        </h6>
                        <div class="bg-dark text-light p-3 rounded" style="font-family: 'Courier New', monospace; font-size: 0.85rem;">
                            <pre class="mb-0">{{ json_encode([
                                'event_data' => [
                                    'primary_key' => $event->{'no_I/O'},
                                    'title' => $event->title,
                                    'created_at' => $event->created_at->toISOString(),
                                    'updated_at' => $event->updated_at->toISOString(),
                                    'tools_count' => $event->tools->count(),
                                    'model_attributes' => $event->getAttributes(),
                                    'fillable_fields' => $event->getFillable(),
                                    'table_name' => $event->getTable(),
                                    'primary_key_name' => $event->getKeyName(),
                                    'key_type' => $event->getKeyType(),
                                    'incrementing' => $event->getIncrementing(),
                                ]
                            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
@if($event->tools->count() > 0)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-calculator fa-2x mb-2"></i>
                <h4>{{ $event->tools->sum('quantity') }}</h4>
                <p class="mb-0">Total Quantity</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-building fa-2x mb-2"></i>
                <h4>{{ $event->tools->pluck('bidang')->unique()->count() }}</h4>
                <p class="mb-0">Bidang Terlibat</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h4>{{ $event->tools->pluck('document')->unique()->count() }}</h4>
                <p class="mb-0">Document Terkait</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-2"></i>
                <h4>{{ $event->tools->where('deliveryDate', '>=', now())->count() }}</h4>
                <p class="mb-0">Pending Delivery</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Tools Section Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center bg-light-custom p-3 rounded border-primary-custom border-start border-3">
            <div>
                <h4 class="text-primary-custom mb-1 fw-semibold">
                    <i class="fas fa-tools me-2"></i>Daftar Tools & Equipment
                </h4>
                <p class="text-muted mb-0 small">Kelola semua tools dan equipment untuk event ini</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('request-items.create', ['event_no_io' => $event->{'no_I/O'}]) }}" class="btn btn-success shadow">
                    <i class="fas fa-clipboard-list me-2"></i>Request Item Baru
                </a>
                <a href="{{ route('tools.create', $event->{'no_I/O'}) }}" class="btn btn-primary shadow">
                    <i class="fas fa-plus me-2"></i>Tambah Tool Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tools Content -->
<div class="card border-0 shadow-custom">
    <div class="card-header card-header-secondary">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-secondary-custom fw-semibold">
                <i class="fas fa-list-ul me-2"></i>Tools dalam Event: {{ $event->title }}
            </h5>
            @if($event->tools->count() > 0)
                <span class="badge badge-primary-custom fs-6">{{ $event->tools->count() }} Items</span>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        @if($event->tools->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">
                                <i class="fas fa-hashtag me-1"></i>ID
                            </th>
                            <th>
                                <i class="fas fa-info-circle me-1"></i>Description
                            </th>
                            <th class="text-center">
                                <i class="fas fa-sort-numeric-up me-1"></i>Qty
                            </th>
                            <th class="text-center">
                                <i class="fas fa-balance-scale me-1"></i>Unit
                            </th>
                            <th class="text-center">
                                <i class="fas fa-calendar-day me-1"></i>Delivery
                            </th>
                            <th>
                                <i class="fas fa-comment me-1"></i>Remarks
                            </th>
                            <th class="text-center">
                                <i class="fas fa-building me-1"></i>Bidang
                            </th>
                            <th class="text-center">
                                <i class="fas fa-file-alt me-1"></i>Document
                            </th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->tools as $index => $tool)
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                        {{ $index + 1 }}
                                    </div>
                                    <strong class="text-primary-custom">#{{ $tool->idTools }}</strong>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong class="text-dark">{{ $tool->description }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Dibuat: {{ $tool->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="bg-light-custom rounded p-2">
                                    <strong class="text-primary-custom">{{ number_format($tool->quantity) }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $tool->unit }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $deliveryDate = \Carbon\Carbon::parse($tool->deliveryDate);
                                    $isOverdue = $deliveryDate->isPast();
                                    $isToday = $deliveryDate->isToday();
                                    $isSoon = $deliveryDate->isFuture() && $deliveryDate->diffInDays() <= 7;
                                @endphp
                                <div>
                                    <strong class="
                                        @if($isOverdue) text-danger 
                                        @elseif($isToday) text-warning 
                                        @elseif($isSoon) text-info 
                                        @else text-success 
                                        @endif
                                    ">
                                        {{ $deliveryDate->format('d/m/Y') }}
                                    </strong>
                                    <br>
                                    <small class="
                                        @if($isOverdue) text-danger 
                                        @elseif($isToday) text-warning 
                                        @elseif($isSoon) text-info 
                                        @else text-muted 
                                        @endif
                                    ">
                                        @if($isOverdue)
                                            <i class="fas fa-exclamation-triangle"></i> Terlambat
                                        @elseif($isToday)
                                            <i class="fas fa-clock"></i> Hari ini
                                        @elseif($isSoon)
                                            <i class="fas fa-hourglass-half"></i> {{ $deliveryDate->diffInDays() }} hari lagi
                                        @else
                                            <i class="fas fa-check-circle"></i> {{ $deliveryDate->diffInDays() }} hari lagi
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $tool->remarks }}">
                                    <i class="fas fa-quote-left text-muted me-1"></i>
                                    {{ $tool->remarks }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($tool->bidang)
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-info mb-1">{{ $tool->bidang->nama_Bidang }}</span>
                                        <small class="text-muted">Kode: {{ $tool->bidang->kodeGl }}</small>
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-minus-circle"></i> Tidak ada
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($tool->document)
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="badge bg-secondary mb-1">{{ $tool->document->no_request }}</span>
                                        @if($tool->document->jenis_request)
                                            <small class="text-muted">{{ $tool->document->jenis_request }}</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-minus-circle"></i> Tidak ada
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($isOverdue)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                    </span>
                                @elseif($isToday)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Due Today
                                    </span>
                                @elseif($isSoon)
                                    <span class="badge bg-info">
                                        <i class="fas fa-hourglass-half me-1"></i>Due Soon
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>On Track
                                    </span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tools.edit', [$event->{'no_I/O'}, $tool->idTools]) }}" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Edit Tool"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('tools.destroy', [$event->{'no_I/O'}, $tool->idTools]) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tool: {{ $tool->description }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Hapus Tool"
                                                data-bs-toggle="tooltip">
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
            
            <!-- Tools Summary Footer -->
            <div class="card-footer bg-light">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">{{ $event->tools->count() }}</h5>
                            <small class="text-muted">Total Items</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h5 class="text-success mb-0">{{ number_format($event->tools->sum('quantity')) }}</h5>
                            <small class="text-muted">Total Quantity</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h5 class="text-info mb-0">{{ $event->tools->where('deliveryDate', '>=', now())->count() }}</h5>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-danger mb-0">{{ $event->tools->where('deliveryDate', '<', now())->count() }}</h5>
                        <small class="text-muted">Overdue</small>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-tools fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">Belum ada tools dalam event ini</h4>
                    <p class="text-muted mb-4">
                        Event ini belum memiliki tools atau equipment yang terdaftar. <br>
                        Mulai tambahkan tools pertama untuk event <strong>{{ $event->title }}</strong>
                    </p>
                </div>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('tools.create', $event->{'no_I/O'}) }}" class="btn btn-primary-red btn-lg shadow">
                        <i class="fas fa-plus me-2"></i>Tambah Tool Pertama
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Events
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Navigation & Quick Actions -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary btn-lg me-2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Events
                        </a>
                        <a href="{{ route('events.create') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Buat Event Baru
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('tools.create', $event->{'no_I/O'}) }}" class="btn btn-primary-red btn-lg me-2">
                            <i class="fas fa-tools me-2"></i>Tambah Tool
                        </a>
                        <a href="{{ route('events.export.single', $event->{'no_I/O'}) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Custom Styles -->
@push('styles')
<style>
    .info-box {
        transition: all 0.3s ease;
    }
    
    .info-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    
    .btn-group .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group .btn:hover {
        transform: scale(1.1);
    }
    
    .badge {
        font-size: 0.75em;
        padding: 0.5em 0.75em;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

<!-- Initialize Tooltips -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endpush
@endsection
