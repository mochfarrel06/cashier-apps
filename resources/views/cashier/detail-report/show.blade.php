@extends('cashier.layouts.master')

@section('title-page')
    Lihat
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Lihat Varian Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Varian Produk', 'url' => route('admin.flavor.index')],
            ['title' => 'Lihat'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Detail Transaksi'" :icon="'fas fa-bread-slice'" />

            <x-content.table-body>

                <x-content.thead :items="['Kode Transaksi', 'Tanggal Transaksi', 'Total Bayar', 'Kembalian']" />

                <x-content.tbody>
                    <tr>
                        <td>{{ $transaction->transaction_number }}</td>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    </tr>

                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>

        <x-content.table-container>

            <x-content.table-header :title="'Rincian Produk'" :icon="'fas fa-bread-slice'" />

            <x-content.table-body>

                <x-content.thead :items="['Nama Produk', 'Varian Produk', 'Jenis Pembelian', 'Jumlah', 'Harga', 'Total']" />

                <tbody>
                    @foreach ($transaction->transactionDetails as $detail)
                        <tr>
                            <td>{{ $detail->cashierProduct->product->name }}</td>
                            <td>{{ $detail->cashierProduct->flavor->flavor_name }}</td>
                            <td>{{ ucfirst($detail->purchase_type) }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>Total:</strong></td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>Bayar:</strong></td>
                        <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>Kembalian:</strong></td>
                        <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>

            </x-content.table-body>
        </x-content.table-container>

        <div class="row mt-3 ml-2">
            <a href="{{ route('cashier.transaction.pdf', $transaction->id) }}" class="btn btn-secondary mr-2">Cetak
                PDF</a>
            <a href="{{ route('cashier.report.dailyReport') }}" class="btn btn-warning">Kembali</a>
        </div>


    </x-content.container-fluid>
@endsection
