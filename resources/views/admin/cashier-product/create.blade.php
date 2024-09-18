@extends('admin.layouts.master')

@section('title-page')
    Tambah
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Tambah Produk Kasir'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Produk Kasir', 'url' => route('admin.cashier-product.index')],
            ['title' => 'Tambah'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tambah Produk Kasir'" :icon="'fas fa-solid fa-plus'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.cashier-product.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="user_id">Data Kasir</label>
                                <select name="user_id" id="user_id"
                                    class="form-control @error('user_id') is-invalid @enderror">
                                    <option value="">-- Pilih Data Kasir --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="product_id">Data Produk</label>
                                <select name="product_id" id="product_id"
                                    class="form-control @error('product_id') is-invalid @enderror">
                                    <option value="">-- Pilih Data Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="flavor_id">Varian Rasa</label>
                                <select name="flavor_id" id="flavor_id" class="form-control">
                                    <option value="">Pilih Varian Rasa</option>
                                    <!-- Flavors akan dimuat di sini secara dinamis -->
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="stock">Jumlah Produk</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" id="stock" value="{{ old('stock') }}"
                                    placeholder="Masukkan jumlah produk">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="submit-btn"
                            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah</button>
                        <a href="{{ route('admin.cashier-product.index') }}"
                            class="d-sm-inline-block btn btn-sm btn-warning shadow-sm ml-2">Kembali</a>
                    </div>
                </form>
            </x-content.card-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const $submitBtn = $('#submit-btn');
            $('#main-form').on('submit', function(event) {
                event.preventDefault();

                const form = $(this)[0];
                const formData = new FormData(form); // Create FormData object with form data

                $submitBtn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the content type
                    success: function(response) {
                        if (response.success) {
                            sessionStorage.setItem('success',
                                'Produk kasir berhasil disubmit.');
                            window.location.href =
                                "{{ route('admin.cashier-product.index') }}"; // Redirect to index page
                        } else {
                            $('#flash-messages').html('<div class="alert alert-danger">' +
                                response.error + '</div>');
                        }
                    },
                    error: function(response) {
                        const errors = response.responseJSON.errors;
                        for (let field in errors) {
                            let input = $('[name=' + field + ']');
                            let error = errors[field][0];
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').remove();
                            input.after('<div class="invalid-feedback">' + error + '</div>');
                        }

                        const message = response.responseJSON.message ||
                            'Terdapat kesalahan pada proses produk kasir';
                        $('#flash-messages').html('<div class="alert alert-danger">' + message +
                            '</div>');

                        if (response.responseJSON.error) {
                            window.location.href =
                                "{{ route('admin.cashier-product.create') }}";
                        }
                    },
                    complete: function() {
                        $submitBtn.prop('disabled', false).text('Tambah');
                    }
                });
            });

            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').text('');
            });

            $('#product_id').on('change', function() {
                var productId = $(this).val();

                if (productId) {
                    $.ajax({
                        url: '/admin/products/' + productId +
                            '/flavors', // Tambahkan prefix /admin di sini
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#flavor_id').empty();
                            $('#flavor_id').append(
                                '<option value="">Pilih Varian Rasa</option>');

                            $.each(data, function(key, flavor) {
                                $('#flavor_id').append('<option value="' + flavor.id +
                                    '">' + flavor.flavor_name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#flavor_id').empty();
                    $('#flavor_id').append('<option value="">Pilih Varian Rasa</option>');
                }
            });

        });
    </script>
@endpush
