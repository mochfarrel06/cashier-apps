@extends('cashier.layouts.master')

@section('title-page')
    Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.table-container>

            <x-content.table-header :title="'Detail Transaksi'" :icon="'fas fa-shopping-cart'" />

            <div class="row mx-2 mt-3">
                <div class="col-lg-8">
                    <p><strong>Nomor Transaksi:</strong> {{ $transaction->transaction_number }}</p>
                    <p><strong>Tanggal:</strong> {{ $transaction->transaction_date }}</p>
                </div>

                <div class="col-lg-4 justify-end">
                    <p><strong>Total:</strong> Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
                    <p><strong>Bayar:</strong> Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</p>
                    <p><strong>Kembalian:</strong> Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</p>
                </div>
            </div>



            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Rasa</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->transactionDetails as $detail)
                                <tr>
                                    <td>{{ $detail->cashierProduct->product->name }}</td>
                                    <td>{{ $detail->cashierProduct->flavor->flavor_name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>Total:</strong></td>
                                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>Bayar:</strong></td>
                                <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td><strong>Kembalian:</strong></td>
                                <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

                <a href="{{ route('cashier.transaction.index') }}" class="btn btn-primary mt-5">Kembali ke Daftar
                    Transaksi</a>
                <a href="{{ route('cashier.transaction.pdf', $transaction->id) }}" class="btn btn-secondary mt-5">Cetak
                    PDF</a>
            </div>
        </x-content.table-container>

    </x-content.container-fluid>
@endsection
