@extends('admin.layouts.master')

@section('title-page')
    Tambah
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Tambah Varian Produk'" :breadcrumbs="[
            ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['title' => 'Varian Produk', 'url' => route('admin.flavor.index')],
            ['title' => 'Tambah'],
        ]" />

        <x-content.table-container>

            <x-content.table-header :title="'Tambah Varian Produk'" :icon="'fas fa-solid fa-plus'" />

            <x-content.card-body>
                <form id="main-form" action="{{ route('admin.flavor.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="product_id">Data Produk</label>
                                <select name="product_id" id="product_id"
                                    class="form-control @error('product_id') is-invalid @enderror">
                                    <option value="">-- Pilih Data Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="flavor_name">Varian Produk</label>
                                <input type="text" class="form-control @error('flavor_name') is-invalid @enderror"
                                    name="flavor_name" id="flavor_name" value="{{ old('flavor_name') }}"
                                    placeholder="Masukkan nama varian produk">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="price_retail">Harga Produk Eceran</label>
                                <input type="number" class="form-control @error('price_retail') is-invalid @enderror"
                                    name="price_retail" id="price_retail" value="{{ old('price_retail') }}"
                                    placeholder="Masukkan harga produk eceran">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="price_pack">Harga Produk Per Pack</label>
                                <input type="number" class="form-control @error('price_pack') is-invalid @enderror"
                                    name="price_pack" id="price_pack" value="{{ old('price_pack') }}"
                                    placeholder="Masukkan harga produk per pack">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="submit-btn"
                            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">Tambah</button>
                        <a href="{{ route('admin.flavor.index') }}"
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
                                'Varian produk berhasil disubmit.');
                            window.location.href =
                                "{{ route('admin.flavor.index') }}"; // Redirect to index page
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
                            'Terdapat kesalahan pada proses varian produk';
                        $('#flash-messages').html('<div class="alert alert-danger">' + message +
                            '</div>');
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
        });
    </script>
@endpush
