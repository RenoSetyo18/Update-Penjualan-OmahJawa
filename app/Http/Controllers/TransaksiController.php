<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'pelayan', 'koki', 'kasir']);
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

    // ================================
    // 1. MENAMPILKAN DATA TRANSAKSI
    // ================================
    public function index()
    {
        // Ambil semua transaksi dengan relasi
        $transaksis = Transaksi::with('details.menu')->get();

        // Sort dengan prioritas: makanan_siap paling tua di paling atas, kemudian transaksi lain berdasarkan waktu
        $transaksis = $transaksis->sortBy(function($item) {
            // Prioritas 1: Status makanan_siap paling tua (created_at paling kecil)
            // Prioritas 2: Status lain berdasarkan created_at
            if ($item->status === Transaksi::STATUS_MAKANAN_SIAP) {
                return [0, $item->created_at->timestamp]; // 0 = prioritas tertinggi
            } else {
                return [1, $item->created_at->timestamp]; // 1 = prioritas lebih rendah
            }
        })->values();

        // Cari pesanan pertama dengan status makanan_siap untuk FCFS
        $firstReadyToDeliver = $transaksis->firstWhere('status', Transaksi::STATUS_MAKANAN_SIAP);

        $totalTransaksi = $transaksis->sum('totalharga');
        $totalPendapatanLunas = $transaksis->where('status', 'lunas')->sum('totalharga');
        $transaksiSedangDiproses = $transaksis->where('status', 'sedang_diproses')->count();
        $transaksiSelesai = $transaksis->where('status', 'selesai')->count();
        $transaksiBelumDiproses = $transaksis->where('status', 'belum_diproses')->count();

        $latestTransactions = $transaksis->sortByDesc('created_at')->take(5);

        $pendapatanHarian = $transaksis->where('status', 'lunas')
            ->groupBy(function($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            })
            ->map(function($group) {
                return $group->sum('totalharga');
            });

        return view('pages.transaksi.index', compact(
            'transaksis', 'totalTransaksi', 'totalPendapatanLunas',
            'transaksiSedangDiproses', 'transaksiSelesai', 'latestTransactions',
            'transaksiBelumDiproses', 'pendapatanHarian', 'firstReadyToDeliver'
        ));
    }

    public function cetakRekap(Request $request)
    {
        $query = Transaksi::with('details.menu')->orderBy('created_at', 'desc');

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        if ($request->filled('mode') && $request->mode === 'history') {
            $query->whereIn('status', ['selesai', 'lunas']);
        }

        $transaksis = $query->get();
        $logoBase64 = $this->getImageBase64('dataset/dist/img/LogoOmahJawa.png');

        if ($request->query('download') === 'pdf') {
            if (!extension_loaded('gd')) {
                return redirect()->route('transaksi.index')->with('error', 'PHP GD extension diperlukan untuk download PDF. Silakan aktifkan ekstensi gd di php.ini.');
            }
            try {
                $fileName = 'rekap_transaksi_' . ($request->dari ?? 'semua') . '_sampai_' . ($request->sampai ?? 'semua') . '.pdf';
                $pdf = Pdf::loadView('pages.transaksi.pdf_rekap', compact('transaksis', 'request', 'logoBase64'));
                return $pdf->download($fileName);
            } catch (\Exception $e) {
                return view('pages.transaksi.cetak_rekap', compact('transaksis', 'request'));
            }
        }

        return view('pages.transaksi.cetak_rekap', compact('transaksis', 'request'));
    }

    // ================================
    // 2. SIMPAN TRANSAKSI
    // ================================
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nomeja' => 'required|integer|min:1|max:999',
            'atasnama' => 'required|string|min:2|max:255',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'items.*.note' => 'nullable|string|max:255',
            'status' => 'nullable|in:belum_diproses,Pesanan Online',
            'metode_pembayaran' => 'required_if:status,Pesanan Online|in:Cash,Dana,BCA,BRI,Qris',
        ]);

        try {
            // Hitung total
            $totalHarga = collect($request->items)->sum('subtotal');

            // Tentukan status: default belum_diproses, atau Pesanan Online jika sudah bayar di awal
            $status = $request->status ?? Transaksi::STATUS_BELUM_DIPROSES;

            // Simpan transaksi utama
            $transaksi = Transaksi::create([
                'kode' => 'TRX-' . Str::upper(Str::random(8)),
                'user_id' => auth()->id(), // Tambahkan user_id dari user yang login
                'nomeja' => $request->nomeja,
                'atasnama' => $request->atasnama,
                'totalharga' => $totalHarga,
                'status' => $status,
            ]);

            // Simpan detail item
            foreach ($request->items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id' => $item['menu_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'note' => $item['note'] ?? null,
                    // Pastikan status menu ter-set secara eksplisit agar tampilan konsisten
                    'menu_status' => DetailTransaksi::MENU_STATUS_BELUM_SIAP,
                ]);
            }

            // ✅ Return sukses (agar JS bisa munculkan SweetAlert dengan icon centang)
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!'
            ], 200);

        } catch (\Exception $e) {
            // ❌ Return gagal
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 3. UPDATE STATUS TRANSAKSI
    // ================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum_diproses,sedang_diproses,makanan_siap,selesai,Pesanan Online'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $current = $transaksi->status;
        $next = $request->status;

        if ($current === Transaksi::STATUS_LUNAS) {
            return back()->with('error', 'Transaksi sudah Lunas.');
        }

        if (in_array($current, [Transaksi::STATUS_BELUM_DIPROSES, Transaksi::STATUS_PESANAN_ONLINE]) && $next === Transaksi::STATUS_SEDANG_DIPROSES) {
            $oldestPending = Transaksi::whereIn('status', [Transaksi::STATUS_BELUM_DIPROSES, Transaksi::STATUS_PESANAN_ONLINE])
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$oldestPending || $oldestPending->id !== $transaksi->id) {
                return back()->with('error', 'Harus memproses pesanan terlama terlebih dahulu.');
            }

            if (Transaksi::where('status', Transaksi::STATUS_SEDANG_DIPROSES)->where('id', '!=', $transaksi->id)->exists()) {
                return back()->with('error', 'Tunggu pesanan yang sedang diproses selesai dulu.');
            }

            $transaksi->status = Transaksi::STATUS_SEDANG_DIPROSES;
        } elseif ($current === Transaksi::STATUS_SEDANG_DIPROSES && $next === Transaksi::STATUS_MAKANAN_SIAP) {
            $transaksi->status = Transaksi::STATUS_MAKANAN_SIAP;
        } elseif ($current === Transaksi::STATUS_MAKANAN_SIAP && $next === Transaksi::STATUS_SELESAI) {

        // Validasi FCFS untuk konfirmasi pengantaran
            $firstReadyToDeliver = Transaksi::where('status', Transaksi::STATUS_MAKANAN_SIAP)
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$firstReadyToDeliver || $firstReadyToDeliver->id !== $transaksi->id) {
                return back()->with('error', 'Harus mengantarkan pesanan siap yang paling tua terlebih dahulu (FCFS).');
            }

            $transaksi->status = Transaksi::STATUS_SELESAI;
        } else {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        $transaksi->save();

        // Pastikan detail yang sebelumnya ditandai siap (memiliki ready_at) tetap berstatus 'siap'
        try {
            DetailTransaksi::where('transaksi_id', $transaksi->id)
                ->whereNotNull('ready_at')
                ->update(['menu_status' => DetailTransaksi::MENU_STATUS_SIAP]);
        } catch (\Exception $e) {
            // Jangan gagalkan operasi utama hanya karena update tambahan; log untuk diagnosa jika perlu
        }

        //$this->assignNextPendingToProcessing();

        return back()->with('success', 'Status berhasil diubah menjadi ' . $transaksi->status);
    }

    private function assignNextPendingToProcessing()
    {
        if (Transaksi::where('status', Transaksi::STATUS_SEDANG_DIPROSES)->exists()) {
            return;
        }

        $nextPending = Transaksi::whereIn('status', [Transaksi::STATUS_BELUM_DIPROSES, Transaksi::STATUS_PESANAN_ONLINE])
            ->orderBy('created_at', 'asc')
            ->first();

        if ($nextPending) {
            $nextPending->status = Transaksi::STATUS_SEDANG_DIPROSES;
            $nextPending->save();
        }
    }

    // ================================
    // 4. DELETE DETAIL TRANSAKSI
    // ================================
    public function deleteDetail(Request $request, $transaksiId, $detailId)
    {
        try {
            $transaksi = Transaksi::findOrFail($transaksiId);
            $detail = DetailTransaksi::where('id', $detailId)
                ->where('transaksi_id', $transaksiId)
                ->firstOrFail();

            // Cek status transaksi - hanya bisa hapus jika belum_diproses
            if ($transaksi->status !== Transaksi::STATUS_BELUM_DIPROSES) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa membatalkan menu untuk pesanan dengan status Belum Diproses. Status saat ini: ' .
                                 Transaksi::transaksiStatusLabel($transaksi->status)
                ], 400);
            }

            // Ambil quantity yang ingin dibatalkan
            $cancelQty = $request->input('cancel_qty', 1);
            $cancelQty = intval($cancelQty);

            // Validasi quantity
            if ($cancelQty < 1 || $cancelQty > $detail->jumlah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah yang dibatalkan tidak valid. Maksimal: ' . $detail->jumlah
                ], 400);
            }

            // Jika jumlah yang dibatalkan sama dengan jumlah order, hapus detail
            if ($cancelQty == $detail->jumlah) {
                $detail->delete();
                $message = 'Menu berhasil dihapus dari pesanan';
            } else {
                // Jika jumlah yang dibatalkan kurang dari total, kurangi jumlah
                $oldSubtotal = $detail->subtotal;
                $hargaPerUnit = $detail->harga;
                $newJumlah = $detail->jumlah - $cancelQty;
                $newSubtotal = $newJumlah * $hargaPerUnit;

                $detail->update([
                    'jumlah' => $newJumlah,
                    'subtotal' => $newSubtotal
                ]);

                $message = "Berhasil membatalkan {$cancelQty} menu. Sisa: {$newJumlah} menu";
            }

            // Update total harga transaksi
            $newTotal = $transaksi->details->sum('subtotal');
            $transaksi->update(['totalharga' => $newTotal]);

            // Jika semua menu sudah dihapus, hapus transaksi
            if ($transaksi->details->count() === 0) {
                $transaksi->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Menu berhasil dihapus. Transaksi dihapus karena tidak ada menu lagi.',
                    'deleted_transaction' => true
                ], 200);
            }
            return response()->json([
                'success' => true,
                'message' => $message,
                'new_total' => $newTotal,
                'deleted_transaction' => false
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu atau transaksi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus menu: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 5. UPDATE DETAIL TRANSAKSI (Edit Note)
    // ================================
    public function updateDetail(Request $request, $transaksiId, $detailId)
    {
        try {
            $request->validate([
                'note' => 'nullable|string|max:255',
            ]);

            $transaksi = Transaksi::findOrFail($transaksiId);
            $detail = DetailTransaksi::where('id', $detailId)
                ->where('transaksi_id', $transaksiId)
                ->firstOrFail();

            // Cek status transaksi - hanya bisa edit jika belum_diproses
            if ($transaksi->status !== Transaksi::STATUS_BELUM_DIPROSES) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa edit menu untuk pesanan dengan status Belum Diproses. Status saat ini: ' .
                                 Transaksi::transaksiStatusLabel($transaksi->status)
                ], 400);
            }

            $detail->update(['note' => $request->note]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan menu berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu atau transaksi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate menu: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 6. CEK TRANSAKSI BERDASARKAN NOMOR MEJA
    // ================================
    public function getTransactionByTable(Request $request)
    {
        $request->validate([
            'nomeja' => 'required|integer|min:1|max:999'
        ]);

        $noMeja = $request->input('nomeja');

        // Cek apakah ada transaksi dengan nomor meja ini dan status belum lunas
        $transaksi = Transaksi::where('nomeja', $noMeja)
            ->where('status', '!=', 'lunas')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($transaksi) {
            return response()->json([
                'found' => true,
                'atasnama' => $transaksi->atasnama,
                'message' => 'Data nama ditemukan dari transaksi sebelumnya'
            ], 200);
        }

        return response()->json([
            'found' => false,
            'message' => 'Nomor meja tidak ditemukan atau transaksi sudah lunas'
        ], 200);
    }

    // ================================
    // 7. UPDATE MENU STATUS (Koki - Mark as Ready)
    // ================================
    public function updateMenuStatus(Request $request, $transaksiId, $detailId)
    {
        try {
            $request->validate([
                'menu_status' => 'required|in:belum_siap,siap',
            ]);

            $transaksi = Transaksi::findOrFail($transaksiId);
            $detail = DetailTransaksi::where('id', $detailId)
                ->where('transaksi_id', $transaksiId)
                ->firstOrFail();

            // Update status menu
            $detail->menu_status = $request->menu_status;
            if ($request->menu_status === DetailTransaksi::MENU_STATUS_SIAP) {
                $detail->ready_at = Carbon::now();
            }
            $detail->save();

            // Cek apakah semua menu dalam transaksi sudah siap
            $allMenusReady = !$transaksi->details()->where('menu_status', DetailTransaksi::MENU_STATUS_BELUM_SIAP)->exists();

            // Jika semua menu siap, set status transaksi di master transaksi menjadi makanan_siap
            // Set secara otomatis tanpa tergantung status sebelumnya, agar UI lain bisa ter-update real-time
            if ($allMenusReady && $transaksi->status !== Transaksi::STATUS_MAKANAN_SIAP) {
                $transaksi->status = Transaksi::STATUS_MAKANAN_SIAP;
                $transaksi->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status menu berhasil diperbarui',
                'menu_status' => $detail->menu_status,
                'all_ready' => $allMenusReady,
                'transaksi_status' => $transaksi->status
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu atau transaksi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status menu: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 8. GET MENU STATUS (Real-time Update)
    // ================================
    public function getMenuStatus($transaksiId)
    {
        try {
            $transaksi = Transaksi::with('details.menu')->findOrFail($transaksiId);

            $details = $transaksi->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'menu_name' => $detail->menu->nama,
                    'menu_status' => $detail->menu_status ?? DetailTransaksi::MENU_STATUS_BELUM_SIAP,
                    'status_label' => DetailTransaksi::menuStatusLabel($detail->menu_status ?? DetailTransaksi::MENU_STATUS_BELUM_SIAP),
                    'ready_at' => $detail->ready_at,
                ];
            });

            $allReady = !$transaksi->details()->where('menu_status', DetailTransaksi::MENU_STATUS_BELUM_SIAP)->exists();

            return response()->json([
                'success' => true,
                'details' => $details,
                'transaksi_status' => $transaksi->status,
                'all_ready' => $allReady,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status menu: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 9. GET READY MENUS FOR PELAYAN NOTIFICATION
    // ================================
    public function getReadyMenusNotification()
    {
        try {
            // Ambil semua transaksi dengan menu yang baru saja menjadi siap
            // (dalam 30 detik terakhir untuk notifikasi real-time)
            $cutoffTime = Carbon::now()->subSeconds(30);

            $readyMenus = DetailTransaksi::with(['transaksi', 'menu'])
                ->where('menu_status', DetailTransaksi::MENU_STATUS_SIAP)
                ->whereNull('notified_at')
                ->where('ready_at', '>=', $cutoffTime)
                ->where('ready_at', '<=', Carbon::now())
                ->get();

            $notifications = $readyMenus->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'transaksi_kode' => $detail->transaksi->kode,
                    'transaksi_id' => $detail->transaksi->id,
                    'nomeja' => $detail->transaksi->nomeja,
                    'menu_name' => $detail->menu->nama,
                    'jumlah' => $detail->jumlah,
                    'ready_at' => $detail->ready_at,
                    'message' => "Menu {$detail->menu->nama} ({$detail->jumlah}x) untuk meja {$detail->transaksi->nomeja} sudah siap!"
                ];
            })->values();

            // Tandai item yang sudah dikirim notifikasinya agar tidak dikirim ulang
            try {
                $ids = $readyMenus->pluck('id')->all();
                if (!empty($ids)) {
                    DetailTransaksi::whereIn('id', $ids)->update(['notified_at' => Carbon::now()]);
                }
            } catch (\Exception $e) {
                // jika gagal menandai, jangan gagalkan pengiriman notifikasi kepada client
            }

            return response()->json([
                'success' => true,
                'ready_menus' => $notifications,
                'count' => $notifications->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil notifikasi menu siap: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 10. GET ALL TRANSAKSIS DATA FOR TABLE REFRESH
    // ================================
    public function getAllTransaksisForRefresh()
    {
        try {
            $transaksis = Transaksi::with('details.menu')->get();

            // Sort dengan prioritas: makanan_siap paling tua di paling atas
            $transaksis = $transaksis->sortBy(function($item) {
                if ($item->status === Transaksi::STATUS_MAKANAN_SIAP) {
                    return [0, $item->created_at->timestamp];
                } else {
                    return [1, $item->created_at->timestamp];
                }
            })->values();

            $data = $transaksis->map(function($transaksi) {
                $statusLabel = Transaksi::transaksiStatusLabel($transaksi->status);
                $statusBadgeClass = 'badge-secondary';

                if (in_array($transaksi->status, ['selesai', 'lunas'])) {
                    $statusLabel = 'Selesai';
                    $statusBadgeClass = 'badge-primary';
                } elseif ($transaksi->status === Transaksi::STATUS_MAKANAN_SIAP) {
                    $statusLabel = 'Makanan Siap';
                    $statusBadgeClass = 'badge-info';
                }

                return [
                    'id' => $transaksi->id,
                    'kode' => $transaksi->kode,
                    'nomeja' => $transaksi->nomeja,
                    'atasnama' => $transaksi->atasnama,
                    'total_items' => $transaksi->details->count(),
                    'status' => $transaksi->status,
                    'status_label' => $statusLabel,
                    'status_badge_class' => $statusBadgeClass,
                    'created_at' => date('d-m-Y H:i', strtotime($transaksi->created_at)),
                    'created_at_timestamp' => strtotime($transaksi->created_at)
                ];
            });

            return response()->json([
                'success' => true,
                'transaksis' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================================
    // 11. GET TRANSAKSI DETAIL FOR MODAL (Real-time)
    // ================================
    public function getTransaksiDetailForModal($transaksiId)
    {
        try {
            $transaksi = Transaksi::with('details.menu')->findOrFail($transaksiId);

            $details = $transaksi->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'menu_nama' => $detail->menu->nama,
                    'note' => $detail->note ?? '-',
                    'jumlah' => $detail->jumlah,
                    'harga' => number_format($detail->harga, 2, ',', '.'),
                    'subtotal' => number_format($detail->subtotal, 2, ',', '.'),
                    'menu_status' => $detail->menu_status ?? DetailTransaksi::MENU_STATUS_BELUM_SIAP,
                    'menu_status_label' => DetailTransaksi::menuStatusLabel($detail->menu_status ?? DetailTransaksi::MENU_STATUS_BELUM_SIAP),
                    'menu_status_badge' => ($detail->menu_status ?? DetailTransaksi::MENU_STATUS_BELUM_SIAP) === DetailTransaksi::MENU_STATUS_SIAP ? 'badge-success' : 'badge-warning'
                ];
            });

            return response()->json([
                'success' => true,
                'transaksi' => [
                    'id' => $transaksi->id,
                    'nomeja' => $transaksi->nomeja,
                    'atasnama' => $transaksi->atasnama,
                    'totalharga' => number_format($transaksi->totalharga, 2, ',', '.'),
                    'status' => $transaksi->status
                ],
                'details' => $details
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

}
