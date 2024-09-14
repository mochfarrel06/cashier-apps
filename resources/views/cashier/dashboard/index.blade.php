@extends('cashier.layouts.master')

@section('title-page')
    Dashboard
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Dashboard Kasir'" />

        {{-- <x-content.card-row>
            @foreach ($cards as $card)
                <x-content.card-dashboard :title="$card['title']" :bgColor="$card['bg_color']" :value="$card['value']" :icon="$card['icon']" />
            @endforeach
        </x-content.card-row> --}}

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Data Barang Minimum'" :icon="'fas fa-circle-exclamation'" />

            <x-content.table-body>

                {{-- <x-content.thead :items="['No', 'Kode Barang', 'Nama Barang', 'Jenis Barang', 'Satuan', 'Stock']" /> --}}

                {{-- <x-content.tbody>
                    @foreach ($data as $item)
                        <x-content.table-row :index="$loop->index + 1" :item="$item" :columns="[
                            $item->item_code,
                            $item->name,
                            $item->itemType->name,
                            $item->unitType->name,
                            $item->stock,
                        ]" />
                    @endforeach
                </x-content.tbody> --}}
            </x-content.table-body>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
