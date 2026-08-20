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
        .btn {
            display: inline-block;
            font-family: Arial, sans-serif;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            font-size: 14px;
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }
        .btn-info {
            background: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }
        .btn-success {
            background: #28a745;
            color: #fff;
            border-color: #28a745;
        }
        .btn:hover { opacity: 0.92; }
    </style>
</head>
<body>
    <div class="container">
        <div style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <button type="button" onclick="window.history.back()" style="padding: 8px 14px; border: 1px solid #6c757d; background: #f8f9fa; color: #212529; border-radius: 4px; cursor: pointer;">Kembali</button>
            <button type="button" onclick="window.print()" style="padding: 8px 14px; border: 1px solid #17a2b8; background: #17a2b8; color: #fff; border-radius: 4px; cursor: pointer;">Preview Print</button>
        </div>
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
                    <td>{{ \App\Models\Transaksi::transaksiStatusLabel($transaksi->status) }}</td>
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
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
