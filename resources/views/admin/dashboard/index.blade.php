@extends('admin.layouts.master')

@section('title-page')
    Admin
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
