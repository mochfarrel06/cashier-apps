@extends('admin.layouts.master')

@section('title-page')
    Laporan Detail Transaksi
@endsection

@section('content')
    <x-content.container-fluid>

        {{-- <x-content.heading-page :title="'Halaman Laporan Detail Transaksi'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Laporan Detail Transaksi'],
        ]" /> --}}

        <!-- Tombol untuk membuat laporan stok harian -->
        <table class="table table-bordered">
            <thead>
                <tr>
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
                        <td>{{ $report->cashierProduct->user->name }}</td>
                        <td>{{ $report->stock_date }}</td>
                        <td>{{ $report->cashierProduct->product->name }}</td>
                        <td>{{ $report->cashierProduct->flavor->flavor_name ?? 'N/A' }}</td>
                        <td>{{ $report->stock_in }}</td>
                        <td>{{ $report->stock_out }}</td>
                        <td>{{ $report->current_stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-content.container-fluid>
@endsection
