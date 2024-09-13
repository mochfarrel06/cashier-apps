@extends('admin.layouts.master')

@section('title-page')
    Edit
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Edit Data Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Data Produk', 'url' => route('admin.product.index')],
            ['title' => 'Edit'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Edit Data Produk'" :icon="'fas fa-solid fa-edit'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.product.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                                    id="code" value="{{ old('code', $product->code) }}">
                            </div>

                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{ old('name', $product->name) }}">
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Produk</label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    name="description" id="description"
                                    value="{{ old('description', $product->description) }}">
                            </div>

                            <div class="form-group">
                                <label for="price_retail">Harga Produk Eceran</label>
                                <input type="number" class="form-control @error('price_retail') is-invalid @enderror"
                                    name="price_retail" id="price_retail"
                                    value="{{ old('price_retail', $product->price_retail) }}">
                            </div>

                            <div class="form-group">
                                <label for="price_pack">Harga Produk Per Pack</label>
                                <input type="number" class="form-control @error('price_pack') is-invalid @enderror"
                                    name="price_pack" id="price_pack" value="{{ old('price_pack', $product->price_pack) }}">
                            </div>

                            <div class="form-group">
                                <label for="items_per_pack">Jumlah Produk Per Pack</label>
                                <input type="number" class="form-control @error('items_per_pack') is-invalid @enderror"
                                    name="items_per_pack" id="items_per_pack"
                                    value="{{ old('items_per_pack', $product->items_per_pack) }}">
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="photo" class="form-label">Gambar Produk</label>
                                <div class="image-upload-wrapper">
                                    <input class="form-control" type="file" id="photo" name="photo"
                                        onchange="previewImage(event)">
                                    <div class="preview-image mt-3">
                                        <img id="preview" src="{{ $product->photo ? asset($product->photo) : '#' }}"
                                            alt="Gambar Produk"
                                            style="{{ $product->photo ? 'display: block;' : 'display: none;' }}">
                                    </div>
                                    <div class="image-upload-text" id="upload-text"
                                        style="{{ $product->photo ? 'display: none;' : 'display: block;' }}">
                                        Choose File
                                    </div>
                                    <div id="error-message" class="text-danger mt-2" style="display: none;"></div>
                                </div>
                                <div class="text-info mt-2">*File harus berformat JPG, JPEG, PNG</div>
                                <div class="text-info">*File harus berukuran 1000 KB</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="submit-btn" class="btn btn-success">Edit</button>
                        <a href="{{ route('admin.product.index') }}" class="btn btn-warning ml-2">Kembali</a>
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
                            'Data produk berhasil disubmit.');
                        window.location.href =
                            "{{ route('admin.product.index') }}"; // Redirect to index page
                    } else if (response.info) {
                        // Flash message info
                        sessionStorage.setItem('info',
                            'Tidak melakukan perubahan data pada produk.');
                        window.location.href =
                            "{{ route('admin.product.index') }}"; // Redirect to index page
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

                        if (field === 'photo') {
                            $('#upload-text')
                                .hide(); // Hide "Choose File" text if there is an error
                        }
                    }

                    const message = response.responseJSON.message ||
                        'Terdapat kesalahan pada jenis Produk.';
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

        function previewImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            let output = document.getElementById('preview');
            let uploadText = document.getElementById('upload-text');
            let errorMessage = document.getElementById('error-message');

            reader.onload = function() {
                output.src = reader.result;
                output.style.display = 'block';
                uploadText.style.display = 'none'; // Hide the "Choose File" text
            };

            reader.readAsDataURL(file);
        }
    </script>
@endpush
