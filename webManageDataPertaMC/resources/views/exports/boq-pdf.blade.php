<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BOQ - {{ $project->title_project }}</title>
    <style>
        @media print {
            body { 
                margin: 0; 
                background: white !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            .no-print { display: none; }
            @page { 
                margin: 2cm 1.5cm;
                size: A4;
            }
            
            .company-header {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            
            .section-row {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            line-height: 1.4;
            color: #000000;
            background: #ffffff;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .company-header {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #dc2626;
            color: white;
            border: 2px solid #dc2626;
            page-break-inside: avoid;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .company-logo {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 15px;
            object-fit: contain;
            background: white;
            padding: 8px;
            border: 2px solid white;
        }
        
        .company-logo-fallback {
            width: 120px;
            height: 80px;
            background: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 15px;
            border: 2px solid white;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin: 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .company-tagline {
            font-size: 12px;
            color: white;
            margin: 0;
            font-style: italic;
            font-weight: normal;
        }
        
        .company-date {
            font-size: 10px;
            color: white;
            margin-top: 5px;
            font-weight: normal;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #ffffff;
            border: 2px solid #007bff;
            page-break-inside: avoid;
        }
        
        .header h1 {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #007bff;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 8px 0;
            color: #dc2626;
        }
        
        .header h3 {
            font-size: 12px;
            font-weight: normal;
            margin: 3px 0;
            color: #000000;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            page-break-inside: auto;
        }
        
        th {
            background: #dc2626;
            color: white;
            font-weight: bold;
            padding: 12px 8px;
            text-align: center;
            border: 1px solid #000000;
            font-size: 11px;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        td {
            padding: 8px 6px;
            border: 1px solid #000000;
            vertical-align: top;
            font-size: 10px;
            line-height: 1.3;
            background: white;
        }

        /* Number Column - Left Aligned */
        .number-col {
            text-align: left !important;
            font-weight: bold;
            color: #000000;
            width: 60px;
            min-width: 60px;
        }

        /* Section Row Styling */
        .section-row td {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
            color: #000000;
            border: 1px solid #dee2e6;
            padding: 10px 8px;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }

        /* Detail Row Styling - Simple and Clean */
        .detail-row-1 td, .detail-row-2 td, .detail-row-3 td, .detail-row-4 td { 
            background: #ffffff;
            border: 1px solid #dee2e6;
            font-weight: normal;
        }

        /* Currency Formatting */
        .currency {
            font-family: Arial, sans-serif;
            font-weight: normal;
            color: #000000;
            text-align: right;
        }
        
        .section-row {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            font-weight: bold;
            color: #1565c0;
        }
        
        .section-row td {
            padding: 10px 8px;
            font-size: 11px;
        }
        
        .detail-row-1 {
            background-color: #f8f9fa;
        }
        
        .detail-row-2 {
            background-color: #ffffff;
        }
        
        .detail-row-3 {
            background-color: #f5f5f5;
        }
        
        .detail-row-4 {
            background-color: #ffffff;
        }
        
        .total-row {
            background: #007bff;
            color: white;
            font-weight: bold;
            font-size: 12px;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .total-row td {
            padding: 12px 8px;
            text-transform: uppercase;
            border: 1px solid #000000;
            background: #007bff;
            color: white;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .indent-1 { padding-left: 15px; }
        .indent-2 { padding-left: 25px; }
        .indent-3 { padding-left: 35px; }
        .indent-4 { padding-left: 45px; }
        
        /* Number column styling */
        .number-col {
            font-weight: 600;
            color: #495057;
            min-width: 60px;
        }
        
        /* Amount styling */
        .amount {
            font-weight: 600;
            color: #28a745;
        }
        
        /* Currency styling */
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }
    </style>
    <script>
        // Auto-open print dialog when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</head>
<body>

    
    <!-- Company Header -->
    <div class="company-header">
        <img src="{{ asset('image/pertamina_logo.jpg') }}" alt="Pertamina Logo" class="company-logo" 
             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
        <div class="company-logo-fallback" style="display: none;">PERTAMINA</div>
        <div class="company-name">PT PERTAMINA MAINTENANCE AND CONSTRUCTION</div>
        <div class="company-tagline">Excellence in Maintenance & Construction Services</div>
        <div class="company-date">
            Generated on: {{ date('d F Y, H:i') }} WIB
        </div>
    </div>
    
    <div class="header">
        <h1>Bill of Quantity (BOQ)</h1>
        <h2>{{ $project->title_project }}</h2>
        <h3>Internal Order: {{ $project->no_IO }} | BOQ Number: {{ $boq->nomorBoq }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                                        <th style="text-align: left;">No</th>
                <th style="width: 35%;">Description</th>
                <th style="width: 15%;">Note</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 8%;">Unit</th>
                <th style="width: 12%;">Harga Satuan</th>
                <th style="width: 10%;">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $sectionIndex => $section)
                @php $sectionNumber = $sectionIndex + 1; @endphp
                
                <!-- Section Row -->
                <tr class="section-row">
                    <td class="text-left number-col">{{ $sectionNumber }}</td>
                    <td style="font-weight: bold; font-size: 12px;">{{ $section->nama }}</td>
                    <td>{{ $section->note ?? '' }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right amount currency">{{ $section->formatted_total_harga ?? 'Rp 0' }}</td>
                </tr>

                @if($section->details && $section->details->count() > 0)
                    @foreach($section->details as $detailIndex => $detail)
                        @php $detailNumber = $sectionNumber . '.' . ($detailIndex + 1); @endphp
                        
                        @include('exports.partials.boq-detail-row-pdf-new', [
                            'detail' => $detail,
                            'level' => 1,
                            'displayNumber' => $detailNumber,
                            'sectionNumber' => $sectionNumber
                        ])
                        
                        @if($detail->children && $detail->children->count() > 0)
                            @foreach($detail->children as $childIndex => $child)
                                @php $childNumber = $detailNumber . '.' . ($childIndex + 1); @endphp
                                
                                @include('exports.partials.boq-detail-row-pdf-new', [
                                    'detail' => $child,
                                    'level' => 2,
                                    'displayNumber' => $childNumber,
                                    'sectionNumber' => $sectionNumber
                                ])
                                
                                @if($child->children && $child->children->count() > 0)
                                    @foreach($child->children as $grandChildIndex => $grandChild)
                                        @php $grandChildNumber = $childNumber . '.' . ($grandChildIndex + 1); @endphp
                                        
                                        @include('exports.partials.boq-detail-row-pdf-new', [
                                            'detail' => $grandChild,
                                            'level' => 3,
                                            'displayNumber' => $grandChildNumber,
                                            'sectionNumber' => $sectionNumber
                                        ])
                                        
                                        @if($grandChild->children && $grandChild->children->count() > 0)
                                            @foreach($grandChild->children as $greatGrandChildIndex => $greatGrandChild)
                                                @php $greatGrandChildNumber = $grandChildNumber . '.' . ($greatGrandChildIndex + 1); @endphp
                                                
                                                @include('exports.partials.boq-detail-row-pdf-new', [
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
            <tr class="total-row">
                <td colspan="6" class="text-center">TOTAL BOQ</td>
                <td class="text-right">{{ $boq->formatted_total_harga ?? 'Rp 0' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Print Button at Bottom -->
    <div class="no-print" style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #dee2e6;">
        <button onclick="window.print()" style="background: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold; margin-right: 10px;">
            🖨️ Print to PDF
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold;">
            ❌ Close
        </button>
    </div>
</body>
</html>