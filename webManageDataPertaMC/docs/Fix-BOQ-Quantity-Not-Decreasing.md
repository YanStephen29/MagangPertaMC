# Fix: BOQ Quantity Tidak Berkurang Setelah Request

## Masalah

Setelah membuat tool request pertama, quantity BOQ tidak berkurang saat membuat request kedua.

**Contoh:**
- BOQ Item A: 100 quantity
- Request 1: 20 quantity → Available should be 80
- Request 2: Masih show 100 available ❌

## Root Cause

Logic penghitungan `usedQuantity` hanya menghitung request dengan status `approved`:

```php
// WRONG ❌
$usedQuantity = $item->requestDetails()
    ->whereHas('request', function($q) {
        $q->where('status_req', 'approved'); // Hanya approved
    })
    ->sum('requested_quantity');
```

Padahal request yang baru dibuat biasanya status `pending` atau `hold`, jadi tidak diperhitungkan.

## Solusi

### 1. Update Logic Penghitungan
Hitung semua request yang **tidak rejected** (approved, pending, hold):

```php
// CORRECT ✅
$usedQuantity = $item->requestDetails()
    ->whereHas('request', function($q) {
        $q->whereIn('status_req', ['approved', 'pending', 'hold']);
    })
    ->sum('requested_quantity');
```

### 2. Helper Methods di Model Detail

```php
// Method untuk get available quantity
public function getAvailableQuantity()
{
    $usedQuantity = $this->requestDetails()
        ->whereHas('request', function($q) {
            $q->whereIn('status_req', ['approved', 'pending', 'hold']);
        })
        ->sum('requested_quantity');
    
    return max(0, $this->quantity - $usedQuantity);
}

// Method untuk usage breakdown
public function getUsageByStatus()
{
    // Return array dengan breakdown per status
}
```

### 3. Debug Routes

Tambahkan routes untuk debug BOQ usage:
- `/debug/boq-usage?detail_id=123`
- `/debug/project-boq/PRJ001`

## Test Scenario

### Before Fix:
```
BOQ Item: 100 quantity
Request 1: 20 quantity (status: pending)
Available: 100 ❌ (tidak berkurang)
```

### After Fix:
```
BOQ Item: 100 quantity  
Request 1: 20 quantity (status: pending)
Available: 80 ✅ (berkurang dengan benar)
```

## Status Request yang Diperhitungkan

| Status    | Diperhitungkan | Alasan |
|-----------|---------------|--------|
| approved  | ✅ Yes        | Request sudah disetujui |
| pending   | ✅ Yes        | Request dalam review, BOQ harus di-reserve |
| hold      | ✅ Yes        | Request hold tapi masih aktif |
| rejected  | ❌ No         | Request ditolak, BOQ quantity dikembalikan |

## Verification

### 1. Manual Test
1. Buat tool request pertama dengan quantity 20
2. Cek available quantity BOQ item
3. Buat tool request kedua
4. Available quantity harus sudah berkurang 20

### 2. Debug API
```bash
# Check BOQ usage
curl "/debug/boq-usage?detail_id=123"

# Response should show:
{
  "total_quantity": 100,
  "total_used": 20,
  "available_quantity": 80,
  "usage_by_status": {
    "approved": 0,
    "pending": 20,
    "hold": 0,
    "rejected": 0
  }
}
```

### 3. Database Check
```sql
-- Check request_details for specific BOQ item
SELECT 
    rd.requested_quantity,
    r.status_req,
    r.created_at
FROM request_details rd
JOIN requests r ON r.id_req = rd.request_id  
WHERE rd.detail_id = 123
ORDER BY r.created_at DESC;
```

## Files Modified

1. `ToolController.php` - Fixed used quantity calculation
2. `Detail.php` - Added helper methods
3. `DebugBoqController.php` - Added debug endpoints
4. `web.php` - Added debug routes

Sekarang BOQ quantity akan berkurang dengan benar setelah setiap request! 🎉