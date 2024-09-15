<?php

namespace App\Http\Controllers\Cashier\Transaction;

use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;
        $cartProducts = CartProduct::whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        // $cartItems = session('cart', []);
        // $totalPrice = array_sum(array_map(function ($item) {
        //     return $item['price'] * $item['quantity'];
        // }, $cartItems));

        return view('cashier.transaction.index', compact('cartProducts'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'cart_product_id' => 'required|exists:cart_products,id',
            'quantity' => 'required|integer|min:1',
            'purchase_type' => 'required|in:retail,pack',
            'price' => 'nullable|numeric',
        ]);

        $cartItems = session()->get('cart', []);
        $cartItems[] = $validated;
        session()->put('cart', $cartItems);

        return redirect()->route('cashier.transaction.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function removeFromCart($id)
    {
        $cartItems = session()->get('cart', []);
        unset($cartItems[$id]);
        session()->put('cart', $cartItems);

        return redirect()->route('cashier.transaction.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_price' => 'required|numeric',
            'payment_amount' => 'required|numeric',
            'change_amount' => 'required|numeric',
        ]);

        $transaction = new Transaction();
        $transaction->user_id = auth()->user()->id;
        $transaction->transaction_date = now();
        $transaction->transaction_number = 'TRX-' . now()->timestamp;
        $transaction->total = $validated['total_price'];
        $transaction->payment_type = 'cash'; // As an example, could be extended to support more types
        $transaction->paid_amount = $validated['payment_amount'];
        $transaction->change_amount = $validated['change_amount'];
        $transaction->save();

        $cartItems = session('cart', []);
        foreach ($cartItems as $item) {
            $transactionDetail = new TransactionDetail();
            $transactionDetail->transaction_id = $transaction->id;
            $transactionDetail->cart_product_id = $item['cart_product_id'];
            $transactionDetail->quantity = $item['quantity'];
            $transactionDetail->price = $item['price'];
            $transactionDetail->purchase_type = $item['purchase_type'];
            $transactionDetail->save();
        }

        session()->forget('cart');

        return redirect()->route('cashier.transaction.index')->with('success', 'Transaksi berhasil diproses.');
    }
}
