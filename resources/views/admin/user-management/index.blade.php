@extends('admin.layouts.master')

@section('title-page')
    Pengguna
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Data Pengguna'" :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')], ['title' => 'Pengguna']]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tabel Data Pengguna'" :icon="'fas fa-user'" :addRoute="'admin.user-management.create'" />

            <x-content.table-body>

                <x-content.thead :items="['No', 'Nama', 'Nomor Transaksi', 'Lokasi', 'Role', 'Aksi']" />

                <x-content.tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="index">{{ $loop->index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->transaction_code ?? '' }}</td>
                            <td>{{ $user->location ?? '' }}</td>
                            <td>{{ $user->role ?? '' }}</td>
                            <td>
                                <a href="{{ route('admin.user-management.edit', $user->id) }}"
                                    class="btn btn-success mr-2 mb-2"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('admin.user-management.destroy', $user->id) }}"
                                    class="btn btn-danger mr-2 mb-2 delete-item"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </x-content.tbody>

            </x-content.table-body>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
