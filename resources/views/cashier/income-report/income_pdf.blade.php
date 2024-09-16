<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h2>Pendapatan Harian Kasir - {{ \Carbon\Carbon::today()->format('d-m-Y') }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Jumlah Bayar</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_number }}</td>
                    <td>{{ number_format($transaction->total, 2) }}</td>
                    <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Total Pendapatan: Rp {{ number_format($totalIncome, 2) }}</h4>
</body>

</html>
