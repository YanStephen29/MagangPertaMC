@php
    $indentText = str_repeat('  ', $level - 1); // 2 spaces per level for indentation
    $displayNumber = isset($parentNumber) ? $parentNumber . '.' . $currentNumber : $currentNumber;
@endphp

<tr>
    <td style="border:1px solid #000;text-align:center;">{{ $rowNumber }}</td>
    <td style="border:1px solid #000;"></td>
    <td style="border:1px solid #000;">{{ $indentText }}{{ $displayNumber }}. {{ $detail->nama_detail }}</td>
    <td style="border:1px solid #000;text-align:center;">{{ $detail->quantity ?? '-' }}</td>
    <td style="border:1px solid #000;text-align:center;">{{ $detail->unit ?? '-' }}</td>
    <td style="border:1px solid #000;text-align:right;">{{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') : '-' }}</td>
    <td style="border:1px solid #000;text-align:right;">{{ $detail->formatted_harga_total ?? '-' }}</td>
</tr>