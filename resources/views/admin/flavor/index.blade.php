@extends('admin.layouts.master')

@section('title-page')
    Varian Produk
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Varian Produk'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Varian Produk']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Varian Produk'" :icon="'fas fa-solid fa-lemon'" :addRoute="'admin.flavor.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Nama Produk', 'Varian Produk', 'Harga Eceran', 'Harga Perpack', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($flavors as $flavor)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $flavor->product->name ?? '' }}</td>
                            <td>{{ $flavor->flavor_name ?? '' }}</td>
                            <td>Rp {{ number_format($flavor->price_retail ?? '0', '0', ',', '.') }}</td>
                            <td>Rp {{ number_format($flavor->price_pack ?? '0', '0', ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.flavor.show', $flavor->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-warning shadow-sm mr-2 mb-2"><i
                                        class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.flavor.edit', $flavor->id) }}"
                                    class="d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2 mb-2"><i
                                        class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.flavor.destroy', $flavor->id) }}"
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
