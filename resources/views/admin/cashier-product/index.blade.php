@extends('admin.layouts.master')

@section('title-page')
    Produk Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Produk Kasir'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Produk Kasir']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Produk Kasir'" :icon="'fas fa-shop'" :addRoute="'admin.cashier-product.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kasir', 'Nama Produk', 'Varian Produk', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($cashierProducts as $cashierProduct)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $cashierProduct->user->name ?? '' }}</td>
                            <td>{{ $cashierProduct->product->name ?? '' }}</td>
                            <td>{{ $cashierProduct->flavor->flavor_name ?? '' }}</td>
                            <td>
                                <a href="{{ route('admin.cashier-product.show', $cashierProduct->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-warning shadow-sm mr-2 mb-2"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.cashier-product.edit', $cashierProduct->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2 mb-2"><i
                                        class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.cashier-product.destroy', $cashierProduct->id) }}"
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
