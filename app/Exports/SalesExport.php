<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class SalesExport implements FromCollection,  WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithColumnWidths, ShouldAutoSize
{
    protected $salesReports;
    protected $startDate;
    protected $endDate;
    protected $index = 1;

    public function __construct($salesReports, $startDate, $endDate)
    {
        $this->salesReports = $salesReports;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->index = 1;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->salesReports;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kasir',
            'Total Penjualan'
        ];
    }

    public function map($salesReport): array
    {
        return [
            $this->index++, // No
            Carbon::parse($salesReport->report_date)->format('d-m-Y'), // Tanggal Transaksi
            $salesReport->user->name,
            $salesReport->total_sales
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur warna header, garis tabel, dan rata tengah
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'Laporan Penjualan');
        // Cek jika startDate dan endDate sama
        if ($this->startDate === $this->endDate) {
            $sheet->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y'));
        } else {
            $sheet->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') . ' - ' . Carbon::parse($this->endDate)->format('d-m-Y'));
        }

        $lastRow = $this->salesReports->count() + 4; // Menghitung total baris data
        $sheet->mergeCells('A' . ($lastRow + 1) . ':B' . ($lastRow + 1));
        $sheet->setCellValue('A' . ($lastRow + 1), 'Total');
        $total = 'D' . ($lastRow + 1);
        $sheet->setCellValue($total, '=SUM(D4:D' . $lastRow . ')');

        // Style untuk border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A4:D' . ($lastRow + 1))->applyFromArray($styleArray);

        $sheet->getStyle('D4:D' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle($total)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');

        return [
            // Style untuk judul dan periode
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
            // Style untuk header tabel
            4 => ['font' => ['bold' => true, 'color' => ['rgb' => '000000']], 'alignment' => ['horizontal' => 'center'], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'ADD8E6']]],
            // Rata tengah untuk isi tabel
            'A' => ['alignment' => ['horizontal' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center']],
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // Tanggal Transaksi
            'C' => 20,  // Kode Transaksi
            'D' => 20,  // Kasir
        ];
    }
}
