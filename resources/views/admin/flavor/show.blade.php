@extends('admin.layouts.master')

@section('title-page')
    Lihat
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Lihat Varian Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Varian Produk', 'url' => route('admin.flavor.index')],
            ['title' => 'Lihat'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Informasi Varian Produk'" :icon="'fas fa-solid fa-eye'" />

            <div class="card-body">
                <form>
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    id="name" value="{{ $product->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    name="code" id="code" value="{{ $product->code }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="flavor_name">Varian Produk</label>
                                <input type="text" class="form-control @error('flavor_name') is-invalid @enderror"
                                    name="flavor_name" id="flavor_name" value="{{ $flavor->flavor_name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="price_retail">Harga Produk Eceran</label>
                                <input type="text" class="form-control" name="price_retail" id="price_retail"
                                    value="Rp {{ number_format($flavor->price_retail ?? '0', '0', ',', '.') }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="price_pack">Harga Produk Per Pack</label>
                                <input type="text" class="form-control" name="price_pack" id="price_pack"
                                    value="Rp {{ number_format($flavor->price_pack ?? '0', '0', ',', '.') }}" disabled>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.flavor.index') }}" class="btn btn-warning mt-4">Kembali</a>
                </form>
            </div>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
