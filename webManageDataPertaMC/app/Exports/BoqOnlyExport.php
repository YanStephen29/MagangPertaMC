<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Storage;

class BoqOnlyExport
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

    public function export()
    {
        // Load template
        $templatePath = storage_path('app/template/templateBoQ.xlsm');
        
        if (!file_exists($templatePath)) {
            throw new \Exception('Template file not found at: ' . $templatePath);
        }
        
        try {
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();
        } catch (\Exception $e) {
            throw new \Exception('Failed to load template: ' . $e->getMessage());
        }

        // Fill header information
        // Row 1-2: Project title
        $worksheet->setCellValue('A1', $this->project->title_project);
        $worksheet->setCellValue('A2', $this->project->title_project);
        
        // Row 3: I/O Number
        $worksheet->setCellValue('A3', $this->project->no_IO ?? 'No I/O');

        // Row 4 contains headers from template, don't overwrite it
        // Clear any existing data from row 5 onwards (from template)
        $highestRow = $worksheet->getHighestRow();
        if ($highestRow > 4) {
            for ($row = 5; $row <= $highestRow; $row++) {
                $worksheet->removeRow($row, 1);
                $highestRow--; // Adjust because we removed a row
                $row--; // Adjust loop counter
            }
        }

        // Start filling data from row 5 onwards
        $currentRow = 5;

        // Process sections and details with proper hierarchy
        foreach ($this->sections as $sectionIndex => $section) {
            $sectionNumber = $sectionIndex + 1;

            // Add section row (indentation 0)
            $worksheet->setCellValue('A' . $currentRow, $sectionNumber);
            $worksheet->setCellValue('B' . $currentRow, $section->nama);
            $worksheet->setCellValue('C' . $currentRow, '');
            $worksheet->setCellValue('D' . $currentRow, '');
            $worksheet->setCellValue('E' . $currentRow, '');
            $worksheet->setCellValue('F' . $currentRow, '');
            $worksheet->setCellValue('G' . $currentRow, $this->formatCurrency($section->total_harga));
            
            // Set indentation for section (Level 0)
            $this->setIndentation($worksheet, 'A' . $currentRow, 0); // No column
            $this->setIndentation($worksheet, 'B' . $currentRow, 0); // Description column
            $worksheet->setCellValue('H' . $currentRow, 0); // Level column
            
            // Set entire section row to bold
            $this->setBoldRow($worksheet, $currentRow);

            $currentRow++;

            // Process details recursively
            if ($section->details && $section->details->count() > 0) {
                $rootDetails = $section->details->whereNull('parent_no')->sortBy('no');
                foreach ($rootDetails as $detailIndex => $detail) {
                    $detailNumber = $sectionNumber . '.' . ($detailIndex + 1);
                    $currentRow = $this->addDetailWithChildren($worksheet, $detail, $detailNumber, $currentRow, 1);
                }
            }
        }

        return $spreadsheet;
    }

    /**
     * Recursively add detail and its children with proper indentation
     */
    private function addDetailWithChildren($worksheet, $detail, $number, $currentRow, $indentLevel)
    {
        // Add current detail
        $worksheet->setCellValue('A' . $currentRow, $number);
        $worksheet->setCellValue('B' . $currentRow, $detail->nama_detail);
        $worksheet->setCellValue('C' . $currentRow, $detail->note ?? '');
        $worksheet->setCellValue('D' . $currentRow, $this->formatNumber($detail->quantity));
        $worksheet->setCellValue('E' . $currentRow, $detail->unit ?? '');
        $worksheet->setCellValue('F' . $currentRow, $this->formatCurrency($detail->harga_satuan));
        $worksheet->setCellValue('G' . $currentRow, $this->formatCurrency($detail->getTotalHargaWithChildren()));
        
        // Set indentation for detail (Level based on indentLevel)
        $this->setIndentation($worksheet, 'A' . $currentRow, $indentLevel); // No column
        $this->setIndentation($worksheet, 'B' . $currentRow, $indentLevel); // Description column
        $worksheet->setCellValue('H' . $currentRow, $indentLevel); // Level column

        $currentRow++;

        // Process children if any
        if ($detail->children && $detail->children->count() > 0) {
            $sortedChildren = $detail->children->sortBy('no');
            foreach ($sortedChildren as $childIndex => $child) {
                $childNumber = $number . '.' . ($childIndex + 1);
                $currentRow = $this->addDetailWithChildren($worksheet, $child, $childNumber, $currentRow, $indentLevel + 1);
            }
        }

        return $currentRow;
    }

    /**
     * Format currency value
     */
    private function formatCurrency($value)
    {
        if (is_null($value) || $value == 0) {
            return 'Rp 0';
        }
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    /**
     * Format number value
     */
    private function formatNumber($value)
    {
        if (is_null($value)) {
            return '';
        }
        return number_format($value, 2);
    }

    /**
     * Set indentation for a cell
     */
    private function setIndentation($worksheet, $cellCoordinate, $indentLevel)
    {
        $style = $worksheet->getStyle($cellCoordinate);
        $alignment = $style->getAlignment();
        $alignment->setIndent($indentLevel);
        
        // Set column No. (A) to left alignment
        if (substr($cellCoordinate, 0, 1) === 'A') {
            $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    }

    /**
     * Set bold formatting for entire row (section rows with indent 0)
     */
    private function setBoldRow($worksheet, $rowNumber)
    {
        $style = $worksheet->getStyle('A' . $rowNumber . ':H' . $rowNumber);
        $font = $style->getFont();
        $font->setBold(true);
    }

    /**
     * Download the export
     */
    public function download($filename = null)
    {
        $spreadsheet = $this->export();
        
        if (!$filename) {
            $cleanProjectTitle = preg_replace('/[^\w\-_\.]/', '_', $this->project->title_project);
            $cleanBoqNumber = preg_replace('/[^\w\-_\.]/', '_', $this->boq->nomorBoq);
            $filename = 'BOQ_Only_' . $cleanProjectTitle . '_' . $cleanBoqNumber . '.xlsm';
        }

        // Set headers for Excel download with macro support
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
            'Pragma' => 'public',
            'Expires' => '0'
        ];

        // Return streaming response with XLSX writer (compatible with XLSM format)
        return response()->streamDownload(function() use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, $headers);
    }
}