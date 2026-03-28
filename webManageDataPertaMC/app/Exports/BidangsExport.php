<?php

namespace App\Exports;

use App\Models\Bidang;
use App\Models\Project;
use App\Models\Tool;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\DB;

class BidangsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $selectedProjectIds;

    public function __construct(array $selectedProjectIds)
    {
        $this->selectedProjectIds = $selectedProjectIds;
    }

    /**
    * Query SEMUA GL Code (Bidang) dan hitung total expenditure HANYA dari project yang dipilih.
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function query()
    {
        $projectIds = $this->selectedProjectIds;

        // Query dimulai dari Bidang
        return Bidang::query()
            ->select('kode_GL', 'nama_Bidang')
            ->withSum(
                // Argumen 1: Array relasi dan closure
                ['tools as total_expenditure_for_projects' => function ($query) use ($projectIds) {
                    $query->whereIn('tools.no_IO', $projectIds)
                          ->select(DB::raw('SUM(COALESCE(request_details.requested_quantity, 0) * COALESCE(details.harga_satuan, 0))'))
                          ->join('requests', 'tools.request_id', '=', 'requests.id_req')
                          ->join('request_details', 'requests.id_req', '=', 'request_details.request_id')
                          ->join('details', 'request_details.detail_id', '=', 'details.no');
                }],
                'kode_GL'
            )
            ->orderBy('kode_GL');
    }

    /**
    * Map data untuk setiap baris di Excel.
    * @param Bidang $bidang Hasil query
    * @return array
    */
    public function map($bidang): array
    {
        $totalExpenditure = $bidang->total_expenditure_for_projects ?? 0;

        // Format data sesuai kolom Excel (mulai dari kolom A)
        return [
            $bidang->kode_GL,
            $bidang->nama_Bidang,
            $totalExpenditure,
            null,
            null,
            null,
        ];
    }

    /**
    * Definisikan header kolom Excel (ini akan diletakkan di baris 7).
    * @return array
    */
    public function headings(): array
    {
        return [
            'Acc. Code',
            'Description',
            'Total (Rp)',
            'Total ($)',
            '% thd Sales',
            'Keterangan',
        ];
    }

    /**
     * Register events to manipulate the sheet.
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $projects = Project::whereIn('no_IO', $this->selectedProjectIds)
                                    ->select('no_IO', 'title_project')
                                    ->orderBy('title_project')
                                    ->get();
                $projectInfo = $projects->map(function($p) {
                    return $p->title_project . ' (' . $p->no_IO . ')';
                })->implode('; ');

                $sheet->insertNewRowBefore(1, 6);

                // 2. Isi Baris 1: Nama Perusahaan
                $sheet->setCellValue('A1', 'PT Pertamina Maintenance & Construction');
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // 3. Isi Baris 2: Judul
                $sheet->setCellValue('A2', 'PROYEKSI LABA RUGI');
                $sheet->mergeCells('A2:F2');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);

                // 4. Baris 3 kosong 

                // 5. Isi Baris 4: Nama Project dan No IO
                $sheet->setCellValue('A4', 'Project: ' . ($projectInfo ?: 'All Projects Included'));
                $sheet->mergeCells('A4:F4');
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A4')->getFont()->setBold(true);
            },

            // Event SETELAH data tabel ditulis
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headerRow = 8;
                $lastRow = $sheet->getHighestRow();

                // 7. Style Baris Header Tabel (Baris 7)
                $headerRange = 'A'.$headerRow.':F'.$headerRow;
                $headerStyle = $sheet->getStyle($headerRange);
                
                // Font, Align, Fill
                $headerStyle->getFont()
                            ->setBold(true)
                            ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
                $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $headerStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $headerStyle->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FF4F81BD');

                // [BARU] Tambahkan Border untuk Header (Semua Sisi)
                $headerStyle->getBorders()->applyFromArray([
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ]);

                // Set tinggi baris header (opsional)
                $sheet->getRowDimension($headerRow)->setRowHeight(20);

                // Terapkan number format dan border untuk data
                if ($lastRow >= $headerRow + 1) {
                    $dataStartRow = $headerRow + 1;
                    $dataRange = 'A'.$dataStartRow.':F'.$lastRow;
                    $lastRowRange = 'A'.$lastRow.':F'.$lastRow;

                    $sheet->getStyle($dataRange)->getBorders()->applyFromArray([
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]);

                    $sheet->getStyle($lastRowRange)->getBorders()->applyFromArray([
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]);

                    // Format Angka Kolom C (Total Rp)
                    $sheet->getStyle('C'.$dataStartRow.':C'.$lastRow)
                          ->getNumberFormat()
                          ->setFormatCode('#,##0'); // Format angka tanpa desimal
                    // Rata kanan kolom Total (Rp)
                    $sheet->getStyle('C'.$dataStartRow.':C'.$lastRow)
                          ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }


                // 8. Tambahkan Footer Baris 1 (Mengetahui/Yang Membuat)
                $footerRow1 = $lastRow + 2; // Beri 1 baris kosong setelah data
                $sheet->setCellValue('D'.$footerRow1, 'Mengetahui/Menyetujui');
                $sheet->setCellValue('F'.$footerRow1, 'Yang Membuat');
                $sheet->getStyle('D'.$footerRow1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F'.$footerRow1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D'.$footerRow1.':F'.$footerRow1)->getFont()->setBold(true);


                // 9. Tambahkan Footer Baris 2 (Jabatan)
                $footerRow2 = $footerRow1 + 1;
                $sheet->setCellValue('D'.$footerRow2, 'Jabatan Penyetuju'); // Placeholder
                $sheet->setCellValue('F'.$footerRow2, 'Jabatan Pembuat');   // Placeholder
                $sheet->getStyle('D'.$footerRow2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F'.$footerRow2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 10. Tambahkan Footer Baris 3 (Nama) - 5 baris di bawah jabatan
                $footerRow3 = $footerRow2 + 5;
                $sheet->setCellValue('D'.$footerRow3, '(Nama Penyetuju)');  // Placeholder
                $sheet->setCellValue('F'.$footerRow3, '(Nama Pembuat)');    // Placeholder
                $sheet->getStyle('D'.$footerRow3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F'.$footerRow3)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D'.$footerRow3.':F'.$footerRow3)->getFont()->setUnderline(true); // Beri garis bawah pada nama

            }
        ];
    }
}