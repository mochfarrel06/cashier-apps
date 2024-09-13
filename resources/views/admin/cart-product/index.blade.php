@extends('admin.layouts.master')

@section('title-page')
    Produk Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Produk Kasir'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Produk Kasir']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Produk Kasir'" :icon="'fas fa-shop'" :addRoute="'admin.cart-product.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kasir', 'Nama Kasir', 'Nama Produk', 'Varian Produk', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($cartProducts as $cartProduct)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $cartProduct->cart->user->name }}</td>
                            <td>{{ $cartProduct->cart->name }}</td>
                            <td>{{ $cartProduct->product->name }}</td>
                            <td>{{ $cartProduct->flavor->flavor_name }}</td>
                            <td>
                                <a href="{{ route('admin.cart-product.show', $cartProduct->id) }}"
                                    class="btn btn-warning mr-2 mb-2"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.cart-product.edit', $cartProduct->id) }}"
                                    class="btn btn-success mr-2 mb-2"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.cart-product.destroy', $cartProduct->id) }}"
                                    class="btn btn-danger mr-2 mb-2 delete-item"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
