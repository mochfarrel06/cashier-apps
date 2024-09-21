@extends('admin.layouts.master')

@section('title-page')
    Edit Profil
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Edit Profil'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Profil', 'url' => route('admin.profile.index')],
            ['title' => 'Edit Profil'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Edit Profil'" :icon="'fas fa-solid fa-user'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.profile.updateProfile') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label for="avatar" class="form-label">Gambar Pengguna</label>
                                <div class="image-upload-wrapper">
                                    <input class="form-control" type="file" id="avatar" name="avatar"
                                        onchange="previewImage(event)">

                                    <!-- Tampilkan gambar yang sudah ada -->
                                    <div class="preview-image mt-3">
                                        <img id="preview"
                                            src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : '#' }}"
                                            alt="Gambar Penggguna"
                                            style="{{ auth()->user()->avatar ? 'display: block;' : 'display: none;' }}">
                                    </div>

                                    <div class="image-upload-text" id="upload-text"
                                        style="{{ auth()->user()->avatar ? 'display: none;' : 'display: block;' }}">
                                        Choose File
                                    </div>
                                    <div id="error-message" class="text-danger mt-2" style="display: none;"></div>
                                </div>
                                <div class="text-info mt-2">*File harus berformat JPG, JPEG, PNG</div>
                                <div class="text-info">*File harus berukuran 1000 KB</div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="form-group">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ auth()->user()->name }}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ auth()->user()->email }}">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control"
                                    value="{{ auth()->user()->username }}">
                            </div>

                            <div class="form-group">
                                <label for="transaction_code">nomor transaksi</label>
                                <input type="text" name="transaction_code" id="transaction_code" class="form-control"
                                    value="{{ auth()->user()->transaction_code }}">
                            </div>
                            <div class="form-group">
                                <label for="location">Lokasi kasir</label>
                                <input type="text" name="location" id="location" class="form-control"
                                    value="{{ auth()->user()->location }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="submit-btn"
                            class="d-sm-inline-block btn btn-sm btn-success">Edit</button>
                        <a href="{{ route('admin.profile.index') }}"
                            class="d-sm-inline-block btn btn-sm btn-warning ml-2">Kembali</a>
                    </div>
                </form>
            </x-content.card-body>

        </x-content.table-container>

    </x-content.container-fluid>
@endsection


@push('scripts')
    <script>
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
                            'profil berhasil disubmit.');
                        window.location.href =
                            "{{ route('admin.profile.index') }}"; // Redirect to index page
                    } else if (response.info) {
                        // Flash message info
                        sessionStorage.setItem('info',
                            'Tidak melakukan perubahan data pada profil.');
                        window.location.href =
                            "{{ route('admin.profile.index') }}"; // Redirect to index page
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
                        'Terdapat kesalahan pada profile.';
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
