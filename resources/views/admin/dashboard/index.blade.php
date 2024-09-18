@extends('admin.layouts.master')

@section('title-page')
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Dashboard Admin'" />

        <x-content.card-row>
            @foreach ($cards as $card)
                <x-content.card-dashboard :title="$card['title']" :bgColor="$card['bg_color']" :value="$card['value']" :icon="$card['icon']" />
            @endforeach
        </x-content.card-row>

        {{-- <x-content.table-container>

            <x-content.table-header :title="'Filter Laporan Produk Terjual'" :icon="'fas fa-solid fa-filter'" />

            <div class="card-body">
                <form action="{{ route('admin.report-detail.index') }}" method="GET">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cashier_id">Pilih Kasir</label>
                                <select name="cashier_id" id="cashier_id" class="form-control" required>
                                    <option value="">-- Pilih Kasir --</option>
                                    @foreach ($cashiers as $cashier)
                                        <option value="{{ $cashier->id }}"
                                            {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                                            {{ $cashier->name }}
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
                        <a href="{{ route('admin.report-detail.exportExcel', ['cashier_id' => $cashierId, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                            class="d-sm-inline-block btn btn-sm btn-success shadow-sm mb-2"><i
                                class="fa-solid fa-file-excel"></i> Export Excel</a>
                    </div>
                </form>
            </div>
        </x-content.table-container> --}}


        <x-content.table-container>

            <x-content.table-header :title="'Tabel Produk Kasir'" :icon="'fas fa-circle-exclamation'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kasir', 'Nama Produk', 'Varian Produk', 'Stok']" />

                <x-content.tbody>
                    @foreach ($cashierProducts as $cashierProduct)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $cashierProduct->user->name ?? '' }}</td>
                            <td>{{ $cashierProduct->product->name ?? '' }}</td>
                            <td>{{ $cashierProduct->flavor->flavor_name }}</td>
                            <td>{{ $cashierProduct->stock }}</td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
