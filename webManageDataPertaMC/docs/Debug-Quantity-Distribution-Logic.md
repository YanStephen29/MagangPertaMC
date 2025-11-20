# Debug: Test Logic Baru untuk Quantity Distribution

## Test Case 1: Single BOQ Item Selected

**Input:**
- User request: 13 quantity
- BOQ items selected: 1 item (quantity: 18 available)

**Expected Output:**
```
request_details:
| detail_id | requested_quantity |
|-----------|-------------------|
| 123       | 13                |
```

**Logic:**
```php
if ($selectedItemsCount == 1) {
    $finalQuantity = min($userRequestedQuantity, $availableQuantity);
    // min(13, 18) = 13 ✅
}
```

## Test Case 2: Multiple BOQ Items Selected

**Input:**
- User request: 13 quantity
- BOQ items selected: 2 items
  - Item A: quantity 10 (available: 10)
  - Item B: quantity 20 (available: 20)
- Total BOQ: 30

**Expected Output:**
```
request_details:
| detail_id | requested_quantity |
|-----------|-------------------|
| A         | 4                 | (10/30 * 13 = 4.33 → 4)
| B         | 9                 | (20/30 * 13 = 8.67 → 9)
```

**Logic:**
```php
$proportionalQuantity = intval(($boqItem->quantity / $totalBoqQuantity) * $userRequestedQuantity);
// Item A: intval((10/30) * 13) = intval(4.33) = 4
// Item B: intval((20/30) * 13) = intval(8.67) = 8
// Total: 4 + 8 = 12 (mendekati 13) ✅
```

## Test Case 3: BOQ Capacity Exceeded

**Input:**
- User request: 25 quantity
- BOQ items selected: 1 item (quantity: 18 available)

**Expected Output:**
```
request_details:
| detail_id | requested_quantity |
|-----------|-------------------|
| 123       | 18                |
```

**Logic:**
```php
$finalQuantity = min($userRequestedQuantity, $availableQuantity);
// min(25, 18) = 18 ✅
```

## Verification Commands

```bash
# Test setelah membuat tool request
php artisan tinker
> $tool = App\Models\Tool::latest()->first()
> $tool->request->requestDetails->pluck('requested_quantity')->sum()
# Should equal user input (13) or less if capacity exceeded
```

## Expected Behavior

1. **User input ALWAYS dipakai** sebagai base calculation
2. **Proportional distribution** jika multiple BOQ items
3. **Capacity check** per BOQ item  
4. **Integer results** tanpa decimal
5. **Total requested ≤ user input**

## Debugging

Jika masih ada masalah, check:
1. Session BOQ items: `session('selected_boq_items_for_tool')`
2. BOQ quantities: `Detail::whereIn('no', $ids)->pluck('quantity')`
3. User input: `$request->quantity`
4. Final quantities: `RequestDetail::where('request_id', $id)->pluck('requested_quantity')`