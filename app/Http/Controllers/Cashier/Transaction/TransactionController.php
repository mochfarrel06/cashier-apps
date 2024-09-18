<?php

namespace App\Http\Controllers\Cashier\Transaction;

use App\Http\Controllers\Controller;
use App\Models\CashierProduct;
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

    public function addToCart(Request $request)
    {
        // Ambil produk dari CashierProduct berdasarkan cashier_product_id
        $cashierProduct = CashierProduct::where('id', $request->cashier_product_id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail(); // Menggunakan firstOrFail untuk memastikan produk ditemukan

        // Tentukan harga berdasarkan jenis pembelian (retail atau pack)
        $price = $request->purchase_type == 'retail' ? $cashierProduct->flavor->price_retail : $cashierProduct->flavor->price_pack;
        $total = $price * $request->quantity;

        // Cek apakah pembelian dalam kelipatan pack
        $itemsPerPack = $cashierProduct->product->items_per_pack; // Ambil jumlah item per pack dari produk
        $quantity = $request->quantity;

        // Jika pembelian retail dan kelipatan jumlah isi pack, gunakan harga pack
        if ($request->purchase_type == 'retail' && $quantity % $itemsPerPack == 0) {
            $packs = $quantity / $itemsPerPack; // Jumlah pack yang dibeli
            $price = $cashierProduct->flavor->price_pack; // Harga pack
            $total = $price * $packs; // Total harga berdasarkan jumlah pack
        } else {
            $total = $price * $quantity; // Total harga berdasarkan jumlah eceran
        }

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Cek apakah produk sudah ada di keranjang
        $existingProductIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['cashier_product_id'] == $request->cashier_product_id && $item['purchase_type'] == $request->purchase_type) {
                $existingProductIndex = $index;
                break;
            }
        }

        // Jika produk sudah ada di keranjang, tambahkan quantity-nya
        if ($existingProductIndex !== null) {
            $cart[$existingProductIndex]['quantity'] += $request->quantity;
            $cart[$existingProductIndex]['total'] += $total;
        } else {
            // Tambahkan produk ke keranjang
            $cart[] = [
                'cashier_product_id' => $request->cashier_product_id,
                'product_name' => $cashierProduct->product->name,
                'flavor_name' => $cashierProduct->flavor->flavor_name, // Menambahkan flavor_name
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
            if ($item['purchase_type'] == 'retail') {
                $cashierProduct->stock -= $item['quantity'];
            } else if ($item['purchase_type'] == 'pack') {
                $cashierProduct->stock -= ($cashierProduct->product->items_per_pack * $item['quantity']);
            }

            // Simpan perubahan stok
            $cashierProduct->save();
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
        $pdf = Pdf::loadView('cashier.transaction.receipt_pdf', compact('transaction'));

        // Download PDF
        return $pdf->download('receipt-transaction-' . $transaction->transaction_number . '.pdf');
    }
}
