# Many-to-Many Tool-Detail Relationship Documentation

## Struktur Database

### Relasi Many-to-Many antara Tools dan Details
Tools dan Details sekarang memiliki relasi many-to-many melalui tabel pivot `tool_details`.

#### Schema:
- **tools**: Primary key `idTools` (int unsigned)
- **details**: Primary key `no` (bigint unsigned)
- **tool_details**: Pivot table dengan foreign keys ke kedua tabel

```sql
CREATE TABLE tool_details (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    idTools INT UNSIGNED NOT NULL,
    no_detail BIGINT UNSIGNED NOT NULL,
    requested_quantity DECIMAL(15,2) DEFAULT 0,
    unit_price DECIMAL(15,2) DEFAULT 0,
    total_price DECIMAL(15,2) DEFAULT 0,
    status VARCHAR(255) DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (idTools) REFERENCES tools(idTools) ON DELETE CASCADE,
    FOREIGN KEY (no_detail) REFERENCES details(no) ON DELETE CASCADE,
    UNIQUE KEY unique_tool_detail (idTools, no_detail)
);
```

## Relasi Model

### Tool Model
```php
// Many-to-Many dengan Details
public function details()
{
    return $this->belongsToMany(Detail::class, 'tool_details', 'idTools', 'no_detail', 'idTools', 'no')
                ->withPivot('requested_quantity', 'unit_price', 'total_price', 'status', 'notes')
                ->withTimestamps();
}

// Direct access ke pivot records
public function toolDetails()
{
    return $this->hasMany(ToolDetail::class, 'idTools', 'idTools');
}
```

### Detail Model
```php
// Many-to-Many dengan Tools
public function tools()
{
    return $this->belongsToMany(Tool::class, 'tool_details', 'no_detail', 'idTools', 'no', 'idTools')
                ->withPivot('requested_quantity', 'unit_price', 'total_price', 'status', 'notes')
                ->withTimestamps();
}

// Direct access ke pivot records
public function toolDetails()
{
    return $this->hasMany(ToolDetail::class, 'no_detail', 'no');
}
```

## Contoh Data dalam Database

### Satu Tool dengan Beberapa Details:
```
tool_details table:
| id | idTools | no_detail | requested_quantity | unit_price | status   |
|----|---------|-----------|-------------------|------------|----------|
| 1  | 123     | 1         | 10.00             | 50000      | approved |
| 2  | 123     | 2         | 5.00              | 75000      | approved |
| 3  | 123     | 3         | 2.00              | 100000     | pending  |
```

### Satu Detail digunakan oleh Beberapa Tools:
```
tool_details table:
| id | idTools | no_detail | requested_quantity | unit_price | status   |
|----|---------|-----------|-------------------|------------|----------|
| 4  | 123     | 1         | 10.00             | 50000      | approved |
| 5  | 124     | 1         | 15.00             | 50000      | approved |
| 6  | 125     | 1         | 8.00              | 50000      | hold     |
```

## Contoh Penggunaan

### 1. Menyimpan Tool dengan Details (Many-to-Many)

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

// 2. Attach existing details ke tool (BOQ items yang dipilih)
$selectedDetails = [1, 2, 3]; // ID details dari BOQ

foreach ($selectedDetails as $detailId) {
    $detail = Detail::find($detailId);
    
    ToolDetail::create([
        'idTools' => $tool->idTools,
        'no_detail' => $detail->no,
        'requested_quantity' => 10, // Quantity yang diminta
        'unit_price' => $detail->harga_satuan,
        'total_price' => 10 * $detail->harga_satuan,
        'status' => 'approved',
        'notes' => 'Selected from BOQ'
    ]);
}
```

### 2. Mengambil Tool dengan Semua Details

```php
// Ambil tool dengan details menggunakan relationship
$tool = Tool::with('details')->find(123);

echo "Tool: " . $tool->Description;
echo "Total Details: " . $tool->details->count();

foreach ($tool->details as $detail) {
    echo "- " . $detail->nama_detail;
    echo "  Requested: " . $detail->pivot->requested_quantity;
    echo "  Price: " . $detail->pivot->unit_price;
    echo "  Status: " . $detail->pivot->status;
}
```

### 3. Mengambil Detail dengan Semua Tools yang Menggunakannya

```php
// Ambil detail dengan semua tools
$detail = Detail::with('tools')->find(1);

echo "Detail: " . $detail->nama_detail;
echo "Used by Tools: " . $detail->tools->count();

foreach ($detail->tools as $tool) {
    echo "- " . $tool->Description;
    echo "  Requested: " . $tool->pivot->requested_quantity;
    echo "  Status: " . $tool->pivot->status;
}
```

### 4. Query Complex dengan Pivot Data

```php
// Ambil semua tools yang menggunakan detail tertentu dengan status approved
$toolsUsingDetail = Tool::whereHas('details', function($query) use ($detailId) {
    $query->where('details.no', $detailId)
          ->wherePivot('status', 'approved');
})->with(['details' => function($query) use ($detailId) {
    $query->where('details.no', $detailId);
}])->get();

// Ambil total quantity yang sudah di-request untuk detail tertentu
$totalRequested = ToolDetail::where('no_detail', $detailId)
                           ->where('status', 'approved')
                           ->sum('requested_quantity');
```

### 5. Update Status di Pivot Table

```php
// Update status tool-detail relationship
$toolDetail = ToolDetail::where('idTools', 123)
                       ->where('no_detail', 1)
                       ->first();

$toolDetail->update([
    'status' => 'approved',
    'notes' => 'Approved by manager'
]);

// Atau menggunakan relationship
$tool = Tool::find(123);
$tool->details()->updateExistingPivot(1, [
    'status' => 'approved',
    'notes' => 'Approved by manager'
]);
```

## Keuntungan Many-to-Many

### 1. Fleksibilitas
- Satu detail BOQ bisa digunakan oleh multiple tool requests
- Satu tool request bisa menggunakan multiple details BOQ
- Tracking quantity dan status per relasi

### 2. Data Integrity
- Tidak mengubah data original BOQ
- History lengkap penggunaan setiap detail
- Status tracking per penggunaan

### 3. Reporting
- Bisa lihat detail mana yang paling sering diminta
- Tool mana yang menggunakan detail terbanyak
- Status approval per tool-detail combination

## API Endpoints

### 1. Get Tool dengan Details
**GET** `/tool-details/{toolId}/details`

Response:
```json
{
  "tool": {
    "idTools": 123,
    "Description": "Excavator Request",
    "details": [
      {
        "no": 1,
        "nama_detail": "Fuel Cost",
        "pivot": {
          "requested_quantity": 10.00,
          "unit_price": 50000,
          "total_price": 500000,
          "status": "approved"
        }
      }
    ]
  }
}
```

### 2. Get Detail dengan Tools
**GET** `/details/{detailId}/tools`

Response:
```json
{
  "detail": {
    "no": 1,
    "nama_detail": "Fuel Cost",
    "tools": [
      {
        "idTools": 123,
        "Description": "Excavator Request",
        "pivot": {
          "requested_quantity": 10.00,
          "status": "approved"
        }
      }
    ]
  }
}
```

## Migration Commands

```bash
# Jalankan migration untuk menghapus foreign key lama
php artisan migrate

# Check status migration
php artisan migrate:status
```

Sekarang sistem menggunakan **true many-to-many relationship** dengan tabel pivot `tool_details` yang menyimpan setiap relasi dalam baris terpisah!