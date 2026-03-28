<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BOQ - {{ $project->title_project }}</title>
    <style>

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            .no-print { display: none; }
        }

        @page { 
            size: A4;
            margin: 0 0 2cm 0;

            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-family: Arial, Helvetica, sans-serif;
                font-size: 9pt;
                color: #666666;
                width: 100%;
                text-align: center;
                padding-top: 1cm; 
            }
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333333;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }

        .company-header {
            display: flex;
            align-items: center;
            padding: 7px 1.5cm;
            background: #dc2626; 
            color: white;
            border-bottom: 2px solid #b91c1c; 
            page-break-inside: avoid;
        }

        .logo-container {
            flex-shrink: 0;
        }

        .company-logo {
            width: 60px; 
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            background: white;
            padding: 2px;
            border: 2px solid #b91c1c;
        }

        .company-details {
            margin-left: auto;
            text-align: right;
        }

        .company-name {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        .company-tagline {
            font-size: 9pt;
            font-style: italic;
            opacity: 0.9;
            margin: 2px 0;
        }

        .company-date {
            font-size: 8pt;
            margin: 2px 0;
        }

        /* --- Document Header --- */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
            page-break-inside: avoid;
            padding-left: 1.5cm;
            padding-right: 1.5cm;
            margin-top: 20px; 
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #333333;
            margin: 0;
        }
        .header h2 {
            font-size: 12pt;
            font-weight: normal;
            color: #555555;
            margin: 5px 0;
        }
        .header h3 {
            font-size: 10pt;
            font-weight: normal;
            color: #666666;
            margin: 3px 0;
        }

        table {
            width: calc(100% - 3cm); 
            margin-left: 1.5cm;     
            margin-right: 1.5cm;    
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 8px;
            vertical-align: top;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        thead th {
            background: #f2f2f2; 
            color: #333333;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
            padding-top: 12px;
            padding-bottom: 12px;
            border: 1px solid #cccccc;
            border-bottom: 2px solid #dc2626; 
        }

        /* --- Row Specific Styles --- */
        .section-row {
            page-break-inside: avoid;
        }
        .section-row td {
            background: #4a5568;
            color: white;
            font-weight: bold;
            font-size: 11pt;
            border: 1px solid #4a5568; 
        }

        .total-row td {
            background: #2d3748;
            color: white;
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
            border: 1px solid #2d3748;
            border-top: 3px double white;
        }

        /* --- Text Alignment & Formatting --- */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .currency {
            font-family: 'Consolas', 'Courier New', monospace;
        }

        /* REVISI: CSS Indentasi ini sudah benar, JANGAN diubah */
        .indent-1 { padding-left: 20px; }
        .indent-2 { padding-left: 35px; }
        .indent-3 { padding-left: 50px; }
        .indent-4 { padding-left: 65px; }

        .no-col { width: 5%; }
        .desc-col { width: 35%; }
        .note-col { width: 15%; }
        .qty-col { width: 8%; text-align: center; }
        .unit-col { width: 7%; text-align: center; }
        .price-col { width: 15%; text-align: right; }
        .total-col { width: 15%; text-align: right; }
        
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</head>
<body>
    
    <div class="company-header">
        <div class="logo-container">
            <img src="{{ asset('image/pertamina_logo.jpg') }}" alt="Pertamina Logo" class="company-logo">
        </div>
        <div class="company-details">
            <div class="company-name">PT PERTAMINA MAINTENANCE AND CONSTRUCTION</div>
            <div class="company-tagline">Excellence in Maintenance & Construction Services</div>
            <div class="company-date">
                Generated on: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d F Y, H:i') }} WIB
            </div>
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
                <th class="no-col">No</th>
                <th class="desc-col">Description</th>
                <th class="note-col">Note</th>
                <th class="qty-col">Quantity</th>
                <th class="unit-col">Unit</th>
                <th class="price-col">Price/Unit</th>
                <th class="total-col">Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $sectionIndex => $section)
                @php $sectionNumber = $sectionIndex + 1; @endphp
                
                <tr class="section-row">
                    <td>{{ $sectionNumber }}</td>
                    <td colspan="5">{{ $section->nama }}</td>
                    <td class="text-right currency">{{ $section->formatted_total_harga ?? 'Rp 0' }}</td>
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
            
            <tr class="total-row">
                <td colspan="6" class="text-center">GRAND TOTAL</td>
                <td class="text-right currency">{{ $boq->formatted_total_harga ?? 'Rp 0' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="no-print" style="text-align: center; margin: 30px 1.5cm;">
        <button onclick="window.print()" style="background: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold; margin-right: 10px;">
            🖨️ Print to PDF
        </button>
        <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: bold;">
            Close
        </button>
    </div>
</body>
</html>