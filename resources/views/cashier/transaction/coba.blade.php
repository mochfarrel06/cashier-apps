@extends('cashier.layouts.master')

@section('title-page')
    Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Kasir'" />

        <div class="row">

            <div class="col-lg-6 mb-4">
                <x-content.table-container>
                    <x-content.table-header :title="'Tambah Produk'" :icon="'fas fa-solid fa-shop'" />

                    <div class="card-body">
                        <form id="addProductForm">
                            @csrf

                            <!-- Tanggal Transaksi -->
                            <div class="form-group">
                                <label for="transaction_date">Tanggal Transaksi</label>
                                <input type="text" class="form-control" id="transaction_date_display" disabled>
                                <input type="hidden" name="transaction_date" id="transaction_date">
                            </div>

                            <!-- Pilih Produk -->
                            <div class="form-group">
                                <label for="cart_product_id">Produk (Nama Produk - Rasa Produk)</label>
                                <select name="cart_product_id" id="cart_product_id" class="form-control">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($cartProducts as $cartProduct)
                                        <option value="{{ $cartProduct->id }}" data-stock="{{ $cartProduct->stock }}"
                                            data-price-retail="{{ $cartProduct->product->price_retail }}"
                                            data-price-pack="{{ $cartProduct->product->price_pack }}">
                                            {{ $cartProduct->product->name }} - {{ $cartProduct->flavor->flavor_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Stok Produk -->
                            <div class="form-group">
                                <label for="stock">Stok Produk</label>
                                <input type="number" class="form-control" name="stock" id="stock" disabled>
                            </div>

                            <!-- Jenis Pembelian -->
                            <div class="form-group">
                                <label for="purchase_type">Jenis Pembelian</label>
                                <select name="purchase_type" id="purchase_type" class="form-control">
                                    <option value="">-- Pilih Jenis Pembelian --</option>
                                    <option value="retail">Eceran</option>
                                    <option value="pack">Pack</option>
                                </select>
                            </div>

                            <!-- Harga -->
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="text" class="form-control" name="price" id="price" disabled>
                            </div>

                            <!-- Jumlah Pembelian -->
                            <div class="form-group">
                                <label for="quantity">Jumlah Pembelian</label>
                                <input type="number" class="form-control" name="quantity" id="quantity"
                                    placeholder="Masukkan jumlah pembelian">
                            </div>

                            <!-- Total Harga -->
                            <div class="form-group">
                                <label for="total">Total Harga</label>
                                <input type="text" class="form-control" name="total" id="total" disabled>
                            </div>

                            <button type="button" id="addProductBtn" class="btn btn-primary">Tambah Produk</button>
                        </form>
                    </div>
                </x-content.table-container>
            </div>

            <!-- Kolom Kanan: Daftar Produk dan Selesaikan Transaksi -->
            <div class="col-lg-6 mb-4">
                <x-content.table-container>
                    <x-content.table-header :title="'Daftar Produk'" :icon="'fas fa-solid fa-eye'" />

                    <div class="card-body">
                        <form id="transactionForm" action="{{ route('cashier.transaction.store') }}" method="POST">
                            @csrf

                            <!-- Tabel Daftar Produk -->
                            <table class="table table-striped" id="productTable">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Jenis Pembelian</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="productTableBody">
                                    <!-- Baris akan ditambahkan secara dinamis -->
                                </tbody>
                            </table>

                            <!-- Jenis Pembayaran -->
                            <div class="form-group">
                                <label for="payment_type">Jenis Pembayaran</label>
                                <select name="payment_type" id="payment_type" class="form-control">
                                    <option value="">-- Pilih Jenis Pembayaran --</option>
                                    <option value="tunai">Tunai</option>
                                    <option value="nontunai">Non Tunai</option>
                                </select>
                            </div>

                            <!-- Total Harga -->
                            <div class="form-group">
                                <label for="total">Total Harga</label>
                                <input type="text" class="form-control" name="total" id="total" disabled>
                            </div>

                            <button type="submit" id="submit-btn" class="btn btn-success">Selesaikan Transaksi</button>
                        </form>
                    </div>
                </x-content.table-container>
            </div>

        </div>

    </x-content.container-fluid>
@endsection

@push('scripts')
    <script>
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Month starts from 0
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;

        // Set the display and hidden input values
        document.getElementById('transaction_date_display').value = formattedDate; // For display
        document.getElementById('transaction_date').value = formattedDate; // For database
    </script>

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
                                "{{ route('cashier.transaction.index') }}"; // Redirect to index page
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartProductSelect = document.getElementById('cart_product_id');
            const stockInput = document.getElementById('stock');
            const purchaseTypeSelect = document.getElementById('purchase_type');
            const priceInput = document.getElementById('price');
            const quantityInput = document.getElementById('quantity');
            const totalInput = document.getElementById('total');

            let currentPrice = 0;

            // Fungsi untuk memformat angka ke format Rupiah
            function formatRupiah(number) {
                return 'Rp. ' + parseInt(number).toLocaleString('id-ID');
            }

            // Fungsi untuk menghitung total harga
            function calculateTotal() {
                const quantity = parseInt(quantityInput.value) || 0;
                const total = currentPrice * quantity;
                totalInput.value = formatRupiah(total); // Menggunakan format Rupiah
            }

            function updateStockAndPrice() {
                const selectedOption = cartProductSelect.options[cartProductSelect.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');
                const priceRetail = selectedOption.getAttribute('data-price-retail');
                const pricePack = selectedOption.getAttribute('data-price-pack');
                const purchaseType = purchaseTypeSelect.value;

                // Update stok
                stockInput.value = stock || '';

                // Update harga berdasarkan jenis pembelian
                if (purchaseType === 'retail') {
                    currentPrice = parseFloat(priceRetail) || 0;
                    priceInput.value = formatRupiah(priceRetail);
                } else if (purchaseType === 'pack') {
                    currentPrice = parseFloat(pricePack) || 0;
                    priceInput.value = formatRupiah(pricePack);
                } else {
                    currentPrice = 0;
                    priceInput.value = ''; // Kosongkan jika tidak ada jenis pembelian yang dipilih
                }

                calculateTotal(); // Menghitung total harga setelah harga diperbarui
            }

            // Event listener untuk update stok dan harga saat produk dipilih
            cartProductSelect.addEventListener('change', updateStockAndPrice);

            // Event listener untuk update harga saat jenis pembelian dipilih
            purchaseTypeSelect.addEventListener('change', updateStockAndPrice);

            // Event listener untuk menghitung total harga saat jumlah pembelian diinput
            quantityInput.addEventListener('input', calculateTotal);
        });
    </script>
@endpush
