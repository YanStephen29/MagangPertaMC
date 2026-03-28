<table>
    <!-- Company Header -->
    <tr>
        <td colspan="7" style="font-weight:bold;font-size:18px;text-align:center;background:#dc2626;color:white;padding:18px;">
            PT PERTAMINA MAINTENANCE AND CONSTRUCTION
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:12px;text-align:center;background:#007bff;color:white;font-style:italic;padding:10px;">
            Excellence in Maintenance & Construction Services
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:11px;text-align:center;background:#ffffff;color:#495057;padding:8px;border:1px solid #dee2e6;">
            Generated on: {{ date('d F Y, H:i') }} WIB
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    
    <!-- Header Information -->
    <tr>
        <td colspan="7" style="font-weight:bold;font-size:16px;text-align:center;background:#f8f9fa;color:#dc2626;padding:12px;border:2px solid #007bff;">
            {{ $project->title_project }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-weight:bold;font-size:14px;text-align:center;background:#ffffff;color:#007bff;padding:10px;border:1px solid #dee2e6;">
            IO: {{ $project->no_IO }} | BOQ: {{ $boq->nomorBoq }}
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    
    <!-- Table Headers -->
    <tr style="background:#dc2626;color:white;font-weight:bold;">
        <td style="border:1px solid #ffffff;text-align:left;width:10%;font-weight:bold;padding:12px;">No.</td>
        <td style="border:1px solid #ffffff;text-align:center;width:35%;padding:12px;">Description</td>
        <td style="border:1px solid #ffffff;text-align:center;width:15%;padding:12px;">Note</td>
        <td style="border:1px solid #ffffff;text-align:center;width:10%;padding:12px;">Quantity</td>
        <td style="border:1px solid #ffffff;text-align:center;width:8%;padding:12px;">Unit</td>
        <td style="border:1px solid #ffffff;text-align:center;width:12%;padding:12px;">Price/Unit</td>
        <td style="border:1px solid #ffffff;text-align:center;width:10%;padding:12px;">Total Cost</td>
    </tr>

    @foreach($sections as $sectionIndex => $section)
        @php $sectionNumber = $sectionIndex + 1; @endphp
        
        <!-- Section Row -->
        <tr style="background:#ffffff;font-weight:bold;">
            <td style="border:1px solid #dee2e6;text-align:left;padding:10px;font-weight:bold;">{{ $sectionNumber }}</td>
            <td style="border:1px solid #dee2e6;padding:10px;font-weight:bold;color:#2c3e50;">{{ $section->nama }}</td>
            <td style="border:1px solid #dee2e6;padding:10px;">{{ $section->note ?? '' }}</td>
            <td style="border:1px solid #dee2e6;"></td>
            <td style="border:1px solid #dee2e6;"></td>
            <td style="border:1px solid #dee2e6;"></td>
            <td style="border:1px solid #dee2e6;text-align:right;padding:10px;font-weight:bold;">{{ $section->formatted_total_harga ?? 'Rp 0' }}</td>
        </tr>

        @if($section->details && $section->details->count() > 0)
            @foreach($section->details as $detailIndex => $detail)
                @php $detailNumber = $sectionNumber . '.' . ($detailIndex + 1); @endphp
                
                @include('exports.partials.boq-detail-row-excel-new', [
                    'detail' => $detail,
                    'level' => 1,
                    'displayNumber' => $detailNumber,
                    'sectionNumber' => $sectionNumber
                ])
                
                @if($detail->children && $detail->children->count() > 0)
                    @foreach($detail->children as $childIndex => $child)
                        @php $childNumber = $detailNumber . '.' . ($childIndex + 1); @endphp
                        
                        @include('exports.partials.boq-detail-row-excel-new', [
                            'detail' => $child,
                            'level' => 2,
                            'displayNumber' => $childNumber,
                            'sectionNumber' => $sectionNumber
                        ])
                        
                        @if($child->children && $child->children->count() > 0)
                            @foreach($child->children as $grandChildIndex => $grandChild)
                                @php $grandChildNumber = $childNumber . '.' . ($grandChildIndex + 1); @endphp
                                
                                @include('exports.partials.boq-detail-row-excel-new', [
                                    'detail' => $grandChild,
                                    'level' => 3,
                                    'displayNumber' => $grandChildNumber,
                                    'sectionNumber' => $sectionNumber
                                ])
                                
                                @if($grandChild->children && $grandChild->children->count() > 0)
                                    @foreach($grandChild->children as $greatGrandChildIndex => $greatGrandChild)
                                        @php $greatGrandChildNumber = $grandChildNumber . '.' . ($greatGrandChildIndex + 1); @endphp
                                        
                                        @include('exports.partials.boq-detail-row-excel-new', [
                                            'detail' => $greatGrandChild,
                                            'level' => 4,
                                            'displayNumber' => $greatGrandChildNumber,
                                            'sectionNumber' => $sectionNumber
                                        ])
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
    
    <!-- Total Row -->
    <tr style="background:#dc2626;color:white;font-weight:bold;">
        <td colspan="6" style="border:1px solid #ffffff;text-align:center;padding:12px;font-size:14px;">TOTAL BOQ</td>
        <td style="border:1px solid #ffffff;text-align:right;padding:12px;font-size:14px;font-weight:bold;">{{ $boq->formatted_total_harga ?? 'Rp 0' }}</td>
    </tr>
</table>