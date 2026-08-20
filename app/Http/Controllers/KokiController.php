<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class KokiController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'koki']);
    }

    public function index()
    {
        // Ambil data transaksi beserta relasi menu dan detailnya
        // Urutkan berdasarkan waktu kedatangan untuk memastikan FCFS
        $koki = Transaksi::with('details.menu')
            ->orderBy('created_at', 'asc')
            ->get();

        // Cari pesanan terlama yang masih menunggu proses koki
        $pendingQueue = $koki->whereIn('status', [Transaksi::STATUS_BELUM_DIPROSES, Transaksi::STATUS_PESANAN_ONLINE]);
        $nextPending = $pendingQueue->first();
        $activeProcessing = $koki->firstWhere('status', Transaksi::STATUS_SEDANG_DIPROSES);

        return view('pages.koki.index', [
            'koki' => $koki,
            'nextPendingId' => $nextPending?->id,
            'activeProcessing' => $activeProcessing !== null,
        ]);
    }

}
