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
    </style>
</head>

<body>

    <h1>Detail Transaksi</h1>
    <p><strong>Nomor Transaksi:</strong> {{ $transaction->transaction_number }}</p>
    <p><strong>Tanggal:</strong> {{ $transaction->transaction_date }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Rasa</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->transactionDetails as $detail)
                <tr>
                    <td>{{ $detail->cashierProduct->product->name }}</td>
                    <td>{{ $detail->cashierProduct->flavor->flavor_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->quantity * $detail->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> Rp {{ number_format($transaction->total, 0, ',', '.') }}</p>
    <p><strong>Bayar:</strong> Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</p>
    <p><strong>Kembalian:</strong> Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</p>

</body>

</html>
