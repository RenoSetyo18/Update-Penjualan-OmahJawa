<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use App\Models\DetailTransaksi;
use App\Models\Menu;
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
        $groupedTransaksis = Transaksi::with('details.menu')->orderByDesc('created_at')->get();

        return view('pages.kasir.index', compact('groupedTransaksis'));
    }

    public function edit($id)
    {
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);
        $menus = Menu::orderBy('nama')->get();

        return view('pages.kasir.edit', compact('transaksi', 'menus'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $validated = $request->validate([
            'atasnama' => 'required|string|min:1|max:255',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $id, $request) {
            $transaksi = Transaksi::query()->lockForUpdate()->findOrFail($id);
            $menuIds = collect($validated['items'])->pluck('menu_id')->unique();
            $menus = Menu::query()->whereIn('id', $menuIds)->get()->keyBy('id');
            if ($menus->count() !== $menuIds->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Salah satu menu sudah tidak tersedia. Muat ulang halaman dan coba lagi.',
                ]);
            }

            $items = collect($validated['items'])->map(function (array $item) use ($menus) {
                $menu = $menus->get((int) $item['menu_id']);
                $harga = (float) $menu->harga;

                return [
                    'menu_id' => $menu->id,
                    'jumlah' => (int) $item['jumlah'],
                    'harga' => $harga,
                    'subtotal' => $harga * (int) $item['jumlah'],
                    'note' => $item['note'] ?? null,
                ];
            });

            $subtotal = round($items->sum('subtotal'), 2);
            $ppnRate = (float) ($transaksi->ppn_rate ?? 0);
            $ppnAmount = round($subtotal * $ppnRate / 100, 2);
            $totalHarga = $subtotal + $ppnAmount;

            $transaksi->fill([
                'atasnama' => $validated['atasnama'],
                'totalharga' => $totalHarga,
                'ppn_amount' => $ppnAmount,
            ]);

            $transaksi->save();
            $transaksi->details()->delete();
            foreach ($items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    ...$item,
                ]);
            }
        });

        return redirect()->route('kasir.index')->with('success', 'Data transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $transaksi = Transaksi::query()->lockForUpdate()->findOrFail($id);
            $transaksi->details()->delete();
            $transaksi->delete();
        });

        return redirect()->route('kasir.index')->with('success', 'Transaksi berhasil dihapus.');
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

        if ($transaksi->status === Transaksi::STATUS_LUNAS) {
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
            $pesananLabel = $transaksi->nomeja === null ? 'pesanan' : 'pesanan meja ' . $transaksi->nomeja;
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Jumlah bayar kurang dari total harga ' . $pesananLabel . '.'], 422);
            }
            return redirect()->back()->with('error', 'Jumlah bayar kurang dari total harga ' . $pesananLabel . '.');
        }

        $paidAt = now();
        $jumlah_bayar = (float) $request->jumlah_bayar;

        // Tandai semua transaksi dalam grup yang sama menjadi lunas
        foreach ($groupedTransaksis as $groupedTransaksi) {
            $paymentData = [
                'status' => Transaksi::STATUS_LUNAS,
                'metode_pembayaran' => $request->metode_pembayaran,
                'paid_at' => $paidAt,
                'jumlah_bayar' => $jumlah_bayar,
            ];

            if (Schema::hasColumn('transaksis', 'kasir_id')) {
                $paymentData['kasir_id'] = $request->user()->id;
            }

            $groupedTransaksi->update($paymentData);
        }

        $kembalian = $jumlah_bayar - $totalHargaSemua;

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
                'receipt' => $this->buildReceiptPayload(
                    $groupedTransaksis,
                    $jumlah_bayar
                ),
                'message' => $transaksi->nomeja === null
                    ? 'Pembayaran berhasil untuk pesanan!'
                    : 'Pembayaran berhasil untuk meja ' . $transaksi->nomeja . '!',
            ]);
        }

        return redirect()->route('kasir.index')->with(
            'success',
            $transaksi->nomeja === null
                ? 'Pembayaran berhasil untuk pesanan!'
                : 'Pembayaran berhasil untuk meja ' . $transaksi->nomeja . '!'
        );
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

    protected function getReceiptTransaksis(Transaksi $transaksi, $transaksis = null)
    {
        if ($transaksis === null) {
            $relations = ['details.menu'];
            if (Schema::hasColumn('transaksis', 'kasir_id')) {
                $relations[] = 'cashier';
            }

            $transaksis = Transaksi::with($relations)
                ->whereKey($transaksi->id)
                ->get();
        }

        $selectedTransaksi = $transaksis->first(function ($item) use ($transaksi) {
            return $item->id == $transaksi->id;
        });

        return collect([$selectedTransaksi ?? $transaksi]);
    }

    private function buildReceiptPayload(Collection $transaksis, float $jumlahBayar): array
    {
        $firstTransaksi = $transaksis->first();
        $totalHarga = (float) $transaksis->sum('totalharga');
        $ppnAmount = (float) $transaksis->sum('ppn_amount');
        $ppnRates = $transaksis->pluck('ppn_rate')->unique();
        $isPaid = $firstTransaksi->status === Transaksi::STATUS_LUNAS;
        $paidAt = $isPaid
            ? ($firstTransaksi->paid_at ?? $firstTransaksi->created_at)
            : $firstTransaksi->created_at;

        return [
            'store_name' => 'Cafe Omah Jawa',
            'transaction_code' => $firstTransaksi->kode,
            'table_number' => $firstTransaksi->nomeja,
            'customer_name' => $firstTransaksi->atasnama,
            'cashier_name' => 'Andik Satriadi',
            'paid_at' => $paidAt
                ? \Illuminate\Support\Carbon::parse($paidAt)->format('d-m-Y H:i')
                : null,
            'status' => $firstTransaksi->status,
            'status_label' => Transaksi::kasirStatusLabel($firstTransaksi->status),
            'payment_method' => $isPaid ? $firstTransaksi->metode_pembayaran : null,
            'subtotal' => $totalHarga - $ppnAmount,
            'ppn_rate' => $ppnRates->count() === 1 ? (float) $ppnRates->first() : null,
            'ppn_amount' => $ppnAmount,
            'items' => $transaksis->flatMap(function ($item) {
                return $item->details->map(function ($detail) {
                    return [
                        'name' => $detail->menu?->nama ?? 'Menu dihapus',
                        'quantity' => (int) $detail->jumlah,
                        'price' => (float) $detail->harga,
                        'subtotal' => (float) $detail->subtotal,
                        'note' => $detail->note,
                    ];
                });
            })->values()->all(),
            'total' => $totalHarga,
            'paid' => $isPaid ? $jumlahBayar : 0,
            'change' => $isPaid ? max(0, $jumlahBayar - $totalHarga) : 0,
        ];
    }

    public function strukData(Request $request, $id)
    {
        $relations = ['details.menu'];
        if (Schema::hasColumn('transaksis', 'kasir_id')) {
            $relations[] = 'cashier';
        }
        $transaksi = Transaksi::with($relations)->findOrFail($id);
        $transaksis = $this->getReceiptTransaksis($transaksi);
        $totalHarga = (float) $transaksis->sum('totalharga');
        $jumlahBayar = $transaksi->status === Transaksi::STATUS_LUNAS
            ? $this->resolvePaymentAmount($request, $transaksi, $totalHarga)
            : 0;

        return response()->json([
            'success' => true,
            'receipt' => $this->buildReceiptPayload($transaksis, $jumlahBayar),
        ]);
    }

    public function cetakStruk(Request $request, $id)
    {
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);
        $allTransaksis = $this->getReceiptTransaksis($transaksi);

        $combinedDetails = collect();
        $totalHarga = 0;
        $ppnAmount = 0;

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
            $ppnAmount += $trx->ppn_amount;
        }

        $strukTransaksi = new Transaksi();
        $strukTransaksi->id = $transaksi->id;
        $strukTransaksi->kode = $transaksi->kode;
        $strukTransaksi->nomeja = $transaksi->nomeja;
        $strukTransaksi->atasnama = $transaksi->atasnama;
        $strukTransaksi->totalharga = $totalHarga;
        $strukTransaksi->ppn_amount = $ppnAmount;
        $ppnRates = $allTransaksis->pluck('ppn_rate')->unique();
        $strukTransaksi->ppn_rate = $ppnRates->count() === 1 ? $ppnRates->first() : null;
        $strukTransaksi->metode_pembayaran = $transaksi->metode_pembayaran;
        $strukTransaksi->status = $transaksi->status;
        $strukTransaksi->created_at = $transaksi->created_at;
        $strukTransaksi->setRelation('details', $combinedDetails);

        $jumlah_bayar = $transaksi->status === Transaksi::STATUS_LUNAS
            ? $this->resolvePaymentAmount($request, $transaksi, $totalHarga)
            : 0;
        $kembalian = max(0, $jumlah_bayar - $totalHarga);
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
