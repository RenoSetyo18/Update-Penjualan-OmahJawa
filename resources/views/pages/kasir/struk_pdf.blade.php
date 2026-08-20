<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; }
        .center { text-align:center; }
        .right { text-align:right; }
        table { width:100%; border-collapse:collapse; margin-top:8px; }
        td, th { padding:4px 0; }
        .small { font-size:11px; color:#555; }
    </style>
</head>
<body>
    <h2 class="center">Cafe Omah Jawa</h2>
    <p class="center"><strong>Struk Pembayaran</strong></p>

    <p><strong>Nomor Meja:</strong> {{ $transaksi->nomeja }}</p>
    <p><strong>Atas Nama:</strong> {{ $transaksi->atasnama }}</p>
    <p><strong>Tanggal:</strong> {{ date('d-m-Y H:i', strtotime($transaksi->created_at ?? now())) }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $transaksi->metode_pembayaran ?? 'Cash' }}</p>

    <table>
        <thead>
            <tr>
                <th style="text-align:left;">Menu</th>
                <th style="text-align:center;">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $detail)
                <tr>
                    <td>{{ $detail->menu->nama ?? '-' }}</td>
                    <td class="center">{{ $detail->jumlah }}</td>
                    <td class="right">Rp{{ number_format($detail->harga, 2) }}</td>
                    <td class="right">Rp{{ number_format(($detail->harga ?? 0) * ($detail->jumlah ?? 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right">Total Harga</td>
                <td class="right">Rp{{ number_format($transaksi->totalharga ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="right">Jumlah Bayar</td>
                <td class="right">Rp{{ number_format(isset($jumlah_bayar) ? $jumlah_bayar : ($transaksi->totalharga ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="right">Kembalian</td>
                <td class="right">Rp{{ number_format(isset($kembalian) ? $kembalian : ((isset($jumlah_bayar) ? $jumlah_bayar : 0) - ($transaksi->totalharga ?? 0)), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p class="small center">Terima kasih sudah berbelanja di Cafe Omah Jawa!</p>
</body>
</html>
