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

class StockExport implements FromCollection,  WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithColumnWidths, ShouldAutoSize
{
    protected $stockReports;
    protected $startDate;
    protected $endDate;
    protected $index = 1;

    public function __construct($stockReports, $startDate, $endDate)
    {
        $this->stockReports = $stockReports;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->index = 1;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->stockReports;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Kasir',
            'Nama Produk',
            'Varian Produk',
            'Produk Masuk',
            'Produk Terjual',
            'Sisa Produk'
        ];
    }

    public function map($stockReport): array
    {
        return [
            $this->index++, // No
            Carbon::parse($stockReport->stock_date)->format('d-m-Y'), // Tanggal Transaksi
            $stockReport->cashierProduct->user->name,
            $stockReport->cashierProduct->product->name,
            $stockReport->cashierProduct->flavor->flavor_name,
            (string)($stockReport->stock_in ?? 0), // Produk Masuk, konversi ke string agar tetap tampil 0
            (string)($stockReport->stock_out ?? 0), // Produk Terjual, konversi ke string agar tetap tampil 0
            (string)($stockReport->current_stock ?? 0), // Sisa Produk, konversi ke string agar tetap tampil 0
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur warna header, garis tabel, dan rata tengah
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Laporan Stok Produk');

        // Cek jika startDate dan endDate sama
        if ($this->startDate === $this->endDate) {
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y'));
        } else {
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') . ' - ' . Carbon::parse($this->endDate)->format('d-m-Y'));
        }

        $lastRow = $this->stockReports->count() + 4; // Menghitung total baris data
        $sheet->mergeCells('A' . ($lastRow + 1) . ':B' . ($lastRow + 1));
        $sheet->setCellValue('A' . ($lastRow + 1), 'Total');

        // Total stok masuk hari ini
        $totalIn = 'F' . ($lastRow + 1);
        $sheet->setCellValue($totalIn, '=SUM(F4:F' . $lastRow . ')');

        // Total Stok terjual
        $totalOut = 'G' . ($lastRow + 1);
        $sheet->setCellValue($totalOut, '=SUM(G4:G' . $lastRow . ')');

        // Total Stok terjual
        $currentStock = 'H' . ($lastRow + 1);
        $sheet->setCellValue($currentStock, '=SUM(H4:H' . $lastRow . ')');

        // Informasi Tambahan
        $infoStartRow = $lastRow + 3;

        // Mendapatkan varian produk dengan penjualan terbanyak
        $maxSold = $this->stockReports->max('stock_out');
        $mostSoldProducts = $this->stockReports->where('stock_out', $maxSold);

        // Mendapatkan varian produk dengan penjualan sedikit atau sisa stok terbanyak
        $minSold = $this->stockReports->min('stock_out');
        $leastSoldProducts = $this->stockReports->where('stock_out', $minSold);

        // Header Informasi Tambahan
        $sheet->setCellValue('B' . $infoStartRow, 'Informasi Tambahan');
        $sheet->mergeCells('B' . $infoStartRow . ':D' . $infoStartRow);
        $sheet->getStyle('B' . $infoStartRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'left']
        ]);

        // Menampilkan Penjualan Terbanyak
        $infoStartRow++;
        $sheet->setCellValue('B' . $infoStartRow, 'Varian Produk dengan Penjualan Terbanyak:');
        $sheet->mergeCells('B' . $infoStartRow . ':D' . $infoStartRow);

        // Loop untuk menampilkan semua varian dengan penjualan terbanyak
        foreach ($mostSoldProducts as $product) {
            $infoStartRow++;
            $sheet->setCellValue('E' . $infoStartRow, $product->cashierProduct->flavor->flavor_name . ' - Terjual: ' . $product->stock_out);
        }

        // Menampilkan Penjualan Sedikit atau Sisa Stok Terbanyak
        $infoStartRow++;
        $sheet->setCellValue('B' . $infoStartRow, 'Varian Produk dengan Penjualan Sedikit:');
        $sheet->mergeCells('B' . $infoStartRow . ':D' . $infoStartRow);

        // Loop untuk menampilkan semua varian dengan penjualan sedikit atau sisa stok banyak
        foreach ($leastSoldProducts as $product) {
            $infoStartRow++;
            $sheet->setCellValue('E' . $infoStartRow, $product->cashierProduct->flavor->flavor_name . ' - Terjual: ' . $product->stock_out);
        }

        // Style untuk border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A4:H' . ($lastRow + 1))->applyFromArray($styleArray);

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
            'E' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
            'G' => ['alignment' => ['horizontal' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'center']],
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
            'E' => 20,  // Kasir
            'F' => 20,  // Kasir
            'G' => 20,  // Kasir
            'H' => 20,  // Kasir
        ];
    }
}
