@extends('admin.layouts.master')

@section('title-page')
    Laporan Transaksi
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Laporan Transaksi'" />

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-bread-slice"></i> Laporan
                    Transaksi Harian
                </h6>
                {{-- <a href="{{ route('cashier.report.downloadAll') }}"
                    class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-2">Download</a> --}}
            </div>

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kode Transaksi', 'Jumlah Bayar', 'Kembalian', 'Tanggal Transaksi', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>{{ number_format($transaction->paid_amount, 2) }}</td>
                            <td>{{ number_format($transaction->change_amount, 2) }}</td>
                            <td>{{ $transaction->transaction_date }}</td>
                            <td>
                                <a href="{{ route('cashier.report.showReportDetail', $transaction->id) }}"
                                    class="btn btn-info">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
