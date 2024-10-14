@extends('cashier.layouts.master')

@section('title-page')
    Detail Transaksi
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Detail Transaksi'" :breadcrumbs="[
            ['title' => 'Beranda', 'url' => route('cashier.dashboard.index')],
            ['title' => 'Laporan Transaksi', 'url' => route('cashier.transaction-report.index')],
            ['title' => 'Detail Transaksi'],
        ]" />

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-solid fa-circle-info"></i> Detail
                    Transaksi</h6>
                <div class="d-flex">
                    <a href="{{ route('cashier.transaction-report.pdf', $transaction->id) }}"
                        class="d-sm-inline-block btn btn-sm btn-secondary shadow-sm mr-2">Unduh
                        PDF</a>
                    <a href="{{ route('cashier.transaction-report.index') }}"
                        class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">Kembali</a>
                </div>
            </div>

            <x-content.table-body>

                <x-content.thead :items="[
                    'Kode Transaksi',
                    'Tanggal Transaksi',
                    'Jenis Pembayaran',
                    'Sub Total',
                    'Diskon',
                    'Total',
                    'Bayar',
                    'Kembalian',
                ]" />

                <x-content.tbody>
                    <tr>
                        <td>{{ $transaction->transaction_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                        <td>{{ ucfirst($transaction->payment_type) }}</td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->net_total, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    </tr>

                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>

        <x-content.table-container>

            <x-content.table-header :title="'Rincian Produk'" :icon="'fas fa-solid fa-box'" />

            <x-content.table-body>

                <x-content.thead :items="['Nama Produk', 'Varian Produk', 'Jenis Pembelian', 'Jumlah', 'Harga Satuan', 'Total']" />

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
                        <td><strong>Sub Total:</strong></td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>Diskon:</strong></td>
                        <td>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td><strong>Total:</strong></td>
                        <td><strong>Rp {{ number_format($transaction->net_total, 0, ',', '.') }}</strong></td>
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

    </x-content.container-fluid>
@endsection
