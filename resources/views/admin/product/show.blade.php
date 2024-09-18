@extends('admin.layouts.master')

@section('title-page')
    Lihat
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Lihat Data Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Data Produk', 'url' => route('admin.product.index')],
            ['title' => 'Lihat'],
        ]" />

        <div class="row">
            <div class="col-lg-5 mb-4">
                <div class="card shadow">
                    <div class="card-body text-center">
                        @if ($product->photo)
                            <img src="{{ asset($product->photo) }}" alt="Produk Image" class="img-fluid rounded" />
                        @else
                            <p>Tidak ada gambar yang tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7 mb-4">

                <x-content.table-container>

                    <x-content.table-header :title="'Informasi Data Produk'" :icon="'fas fa-solid fa-eye'" />

                    <div class="card-body">
                        <form>
                            @csrf

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code"
                                    value="{{ $product->code }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $product->name }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Produk</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                    rows="4" placeholder="Masukkan deskripsi produk" disabled>{{ $product->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="items_per_pack">Jumlah Produk Per Pack</label>
                                <input type="number" class="form-control" name="items_per_pack" id="items_per_pack"
                                    value="{{ $product->items_per_pack }}" disabled>
                            </div>

                            <a href="{{ route('admin.product.index') }}" class="btn btn-warning mt-3">Kembali</a>
                        </form>
                    </div>

                </x-content.table-container>

            </div>
        </div>

    </x-content.container-fluid>
@endsection
