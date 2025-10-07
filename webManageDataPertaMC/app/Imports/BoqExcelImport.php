<?php

namespace App\Imports;

use App\Models\Boq;
use App\Models\Section;
use App\Models\Detail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class BoqExcelImport implements ToCollection, WithHeadingRow
{
    protected $boq;
    protected $sectionMap = [];
    protected $detailMap = []; 

    public function __construct(Boq $boq)
    {
        $this->boq = $boq;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $row) {
                $this->processRow($row);
            }
            
            // Update total harga for all sections
            $this->updateSectionTotals();
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function processRow($row)
    {
        // Map column names to handle different Excel formats
        $mappedRow = $this->mapColumns($row);
        
        $no = trim($mappedRow['no'] ?? '');
        $description = trim($mappedRow['description'] ?? '');
        
        if (empty($no) || empty($description)) {
            return; // Skip empty rows
        }

        $indentLevel = $this->getIndentLevel($no);
        
        if ($indentLevel === 0) {
            // This is a section (indent 0)
            $this->createSection($no, $description);
        } else {
            // This is a detail (indent 1+)
            $this->createDetail($no, $mappedRow);
        }
    }
    
    protected function mapColumns($row)
    {
        // Convert array to lowercase keys for flexible matching
        $lowerRow = array_change_key_case($row->toArray(), CASE_LOWER);
        
        return [
            'no' => $lowerRow['no'] ?? $lowerRow['number'] ?? $lowerRow['nomor'] ?? '',
            'description' => $lowerRow['description'] ?? $lowerRow['deskripsi'] ?? $lowerRow['nama'] ?? '',
            'unit' => $lowerRow['unit'] ?? $lowerRow['satuan'] ?? 'pcs',
            'quantity' => $lowerRow['quantity'] ?? $lowerRow['qty'] ?? $lowerRow['jumlah'] ?? 1,
            'unit_price' => $lowerRow['unit_price'] ?? $lowerRow['unit price'] ?? $lowerRow['harga_satuan'] ?? $lowerRow['harga satuan'] ?? 0,
            'note' => $lowerRow['note'] ?? $lowerRow['catatan'] ?? $lowerRow['keterangan'] ?? ''
        ];
    }

    protected function getIndentLevel($no)
    {
        // Count the number of dots to determine indent level
        // 1 = indent 0 (section)
        // 1.1 = indent 1 (detail)
        // 1.1.1 = indent 2 (sub-detail)
        $parts = explode('.', $no);
        return count($parts) - 1;
    }

    protected function createSection($no, $description)
    {
        $section = Section::create([
            'nama' => $description,
            'boq_nomorBoq' => $this->boq->nomorBoq,
            'total_harga' => 0 // Will be calculated later
        ]);

        $this->sectionMap[$no] = $section;
    }

    protected function createDetail($no, $row)
    {
        $parentNo = $this->getParentNumber($no);
        $sectionNo = $this->getSectionNumber($no);
        
        // Find the section this detail belongs to
        $section = $this->sectionMap[$sectionNo] ?? null;
        if (!$section) {
            throw new Exception("Section not found for detail: $no");
        }

        // Find parent detail if exists
        $parentDetail = null;
        if ($parentNo && isset($this->detailMap[$parentNo])) {
            $parentDetail = $this->detailMap[$parentNo];
        }

        $detail = Detail::create([
            'nama_detail' => trim($row['description']),
            'parent_no' => $parentDetail ? $parentDetail->no : null,
            'quantity' => (float)$row['quantity'],
            'unit' => trim($row['unit']),
            'harga_satuan' => (int)$row['unit_price'],
            'note' => trim($row['note']),
            'section_id' => $section->id
        ]);

        $this->detailMap[$no] = $detail;
    }

    protected function getParentNumber($no)
    {
        $parts = explode('.', $no);
        if (count($parts) <= 2) {
            return null; // No parent for level 1 details (1.1)
        }
        
        // Remove the last part to get parent number
        array_pop($parts);
        return implode('.', $parts);
    }

    protected function getSectionNumber($no)
    {
        $parts = explode('.', $no);
        return $parts[0]; // First part is always the section number
    }

    protected function updateSectionTotals()
    {
        foreach ($this->sectionMap as $section) {
            $section->updateTotalHarga();
        }
    }
}