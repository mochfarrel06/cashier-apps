@extends('cashier.layouts.master')

@section('title-page')
    Laporan Transaksi Harian
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Laporan Transaksi Harian'" :breadcrumbs="[
            ['title' => 'Beranda', 'url' => route('cashier.dashboard.index')],
            ['title' => 'Laporan Transaksi'],
        ]" />

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-file"></i> Laporan
                    Transaksi Harian
                </h6>
                <a href="{{ route('cashier.transaction-report.exportExcel') }}"
                    class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-2">Unduh Laporan</a>
            </div>

            <x-content.table-body>

                <x-content.thead :items="['No', 'Tanggal Transaksi', 'Kode Transaksi', 'Total', 'Varian Terjual', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>Rp {{ number_format($transaction->net_total, 0, ',', '.') }}</td>
                            <td>
                                @foreach ($transaction->transactionDetails as $detail)
                                    {{ $detail->cashierProduct->flavor->flavor_name ?? '' }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('cashier.transaction-report.show', $transaction->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-info shadow-sm">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
