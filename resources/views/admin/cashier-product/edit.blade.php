@extends('admin.layouts.master')

@section('title-page')
    Edit
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Edit Produk Kasir'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Produk Kasir', 'url' => route('admin.cashier-product.index')],
            ['title' => 'Edit'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Edit Produk Kasir'" :icon="'fas fa-solid fa-edit'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.cashier-product.update', $cashierProduct->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="user_id">Data Kasir</label>
                                <input type="text" class="form-control @error('user_id') is-invalid @enderror"
                                    name="user_id" id="user_id" value="{{ $cashierProduct->user->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="product_id">Data Produk</label>
                                <input type="text" class="form-control @error('product_id') is-invalid @enderror"
                                    name="product_id" id="product_id" value="{{ $cashierProduct->product->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="flavor_id">Varian Rasa</label>
                                <input type="text" class="form-control @error('flavor_id') is-invalid @enderror"
                                    name="flavor_id" id="flavor_id" value="{{ $flavor->flavor_name }}" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="stock">Jumlah Produk</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    name="stock" id="stock" value="{{ old('stock', $cashierProduct->stock) }}">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="submit-btn"
                            class="d-sm-inline-block btn btn-sm btn-success shadow-sm">Save</button>
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
            // Load flavors based on selected product
            $('#product_id').on('change', function() {
                var productId = $(this).val();
                var selectedFlavorId = $('#selected_flavor_id').val();

                if (productId) {
                    $.ajax({
                        url: '/admin/products/' + productId + '/flavors',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#flavor_id').empty();
                            $('#flavor_id').append(
                                '<option value="">Pilih Varian Rasa</option>');

                            $.each(data, function(key, flavor) {
                                $('#flavor_id').append(
                                    '<option value="' + flavor.id + '"' +
                                    (flavor.id == selectedFlavorId ? ' selected' :
                                        '') + '>' +
                                    flavor.flavor_name + '</option>'
                                );
                            });
                        }
                    });
                } else {
                    $('#flavor_id').empty();
                    $('#flavor_id').append('<option value="">Pilih Varian Rasa</option>');
                }
            });

            // Trigger change event on page load to populate flavors
            $('#product_id').trigger('change');

            // Handle form submission using AJAX
            $('#main-form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                const form = $(this);
                const formData = new FormData(form[0]); // Use FormData to handle file uploads
                const submitButton = $('#submit-btn');
                submitButton.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            sessionStorage.setItem('success',
                                'Kasir produk berhasil disubmit.');
                            window.location.href =
                                "{{ route('admin.cashier-product.index') }}";
                        } else if (response.info) {
                            sessionStorage.setItem('info',
                                'Tidak melakukan perubahan data pada kasir produk.');
                            window.location.href =
                                "{{ route('admin.cashier-product.index') }}";
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
                            input.siblings('.invalid-feedback').text(error);
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Edit');
                    }
                });
            });
        });
    </script>
@endpush
