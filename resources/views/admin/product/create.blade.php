@extends('admin.layouts.master')

@section('title-page')
    Tambah
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Tambah Data Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Data Produk', 'url' => route('admin.product.index')],
            ['title' => 'Tambah'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tambah Data Produk'" :icon="'fas fa-solid fa-plus'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.product.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" name="code"
                                    id="code" value="{{ old('code') }}" placeholder="Masukkan kode produk">
                            </div>

                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" id="name" value="{{ old('name') }}"
                                    placeholder="Masukkan nama produk">
                            </div>

                            <div class="form-group">
                                <label for="description">Deskripsi Produk</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                    rows="4" placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="price_retail">Harga Produk Eceran</label>
                                <input type="number" class="form-control @error('price_retail') is-invalid @enderror"
                                    name="price_retail" id="price_retail" value="{{ old('price_retail') }}"
                                    placeholder="Masukkan harga produk eceran">
                            </div>

                            <div class="form-group">
                                <label for="price_pack">Harga Produk Per Pack</label>
                                <input type="number" class="form-control @error('price_pack') is-invalid @enderror"
                                    name="price_pack" id="price_pack" value="{{ old('price_pack') }}"
                                    placeholder="Masukkan harga produk per pack">
                            </div>

                            <div class="form-group">
                                <label for="items_per_pack">Jumlah Produk Per Pack</label>
                                <input type="number" class="form-control @error('items_per_pack') is-invalid @enderror"
                                    name="items_per_pack" id="items_per_pack" value="{{ old('items_per_pack') }}"
                                    placeholder="Masukkan jumlah produk per pack">
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="photo" class="form-label">Gambar Produk</label>
                                <div class="image-upload-wrapper">
                                    <input class="form-control @error('photo') is-invalid @enderror" type="file"
                                        id="photo" name="photo" onchange="previewImage(event)">
                                    <div class="image-upload-text" id="upload-text">Choose File</div>
                                    <div class="preview-image mt-3">
                                        <img id="preview" src="#" alt="Gambar Produk" style="display: none;">
                                    </div>
                                    <div id="error-message" class="text-danger mt-2" style="display: none;"></div>
                                </div>
                                <div class="text-info mt-2">*File harus berformat JPG, JPEG, PNG</div>
                                <div class="text-info">*File harus berukuran 1000 KB</div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="btn btn-primary mt-3">Tambah</button>
                    <a href="{{ route('admin.product.index') }}" class="btn btn-warning ml-2 mt-3">Kembali</a>
                </form>
            </x-content.card-body>

        </x-content.table-container>
    </x-content.container-fluid>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const submitBtn = $('#submit-btn');
            $('#main-form').on('submit', function(event) {
                event.preventDefault();

                const form = $(this)[0];
                const formData = new FormData(form); // Create FormData object with form data

                // Validasi ukuran dan format file
                const file = $('#photo')[0].files[0];
                const errorMessage = $('#error-message');
                let valid = true;

                if (file) {
                    if (file.size > 1024 * 1024) {
                        errorMessage.text('*File harus berukuran maksimal 1000 KB');
                        errorMessage.show();
                        valid = false;
                    }

                    const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!validImageTypes.includes(file.type)) {
                        errorMessage.text('*File harus berformat JPG, JPEG, PNG');
                        errorMessage.show();
                        valid = false;
                    }
                }

                if (!valid) {
                    return;
                }

                submitBtn.prop('disabled', true).text('Loading...');

                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting the content type
                    success: function(response) {
                        if (response.success) {
                            sessionStorage.setItem('success', 'Data produk berhasil disubmit.');
                            window.location.href =
                                "{{ route('admin.product.index') }}"; // Redirect to index page
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

                            if (field === 'photo') {
                                $('#upload-text')
                                    .hide(); // Hide "Choose File" text if there is an error
                            }
                        }

                        const message = response.responseJSON.message ||
                            'Terdapat kesalahan pada proses data produk';
                        $('#flash-messages').html('<div class="alert alert-danger">' + message +
                            '</div>');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Tambah');
                    }
                });
            });

            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').text('');
            });
        });

        function previewImage(event) {
            let reader = new FileReader();
            let output = document.getElementById('preview');
            let uploadText = document.getElementById('upload-text');

            reader.onload = function() {
                output.src = reader.result;
                output.style.display = 'block';
                uploadText.style.display = 'none'; // Sembunyikan teks "Choose File"
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
