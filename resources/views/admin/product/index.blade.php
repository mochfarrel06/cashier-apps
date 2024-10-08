@extends('admin.layouts.master')

@section('title-page')
    Data Produk
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Data Produk'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Produk']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Data Produk'" :icon="'fas fa-box'" :addRoute="'admin.product.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kode Produk', 'Nama Produk', 'Isi per pack', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $product->code ?? '' }}</td>
                            <td>{{ $product->name ?? '' }}</td>
                            <td>{{ $product->items_per_pack ?? '' }}</td>
                            <td>
                                <a href="{{ route('admin.product.show', $product->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-warning shadow-sm mr-2 mb-2"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.product.edit', $product->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2 mb-2"><i
                                        class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.product.destroy', $product->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-2 mb-2 delete-item"><i
                                        class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
