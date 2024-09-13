@extends('admin.layouts.master')

@section('title-page')
    Lihat
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Lihat Kasir Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Kasir Produk', 'url' => route('admin.cart-product.index')],
            ['title' => 'Lihat'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Informasi Kasir Produk'" :icon="'fas fa-solid fa-eye'" />

            <div class="card-body">
                <form>
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cart_id">Data Kasir</label>
                                <input type="text" class="form-control @error('cart_id') is-invalid @enderror"
                                    name="cart_id" id="cart_id" value="{{ $cart->user->name }}" disabled>
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
                                    name="stock" id="stock" value="{{ $cartProduct->stock }}" disabled>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.cart-product.index') }}" class="btn btn-warning mt-4">Kembali</a>
                </form>
            </div>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection
