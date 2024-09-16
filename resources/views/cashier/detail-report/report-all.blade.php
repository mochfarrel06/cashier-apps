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

    <h2>Laporan Transaksi Harian - {{ \Carbon\Carbon::today()->format('d-m-Y') }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Jumlah Bayar</th>
                <th>Kembalian</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_number }}</td>
                    <td>{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                    <td>{{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
