<?php

namespace Database\Seeders;

use App\Models\CashierProduct;
use App\Models\StockReport;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashierProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set tanggal awal dan akhir
        $startDate = Carbon::create(2024, 9, 16);
        $endDate = Carbon::create(2024, 9, 22);

        // Array untuk menyimpan user_id, product_id, dan flavor_id yang berulang digunakan
        $cashierProducts = [
            ['user_id' => 2, 'product_id' => 1, 'flavor_id' => 1],
            ['user_id' => 3, 'product_id' => 2, 'flavor_id' => 2],
        ];

        // Iterasi setiap tanggal dari 16 hingga 22
        while ($startDate->lessThanOrEqualTo($endDate)) {
            foreach ($cashierProducts as $index => $cashierProduct) {
                // Contoh perubahan stok setiap harinya
                $stock = 50 + ($index * 10) + $startDate->dayOfYear % 10; // Formula stok dinamis

                // Jika tanggal adalah 16, maka buat data baru
                if ($startDate->isSameDay(Carbon::create(2024, 9, 16))) {
                    // Buat data CashierProduct baru
                    $createdCashierProduct = CashierProduct::create([
                        'user_id' => $cashierProduct['user_id'],
                        'product_id' => $cashierProduct['product_id'],
                        'flavor_id' => $cashierProduct['flavor_id'],
                        'stock' => $stock,
                        'created_at' => $startDate,
                        'updated_at' => $startDate,
                    ]);

                    // Tambahkan data ke StockReport pada tanggal 16
                    $this->addStockToDailyReport($createdCashierProduct, $stock, $startDate);
                } else {
                    // Cari CashierProduct yang sesuai untuk diupdate
                    $existingCashierProduct = CashierProduct::where('user_id', $cashierProduct['user_id'])
                        ->where('product_id', $cashierProduct['product_id'])
                        ->where('flavor_id', $cashierProduct['flavor_id'])
                        ->first();

                    // Jika ditemukan, update stoknya
                    if ($existingCashierProduct) {
                        $existingCashierProduct->update([
                            'stock' => $stock,
                            'updated_at' => $startDate,
                        ]);

                        // Update data di StockReport sesuai perubahan stok
                        $this->addStockToDailyReport($existingCashierProduct, $stock, $startDate);
                    }
                }
            }

            // Pindah ke tanggal berikutnya
            $startDate->addDay();
        }
    }

    /**
     * Fungsi untuk menambah atau memperbarui StockReport.
     */
    private function addStockToDailyReport($cashierProduct, $stockIn, $date)
    {
        // Format tanggal yang sama dengan seeder
        $currentDate = $date->format('Y-m-d');

        // Cek jika ada catatan stok harian untuk produk ini
        $dailyStock = StockReport::where('cashier_product_id', $cashierProduct->id)
            ->whereDate('stock_date', $currentDate)
            ->first();

        if ($dailyStock) {
            // Update stok yang ada
            $dailyStock->stock_in += $stockIn;
            $dailyStock->current_stock += $stockIn;
            $dailyStock->save();
        } else {
            // Buat catatan stok baru
            StockReport::create([
                'cashier_product_id' => $cashierProduct->id,
                'stock_date' => $currentDate,
                'stock_in' => $stockIn,
                'stock_out' => 0, // Stok keluar belum ada
                'current_stock' => $stockIn,
            ]);
        }
    }
}
