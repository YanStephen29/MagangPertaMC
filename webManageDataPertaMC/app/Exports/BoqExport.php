<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BoqExport implements FromArray, WithTitle, WithStyles, WithEvents
{
    protected $project;
    protected $boq;
    protected $sections;

    public function __construct($project, $boq, $sections)
    {
        $this->project = $project;
        $this->boq = $boq;
        $this->sections = $sections;
    }

    public function array(): array
    {
        $data = [];
        
        // Add company header rows
        $data[] = ['PT PERTAMINA MAINTENANCE AND CONSTRUCTION', '', '', '', '', '', ''];
        $data[] = ['Excellence in Maintenance & Construction Services', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '']; // Empty row
        
        // Add project information
        $data[] = ['PROJECT:', $this->project->title_project, '', '', '', '', ''];
        $data[] = ['BOQ NUMBER:', $this->boq->nomorBoq, '', '', '', '', ''];
        $data[] = ['TOTAL BUDGET:', ($this->boq->formatted_total_harga ?? 'Rp 0'), '', '', '', '', ''];
        $data[] = ['GENERATED ON:', date('d F Y, H:i') . ' WIB', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '']; // Empty row
        
        // Add table headers manually
        $data[] = ['No.', 'Description', 'Note', 'Quantity', 'Unit', 'Unit Price', 'Total Cost'];
        
        // Process sections and details
        foreach ($this->sections as $sectionIndex => $section) {
            $sectionNumber = $sectionIndex + 1;
            
            // Section row
            $data[] = [
                $sectionNumber,
                $section->nama,
                $section->keterangan ?? '-',
                '-',
                '-',
                '-',
                $section->formatted_total_harga ?? 'Rp 0'
            ];
            
            // Process details
            if ($section->details && $section->details->count() > 0) {
                foreach ($section->details as $detailIndex => $detail) {
                    $detailNumber = $sectionNumber . '.' . ($detailIndex + 1);
                    
                    // Detail level 1
                    $data[] = [
                        $detailNumber,
                        $detail->description,
                        $detail->keterangan ?? '-',
                        $detail->quantity ?? 0,
                        $detail->unit ?? '-',
                        $detail->formatted_harga_satuan ?? 'Rp 0',
                        $detail->formatted_total_harga ?? 'Rp 0'
                    ];
                    
                    // Process children level 2
                    if ($detail->children && $detail->children->count() > 0) {
                        foreach ($detail->children as $childIndex => $child) {
                            $childNumber = $detailNumber . '.' . ($childIndex + 1);
                            
                            $data[] = [
                                $childNumber,
                                '  ' . $child->description,
                                $child->keterangan ?? '-',
                                $child->quantity ?? 0,
                                $child->unit ?? '-',
                                $child->formatted_harga_satuan ?? 'Rp 0',
                                $child->formatted_total_harga ?? 'Rp 0'
                            ];
                            
                            // Process children level 3
                            if ($child->children && $child->children->count() > 0) {
                                foreach ($child->children as $grandChildIndex => $grandChild) {
                                    $grandChildNumber = $childNumber . '.' . ($grandChildIndex + 1);
                                    
                                    $data[] = [
                                        $grandChildNumber,
                                        '    ' . $grandChild->description,
                                        $grandChild->keterangan ?? '-',
                                        $grandChild->quantity ?? 0,
                                        $grandChild->unit ?? '-',
                                        $grandChild->formatted_harga_satuan ?? 'Rp 0',
                                        $grandChild->formatted_total_harga ?? 'Rp 0'
                                    ];
                                    
                                    // Process children level 4
                                    if ($grandChild->children && $grandChild->children->count() > 0) {
                                        foreach ($grandChild->children as $greatGrandChildIndex => $greatGrandChild) {
                                            $greatGrandChildNumber = $grandChildNumber . '.' . ($greatGrandChildIndex + 1);
                                            
                                            $data[] = [
                                                $greatGrandChildNumber,
                                                '      ' . $greatGrandChild->description,
                                                $greatGrandChild->keterangan ?? '-',
                                                $greatGrandChild->quantity ?? 0,
                                                $greatGrandChild->unit ?? '-',
                                                $greatGrandChild->formatted_harga_satuan ?? 'Rp 0',
                                                $greatGrandChild->formatted_total_harga ?? 'Rp 0'
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Add total row
        $data[] = ['', '', '', '', '', 'TOTAL BOQ:', $this->boq->formatted_total_harga ?? 'Rp 0'];
        
        return $data;
    }

    public function title(): string
    {
        $cleanTitle = preg_replace('/[^\w\-_\.]/', '_', $this->project->title_project);
        return 'BOQ_' . $cleanTitle;
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for headers
        $sheet->mergeCells('A1:G1'); // Company name
        $sheet->mergeCells('A2:G2'); // Company tagline
        $sheet->mergeCells('B4:G4'); // Project title
        $sheet->mergeCells('B5:G5'); // BOQ number  
        $sheet->mergeCells('B6:G6'); // Total budget
        $sheet->mergeCells('B7:G7'); // Generated date
        
        // Company name styling (Row 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Company tagline styling (Row 2)
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '007BFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Project info labels styling (Column A, rows 4-7)
        $sheet->getStyle('A4:A7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8F9FA'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);
        
        // Project info values styling (Column B-G, rows 4-7)
        $sheet->getStyle('B4:G7')->applyFromArray([
            'font' => [
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);
        
        // Table header row styling (row 9 - where our manual header is)
        $headerRow = 9;
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Find the last row with data
        $lastRow = count($this->array()); // Total rows in our array
        
        // Data rows styling (from row 10 onwards, excluding header row 9)
        $sheet->getStyle('A10:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'DEE2E6'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Numeric columns alignment (Quantity, Unit Price, Total Cost)
        $sheet->getStyle('D10:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F10:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Total row styling (last row)
        $sheet->getStyle('A' . $lastRow . ':G' . $lastRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Set row heights
        $sheet->getRowDimension('1')->setRowHeight(25);
        $sheet->getRowDimension('2')->setRowHeight(20);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);   // No.
        $sheet->getColumnDimension('B')->setWidth(40);  // Description
        $sheet->getColumnDimension('C')->setWidth(20);  // Note
        $sheet->getColumnDimension('D')->setWidth(12);  // Quantity
        $sheet->getColumnDimension('E')->setWidth(10);  // Unit
        $sheet->getColumnDimension('F')->setWidth(18);  // Unit Price
        $sheet->getColumnDimension('G')->setWidth(18);  // Total Cost
        
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Style section rows (rows that don't have spaces in description)
                $data = $this->array();
                
                foreach ($data as $rowIndex => $row) {
                    $actualRowNumber = $rowIndex + 1; // Excel rows start from 1
                    
                    // Skip if this is header info, empty row, or table header row
                    if ($rowIndex < 10) continue;
                    
                    $description = $row[1] ?? '';
                    
                    // Section rows (no indentation spaces)
                    if (!empty($description) && !str_starts_with($description, '  ')) {
                        // Check if it's a section (contains only section number like "1", "2", etc.)
                        $number = $row[0] ?? '';
                        if (is_numeric($number) && strpos($number, '.') === false) {
                            $sheet->getStyle('A' . $actualRowNumber . ':G' . $actualRowNumber)->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'size' => 11,
                                ],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'E3F2FD'],
                                ],
                            ]);
                        }
                    }
                }
            },
        ];
    }
}