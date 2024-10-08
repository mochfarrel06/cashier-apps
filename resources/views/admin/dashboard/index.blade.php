@extends('admin.layouts.master')

@section('title-page')
    Dashboard
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Dashboard Admin'" />

        <x-content.card-row>
            @foreach ($cards as $card)
                <x-content.card-dashboard :title="$card['title']" :bgColor="$card['bg_color']" :value="$card['value']" :icon="$card['icon']" />
            @endforeach
        </x-content.card-row>

        <x-content.table-container>

            <x-content.table-header :title="'Filter Produk Kasir'" :icon="'fas fa-solid fa-filter'" />

            <div class="card-body">
                <form action="{{ route('admin.dashboard') }}" method="GET">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cashier_id">Kasir</label>
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
                    </div>

                    <div class="mt-2">
                        <button type="submit"
                            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mb-2 mr-3">Tampilkan
                            Data</button>
                    </div>
                </form>
            </div>
        </x-content.table-container>

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Produk Kasir'" :icon="'fas fa-box'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kasir', 'Lokasi', 'Nama Produk', 'Varian Produk', 'Stok']" />

                <x-content.tbody>
                    @foreach ($cashierProducts as $cashierProduct)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $cashierProduct->user->name ?? '' }}</td>
                            <td>{{ $cashierProduct->user->location ?? '' }}</td>
                            <td>{{ $cashierProduct->product->name ?? '' }}</td>
                            <td>{{ $cashierProduct->flavor->flavor_name ?? '' }}</td>
                            <td>{{ $cashierProduct->stock ?? '' }}</td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
