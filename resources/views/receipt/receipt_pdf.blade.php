<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 25px;
        }

        .header p {
            margin: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Via Vio</h1>
        <p>{{ $transaction->user->name }}</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <p><strong>Nomor Transaksi:</strong> {{ $transaction->transaction_number }}</p>
    <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}
    </p>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Varian Produk</th>
                <th>Jenis Pembelian</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->transactionDetails as $detail)
                <tr>
                    <td>{{ $detail->cashierProduct->product->name }}</td>
                    <td>{{ $detail->cashierProduct->flavor->flavor_name }}</td>
                    <td>{{ ucfirst($detail->purchase_type) }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <!-- Row for Total, Bayar, and Kembalian -->
            <tr>
                <th colspan="5">Total</th>
                <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="5">Bayar</th>
                <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th colspan="5">Kembalian</th>
                <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Terima kasih telah berbelanja di Via Vio!</p>
    </div>

</body>

</html>
