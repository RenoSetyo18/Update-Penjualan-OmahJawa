<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;

class KasirController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'kasir']);
    }

    private function getImageBase64($imagePath)
    {
        $fullPath = public_path($imagePath);
        if (file_exists($fullPath)) {
            $imageData = file_get_contents($fullPath);
            $base64 = base64_encode($imageData);
            $mime = function_exists('mime_content_type') ? mime_content_type($fullPath) : null;
            if (!$mime) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $fullPath);
                finfo_close($finfo);
            }
            return "data:{$mime};base64,{$base64}";
        }
        return null;
    }
    public function index()
    {
        // Ambil semua transaksi beserta relasi detail dan menu
        $transaksis = Transaksi::with('details.menu')->orderByDesc('created_at')->get();

        // Kelompokkan transaksi berdasarkan nomor meja yang belum lunas
        $groupedTransaksis = collect();
        $processedMejas = [];

        foreach ($transaksis as $transaksi) {
            // Jika status sudah lunas atau Pesanan Online / ORD- (sudah bayar di awal), tampilkan sebagai data terpisah
            if ($transaksi->isAlreadyPaidOnline()) {
                $groupedTransaksis->push($transaksi);
                continue;
            }

            // Jika nomor meja belum diproses dan status belum lunas, kelompokkan
            if (!in_array($transaksi->nomeja, $processedMejas)) {
                $processedMejas[] = $transaksi->nomeja;

                // Cari semua transaksi dengan nomor meja yang sama dan belum lunas, kecuali order online
                $relatedTransaksis = $this->getKasirGroupTransaksis($transaksi, $transaksis);

                if ($relatedTransaksis->count() > 1) {
                    // Gabungkan transaksi dengan nomor meja yang sama
                    $combinedTransaksi = $this->combineTransaksis($relatedTransaksis);
                    $groupedTransaksis->push($combinedTransaksi);
                } else {
                    // Jika hanya satu transaksi, tampilkan seperti biasa
                    $groupedTransaksis->push($transaksi);
                }
            }
        }

        return view('pages.kasir.index', compact('groupedTransaksis'));
    }

    private function combineTransaksis($transaksis)
    {
        // Ambil transaksi pertama sebagai base
        $baseTransaksi = $transaksis->first();

        // Gabungkan semua detail dari transaksi yang sama nomor mejanya
        $combinedDetails = collect();
        $totalHarga = 0;
        $latestCreatedAt = $baseTransaksi->created_at;

        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->details as $detail) {
                // Cek apakah menu sudah ada di combined details
                $existingDetail = $combinedDetails->first(function ($item) use ($detail) {
                    return $item->menu_id === $detail->menu_id;
                });

                if ($existingDetail) {
                    // Jika menu sudah ada, tambahkan jumlah dan subtotalnya
                    $existingDetail->jumlah += $detail->jumlah;
                    $existingDetail->subtotal += $detail->subtotal;

                    // Pertahankan catatan jika ada
                    $detailNote = trim($detail->note ?? '');
                    if ($detailNote === '-') {
                        $detailNote = '';
                    }
                    $existingNote = trim($existingDetail->note ?? '');
                    if ($existingNote === '-') {
                        $existingNote = '';
                    }
                    if ($detailNote && !$existingNote) {
                        $existingDetail->note = $detailNote;
                    } elseif ($detailNote && $existingNote && $existingNote !== $detailNote) {
                        $notes = array_filter(array_unique(array_map('trim', explode(';', $existingNote . ';' . $detailNote))));
                        $existingDetail->note = implode('; ', $notes);
                    }
                } else {
                    // Jika menu belum ada, tambahkan sebagai detail baru
                    $combinedDetails->push($detail);
                }
            }

            $totalHarga += $transaksi->totalharga;

            // Ambil created_at yang paling baru
            if ($transaksi->created_at > $latestCreatedAt) {
                $latestCreatedAt = $transaksi->created_at;
            }
        }

        // Buat objek transaksi gabungan
        $combinedTransaksi = new Transaksi();
        $combinedTransaksi->id = $baseTransaksi->id; // Gunakan ID transaksi pertama
        $combinedTransaksi->kode = $baseTransaksi->kode;
        $combinedTransaksi->user_id = $baseTransaksi->user_id;
        $combinedTransaksi->nomeja = $baseTransaksi->nomeja;
        $combinedTransaksi->atasnama = $baseTransaksi->atasnama;
        $combinedTransaksi->totalharga = $totalHarga;
        $combinedTransaksi->status = $baseTransaksi->status; // Status dari transaksi pertama
        $combinedTransaksi->created_at = $latestCreatedAt;
        $combinedTransaksi->updated_at = $baseTransaksi->updated_at;

        // Set details yang sudah digabungkan
        $combinedTransaksi->setRelation('details', $combinedDetails);

        return $combinedTransaksi;
    }

    public function bayar(Request $request, $id)
    {
        // Validasi input dari form pembayaran
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:Cash,Dana,BCA,BRI,Qris',
        ]);

        // Ambil transaksi terkait
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);

        // Jika sudah lunas atau Pesanan Online / ORD- (sudah bayar di awal), tidak perlu proses lagi
        if ($transaksi->isAlreadyPaidOnline()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Transaksi sudah lunas.'], 422);
            }
            return redirect()->route('kasir.index')->with('info', 'Transaksi sudah lunas.');
        }

        $groupedTransaksis = $this->getReceiptTransaksis($transaksi);

        // Hitung total harga seluruh transaksi dalam grup pembayaran yang sama
        $totalHargaSemua = $groupedTransaksis->sum('totalharga');

        // Cek apakah jumlah bayar cukup untuk seluruh grup yang dibayar
        if ($request->jumlah_bayar < $totalHargaSemua) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Jumlah bayar kurang dari total harga pesanan meja ' . $transaksi->nomeja . '.'], 422);
            }
            return redirect()->back()->with('error', 'Jumlah bayar kurang dari total harga pesanan meja ' . $transaksi->nomeja . '.');
        }

        $paidAt = now();
        $jumlah_bayar = (float) $request->jumlah_bayar;

        // Tandai semua transaksi dalam grup yang sama menjadi lunas
        foreach ($groupedTransaksis as $groupedTransaksi) {
            $groupedTransaksi->update([
                'status' => 'lunas',
                'metode_pembayaran' => $request->metode_pembayaran,
                'paid_at' => $paidAt,
                'jumlah_bayar' => $jumlah_bayar,
            ]);
        }

        $kembalian = $jumlah_bayar - $totalHargaSemua;
        $printUrl = route('kasir.cetak.struk', [
            'id' => $transaksi->id,
            'jumlah_bayar' => $jumlah_bayar,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'meja' => $transaksi->nomeja,
                'atas_nama' => $transaksi->atasnama,
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_harga' => $totalHargaSemua,
                'total_harga_formatted' => number_format($totalHargaSemua, 2, ',', '.'),
                'jumlah_bayar' => $jumlah_bayar,
                'jumlah_bayar_formatted' => number_format($jumlah_bayar, 2, ',', '.'),
                'kembalian' => $kembalian,
                'kembalian_formatted' => number_format($kembalian, 2, ',', '.'),
                'print_url' => $printUrl,
                'message' => 'Pembayaran berhasil untuk meja ' . $transaksi->nomeja . '!',
            ]);
        }

        return redirect()->route('kasir.index')->with('success', 'Pembayaran berhasil untuk meja ' . $transaksi->nomeja . '!');
    }

    protected function resolvePaymentAmount(Request $request, Transaksi $transaksi, float $fallbackAmount): float
    {
        $requestAmount = $request->query('jumlah_bayar');
        if (is_numeric($requestAmount)) {
            return (float) $requestAmount;
        }

        $savedAmount = $transaksi->jumlah_bayar;
        if (is_numeric($savedAmount)) {
            return (float) $savedAmount;
        }

        return $fallbackAmount;
    }

    protected function getKasirGroupTransaksis(Transaksi $transaksi, $transaksis = null)
    {
        $source = $transaksis ?? Transaksi::query()->orderByDesc('created_at')->get();

        return $source->filter(function ($item) use ($transaksi) {
            if ($item->isOnlineOrder()) {
                return false;
            }

            return $item->nomeja === $transaksi->nomeja
                && $item->status !== 'lunas';
        })->values();
    }

    protected function getReceiptTransaksis(Transaksi $transaksi, $transaksis = null)
    {
        if ($transaksi->isOnlineOrder()) {
            return collect([$transaksi]);
        }

        $source = $transaksis ?? Transaksi::with('details.menu')
            ->where('nomeja', $transaksi->nomeja)
            ->orderBy('created_at')
            ->get();

        if ($transaksi->status === 'lunas' && $transaksi->paid_at) {
            $selectedPaidAt = $transaksi->paid_at instanceof \Carbon\CarbonInterface
                ? $transaksi->paid_at
                : \Carbon\Carbon::parse($transaksi->paid_at);

            return $source->filter(function ($item) use ($selectedPaidAt) {
                if ($item->status !== 'lunas' || !$item->paid_at) {
                    return false;
                }

                $itemPaidAt = $item->paid_at instanceof \Carbon\CarbonInterface
                    ? $item->paid_at
                    : \Carbon\Carbon::parse($item->paid_at);

                return $itemPaidAt->equalTo($selectedPaidAt);
            })->values();
        }

        $grouped = $this->getKasirGroupTransaksis($transaksi, $source);

        return $grouped->isEmpty() ? collect([$transaksi]) : $grouped;
    }

    public function cetakStruk(Request $request, $id)
    {
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);
        $allTransaksis = $this->getReceiptTransaksis($transaksi);

        $combinedDetails = collect();
        $totalHarga = 0;

        foreach ($allTransaksis as $trx) {
            foreach ($trx->details as $detail) {
                $existingDetail = $combinedDetails->first(function ($item) use ($detail) {
                    return $item->menu_id === $detail->menu_id;
                });

                if ($existingDetail) {
                    $existingDetail->jumlah += $detail->jumlah;
                    $existingDetail->subtotal += $detail->subtotal;
                } else {
                    $combinedDetails->push($detail);
                }
            }
            $totalHarga += $trx->totalharga;
        }

        $strukTransaksi = new Transaksi();
        $strukTransaksi->id = $transaksi->id;
        $strukTransaksi->kode = $transaksi->kode;
        $strukTransaksi->nomeja = $transaksi->nomeja;
        $strukTransaksi->atasnama = $transaksi->atasnama;
        $strukTransaksi->totalharga = $totalHarga;
        $strukTransaksi->metode_pembayaran = $transaksi->metode_pembayaran;
        $strukTransaksi->setRelation('details', $combinedDetails);

        $jumlah_bayar = $this->resolvePaymentAmount($request, $transaksi, $totalHarga);

        $kembalian = $jumlah_bayar - $totalHarga;
        $transaksi = $strukTransaksi;

        if ($request->query('download') === 'pdf') {
            if (app()->bound('dompdf.wrapper')) {
                try {
                    $pdf = app('dompdf.wrapper')->loadView('pages.kasir.struk_pdf', compact('transaksi', 'jumlah_bayar', 'kembalian'));
                    return $pdf->download('struk_' . ($transaksi->kode ?? $transaksi->id) . '.pdf');
                } catch (\Exception $e) {
                    return view('pages.kasir.cetak_struk', compact('transaksi', 'jumlah_bayar', 'kembalian'));
                }
            }

            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                try {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.kasir.struk_pdf', compact('transaksi', 'jumlah_bayar', 'kembalian'));
                    return $pdf->download('struk_' . ($transaksi->kode ?? $transaksi->id) . '.pdf');
                } catch (\Exception $e) {
                    return view('pages.kasir.cetak_struk', compact('transaksi', 'jumlah_bayar', 'kembalian'));
                }
            }

            return view('pages.kasir.cetak_struk', compact('transaksi', 'jumlah_bayar', 'kembalian'));
        }

        return view('pages.kasir.cetak_struk', compact('transaksi', 'jumlah_bayar', 'kembalian'));
    }

    public function cetakRekap(Request $request)
    {
        $query = Transaksi::with('details.menu')->orderBy('created_at', 'desc');

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59'
            ]);
        }

        $transaksis = $query->get();
        $logoBase64 = $this->getImageBase64('dataset/dist/img/LogoOmahJawa.png');

        if ($request->query('download') === 'pdf') {
            if (!extension_loaded('gd')) {
                return redirect()->route('kasir.index')->with('error', 'PHP GD extension diperlukan untuk download PDF. Silakan aktifkan ekstensi gd di php.ini.');
            }
            try {
                $fileName = 'rekap_kasir_' . ($request->dari ?? 'semua') . '_sampai_' . ($request->sampai ?? 'semua') . '.pdf';
                $pdf = Pdf::loadView('pages.kasir.pdf_rekap', compact('transaksis', 'request', 'logoBase64'));
                return $pdf->download($fileName);
            } catch (\Exception $e) {
                return view('pages.kasir.cetak_rekap', compact('transaksis'));
            }
        }

        return view('pages.kasir.cetak_rekap', compact('transaksis', 'request'));
    }

}
