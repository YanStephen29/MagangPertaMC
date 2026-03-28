# Final Tool-Detail Relationship via RequestDetails

## Struktur Database Akhir

Sekarang sistem menggunakan **single source of truth** melalui `request_details`:

```
Tools (1) → Requests (1) → RequestDetails (many) → Details (many)
```

### Schema:
- **tools**: Primary key `idTools`
- **requests**: Foreign key `tool_id` → `tools.idTools`  
- **request_details**: Foreign keys `request_id` → `requests.id_req` dan `detail_id` → `details.no`
- **details**: Primary key `no`

## Relasi Model

### Tool Model
```php
// Akses details melalui request -> request_details -> details
public function details()
{
    return $this->hasManyThrough(
        Detail::class,           // Target model
        RequestDetail::class,    // Intermediate model
        'request_id',           // FK di request_details ke requests
        'no',                   // PK di details
        'idTools',              // PK di tools
        'detail_id'             // FK di request_details ke details
    )->join('requests', 'requests.id_req', '=', 'request_details.request_id')
     ->where('requests.tool_id', $this->idTools);
}

// Method alternatif yang lebih jelas
public function getDetailsWithRequestInfo()
{
    if (!$this->request) return collect();
    
    return $this->request->requestDetails()->with('detail')->get()
        ->map(function($rd) {
            $detail = $rd->detail;
            $detail->pivot_requested_quantity = $rd->requested_quantity;
            $detail->pivot_unit_price = $rd->unit_price;
            $detail->pivot_total_price = $rd->total_price;
            $detail->pivot_status = $rd->status;
            return $detail;
        });
}
```

### Detail Model
```php
// Akses tools yang menggunakan detail ini
public function getToolsUsingThisDetail()
{
    return $this->requestDetails()->with(['request.tool'])->get()
        ->map(function($rd) {
            $tool = $rd->request->tool;
            if ($tool) {
                $tool->pivot_requested_quantity = $rd->requested_quantity;
                $tool->pivot_unit_price = $rd->unit_price;
                $tool->pivot_total_price = $rd->total_price;
                $tool->pivot_status = $rd->status;
            }
            return $tool;
        })->filter();
}
```

## Contoh Penggunaan

### 1. Mendapatkan Details dari Tool
```php
$tool = Tool::find(123);

// Method 1: Menggunakan relationship (kompleks)
$details = $tool->details;

// Method 2: Menggunakan helper method (direkomendasikan)
$details = $tool->getDetailsWithRequestInfo();

foreach ($details as $detail) {
    echo "Detail: " . $detail->nama_detail;
    echo "Requested: " . $detail->pivot_requested_quantity;
    echo "Price: " . $detail->pivot_unit_price;
    echo "Status: " . $detail->pivot_status;
}
```

### 2. Mendapatkan Tools dari Detail
```php
$detail = Detail::find(1);
$tools = $detail->getToolsUsingThisDetail();

foreach ($tools as $tool) {
    echo "Tool: " . $tool->Description;
    echo "Requested: " . $tool->pivot_requested_quantity;
    echo "Status: " . $tool->pivot_status;
}
```

### 3. Create Tool dengan Details
```php
// 1. Buat tool
$tool = Tool::create([...]);

// 2. Buat request untuk tool (otomatis di controller)
$request = Request::create([
    'tool_id' => $tool->idTools,
    'type_surat' => 'SPS',
    'jenis_req' => 'PO',
    'no_surat' => 'REQ-' . date('YmdHis'),
    'date_req' => now(),
    'status_req' => 'approved'
]);

// 3. Attach BOQ details via RequestDetails
foreach ($selectedBoqItems as $boqItem) {
    RequestDetail::create([
        'request_id' => $request->id_req,
        'detail_id' => $boqItem->no,
        'requested_quantity' => $proportionalQuantity,
        'unit_price' => $boqItem->harga_satuan,
        'total_price' => $proportionalQuantity * $boqItem->harga_satuan,
        'status' => 'approved'
    ]);
}
```

## Keuntungan Solusi Ini

### 1. **Single Source of Truth**
- Tidak ada duplikasi data
- Satu tempat untuk menyimpan detail usage
- Konsisten dengan business flow

### 2. **Flexibility**
- Tools bisa punya multiple requests (future expansion)
- Details bisa digunakan multiple tools
- Status tracking per request-detail combination

### 3. **Data Integrity**
- Semua detail usage tercatat di request_details
- Audit trail lengkap
- Relationship yang jelas dan logical

### 4. **Performance**
- Satu join table lebih efisien
- Index yang sudah ada di request_details
- Mengurangi query complexity

## Migration Status

✅ Tabel `tool_details` berhasil dihapus
✅ Model `ToolDetail` berhasil dihapus  
✅ Relationships updated ke `request_details`
✅ Controllers updated untuk menggunakan `RequestDetails`

## Testing

```php
// Test relationship
$tool = Tool::with('request.requestDetails.detail')->find(1);
$details = $tool->getDetailsWithRequestInfo();

// Verify data
$this->assertGreaterThan(0, $details->count());
$this->assertNotNull($details->first()->pivot_requested_quantity);
```

Sekarang sistem lebih clean dan menggunakan single source of truth!