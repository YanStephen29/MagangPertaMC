@php
    // Create indentation using different approach for Excel
    $indentText = match($level) {
        1 => '  • ',          // Level 1: 2 spaces + bullet
        2 => '    ◦ ',        // Level 2: 4 spaces + hollow bullet  
        3 => '      ▪ ',      // Level 3: 6 spaces + small square
        4 => '        - ',    // Level 4: 8 spaces + dash
        default => ''
    };
@endphp

<tr style="background:#ffffff;">
    <td style="border:1px solid #dee2e6;text-align:left;padding:8px;">{{ $displayNumber }}</td>
    <td style="border:1px solid #dee2e6;padding:8px;">{{ $indentText }}{{ $detail->nama_detail }}</td>
    <td style="border:1px solid #dee2e6;padding:8px;">{{ $detail->note ?? '' }}</td>
    <td style="border:1px solid #dee2e6;text-align:center;padding:8px;">{{ $detail->quantity ?? '' }}</td>
    <td style="border:1px solid #dee2e6;text-align:center;padding:8px;">{{ $detail->unit ?? '' }}</td>
    <td style="border:1px solid #dee2e6;text-align:right;padding:8px;">{{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') : '' }}</td>
    <td style="border:1px solid #dee2e6;text-align:right;padding:8px;font-weight:normal;">{{ $detail->formatted_harga_total ?? '' }}</td>
</tr>