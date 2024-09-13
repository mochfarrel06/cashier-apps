@extends('admin.layouts.master')

@section('title-page')
    Edit
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Edit Produk Kasir'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Produk Kasir', 'url' => route('admin.cart-product.index')],
            ['title' => 'Edit'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Edit Produk Kasir'" :icon="'fas fa-solid fa-edit'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.cart-product.update', $cartProduct->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cart_id">Data Kasir</label>
                                <select name="cart_id" id="cart_id"
                                    class="form-control @error('cart_id') is-invalid @enderror">
                                    <option value="">-- Pilih Data Kasir --</option>
                                    @foreach ($carts as $cart)
                                        <option value="{{ $cart->id }}"
                                            {{ $cart->id == $cartProduct->cart_id ? 'selected' : '' }}>
                                            {{ $cart->user->name }}</option>
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
                                        <option value="{{ $product->id }}"
                                            {{ $product->id == $cartProduct->product_id ? 'selected' : '' }}>
                                            {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="flavor_id">Varian Produk</label>
                                <select name="flavor_id" id="flavor_id"
                                    class="form-control @error('flavor_id') is-invalid @enderror">
                                    <option value="">-- Pilih Varian Produk --</option>
                                    @foreach ($flavors as $flavor)
                                        <option value="{{ $flavor->id }}"
                                            {{ $flavor->id == $cartProduct->flavor_id ? 'selected' : '' }}>
                                            {{ $flavor->flavor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="stock">Jumlah Produk</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" id="stock" value="{{ old('stock', $cartProduct->stock) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" id="submit-btn" class="btn btn-success">Edit</button>
                        <a href="{{ route('admin.cart-product.index') }}" class="btn btn-warning ml-2">Kembali</a>
                    </div>
                </form>
            </x-content.card-body>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection

@push('scripts')
    <script>
        // Handle form submission using AJAX
        $('#main-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            const form = $(this);
            const formData = new FormData(form[0]); // Use FormData to handle file uploads
            const submitButton = $('#submit-btn');
            submitButton.prop('disabled', true).text('Loading...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST', // Use POST for form submission
                data: formData,
                contentType: false, // Prevent jQuery from setting content type
                processData: false, // Prevent jQuery from processing data
                success: function(response) {
                    if (response.success) {
                        // Flash message sukses
                        sessionStorage.setItem('success',
                            'Kasir produk berhasil disubmit.');
                        window.location.href =
                            "{{ route('admin.cart-product.index') }}"; // Redirect to index page
                    } else if (response.info) {
                        // Flash message info
                        sessionStorage.setItem('info',
                            'Tidak melakukan perubahan data pada kasir produk.');
                        window.location.href =
                            "{{ route('admin.cart-product.index') }}"; // Redirect to index page
                    } else {
                        // Flash message error
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
                        // Remove existing invalid feedback to avoid duplicates
                        input.next('.invalid-feedback').remove();
                        input.after('<div class="invalid-feedback">' + error + '</div>');
                    }

                    const message = response.responseJSON.message ||
                        'Terdapat kesalahan pada kasir produk.';
                    $('#flash-messages').html('<div class="alert alert-danger">' + message +
                        '</div>');
                },
                complete: function() {
                    submitButton.prop('disabled', false).text('Edit');
                }
            });
        });

        // Remove validation error on input change
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    </script>
@endpush
