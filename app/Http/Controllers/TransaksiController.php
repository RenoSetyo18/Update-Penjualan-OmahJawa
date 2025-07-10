<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    // ================================
    // 1. MENAMPILKAN DATA TRANSAKSI
    // ================================
    public function index()
    {
        $transaksis = Transaksi::with('details.menu')->get();

        $totalTransaksi = $transaksis->sum('totalharga');
        $totalPendapatanLunas = $transaksis->where('status', 'Lunas')->sum('totalharga');
        $transaksiProcessing = $transaksis->where('status', 'Processing')->count();
        $transaksiCompleted = $transaksis->where('status', 'Completed')->count();
        $transaksiPending = $transaksis->where('status', 'pending')->count();

        $latestTransactions = $transaksis->sortByDesc('created_at')->take(5);

        $pendapatanHarian = $transaksis->where('status', 'Lunas')
            ->groupBy(function($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            })
            ->map(function($group) {
                return $group->sum('totalharga');
            });

        return view('pages.transaksi.index', compact(
            'transaksis', 'totalTransaksi', 'totalPendapatanLunas',
            'transaksiProcessing', 'transaksiCompleted', 'latestTransactions',
            'transaksiPending', 'pendapatanHarian'
        ));
    }

    // ================================
    // 2. SIMPAN TRANSAKSI
    // ================================
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nomeja' => 'required|string',
            'atasnama' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        try {
            // Hitung total
            $totalHarga = collect($request->items)->sum('subtotal');

            // Simpan transaksi utama
            $transaksi = Transaksi::create([
                'kode' => 'TRX-' . Str::upper(Str::random(8)),
                'nomeja' => $request->nomeja,
                'atasnama' => $request->atasnama,
                'totalharga' => $totalHarga,
                'status' => 'belum_diproses'

            ]);

            // Simpan detail item
            foreach ($request->items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id' => $item['menu_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal']
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
        'status' => 'required|in:belum_diproses,sedang_diproses,pending,Completed,Lunas'
    ]);

    $transaksi = Transaksi::findOrFail($id);
    $current = $transaksi->status;
    $next = $request->status;

    if ($current === 'Lunas') {
        return back()->with('error', 'Transaksi sudah Lunas.');
    }

    // Skema transisi status
    if ($current === 'belum_diproses' && $next === 'sedang_diproses') {
        $transaksi->status = 'sedang_diproses';
    } elseif ($current === 'sedang_diproses' && $next === 'pending') {
        $transaksi->status = 'pending';
    } elseif ($current === 'pending' && $next === 'Completed') {
        $transaksi->status = 'Completed';
    } elseif ($current === 'Completed' && $next === 'Lunas') {
        $transaksi->status = 'Lunas';
    } else {
        return back()->with('error', 'Transisi status tidak valid.');
    }

    $transaksi->save();

    return back()->with('success', 'Status berhasil diubah menjadi ' . $transaksi->status);
}


}
