@extends('cashier.layouts.master')

@section('title-page')
    Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Kasir'" />

        <div class="row">

            <div class="col-lg-5 mb-4">
                <x-content.table-container>

                    <x-content.table-header :title="'Penjualan Produk'" :icon="'fas fa-solid fa-eye'" />

                    <div class="card-body">
                        <form>
                            @csrf

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo" disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                        </form>
                    </div>

                </x-content.table-container>
            </div>

            <div class="col-lg-7 mb-4">

                <x-content.table-container>

                    <x-content.table-header :title="'Informasi Data Produk'" :icon="'fas fa-solid fa-eye'" />

                    <div class="card-body">
                        <form>
                            @csrf

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control" name="code" id="code" value="hallo"
                                    disabled>
                            </div>

                        </form>
                    </div>

                </x-content.table-container>

            </div>
        </div>

    </x-content.container-fluid>
@endsection
