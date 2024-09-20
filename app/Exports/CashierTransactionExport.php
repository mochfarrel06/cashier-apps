<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashierTransactionExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithColumnWidths, ShouldAutoSize
{
    protected $transactions;
    protected $index = 1;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
        $this->index = 1;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * Menambahkan header untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'Kode Transaksi',
            'Qty',
            'Jumlah Produk',
            'Jumlah Bayar (Rp)',
            'Kembalian (Rp)',
            'Total (Rp)',
        ];
    }

    /**
     * Mapping data untuk setiap baris pada file Excel.
     *
     * @param \App\Models\Transaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        $totalProducts = 0;

        // Menghitung jumlah produk retail dan pack dalam transaksi
        foreach ($transaction->transactionDetails as $transactionDetail) {
            $jumlahProduk = $transactionDetail->purchase_type === 'retail'
                ? $transactionDetail->quantity
                : $transactionDetail->quantity * $transactionDetail->cashierProduct->product->items_per_pack;

            // Menambahkan jumlah produk dari setiap item di transaksi
            $totalProducts += $jumlahProduk;
        }

        return [
            $this->index++, // No
            Carbon::parse($transaction->transaction_date)->format('d-m-Y'), // Tanggal Transaksi
            $transaction->transaction_number, // Kode Transaksi
            $transaction->transactionDetails->sum('quantity'), // Jumlah Produk
            $totalProducts,
            $transaction->paid_amount, // Jumlah Bayar
            $transaction->change_amount, // Kembalian
            $transaction->total, // Total
        ];
    }

    // Mengatur style pada sheet Excel
    public function styles(Worksheet $sheet)
    {
        // Mengatur warna header, garis tabel, dan rata tengah
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'Laporan Transaksi');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse(today())->format('d-m-Y'));

        // Total Pendapatan di baris akhir setelah data
        $lastRow = $this->transactions->count() + 4; // Menghitung total baris data
        $sheet->mergeCells('A' . ($lastRow + 1) . ':B' . ($lastRow + 1));
        $sheet->setCellValue('A' . ($lastRow + 1), 'Total Pendapatan');
        $totalPendapatanCell = 'H' . ($lastRow + 1);
        $sheet->setCellValue($totalPendapatanCell, $this->transactions->sum('total'));

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
        $sheet->getStyle('A4:H' . ($lastRow + 1))->applyFromArray($styleArray);
        // Format untuk kolom Rupiah (F, G, H)
        $sheet->getStyle('F4:F' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('G4:G' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('H4:H' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle($totalPendapatanCell)
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
            'E' => 20,  // Jumlah Produk
            'F' => 20,  // Total
            'G' => 20,  // Jumlah Bayar
            'H' => 20,  // Kembalian
        ];
    }
}
