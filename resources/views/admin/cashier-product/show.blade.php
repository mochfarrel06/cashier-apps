@extends('admin.layouts.master')

@section('title-page')
    Lihat
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Lihat Produk Kasir'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Produk Kasir', 'url' => route('admin.cashier-product.index')],
            ['title' => 'Lihat'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Informasi Produk Kasir'" :icon="'fas fa-solid fa-eye'" />

            <div class="card-body">
                <form>
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="user_id">Nama Kasir</label>
                                <input type="text" class="form-control @error('user_id') is-invalid @enderror"
                                    name="user_id" id="user_id" value="{{ $user->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="product_id">Nama Produk</label>
                                <input type="text" class="form-control @error('product_id') is-invalid @enderror"
                                    name="product_id" id="product_id" value="{{ $product->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="flavor_id">Varian Produk</label>
                                <input type="text" class="form-control @error('flavor_id') is-invalid @enderror"
                                    name="flavor_id" id="flavor_id" value="{{ $flavor->flavor_name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="stock">Jumlah Produk</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" id="stock" value="{{ $cashierProduct->stock }}" disabled>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.cashier-product.index') }}"
                        class="d-sm-inline-block btn btn-sm btn-warning shadow-sm mt-3">Kembali</a>
                </form>
            </div>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
