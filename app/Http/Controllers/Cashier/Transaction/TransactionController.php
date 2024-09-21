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
        $cashierProduct = CashierProduct::where('id', $request->cashier_product_id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        // Tentukan harga berdasarkan jenis pembelian (retail atau pack)
        $price = $request->purchase_type == 'retail' ? $cashierProduct->flavor->price_retail : $cashierProduct->flavor->price_pack;
        $total = $price * $request->quantity;

        $itemsPerPack = $cashierProduct->product->items_per_pack;
        $quantity = $request->quantity;

        // Hitung total quantity yang diminta berdasarkan jenis pembelian
        $totalQuantityRequested = $request->purchase_type == 'pack' ? $quantity * $itemsPerPack : $quantity;

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Hitung total stok yang telah digunakan di keranjang
        $usedStock = 0;
        foreach ($cart as $item) {
            if ($item['cashier_product_id'] == $request->cashier_product_id) {
                $usedStock += $item['purchase_type'] == 'pack' ? $item['quantity'] * $itemsPerPack : $item['quantity'];
            }
        }

        // Cek ketersediaan stok termasuk stok yang telah digunakan di keranjang
        if ($cashierProduct->stock - $usedStock < $totalQuantityRequested) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pembelian ini');
        }

        // Jika pembelian retail dan kelipatan jumlah isi pack, ubah type menjadi pack
        if ($request->purchase_type == 'retail' && $quantity % $itemsPerPack == 0) {
            $packs = $quantity / $itemsPerPack;
            $price = $cashierProduct->flavor->price_pack;
            $total = $price * $packs;

            $request->merge([
                'purchase_type' => 'pack',
                'quantity' => $packs
            ]);
        } else {
            $total = $price * $quantity;
        }

        // Cek apakah produk sudah ada di keranjang
        $existingProductIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['cashier_product_id'] == $request->cashier_product_id && $item['purchase_type'] == $request->purchase_type) {
                $existingProductIndex = $index;
                break;
            }
        }

        // Jika produk sudah ada di keranjang, tambahkan quantity dan update total harga
        if ($existingProductIndex !== null) {
            // Update quantity dan total
            $cart[$existingProductIndex]['quantity'] += $request->quantity;
            $cart[$existingProductIndex]['total'] += $total;

            // Cek apakah jumlah retail bisa diubah menjadi pack
            if ($request->purchase_type == 'retail' && $cart[$existingProductIndex]['quantity'] >= $itemsPerPack) {
                // Ubah jumlah retail menjadi pack
                $packs = intdiv($cart[$existingProductIndex]['quantity'], $itemsPerPack);
                $remainingRetail = $cart[$existingProductIndex]['quantity'] % $itemsPerPack;
                $price = $cashierProduct->flavor->price_pack;
                $totalPack = $price * $packs;

                // Update quantity dan harga
                $cart[$existingProductIndex]['purchase_type'] = 'pack';
                $cart[$existingProductIndex]['quantity'] = $packs;
                $cart[$existingProductIndex]['total'] = $totalPack;

                // Jika ada sisa retail, tambahkan sebagai item retail baru
                if ($remainingRetail > 0) {
                    $cart[] = [
                        'cashier_product_id' => $request->cashier_product_id,
                        'product_name' => $cashierProduct->product->name,
                        'flavor_name' => $cashierProduct->flavor->flavor_name,
                        'quantity' => $remainingRetail,
                        'purchase_type' => 'retail',
                        'price' => $cashierProduct->flavor->price_retail,
                        'total' => $cashierProduct->flavor->price_retail * $remainingRetail,
                    ];
                }
            }
        } else {
            // Tambahkan produk ke keranjang jika belum ada
            $cart[] = [
                'cashier_product_id' => $request->cashier_product_id,
                'product_name' => $cashierProduct->product->name,
                'flavor_name' => $cashierProduct->flavor->flavor_name,
                'quantity' => $request->quantity,
                'purchase_type' => $request->purchase_type,
                'price' => $price,
                'total' => $total,
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
