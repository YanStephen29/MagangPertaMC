# Fix: Requested Quantity Harus Integer

## Masalah yang Diperbaiki

1. **Requested quantity berupa decimal (koma)** - Seharusnya integer
2. **Proportional distribution** - Seharusnya menggunakan quantity penuh dari BOQ
3. **Form input masih accept decimal** - Seharusnya integer only

## Perubahan yang Dilakukan

### 1. Database Schema
- **Migration**: `2025_10_13_100000_change_requested_quantity_to_integer.php`
- **Column**: `request_details.requested_quantity` diubah dari `DECIMAL(15,2)` ke `INTEGER`

### 2. Model Updates
```php
// RequestDetail.php
protected $casts = [
    'requested_quantity' => 'integer', // Changed from 'decimal:2'
    'unit_price' => 'decimal:2',
    'total_price' => 'decimal:2',
];
```

### 3. Controller Logic - ToolController.php
**Before (Proportional):**
```php
$proportionalQuantity = ($boqItem->quantity / $totalBoqQuantity) * $requestedQuantity;
// Result: 10.75, 5.33, etc (decimal)
```

**After (Full Quantity):**
```php
$requestedQuantityForItem = intval($boqItem->quantity);
$finalQuantity = min($requestedQuantityForItem, $availableQuantity);
// Result: 10, 5, etc (integer)
```

### 4. Validation Updates
```php
// ToolController.php store()
'quantity' => 'required|integer|min:1', // Changed from 'numeric|min:0.01'

// ToolDetailController.php
'details.*.quantity' => 'required|integer|min:1', // Changed from 'numeric|min:0'
```

### 5. Form Input Updates
```html
<!-- Before -->
<input type="number" step="0.01" min="0.01">

<!-- After -->
<input type="number" step="1" min="1">
```

## Logic Baru untuk Tool Request

### Flow:
1. **User pilih BOQ items** dari BOQ selection
2. **System menggunakan quantity penuh** dari setiap BOQ item yang dipilih
3. **Check availability** untuk setiap item (BOQ quantity - used quantity)
4. **Save RequestDetail** dengan integer quantity

### Contoh:
**BOQ Items yang dipilih:**
- Item A: quantity = 100 unit
- Item B: quantity = 50 unit  
- Item C: quantity = 25 unit

**Result di request_details:**
```
| request_id | detail_id | requested_quantity |
|------------|-----------|-------------------|
| 123        | A         | 100               |
| 123        | B         | 50                |
| 123        | C         | 25                |
```

**NOT (proportional):**
```
| request_id | detail_id | requested_quantity |
|------------|-----------|-------------------|
| 123        | A         | 57.14             |
| 123        | B         | 28.57             |
| 123        | C         | 14.29             |
```

## Benefits

### 1. **Data Integrity**
- Quantity selalu integer (tidak ada 10.75 unit excavator)
- Konsisten dengan real-world usage

### 2. **Business Logic**
- Memaksimalkan penggunaan BOQ items yang dipilih
- User tidak perlu hitung manual proportional

### 3. **User Experience**
- Form input lebih jelas (step=1, min=1)
- Tidak ada confusion dengan decimal quantities

### 4. **Database Performance**
- Integer operations lebih cepat dari decimal
- Index lebih efisien untuk integer

## Testing

### Before Fix:
```sql
SELECT requested_quantity FROM request_details;
-- Result: 10.75, 5.33, 2.45 (decimal)
```

### After Fix:
```sql
SELECT requested_quantity FROM request_details;
-- Result: 100, 50, 25 (integer)
```

### Form Validation Test:
```javascript
// Input: 10.5
// Before: ✅ Accepted
// After: ❌ Rejected (step=1)

// Input: 10
// Before: ✅ Accepted  
// After: ✅ Accepted
```

## Migration Commands

```bash
# Apply the fix
php artisan migrate

# Check status
php artisan migrate:status
```

Sekarang sistem akan selalu menggunakan **integer quantity** dan **quantity penuh dari BOQ items** yang dipilih!