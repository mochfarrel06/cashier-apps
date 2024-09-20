@extends('admin.layouts.master')

@section('title-page')
    Profile
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Profil'" />

        <div class="row">
            <div class="col-lg-4 mb-4">
                <!-- Gambar Produk -->
                <div class="card shadow">
                    <div class="card-body text-center">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset(auth()->user()->avatar) }}" alt="Location Image" class="img-fluid rounded" />
                        @else
                            <p>Tidak ada gambar yang tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-4">
                <x-content.table-container>

                    <x-content.table-header :title="'Informasi Profil'" :icon="'fas fa-solid fa-circle-info'" />

                    <x-content.card-body>
                        <form>
                            @csrf

                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->email }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled>
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->role }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="transaction_code">nomor transaksi</label>
                                <input type="text" name="transaction_code" id="transaction_code" class="form-control"
                                    value="{{ auth()->user()->transaction_code }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="location">Lokasi</label>
                                <input type="text" name="location" id="location" class="form-control"
                                    value="{{ auth()->user()->location }}" disabled>
                            </div>

                            <a href="{{ route('admin.profile.editProfile') }}" class="btn btn-success mt-3 mr-2">Edit
                                Profil</a>
                            <a href="{{ route('admin.profile.editPassword') }}" class="btn btn-warning mt-3">Ganti
                                Password</a>
                        </form>
                    </x-content.card-body>

                </x-content.table-container>
            </div>
        </div>
    </x-content.container-fluid>
@endsection
