@extends('admin.layouts.master')

@section('title-page')
    Laporan Stok
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Laporan Stok'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Laporan Stok']]" />

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-solid fa-filter"></i>
                    Filter Laporan Stok
                </h6>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.stock-report.index') }}" method="GET">
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
                        <button type="submit" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">Tampilkan
                            Data</button>
                        <a href="{{ route('admin.stock-report.exportExcel', ['cashier_id' => $cashierId, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                                class="fa-solid fa-file-excel"></i>
                            Export
                            Excel</a>
                    </div>
                </form>
            </div>
        </x-content.table-container>

        <x-content.table-container>

            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold mb-2" style="color: #722c75"><i class="fas fa-file"></i> Laporan
                    Transaksi Per Kasir
                </h6>
            </div>

            <x-content.table-body>

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kasir</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Varian Produk</th>
                        <th>Stok Masuk</th>
                        <th>Stok Keluar</th>
                        <th>Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockReports as $report)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $report->cashierProduct->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($report->stock_date)->format('d-m-Y') }}</td>
                            <td>{{ $report->cashierProduct->product->name }}</td>
                            <td>{{ $report->cashierProduct->flavor->flavor_name ?? 'N/A' }}</td>
                            <td>{{ $report->stock_in }}</td>
                            <td>{{ $report->stock_out }}</td>
                            <td>{{ $report->current_stock }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
