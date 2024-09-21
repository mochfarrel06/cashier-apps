@extends('admin.layouts.master')

@section('title-page')
    Tambah
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Tambah Data Pengguna'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Pengguna', 'url' => route('admin.user-management.index')],
            ['title' => 'Tambah'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tambah Data Pengguna'" :icon="'fas fa-solid fa-plus'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.user-management.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Masukkan nama" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control"
                                    placeholder="Masukkan username" value="{{ old('username') }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Masukkan email" value="{{ old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="transaction_code">Nomor Transaksi</label>
                                <input type="text" name="transaction_code" id="transaction_code" class="form-control"
                                    placeholder="Masukkan nomor transaksi" value="{{ old('transaction_code') }}">
                            </div>
                            <div class="form-group">
                                <label for="location">Lokasi kasir</label>
                                <input type="text" name="location" id="location" class="form-control"
                                    placeholder="Masukkan lokasi kasir" value="{{ old('location') }}">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="cashier">Kasir</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" placeholder="Masukkan password"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Masukkan konfirmasi password" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="avatar" class="form-label">Gambar Pengguna</label>
                                <div class="image-upload-wrapper">
                                    <input class="form-control @error('item_code') is-invalid @enderror" type="file"
                                        id="avatar" name="avatar" onchange="previewImage(event)">
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

                    <button type="submit" id="submit-btn"
                        class="d-sm-inline-block btn btn-sm btn-primary mt-3">Tambah</button>
                    <a href="{{ route('admin.user-management.index') }}"
                        class="d-sm-inline-block btn btn-sm btn-warning mt-3 ml-2">Kembali</a>
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
                const file = $('#avatar')[0].files[0];
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
                            sessionStorage.setItem('success', 'Pengguna berhasil disubmit.');
                            window.location.href =
                                "{{ route('admin.user-management.index') }}"; // Redirect to index page
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

                            if (field === 'avatar') {
                                $('#upload-text')
                                    .hide(); // Hide "Choose File" text if there is an error
                            }
                        }

                        const message = response.responseJSON.message ||
                            'Terdapat kesalahan pada proses Pengguna';
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
