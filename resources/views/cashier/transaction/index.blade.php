@extends('cashier.layouts.master')

@section('title-page')
    Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Transaksi Kasir'" :breadcrumbs="[['title' => 'Beranda', 'url' => route('cashier.dashboard.index')], ['title' => 'Kasir']]" />

        <x-content.table-container>
            <x-content.table-header :title="'Transaksi Kasir'" :icon="'fas fa-solid fa-shop'" />

            <div class="card-body">
                <form action="{{ route('cashier.transaction.addToCart') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="cashier_product_id">Produk Tersedia</label>
                                <select class="form-control" name="cashier_product_id" id="cashier_product_id" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($cashierProducts as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}"
                                            data-flavor="{{ $product->flavor->flavor_name }}">{{ $product->product->name }}
                                            -
                                            {{ $product->flavor->flavor_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Stok Tersedia</label>
                                <input type="number" id="stock" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Varian Rasa</label>
                                <input type="text" class="form-control" id="flavor" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="purchase_type">Jenis Pembelian</label>
                                <select class="form-control" name="purchase_type" id="purchase_type" required>
                                    <option value="">-- Jenis Pembelian --</option>
                                    <option value="retail">Retail/Eceran</option>
                                    <option value="pack">Pack/Box</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="quantity">Jumlah</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    name="quantity" id="quantity" placeholder="Masukkan jumlah pembelian" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm mt-3">Tambah ke
                        Keranjang</button>
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
                                <x-content.thead :items="[
                                    'Nama produk',
                                    'Varian',
                                    'Jumlah',
                                    'Jenis Pembelian',
                                    'Harga',
                                    'Total',
                                    'Aksi',
                                ]" />

                                <tbody>
                                    @php $total = 0; @endphp
                                    @if (session('cart'))
                                        @foreach (session('cart') as $index => $item)
                                            <tr>
                                                <td>{{ $item['product_name'] }}</td>
                                                <td>{{ $item['flavor_name'] }}</td>
                                                <td>{{ $item['quantity'] }}</td>
                                                <td>{{ ucfirst($item['purchase_type']) }}</td>
                                                <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item['total'] ?? 0, 0, ',', '.') }}</td>
                                                <td>
                                                    <form
                                                        action="{{ route('cashier.transaction.removeFromCart', $index) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="d-sm-inline-block btn btn-sm btn-danger shadow-sm">x</button>
                                                    </form>
                                                </td>
                                                @php $total += $item['total']; @endphp
                                            </tr>
                                        @endforeach
                                    @endif
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
                        <form action="{{ route('cashier.transaction.checkout') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="hidden" name="total" id="total" value="{{ $total ?? 0 }}">
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($total ?? 0, 0, ',', '.') }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="discount">Diskon Produk (Rp)</label>
                                <input type="text" class="form-control @error('discount') is-invalid @enderror"
                                    name="discount" id="discount" placeholder="Masukkan Diskon">
                            </div>


                            <div class="form-group">
                                <label for="net_total">Total Pembayaran</label>
                                <input type="hidden" class="form-control @error('net_total') is-invalid @enderror"
                                    name="net_total" id="net_total" value="{{ $total ?? 0 }}" readonly>
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($total ?? 0, 0, ',', '.') }}" readonly>
                                <input type="hidden" id="net_total_raw" value="{{ $total ?? 0 }}">
                            </div>

                            <div class="form-group">
                                <label for="payment_type">Metode Pembayaran</label>
                                <select class="form-control" name="payment_type">
                                    <option value="tunai">Tunai</option>
                                    <option value="nontunai">Non Tunai</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="paid_amount">Bayar</label>
                                <input type="text" class="form-control" name="paid_amount" id="paid_amount"
                                    placeholder="Masukkan jumlah bayar" required>
                            </div>

                            <div class="form-group">
                                <label for="change_amount">Kembalian</label>
                                <input type="text" name="change_amount" id="change_amount" class="form-control"
                                    readonly>
                            </div>

                            <button type="submit"
                                class="d-sm-inline-block btn btn-sm btn-success shadow-sm mt-3">Selesaikan
                                Transaksi</button>
                        </form>
                    </x-content.card-body>
                </x-content.table-container>
            </div>
        </div>

    </x-content.container-fluid>
@endsection

@push('scripts')
    <script>
        document.getElementById('cashier_product_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('stock').value = selectedOption.getAttribute('data-stock');
            document.getElementById('flavor').value = selectedOption.getAttribute('data-flavor');
        });
    </script>

    <script>
        document.getElementById('discount').addEventListener('input', function() {
            // Menghilangkan karakter yang tidak diinginkan dan mengkonversi ke angka
            const input = this.value.replace(/[^\d]/g, '');
            const discountValue = parseFloat(input) || 0;

            // Format nilai diskon ke dalam Rupiah
            const formattedDiscount = `Rp ${discountValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
            this.value = formattedDiscount; // Menampilkan diskon yang diformat ke dalam input

            const totalInput = document.getElementById('total').value;
            let netTotal = totalInput;

            // Jika diskon lebih besar dari 0, hitung diskon
            if (discountValue > 0) {
                netTotal = totalInput - discountValue;
            }

            // Set nilai net_total_raw untuk penggunaan lebih lanjut
            document.getElementById('net_total_raw').value = netTotal;

            // Format nilai netTotal ke dalam rupiah dan tampilkan di input net_total
            const formattedNetTotal = `Rp ${netTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
            document.getElementById('net_total').value = netTotal;
            document.getElementById('net_total').nextElementSibling.value = formattedNetTotal;


            // Perbarui nilai kembalian ketika diskon berubah
            updateChangeAmount();
        });

        // Fungsi untuk menghitung dan memperbarui kembalian
        function updateChangeAmount() {
            // Menghilangkan karakter yang tidak diinginkan dan mengkonversi ke angka
            const input = this.value.replace(/[^\d]/g, '');
            const paidValue = parseFloat(input) || 0;

            // Format nilai diskon ke dalam Rupiah
            const formattedPaid = `Rp ${paidValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
            this.value = formattedPaid; // Menampilkan diskon yang diformat ke dalam input

            const netTotal = parseFloat(document.getElementById('net_total_raw').value);
            // const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
            const changeAmountInput = document.getElementById('change_amount');

            const changeAmount = paidValue - netTotal;
            const formattedChange = `Rp ${changeAmount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}`;
            document.getElementById('change_amount').value = changeAmount >= 0 ? formattedChange : "Rp 0";
        }

        // Event listener untuk input paid_amount
        document.getElementById('paid_amount').addEventListener('input', updateChangeAmount);
    </script>
@endpush
