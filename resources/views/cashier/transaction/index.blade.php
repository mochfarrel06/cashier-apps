@extends('cashier.layouts.master')

@section('title-page')
    Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Kasir'" />

        <x-content.table-container>
            <x-content.table-header :title="'Tambah Produk'" :icon="'fas fa-solid fa-shop'" />

            <div class="card-body">
                <form id="addProductForm">
                    @csrf

                    <!-- Tanggal Transaksi -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="transaction_date">Tanggal Transaksi</label>
                                <input type="text" class="form-control" id="transaction_date_display" disabled>
                                <input type="hidden" name="transaction_date" id="transaction_date">
                            </div>
                        </div>

                        <div class="col-lg-6">
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
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="stock">Stok Produk</label>
                                <input type="number" class="form-control" name="stock" id="stock" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="purchase_type">Jenis Pembelian</label>
                                <select name="purchase_type" id="purchase_type" class="form-control">
                                    <option value="">-- Pilih Jenis Pembelian --</option>
                                    <option value="retail">Eceran</option>
                                    <option value="pack">Pack</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="price">Harga</label>
                                <input type="text" class="form-control" name="price" id="price" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="quantity">Jumlah Pembelian</label>
                                <input type="number" class="form-control" name="quantity" id="quantity"
                                    placeholder="Masukkan jumlah pembelian">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="total">Total Harga</label>
                                <input type="text" class="form-control" name="total" id="total" disabled>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="addProductBtn" class="btn btn-primary">Tambah Produk</button>
                </form>
            </div>
        </x-content.table-container>

        <div class="row">

            <div class="col-lg-8">
                <x-content.table-container>
                    <x-content.table-header :title="'Keranjang Belanja'" :icon="'fas fa-shopping-cart'" />

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <x-content.thead :items="['Nama Produk', 'Varian', 'Jenis Beli', 'Qty', 'Harga', 'Subtotal', 'Aksi']" />

                                <tbody id="productTableBody">
                                    <!-- Baris akan ditambahkan secara dinamis -->
                                </tbody>

                            </table>
                        </div>
                    </div>
                </x-content.table-container>
            </div>

            <div class="col-lg-4">
                <x-content.table-container>
                    <x-content.table-header :title="'Pembayaran'" :icon="'fas fa-money-bill'" />

                    <x-content.card-body>
                        <form id="payment-form" action="" method="POST">
                            @csrf

                            {{-- <div class="form-group">
                                <label for="total_price">Total Harga</label>
                                <input type="text" class="form-control" id="total_price" name="total_price"
                                    value="{{ number_format($totalPrice, 2) }}" readonly>
                            </div> --}}

                            <div class="form-group">
                                <label for="payment_amount">Bayar</label>
                                <input type="number" id="payment_amount" name="payment_amount" class="form-control"
                                    placeholder="Masukkan jumlah pembayaran">
                            </div>

                            <div class="form-group">
                                <label for="change_amount">Kembalian</label>
                                <input type="number" id="change_amount" name="change_amount" class="form-control"
                                    value="0" readonly>
                            </div>

                            <button type="submit" class="btn btn-success">Selesaikan Transaksi</button>
                        </form>
                    </x-content.card-body>
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

            function addProductToCart() {
                const productSelect = document.getElementById('cart_product_id');
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const productId = selectedOption.value;
                const productName = selectedOption.text;
                const stock = document.getElementById('stock').value;
                const purchaseType = document.getElementById('purchase_type').value;
                const price = document.getElementById('price').value;
                const quantity = document.getElementById('quantity').value;
                const total = document.getElementById('total').value;

                // Inisialisasi variabel untuk total harga transaksi
                let totalPriceTransaction = 0;

                // Fungsi untuk menghitung ulang total harga
                function updateTotalPriceTransaction() {
                    document.getElementById('total_price').value = formatRupiah(totalPriceTransaction);
                }

                // Validasi jika produk sudah ada di keranjang
                if (document.querySelector(`#productTableBody tr[data-product-id="${productId}"]`)) {
                    alert('Produk sudah ada di keranjang.');
                    return;
                }

                // Tambahkan produk ke tabel daftar produk
                const row = `<tr data-product-id="${productId}">
                            <td>${productName}</td>
                            <td>${stock}</td>
                            <td>${purchaseType}</td>
                            <td>${price}</td>
                            <td>${quantity}</td>
                            <td>${quantity}</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-btn">Hapus</button></td>
                         </tr>`;
                document.getElementById('productTableBody').insertAdjacentHTML('beforeend', row);

                // Update total transaksi
                totalPriceTransaction += parseInt(total.replace(/\D/g, ''));
                updateTotalPriceTransaction();

                // Reset form setelah produk ditambahkan
                document.getElementById('addProductForm').reset();
                document.getElementById('price').value = '';
                document.getElementById('total').value = '';
            }

            document.getElementById('addProductBtn').addEventListener('click', addProductToCart);

            // Event listener untuk update stok dan harga saat produk dipilih
            cartProductSelect.addEventListener('change', updateStockAndPrice);

            // Event listener untuk update harga saat jenis pembelian dipilih
            purchaseTypeSelect.addEventListener('change', updateStockAndPrice);

            // Event listener untuk menghitung total harga saat jumlah pembelian diinput
            quantityInput.addEventListener('input', calculateTotal);
        });
    </script>
@endpush
