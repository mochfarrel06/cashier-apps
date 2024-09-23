@extends('cashier.layouts.master')

@section('title-page')
    Laporan Pendapatan
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Laporan Pendapatan Harian'" :breadcrumbs="[
            ['title' => 'Beranda', 'url' => route('cashier.dashboard.index')],
            ['title' => 'Laporan Pendapatan'],
        ]" />

        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Pendapatan Hari ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($totalIncome, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="far fas fa-solid fa-money-bill fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-file"></i> Laporan
                    Pendapatan Harian
                </h6>
                <a href="{{ route('cashier.income-report.download') }}"
                    class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-2">Unduh Laporan</a>
            </div>

            <x-content.table-body>

                <x-content.thead :items="['No', 'Tanggal Transaksi', 'Kode Transaksi', 'Jumlah Bayar']" />

                <x-content.tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
