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
    protected $totalQuantity = 0;
    protected $packCount = 0; // Tambahan variabel untuk jumlah pack terjual
    protected $flavors = []; // Tambahan variabel untuk menyimpan jumlah produk per varian

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
            'Sub Total',
            'Diskon',
            'Total',
            'Jumlah Bayar',
            'Kembalian',
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

            // Tambahkan jumlah produk ke total
            $totalProducts += $jumlahProduk;

            // Perhitungan jumlah pack terjual
            if ($transactionDetail->purchase_type === 'pack') {
                $this->packCount += $transactionDetail->quantity;
            }

            // Perhitungan jumlah produk per varian
            $flavorName = $transactionDetail->cashierProduct->flavor->flavor_name ?? 'Unknown';
            if (!isset($this->flavors[$flavorName])) {
                $this->flavors[$flavorName] = 0;
            }

            $this->flavors[$flavorName] += $jumlahProduk;
        }

        // Tambahkan total produk dari transaksi ini ke totalQuantity
        $this->totalQuantity += $totalProducts;

        return [
            $this->index++, // No
            Carbon::parse($transaction->transaction_date)->format('d-m-Y'), // Tanggal Transaksi
            $transaction->transaction_number, // Kode Transaksi
            $transaction->transactionDetails->sum('quantity'), // Jumlah Produk
            $totalProducts,
            $transaction->total, // Sub Total
            $transaction->discount,
            $transaction->net_total, // Total
            $transaction->paid_amount, // Jumlah Bayar
            $transaction->change_amount, // Kembalian
        ];
    }

    // Mengatur style pada sheet Excel
    public function styles(Worksheet $sheet)
    {
        // Mengatur warna header, garis tabel, dan rata tengah
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'Laporan Transaksi');
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse(today())->format('d-m-Y'));

        // Total Pendapatan di baris akhir setelah data
        $lastRow = $this->transactions->count() + 4; // Menghitung total baris data
        $sheet->mergeCells('A' . ($lastRow + 1) . ':B' . ($lastRow + 1));
        $sheet->setCellValue('A' . ($lastRow + 1), 'Total Pendapatan');
        $totalPendapatanCell = 'H' . ($lastRow + 1);
        $sheet->setCellValue($totalPendapatanCell, $this->transactions->sum('net_total'));

        // Informasi Tambahan
        $infoStartRow = $lastRow + 3;

        $sheet->setCellValue('B' . $infoStartRow, 'Informasi Tambahan');
        $sheet->mergeCells('B' . $infoStartRow . ':D' . $infoStartRow);
        $sheet->getStyle('B' . $infoStartRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'left']
        ]);

        // Total Pendapatan
        $sheet->setCellValue('B' . ($infoStartRow + 1), 'Pendapatan:');
        $sheet->setCellValue('D' . ($infoStartRow + 1), '=SUM(H4:H' . $lastRow . ')');

        // Jumlah Produk
        $sheet->setCellValue('B' . ($infoStartRow + 2), 'Jumlah Produk Terjual:');
        $sheet->setCellValue('D' . ($infoStartRow + 2), $this->totalQuantity);

        // Tambahkan perhitungan jumlah pack terjual
        $sheet->setCellValue('B' . ($infoStartRow + 3), 'Jumlah Pack/Box Terjual:');
        $sheet->setCellValue('D' . ($infoStartRow + 3), $this->packCount); // Menampilkan jumlah pack terjual

        // Jumlah Produk Terjual Per Varian
        $sheet->setCellValue('B' . ($infoStartRow + 4), 'Jumlah Produk Terjual per Varian:');
        $currentRow = $infoStartRow + 5;
        foreach ($this->flavors as $flavorName => $totalVarian) {
            $sheet->setCellValue('C' . $currentRow, $flavorName . ': ' . $totalVarian);
            $currentRow++;
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

        // Mengatur border pada range sel tabel (A4 sampai H$lastRow)
        $sheet->getStyle('A4:J' . ($lastRow + 1))->applyFromArray($styleArray);
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
        $sheet->getStyle('I4:I' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('J4:J' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle($totalPendapatanCell)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('D' . ($infoStartRow + 1))
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
            'I' => 20,  // Jumlah Bayar
            'J' => 20,  // Kembalian
        ];
    }
}
