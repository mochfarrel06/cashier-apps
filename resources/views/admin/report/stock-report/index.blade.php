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
        <form action="{{ route('admin.stock-report.generateReport') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary mb-3">Buat Laporan Stok Harian</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Varian Rasa</th>
                    <th>Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockReports as $report)
                    <tr>
                        <td>{{ $report->report_date }}</td>
                        <td>{{ $report->product->name }}</td>
                        <td>{{ $report->flavor ? $report->flavor->flavor_name : '-' }}</td>
                        <td>{{ $report->stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-content.container-fluid>
@endsection
