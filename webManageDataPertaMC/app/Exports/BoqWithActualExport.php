<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Storage;

class BoqWithActualExport
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

        // Add new headers for actual columns (insert before Level column)
        $worksheet->insertNewColumnBefore('H', 2); // Insert 2 columns before H (which becomes J)
        $worksheet->setCellValue('H4', 'Quantity Used');
        $worksheet->setCellValue('I4', 'Remaining Funds');
        $worksheet->setCellValue('J4', 'Level');

        // Row 4 contains headers from template, don't overwrite existing ones
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

            // Calculate section totals for quantity used and remaining funds
            $sectionUsedQuantity = 0;
            $sectionRemainingFunds = 0;
            
            foreach ($section->details as $detail) {
                $sectionUsedQuantity += $detail->getActualUsedQuantity();
                $remainingQty = max(0, $detail->quantity - $detail->getActualUsedQuantity());
                $sectionRemainingFunds += ($remainingQty * $detail->harga_satuan);
            }

            // Add section row (indentation 0)
            $worksheet->setCellValue('A' . $currentRow, $sectionNumber);
            $worksheet->setCellValue('B' . $currentRow, $section->nama);
            $worksheet->setCellValue('C' . $currentRow, '');
            $worksheet->setCellValue('D' . $currentRow, '');
            $worksheet->setCellValue('E' . $currentRow, '');
            $worksheet->setCellValue('F' . $currentRow, '');
            $worksheet->setCellValue('G' . $currentRow, $this->formatCurrency($section->total_harga));
            $worksheet->setCellValue('H' . $currentRow, $this->formatNumber($sectionUsedQuantity)); // Quantity Used
            $worksheet->setCellValue('I' . $currentRow, $this->formatCurrency($sectionRemainingFunds)); // Remaining Funds
            
            // Set indentation for section (Level 0)
            $this->setIndentation($worksheet, 'A' . $currentRow, 0); // No column
            $this->setIndentation($worksheet, 'B' . $currentRow, 0); // Description column
            $worksheet->setCellValue('J' . $currentRow, 0); // Level column (moved to J)
            
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
        // Calculate actual usage data
        $usedQuantity = $detail->getActualUsedQuantity();
        $remainingQuantity = max(0, $detail->quantity - $usedQuantity);
        $remainingFunds = $remainingQuantity * $detail->harga_satuan;

        // Add current detail
        $worksheet->setCellValue('A' . $currentRow, $number);
        $worksheet->setCellValue('B' . $currentRow, $detail->nama_detail);
        $worksheet->setCellValue('C' . $currentRow, $detail->note ?? '');
        $worksheet->setCellValue('D' . $currentRow, $this->formatNumber($detail->quantity));
        $worksheet->setCellValue('E' . $currentRow, $detail->unit ?? '');
        $worksheet->setCellValue('F' . $currentRow, $this->formatCurrency($detail->harga_satuan));
        $worksheet->setCellValue('G' . $currentRow, $this->formatCurrency($detail->getTotalHargaWithChildren()));
        $worksheet->setCellValue('H' . $currentRow, $this->formatNumber($usedQuantity));
        $worksheet->setCellValue('I' . $currentRow, $this->formatCurrency($remainingFunds)); // Remaining Funds
        
        // Set indentation for detail (Level based on indentLevel)
        $this->setIndentation($worksheet, 'A' . $currentRow, $indentLevel); // No column
        $this->setIndentation($worksheet, 'B' . $currentRow, $indentLevel); // Description column
        $worksheet->setCellValue('J' . $currentRow, $indentLevel); // Level column (moved to J)

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
            return '0';
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
        // Now includes columns A through J (added H, I)
        $style = $worksheet->getStyle('A' . $rowNumber . ':J' . $rowNumber);
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
            $filename = 'BOQ_With_Actual_' . $cleanProjectTitle . '_' . $cleanBoqNumber . '.xlsm';
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