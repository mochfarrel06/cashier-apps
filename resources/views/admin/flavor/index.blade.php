@extends('admin.layouts.master')

@section('title-page')
    Varian Produk
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Varian Produk'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Varian Produk']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Varian Produk'" :icon="'fas fa-bread-slice'" :addRoute="'admin.flavor.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Nama Produk', 'Varian Produk', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($flavors as $flavor)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $flavor->product->name }}</td>
                            <td>{{ $flavor->flavor_name }}</td>
                            <td>
                                <a href="{{ route('admin.flavor.show', $flavor->id) }}" class="btn btn-warning mr-2 mb-2"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.flavor.edit', $flavor->id) }}" class="btn btn-success mr-2 mb-2"><i
                                        class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.flavor.destroy', $flavor->id) }}"
                                    class="btn btn-danger mr-2 mb-2 delete-item"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection
