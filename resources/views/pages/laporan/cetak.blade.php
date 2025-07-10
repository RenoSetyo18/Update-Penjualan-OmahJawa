<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px; /* Make the font size smaller */
        }
        .container {
            width: 100%;
            margin: 0 auto;
            text-align: center; /* Center the content */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ asset('dataset/dist/img/LogoOmahJawa.png') }}" class="img-circle elevation-2" alt="User Image" style="max-width: 15%; height: auto;"> <!-- Corrected image path -->
        <h1>Cetak Laporan Transaksi</h1>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p> <!-- Add print date -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksi->id }}</td>
                    <td>{{ $transaksi->created_at }}</td>
                    <td>{{ $transaksi->totalharga }}</td>
                    <td>{{ $transaksi->status }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2"><strong>Total Pendapatan</strong></td>
                    <td colspan="2"><strong>{{ $transaksis->sum('totalharga') }}</strong></td> <!-- Add total revenue -->
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    </script>
</body>
</html>
