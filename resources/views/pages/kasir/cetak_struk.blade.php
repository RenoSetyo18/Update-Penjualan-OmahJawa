<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $transaksi->kode ?? $transaksi->id }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px 12px;
            color: #000;
            font-family: "Courier New", monospace;
            font-size: 12px;
        }

        .receipt {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: 700;
        }

        .separator {
            margin: 8px 0;
            border-top: 1px dashed #000;
        }

        .receipt-header {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .receipt-header .store-name {
            font-size: 18px;
            font-weight: 700;
        }

        .receipt-meta,
        .receipt-total {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin: 4px 0;
        }

        .receipt-meta span:last-child,
        .receipt-total span:last-child {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .receipt-status {
            margin: 10px 0;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
        }

        .receipt-item {
            margin: 8px 0;
        }

        .receipt-item-name {
            overflow-wrap: anywhere;
        }

        .receipt-item-total {
            margin-top: 3px;
        }

        .receipt-footer {
            margin-top: 16px;
            line-height: 1.5;
        }

        @media print {
            @page {
                margin: 4mm;
            }

            body {
                padding: 0;
            }

            .receipt {
                max-width: none;
                width: 72mm;
            }
        }
    </style>
</head>
<body>
    @php
        $isPaid = $transaksi->status === \App\Models\Transaksi::STATUS_LUNAS;
        $jumlahBayar = isset($jumlah_bayar) ? (float) $jumlah_bayar : (float) $transaksi->totalharga;
        $kembalian = isset($kembalian) ? (float) $kembalian : max(0, $jumlahBayar - (float) $transaksi->totalharga);
        $itemCount = $transaksi->details->sum('jumlah');
    @endphp

    <main class="receipt">
        <header class="receipt-header center">
            <div class="store-name">Omah Jawa</div>
            <div>Pusat</div>
            <div>Instagram : warung_omah_jawa</div>
        </header>

        <div class="separator"></div>
        <div class="receipt-meta"><span>Kasir</span><span>Andik Satriadi</span></div>
        <div class="receipt-meta">
            <span>Waktu</span>
            <span>{{ date('d-m-Y H:i', strtotime($isPaid ? ($transaksi->paid_at ?? $transaksi->created_at) : $transaksi->created_at)) }}</span>
        </div>
        <div class="receipt-meta"><span>No. Struk</span><span>{{ $transaksi->kode ?? $transaksi->id }}</span></div>
        @if($isPaid)
            <div class="receipt-meta">
                <span>Bayar</span>
                <span>{{ strtolower($transaksi->metode_pembayaran ?? 'Cash') === 'cash' ? 'Tunai' : $transaksi->metode_pembayaran }}</span>
            </div>
        @endif

        <div class="separator"></div>
        <div class="receipt-status">*** {{ \App\Models\Transaksi::kasirStatusLabel($transaksi->status) }} ***</div>
        <div class="separator"></div>

        @foreach($transaksi->details as $detail)
            <div class="receipt-item">
                <div class="receipt-item-name">{{ $detail->menu->nama ?? 'Menu dihapus' }}</div>
                <div class="receipt-meta receipt-item-total">
                    <span>{{ number_format($detail->harga, 0, ',', '.') }} x {{ $detail->jumlah }}</span>
                    <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="separator"></div>
        <div class="receipt-total"><span>Subtotal</span><span>{{ number_format($transaksi->totalharga - ($transaksi->ppn_amount ?? 0), 0, ',', '.') }}</span></div>
        <div class="receipt-total">
            <span>PPN{{ $transaksi->ppn_rate !== null ? ' (' . number_format($transaksi->ppn_rate, 2, ',', '.') . '%)' : '' }}</span>
            <span>{{ number_format($transaksi->ppn_amount ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="separator"></div>
        <div class="receipt-total bold">
            <span>Total ({{ $itemCount }})</span>
            <span>{{ number_format($transaksi->totalharga, 0, ',', '.') }}</span>
        </div>
        <div class="separator"></div>
        @if($isPaid)
            <div class="receipt-total"><span>Bayar</span><span>{{ number_format($jumlahBayar, 0, ',', '.') }}</span></div>
            <div class="receipt-total"><span>Kembali</span><span>{{ number_format($kembalian, 0, ',', '.') }}</span></div>
        @else
            <div class="receipt-total"><span>Status</span><span>Belum Lunas</span></div>
        @endif

        <footer class="receipt-footer center">
            <div>Panggonan Mewah, Regane Murah,</div>
            <div>Ning ati bungah</div>
            <br>
            <div>Powered by Qasir</div>
            <div>www.qasir.id</div>
        </footer>
    </main>

    @if(request()->boolean('print'))
        <script>
            window.addEventListener('load', function () {
                window.print();
            });
        </script>
    @endif
</body>
</html>
