# Tool-Detail Relationship Documentation

## Struktur Database

### Relasi Tool dengan Detail
Sekarang tabel `details` sudah terhubung dengan tabel `tools` melalui foreign key `idTools`.

#### Schema:
- **tools**: Primary key `idTools` (int unsigned)
- **details**: Foreign key `idTools` (int unsigned, nullable)

## Relasi yang Tersedia

### 1. One-to-Many: Tool → Details
Satu tool request dapat mengandung banyak details (BOQ items).

```php
// Model Tool
public function details()
{
    return $this->hasMany(Detail::class, 'idTools', 'idTools');
}

// Model Detail  
public function tool()
{
    return $this->belongsTo(Tool::class, 'idTools', 'idTools');
}
```

## Contoh Penggunaan

### 1. Menyimpan Tool dengan Details

```php
// 1. Buat tool request
$tool = Tool::create([
    'Description' => 'Excavator Request',
    'quantity' => 2,
    'unit' => 'unit',
    'delivery_date' => '2025-12-01',
    'no_IO' => 'PRJ001',
    'kode_GL' => 'GL001'
]);

// 2. Tambahkan details ke tool
$details = [
    [
        'nama_detail' => 'Fuel Cost',
        'quantity' => 100,
        'unit' => 'liter',
        'harga_satuan' => 15000,
        'section_id' => 1,
        'idTools' => $tool->idTools
    ],
    [
        'nama_detail' => 'Operator Cost',
        'quantity' => 2,
        'unit' => 'person',
        'harga_satuan' => 500000,
        'section_id' => 1,
        'idTools' => $tool->idTools
    ]
];

foreach ($details as $detailData) {
    Detail::create($detailData);
}
```

### 2. Mengambil Tool dengan Details

```php
// Ambil tool dengan semua details
$tool = Tool::with('details')->find(1);

echo "Tool: " . $tool->Description;
echo "Total Details: " . $tool->details->count();
echo "Total Value: " . $tool->details->sum('harga_total');

foreach ($tool->details as $detail) {
    echo "- " . $detail->nama_detail . ": " . $detail->formatted_harga_total;
}
```

### 3. Mengambil Detail dengan Tool

```php
// Ambil detail dengan informasi tool
$detail = Detail::with('tool')->find(1);

if ($detail->tool) {
    echo "Detail ini bagian dari tool: " . $detail->tool->Description;
} else {
    echo "Detail ini tidak terkait dengan tool request";
}
```

## API Endpoints yang Tersedia

### 1. Store Tool dengan Details
**POST** `/tool-details/store-with-details`

```json
{
  "Description": "Excavator Request",
  "quantity": 2,
  "unit": "unit",
  "delivery_date": "2025-12-01",
  "no_IO": "PRJ001",
  "kode_GL": "GL001",
  "details": [
    {
      "nama_detail": "Fuel Cost",
      "quantity": 100,
      "unit": "liter",
      "harga_satuan": 15000,
      "section_id": 1
    }
  ]
}
```

### 2. Get Tool dengan Details
**GET** `/tool-details/{toolId}/details`

### 3. Add Detail ke Tool
**POST** `/tool-details/{toolId}/add-detail`

```json
{
  "nama_detail": "Maintenance Cost",
  "quantity": 1,
  "unit": "service",
  "harga_satuan": 2000000,
  "section_id": 1
}
```

### 4. List Details by Tool
**GET** `/tool-details/{toolId}/list-details`

## Cara Kerja Request Process

### Flow Proses:
1. **Tool Request Creation**: User membuat tool request
2. **BOQ Selection**: User memilih BOQ items yang dibutuhkan
3. **Detail Assignment**: BOQ items yang dipilih akan di-assign ke tool dengan `idTools`
4. **Database Save**: Details disimpan dengan foreign key ke tool

### Contoh dalam Form:
```html
<!-- Form Tool Request -->
<form action="/tool-details/store-with-details" method="POST">
    <!-- Tool Information -->
    <input name="Description" value="Excavator Request">
    <input name="quantity" value="2">
    
    <!-- Selected BOQ Items akan menjadi Details -->
    <input name="details[0][nama_detail]" value="Fuel Cost">
    <input name="details[0][quantity]" value="100">
    <input name="details[0][harga_satuan]" value="15000">
    
    <input name="details[1][nama_detail]" value="Operator Cost">
    <input name="details[1][quantity]" value="2">
    <input name="details[1][harga_satuan]" value="500000">
</form>
```

## Benefits

### 1. Data Integrity
- Foreign key constraint memastikan detail hanya bisa terkait dengan tool yang valid
- Cascade rules mengatur penghapusan data

### 2. Query Efficiency
- Bisa mengambil tool dengan semua details sekaligus menggunakan eager loading
- Index pada `idTools` di tabel details meningkatkan performa query

### 3. Business Logic
- Satu tool request bisa mengandung multiple BOQ items
- Tracking cost breakdown per tool request
- Audit trail untuk approval process

## Database Constraints

```sql
-- Foreign key constraint di tabel details
ALTER TABLE details 
ADD CONSTRAINT fk_details_tools 
FOREIGN KEY (idTools) REFERENCES tools(idTools) 
ON DELETE SET NULL;
```

**Note**: Menggunakan `SET NULL` agar jika tool dihapus, details tetap tersimpan untuk audit purpose tapi tidak lagi terkait dengan tool.

## Testing

Untuk testing relasi ini:

```php
// Test basic relationship
$tool = Tool::factory()->create();
$detail = Detail::factory()->create(['idTools' => $tool->idTools]);

// Test relationship works
$this->assertTrue($tool->details->contains($detail));
$this->assertEquals($tool->idTools, $detail->tool->idTools);
```