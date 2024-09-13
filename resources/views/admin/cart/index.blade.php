@extends('admin.layouts.master')

@section('title-page')
    Data Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Data Kasir'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Data Kasir']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Data Kasir'" :icon="'fas fa-shop'" :addRoute="'admin.cart.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Kasir', 'Nama Kasir', 'Lokasi', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($carts as $cart)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $cart->user->name }}</td>
                            <td>{{ $cart->name }}</td>
                            <td>{{ $cart->location }}</td>
                            <td>
                                <a href="{{ route('admin.cart.show', $cart->id) }}" class="btn btn-warning mr-2 mb-2"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.cart.edit', $cart->id) }}" class="btn btn-success mr-2 mb-2"><i
                                        class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.cart.destroy', $cart->id) }}"
                                    class="btn btn-danger mr-2 mb-2 delete-item"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
