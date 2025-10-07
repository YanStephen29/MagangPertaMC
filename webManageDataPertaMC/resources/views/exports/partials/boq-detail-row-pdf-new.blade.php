@php
    $indentClass = 'indent-' . ($level > 4 ? 4 : $level);
    $rowClass = 'detail-row-' . $level;
    
    // Add visual indicators for better hierarchy display in PDF
    $indentPrefix = match($level) {
        1 => '• ',          // Level 1: bullet
        2 => '◦ ',          // Level 2: hollow bullet  
        3 => '▪ ',          // Level 3: small square
        4 => '- ',          // Level 4: dash
        default => ''
    };
@endphp

<tr class="{{ $rowClass }}">
    <td class="text-left number-col">{{ $displayNumber }}</td>
    <td class="{{ $indentClass }}">{{ $indentPrefix }}{{ $detail->nama_detail }}</td>
    <td>{{ $detail->note ?? '' }}</td>
    <td class="text-center">{{ $detail->quantity ?? '' }}</td>
    <td class="text-center">{{ $detail->unit ?? '' }}</td>
    <td class="text-right currency">{{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') : '' }}</td>
    <td class="text-right amount currency">{{ $detail->formatted_harga_total ?? '' }}</td>
</tr>