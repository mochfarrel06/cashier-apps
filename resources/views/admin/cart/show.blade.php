@extends('admin.layouts.master')

@section('title-page')
    Lihat
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Lihat Data Kasir'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Data Kasir', 'url' => route('admin.cart.index')],
            ['title' => 'Lihat'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Informasi Data Kasir'" :icon="'fas fa-solid fa-eye'" />

            <div class="card-body">
                <form>
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="user_id">Kasir</label>
                                <input type="text" class="form-control @error('user_id') is-invalid @enderror"
                                    name="user_id" id="user_id" value="{{ $user->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nama Kasir</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{ $cart->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="location">Lokasi</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    name="location" id="location" value="{{ $cart->location }}" disabled>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.cart.index') }}" class="btn btn-warning mt-4">Kembali</a>
                </form>
            </div>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
