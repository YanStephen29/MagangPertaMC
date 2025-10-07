@php
    $indentClass = 'indent-' . ($level > 3 ? 3 : $level);
    $displayNumber = isset($parentNumber) ? $parentNumber . '.' . $currentNumber : $currentNumber;
@endphp

<tr>
    <td class="text-center">{{ $rowNumber }}</td>
    <td></td>
    <td class="{{ $indentClass }}">{{ $displayNumber }}. {{ $detail->nama_detail }}</td>
    <td class="text-center">{{ $detail->quantity ?? '-' }}</td>
    <td class="text-center">{{ $detail->unit ?? '-' }}</td>
    <td class="text-right">{{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') : '-' }}</td>
    <td class="text-right">{{ $detail->formatted_harga_total ?? '-' }}</td>
</tr>