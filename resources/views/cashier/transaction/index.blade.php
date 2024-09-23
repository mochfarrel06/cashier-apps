@extends('cashier.layouts.master')

@section('title-page')
    Kasir
@endsection

@section('content')
    <x-content.container-fluid>

        <x-content.heading-page :title="'Halaman Transaksi Kasir'" :breadcrumbs="[['title' => 'Beranda', 'url' => route('cashier.dashboard.index')], ['title' => 'Kasir']]" />

        <x-content.table-container>
            <x-content.table-header :title="'Tambah Produk Transaksi'" :icon="'fas fa-solid fa-shop'" />

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
                                <label for="total">Total Pembayaran</label>
                                <input type="text" name="total" id="total" class="form-control"
                                    value="{{ $total }}" readonly>
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
                                <input type="number" class="form-control" name="paid_amount" id="paid_amount" required>
                            </div>

                            <div class="form-group">
                                <label for="change_amount">Kembalian</label>
                                <input type="text" name="change_amount" id="change_amount" class="form-control" readonly>
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
        document.addEventListener('DOMContentLoaded', () => {
            const totalInput = document.getElementById('total');
            const paidAmountInput = document.getElementById('paid_amount');
            const changeAmountInput = document.getElementById('change_amount');

            // Fungsi untuk menghitung dan memperbarui kembalian
            function updateChangeAmount() {
                const total = parseFloat(totalInput.value) || 0;
                const paidAmount = parseFloat(paidAmountInput.value) || 0;
                const changeAmount = paidAmount - total;

                // Update kembalian hanya jika jumlah bayar lebih besar dari total
                changeAmountInput.value = changeAmount >= 0 ? changeAmount.toFixed(2) : '0.00';
            }

            // Tambahkan event listener untuk mengupdate kembalian saat jumlah bayar berubah
            paidAmountInput.addEventListener('input', updateChangeAmount);

            // Tambahkan event listener untuk menghitung kembalian jika total diubah (jika ada elemen yang mengubah total)
            totalInput.addEventListener('input', updateChangeAmount);
        });
    </script>
@endpush
