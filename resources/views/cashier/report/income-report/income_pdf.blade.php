<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pendapatan Harian Kasir</title>
    <style>
        /* Mengatur teks judul utama di tengah */
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        /* Mengatur teks periode dengan ukuran lebih kecil */
        h3 {
            text-align: center;
            font-size: 16px;
            margin-top: 0;
        }

        /* Styling total pendapatan agar terlihat lebih baik */
        h4 {
            text-align: center;
            color: #4CAF50;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Tabel dengan border */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <!-- Judul Utama -->
    <h2>Pendapatan Harian Kasir</h2>

    <!-- Periode -->
    <h3>Periode: {{ \Carbon\Carbon::today()->format('d-m-Y') }}</h3>

    <!-- Total Pendapatan -->
    <h4>Total Pendapatan: Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>

    <!-- Tabel Transaksi -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $transaction->transaction_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
