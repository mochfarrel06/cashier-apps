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
            'Qty',
            'Jumlah Produk',
            'Harga',
            'Total'
        ];
    }

    // Memetakan data transaksi ke dalam format Excel
    public function map($transactionDetail): array
    {
        // Menghitung jumlah produk berdasarkan jenis pembelian
        $jumlahProduk = $transactionDetail->purchase_type === 'retail'
            ? $transactionDetail->quantity
            : $transactionDetail->quantity * $transactionDetail->cashierProduct->product->items_per_pack;

        return [
            $this->index++,
            Carbon::parse($transactionDetail->transaction->transaction_date)->format('d-m-Y'),
            $transactionDetail->transaction->transaction_number,
            $transactionDetail->transaction->user->name,
            $transactionDetail->cashierProduct->product->name,
            $transactionDetail->cashierProduct->flavor->flavor_name,
            ucfirst($transactionDetail->purchase_type),
            $transactionDetail->quantity,
            $jumlahProduk, // Jumlah Produk
            $transactionDetail->price,
            $transactionDetail->quantity * $transactionDetail->price
        ];
    }

    // Mengatur style pada sheet Excel
    public function styles(Worksheet $sheet)
    {
        // Mengatur warna header, garis tabel, dan rata tengah
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'Laporan Detail Transaksi');
        if ($this->startDate === $this->endDate) {
            $sheet->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y'));
        } else {
            $sheet->mergeCells('A2:K2');
            $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($this->startDate)->format('d-m-Y') . ' - ' . Carbon::parse($this->endDate)->format('d-m-Y'));
        }

        // Total Pendapatan di baris akhir setelah data
        $lastRow = $this->transactionDetails->count() + 4; // Menghitung total baris data
        $sheet->mergeCells('A' . ($lastRow + 1) . ':B' . ($lastRow + 1));
        $sheet->mergeCells('C' . ($lastRow + 1) . ':G' . ($lastRow + 1));
        $sheet->setCellValue('A' . ($lastRow + 1), 'Total');

        $totalProduct = 'H' . ($lastRow + 1);
        $sheet->setCellValue($totalProduct, $this->transactionDetails->sum('quantity'));

        $totalAll = 'I' . ($lastRow + 1);
        $totalQuantity = $this->transactionDetails->reduce(function ($carry, $detail) {
            // Ambil nilai items_per_pack, default ke 1 jika tidak ada
            $itemsPerPack = isset($detail->cashierProduct->product->items_per_pack)
                ? (int) $detail->cashierProduct->product->items_per_pack
                : 1;

            // Hitung quantity berdasarkan jenis pembelian
            $productQuantity = ($detail->purchase_type === 'retail')
                ? $detail->quantity // Jika retail, ambil quantity langsung
                : $detail->quantity * $itemsPerPack; // Jika pack, kalikan dengan items_per_pack

            // Tambahkan jumlah produk ke total akumulasi
            return $carry + $productQuantity;
        }, 0);
        $sheet->setCellValue($totalAll, $totalQuantity);

        $totalPrice = 'J' . ($lastRow + 1);
        $sheet->setCellValue($totalPrice, $this->transactionDetails->sum('price'));

        $total = 'K' . ($lastRow + 1);
        $sheet->setCellValue($total, '=SUM(K4:K' . $lastRow . ')');

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
        $sheet->setCellValue('D' . ($infoStartRow + 1), '=SUM(K4:K' . $lastRow . ')');

        // Jumlah Produk
        $sheet->setCellValue('B' . ($infoStartRow + 2), 'Jumlah Produk Terjual:');
        $sheet->setCellValue('D' . ($infoStartRow + 2), $totalQuantity);

        // Jumlah Produk Terjual Per Varian
        $sheet->setCellValue('B' . ($infoStartRow + 3), 'Jumlah Produk Terjual per Varian:');
        $flavors = $this->transactionDetails->groupBy('cashierProduct.flavor.flavor_name')->map(function ($group) {
            return $group->reduce(function ($carry, $detail) {
                // Ambil nilai items_per_pack, default ke 1 jika tidak ada
                $itemsPerPack = isset($detail->cashierProduct->product->items_per_pack)
                    ? (int) $detail->cashierProduct->product->items_per_pack
                    : 1;

                // Hitung quantity berdasarkan jenis pembelian
                $productQuantity = ($detail->purchase_type === 'retail')
                    ? $detail->quantity // Jika retail, ambil quantity langsung
                    : $detail->quantity * $itemsPerPack; // Jika pack, kalikan dengan items_per_pack

                // Tambahkan jumlah produk ke total akumulasi
                return $carry + $productQuantity;
            }, 0);
        });

        $currentRow = $infoStartRow + 4;
        foreach ($flavors as $flavorName => $totalVarian) {
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

        // Mengatur border pada range sel tabel (A4 sampai K$lastRow)
        $sheet->getStyle('A4:K' . ($lastRow + 1))->applyFromArray($styleArray);

        // Format untuk kolom Rupiah (I, J, K)
        $sheet->getStyle('J4:J' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('K4:K' . $lastRow)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle('D' . ($infoStartRow + 1))
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle($totalPrice)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');
        $sheet->getStyle($total)
            ->getNumberFormat()
            ->setFormatCode('"Rp " #,##0');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
            2 => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
            4 => ['font' => ['bold' => true, 'color' => ['rgb' => '000000']], 'alignment' => ['horizontal' => 'center'], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'ADD8E6']]],
            'A' => ['alignment' => ['horizontal' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'left']],
            'C' => ['alignment' => ['horizontal' => 'left']],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'center']],
            'F' => ['alignment' => ['horizontal' => 'center']],
            'G' => ['alignment' => ['horizontal' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'center']],
            'I' => ['alignment' => ['horizontal' => 'center']],
            'J' => ['alignment' => ['horizontal' => 'center']],
            'K' => ['alignment' => ['horizontal' => 'center']],
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
            'E' => 20,  // Nama Produk
            'F' => 25,  // Varian Produk
            'G' => 20,  // Jenis Pembelian
            'H' => 10,  // Qty
            'I' => 15,  // Jumlah Produk
            'J' => 20,  // Harga
            'K' => 25,  // Total
        ];
    }
}
