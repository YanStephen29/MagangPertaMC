<table>
    <!-- Company Header -->
    <tr>
        <td colspan="7" style="font-weight:bold;font-size:16px;text-align:center;background:#dc2626;color:white;">
            PT PERTAMINA MAINTENANCE AND CONSTRUCTION
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:12px;text-align:center;background:#007bff;color:white;">
            Excellence in Maintenance & Construction Services
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:10px;text-align:center;">
            Generated on: {{ date('d F Y, H:i') }} WIB
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    
    <!-- Project Information -->
    <tr>
        <td colspan="7" style="font-weight:bold;font-size:14px;text-align:center;background:#f8f9fa;">
            BOQ DETAILS - {{ $project->title_project }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:11px;text-align:center;">
            BOQ Number: {{ $boq->nomorBoq }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="font-size:11px;text-align:center;">
            Total Budget: {{ $boq->formatted_total_harga ?? 'Rp 0' }}
        </td>
    </tr>
    <tr>
        <td colspan="7"></td>
    </tr>
    
    <!-- Table Headers -->
    <tr style="background:#dc2626;color:white;font-weight:bold;">
        <td style="border:1px solid #000;text-align:center;">No.</td>
        <td style="border:1px solid #000;text-align:center;">Description</td>
        <td style="border:1px solid #000;text-align:center;">Note</td>
        <td style="border:1px solid #000;text-align:center;">Quantity</td>
        <td style="border:1px solid #000;text-align:center;">Unit</td>
        <td style="border:1px solid #000;text-align:center;">Unit Price</td>
        <td style="border:1px solid #000;text-align:center;">Total Cost</td>
    </tr>

    @foreach($sections as $sectionIndex => $section)
        @php $sectionNumber = $sectionIndex + 1; @endphp
        
        <!-- Section Row -->
        <tr style="background:#e3f2fd;font-weight:bold;">
            <td style="border:1px solid #000;">{{ $sectionNumber }}</td>
            <td style="border:1px solid #000;">{{ $section->nama }}</td>
            <td style="border:1px solid #000;">{{ $section->keterangan ?? '-' }}</td>
            <td style="border:1px solid #000;">-</td>
            <td style="border:1px solid #000;">-</td>
            <td style="border:1px solid #000;">-</td>
            <td style="border:1px solid #000;">{{ $section->formatted_total_harga ?? 'Rp 0' }}</td>
        </tr>

        @if($section->details && $section->details->count() > 0)
            @foreach($section->details as $detailIndex => $detail)
                @php $detailNumber = $sectionNumber . '.' . ($detailIndex + 1); @endphp
                
                <!-- Detail Row Level 1 -->
                <tr>
                    <td style="border:1px solid #000;">{{ $detailNumber }}</td>
                    <td style="border:1px solid #000;">{{ $detail->description }}</td>
                    <td style="border:1px solid #000;">{{ $detail->keterangan ?? '-' }}</td>
                    <td style="border:1px solid #000;text-align:right;">{{ $detail->quantity ?? 0 }}</td>
                    <td style="border:1px solid #000;text-align:center;">{{ $detail->unit ?? '-' }}</td>
                    <td style="border:1px solid #000;text-align:right;">{{ $detail->formatted_harga_satuan ?? 'Rp 0' }}</td>
                    <td style="border:1px solid #000;text-align:right;">{{ $detail->formatted_total_harga ?? 'Rp 0' }}</td>
                </tr>

                <!-- Level 2 Children -->
                @if($detail->children && $detail->children->count() > 0)
                    @foreach($detail->children as $childIndex => $child)
                        @php $childNumber = $detailNumber . '.' . ($childIndex + 1); @endphp
                        
                        <tr>
                            <td style="border:1px solid #000;">{{ $childNumber }}</td>
                            <td style="border:1px solid #000;">&nbsp;&nbsp;{{ $child->description }}</td>
                            <td style="border:1px solid #000;">{{ $child->keterangan ?? '-' }}</td>
                            <td style="border:1px solid #000;text-align:right;">{{ $child->quantity ?? 0 }}</td>
                            <td style="border:1px solid #000;text-align:center;">{{ $child->unit ?? '-' }}</td>
                            <td style="border:1px solid #000;text-align:right;">{{ $child->formatted_harga_satuan ?? 'Rp 0' }}</td>
                            <td style="border:1px solid #000;text-align:right;">{{ $child->formatted_total_harga ?? 'Rp 0' }}</td>
                        </tr>

                        <!-- Level 3 Children -->
                        @if($child->children && $child->children->count() > 0)
                            @foreach($child->children as $grandChildIndex => $grandChild)
                                @php $grandChildNumber = $childNumber . '.' . ($grandChildIndex + 1); @endphp
                                
                                <tr>
                                    <td style="border:1px solid #000;">{{ $grandChildNumber }}</td>
                                    <td style="border:1px solid #000;">&nbsp;&nbsp;&nbsp;&nbsp;{{ $grandChild->description }}</td>
                                    <td style="border:1px solid #000;">{{ $grandChild->keterangan ?? '-' }}</td>
                                    <td style="border:1px solid #000;text-align:right;">{{ $grandChild->quantity ?? 0 }}</td>
                                    <td style="border:1px solid #000;text-align:center;">{{ $grandChild->unit ?? '-' }}</td>
                                    <td style="border:1px solid #000;text-align:right;">{{ $grandChild->formatted_harga_satuan ?? 'Rp 0' }}</td>
                                    <td style="border:1px solid #000;text-align:right;">{{ $grandChild->formatted_total_harga ?? 'Rp 0' }}</td>
                                </tr>

                                <!-- Level 4 Children -->
                                @if($grandChild->children && $grandChild->children->count() > 0)
                                    @foreach($grandChild->children as $greatGrandChildIndex => $greatGrandChild)
                                        @php $greatGrandChildNumber = $grandChildNumber . '.' . ($greatGrandChildIndex + 1); @endphp
                                        
                                        <tr>
                                            <td style="border:1px solid #000;">{{ $greatGrandChildNumber }}</td>
                                            <td style="border:1px solid #000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $greatGrandChild->description }}</td>
                                            <td style="border:1px solid #000;">{{ $greatGrandChild->keterangan ?? '-' }}</td>
                                            <td style="border:1px solid #000;text-align:right;">{{ $greatGrandChild->quantity ?? 0 }}</td>
                                            <td style="border:1px solid #000;text-align:center;">{{ $greatGrandChild->unit ?? '-' }}</td>
                                            <td style="border:1px solid #000;text-align:right;">{{ $greatGrandChild->formatted_harga_satuan ?? 'Rp 0' }}</td>
                                            <td style="border:1px solid #000;text-align:right;">{{ $greatGrandChild->formatted_total_harga ?? 'Rp 0' }}</td>
                                        </tr>
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
        <td colspan="6" style="border:1px solid #000;text-align:center;">TOTAL BOQ</td>
        <td style="border:1px solid #000;text-align:right;">{{ $boq->formatted_total_harga ?? 'Rp 0' }}</td>
    </tr>
</table>