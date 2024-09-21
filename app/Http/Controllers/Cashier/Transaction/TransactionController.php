<?php

namespace App\Http\Controllers\Cashier\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\AddToCartStoreRequest;
use App\Models\CashierProduct;
use App\Models\SalesReport;
use App\Models\StockReport;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $cashierProducts = CashierProduct::with('product')
            ->where('user_id', auth()->user()->id)
            ->get();

        return view('cashier.transaction.index', compact('cashierProducts'));
    }

    public function addToCart(AddToCartStoreRequest $request)
    {
        // Ambil produk dari CashierProduct berdasarkan cashier_product_id
        $cashierProduct = CashierProduct::where('id', $request->cashier_product_id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        // Tentukan harga berdasarkan jenis pembelian (retail atau pack)
        $priceRetail = $cashierProduct->flavor->price_retail;
        $pricePack = $cashierProduct->flavor->price_pack;

        // Cek apakah pembelian dalam kelipatan pack
        $itemsPerPack = $cashierProduct->product->items_per_pack;
        $quantity = $request->quantity;

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Hitung total stok yang telah digunakan di keranjang
        $usedStock = 0;
        foreach ($cart as $item) {
            if ($item['cashier_product_id'] == $request->cashier_product_id) {
                $usedStock += $item['purchase_type'] == 'pack' ? $item['quantity'] * $itemsPerPack : $item['quantity'];
            }
        }

        // Hitung total quantity yang diminta berdasarkan jenis pembelian
        $totalQuantityRequested = $quantity;

        // Cek ketersediaan stok termasuk stok yang telah digunakan di keranjang
        if ($cashierProduct->stock - $usedStock < $totalQuantityRequested) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pembelian ini');
        }

        // Jika jumlah tidak kelipatan pack, ubah menjadi kombinasi pack dan retail
        $packs = intdiv($quantity, $itemsPerPack); // Jumlah pack
        $remainingRetail = $quantity % $itemsPerPack; // Sisa retail

        // Jika ada pack
        if ($packs > 0) {
            $totalPackPrice = $packs * $pricePack;

            // Tambahkan produk dalam bentuk pack ke keranjang
            $cart[] = [
                'cashier_product_id' => $request->cashier_product_id,
                'product_name' => $cashierProduct->product->name,
                'flavor_name' => $cashierProduct->flavor->flavor_name,
                'quantity' => $packs,
                'purchase_type' => 'pack',
                'price' => $pricePack,
                'total' => $totalPackPrice,
            ];
        }

        // Jika ada sisa retail
        if ($remainingRetail > 0) {
            $totalRetailPrice = $remainingRetail * $priceRetail;

            // Tambahkan produk dalam bentuk retail ke keranjang
            $cart[] = [
                'cashier_product_id' => $request->cashier_product_id,
                'product_name' => $cashierProduct->product->name,
                'flavor_name' => $cashierProduct->flavor->flavor_name,
                'quantity' => $remainingRetail,
                'purchase_type' => 'retail',
                'price' => $priceRetail,
                'total' => $totalRetailPrice,
            ];
        }

        // Simpan keranjang ke session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart(Request $request, $index)
    {
        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Hapus item berdasarkan index
        if (isset($cart[$index])) {
            unset($cart[$index]);
            session()->put('cart', array_values($cart)); // Reindex array
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang masih kosong!');
        }

        // Validasi jumlah bayar
        $total = array_sum(array_column($cart, 'total'));
        $paidAmount = $request->paid_amount;

        if ($paidAmount < $total) {
            return redirect()->back()->with('error', 'Jumlah bayar tidak cukup!');
        }

        // Ambil kode transaksi dari user yang sedang login
        $user = auth()->user();
        $transactionCode = $user->transaction_code; // Kolom transaction_code di tabel users

        // Buat transaksi baru
        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'transaction_number' => $transactionCode . '-' . time(),
            'transaction_date' => now(),
            'total' => $total,
            'payment_type' => $request->payment_type,
            'paid_amount' => $paidAmount,
            'change_amount' => $paidAmount - $total,
        ]);

        // Loop melalui item di keranjang
        foreach ($cart as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'cashier_product_id' => $item['cashier_product_id'], // Ganti product_id dengan cashier_product_id
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'purchase_type' => $item['purchase_type'],
            ]);

            $cashierProduct = CashierProduct::findOrFail($item['cashier_product_id']); // Menggunakan findOrFail untuk mendapatkan cashierProduct berdasarkan ID

            // Jika pembelian eceran, kurangi stok dengan jumlah yang dibeli
            // Mengurangi stok berdasarkan jenis pembelian
            $quantitySold = $item['purchase_type'] == 'retail' ? $item['quantity'] : ($cashierProduct->product->items_per_pack * $item['quantity']);
            $cashierProduct->stock -= $quantitySold;

            // Simpan perubahan stok
            $cashierProduct->save();

            // Cek stok harian
            $currentDate = now()->format('Y-m-d');
            $dailyStock = StockReport::where('cashier_product_id', $cashierProduct->id)
                ->whereDate('stock_date', $currentDate)
                ->first();

            // Update atau buat stok harian
            if ($dailyStock) {
                $dailyStock->stock_out += $quantitySold; // Tambah stok keluar
                $dailyStock->current_stock = $cashierProduct->stock; // Update stok saat ini
                $dailyStock->save();
            } else {
                StockReport::create([
                    'cashier_product_id' => $cashierProduct->id,
                    'stock_date' => $currentDate,
                    'stock_in' => 0, // Stok masuk
                    'stock_out' => $quantitySold, // Stok keluar
                    'current_stock' => $cashierProduct->stock, // Stok saat ini
                ]);
            }
        }

        // Tambahkan atau perbarui laporan penjualan
        $existingReport = SalesReport::where('user_id', $user->id)
            ->whereDate('report_date', now()->format('Y-m-d'))
            ->first();

        if ($existingReport) {
            // Jika sudah ada, perbarui total penjualan
            $existingReport->total_sales += $total;
            $existingReport->save();
        } else {
            // Jika belum ada, buat laporan baru
            SalesReport::create([
                'user_id' => $user->id,
                'report_date' => now(),
                'total_sales' => $total,
            ]);
        }

        // Kosongkan keranjang
        session()->forget('cart');

        return redirect()->route('cashier.transaction.receipt', $transaction->id)->with('success', 'Transaksi berhasil diselesaikan!');
    }

    public function receipt($id)
    {
        // Ambil transaksi dan detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'transactionDetails.cashierProduct.flavor')->findOrFail($id);

        // Tampilkan tampilan nota dengan data transaksi
        return view('cashier.transaction.receipt', compact('transaction'));
    }

    public function generatePDF($id)
    {
        // Ambil transaksi beserta detailnya
        $transaction = Transaction::with('transactionDetails.cashierProduct.product', 'transactionDetails.cashierProduct.flavor')->findOrFail($id);

        // Generate PDF menggunakan view receipt
        $pdf = Pdf::loadView('receipt.receipt_pdf', compact('transaction'));

        // Download PDF
        return $pdf->download('Nota-' . $transaction->transaction_number . '.pdf');
    }
}
