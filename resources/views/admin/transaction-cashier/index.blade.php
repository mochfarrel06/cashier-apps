@extends('admin.layouts.master')

@section('title-page')
    Data Transaksi
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Data Transaksi'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Data Transaksi']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Filter Laporan Data Transaksi'" :icon="'fas fa-solid fa-filter'" />

            <div class="card-body">
                <form action="" method="GET">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cashier_id">Pilih Kasir</label>
                                <select name="cashier_id" id="cashier_id" class="form-control" required>
                                    <option value="">-- Pilih Kasir --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $cashierId == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="form-control"
                                    value="{{ $startDate }}" required>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="date" name="end_date" id="end_date" class="form-control"
                                    value="{{ $endDate }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit"
                            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-2 mr-3">Tampilkan
                            Data</button>
                    </div>
                </form>
            </div>
        </x-content.table-container>

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-solid fa-handshake"></i>
                    Laporan Data
                    Transaksi
                </h6>
            </div>

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kode Transaksi', 'Tanggal Transaksi', 'Jumlah Bayar', 'Varian terjual', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($transaction->paid_amount ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @foreach ($transaction->transactionDetails as $detail)
                                    {{ $detail->cashierProduct->flavor->flavor_name ?? '' }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.transaction-cashier.show', ['id' => $transaction->id, 'cashier_id' => request('cashier_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                    class="d-sm-inline-block btn btn-sm btn-info shadow-sm">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
