# Analisis: Haruskah Menghapus tool_details dan Menggunakan request_details Saja?

## Current Situation
Saat ini ada 2 tabel pivot yang fungsinya hampir sama:
- `tool_details`: tools ←→ details  
- `request_details`: requests ←→ details

## Masalah
1. **Data Duplication**: Data BOQ items disimpan 2x
2. **Complexity**: Maintain 2 tabel untuk hal yang sama
3. **Confusion**: Developer bingung harus pakai yang mana

## Solusi yang Direkomendasikan

### Opsi 1: Hapus tool_details, Gunakan request_details Saja
**Pros:**
- Satu source of truth
- Lebih simple
- Data tidak duplikat
- Request table sudah ada relationship ke tools (`tool_id`)

**Cons:**
- Harus selalu buat Request ketika tool dibuat

### Opsi 2: Hapus request_details, Gunakan tool_details Saja
**Pros:**
- Tool adalah entitas utama
- Lebih direct relationship
- Tools bisa exist tanpa Request

**Cons:**
- Request kehilangan detail breakdown
- Sulit tracking per-request basis

### Opsi 3: Merge Keduanya
**Pros:**
- Fleksibilitas maksimal
- Support kedua use case

**Cons:**
- Kompleks
- Over-engineering

## Rekomendasi: Gunakan request_details Saja

### Alasan:
1. **Request sudah terhubung ke Tool** via `tool_id`
2. **BOQ details terhubung ke Request** via `request_details`
3. **Tools bisa akses details** melalui: `tool -> request -> request_details -> details`

### Flow yang Disarankan:
```
Tools (1) → Requests (1) → RequestDetails (many) → Details (many)

tool.request.requestDetails.each { |rd| rd.detail }
```

### Implementasi:
```php
// Di Tool model
public function details()
{
    return $this->hasManyThrough(
        Detail::class,           // Final model
        RequestDetail::class,    // Intermediate model  
        'request_id',           // Foreign key di request_details ke requests
        'no',                   // Foreign key di details
        'idTools',              // Local key di tools
        'detail_id'             // Local key di request_details ke details
    )->join('requests', 'requests.id_req', '=', 'request_details.request_id')
     ->where('requests.tool_id', $this->idTools);
}
```

### Migration untuk Cleanup:
```php
// Drop tool_details table
Schema::dropIfExists('tool_details');
```

## Kesimpulan
**Hapus `tool_details` dan gunakan `request_details` saja** karena:
1. Requests sudah ada relationship ke Tools
2. Menghindari data duplication
3. Satu source of truth untuk BOQ usage
4. Lebih align dengan business process (Tool → Request → Procurement)