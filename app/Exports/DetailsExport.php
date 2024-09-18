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

class DetailsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithColumnWidths, ShouldAutoSize
{
    protected $transactionDetails;
    protected $startDate;
    protected $endDate;
    protected $index = 1;

    public function __construct($transactionDetails, $startDate, $endDate)
    {
        $this->transactionDetails = $transactionDetails;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->index = 1;
    }

    // Mengambil data dari collection transactionDetails
    public function collection()
    {
        return $this->transactionDetails;
    }

    // Membuat header di Excel
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'Kode Transaksi',
            'Kasir',
            'Nama Produk',
            'Varian Produk',
            'Jenis Pembelian',
            'Jumlah Produk',
            'Harga',
            'Total'
        ];
    }

    // Memetakan data transaksi ke dalam format Excel
    public function map($transactionDetail): array
    {
        return [
            $this->index++, // No
            Carbon::parse($transactionDetail->transaction->transaction_date)->format('d-m-Y'),
            $transactionDetail->transaction->transaction_number,
            $transactionDetail->transaction->user->name,
            $transactionDetail->cashierProduct->product->name, // Jumlah Produk
            $transactionDetail->cashierProduct->flavor->flavor_name, // Total
            $transactionDetail->purchase_type, // Jumlah Bayar
            $transactionDetail->quantity, // Kembalian
            $transactionDetail->price,
            $transactionDetail->quantity * $transactionDetail->price
        ];
    }

    // Mengatur style pada sheet Excel
    public function styles(Worksheet $sheet)
    {
        // Mengatur warna header, garis tabel, dan rata tengah
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Laporan Produk Terjual');
        // Cek jika startDate dan endDate sama
        if ($this->startDate === $this->endDate) {
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y'));
        } else {
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') . ' - ' . Carbon::parse($this->endDate)->format('d-m-Y'));
        }

        // Total Pendapatan di baris akhir setelah data
        $lastRow = $this->transactionDetails->count() + 4; // Menghitung total baris data
        $sheet->setCellValue('G' . ($lastRow + 1), 'Total');
        $totalProduct = 'H' . ($lastRow + 1);
        $sheet->setCellValue($totalProduct, $this->transactionDetails->sum('quantity'));
        $totalPrice = 'I' . ($lastRow + 1);
        $sheet->setCellValue($totalPrice, $this->transactionDetails->sum('price'));
        $total = 'J' . ($lastRow + 1);
        $sheet->setCellValue($total, '=SUM(J4:J' . $lastRow . ')');

        // Style untuk border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Mengatur border pada range sel tabel (A4 sampai H$lastRow)
        $sheet->getStyle('A4:J' . ($lastRow + 1))->applyFromArray($styleArray);

        // Format untuk kolom Rupiah (F, G, H)
        $sheet->getStyle('I4:I' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('J4:J' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle($totalPrice)
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
            'E' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
            'G' => ['alignment' => ['horizontal' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'center']],
            'I' => ['alignment' => ['horizontal' => 'center']],
            'J' => ['alignment' => ['horizontal' => 'center']],
        ];
    }


    // Mengatur kolom dimulai dari baris ke-4 (karena baris 1 dan 2 untuk heading custom)
    public function startCell(): string
    {
        return 'A4';
    }

    // Mengatur lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 20,  // Tanggal Transaksi
            'C' => 20,  // Kode Transaksi
            'D' => 20,  // Kasir
            'E' => 20,  // Jumlah Produk
            'F' => 20,  // Total
            'G' => 20,  // Jumlah Bayar
            'H' => 20,  // Kembalian
            'I' => 20,  // Kembalian
        ];
    }
}
