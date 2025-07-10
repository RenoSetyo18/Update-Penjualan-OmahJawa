<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class KokiController extends Controller
{
    public function index()
    {
        // Ambil data transaksi beserta relasi menu dan detailnya
        // Urutkan berdasarkan waktu terbaru
        $koki = Transaksi::with('details.menu')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim ke view
        return view('pages.koki.index', compact('koki'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum_diproses,sedang_diproses,pending,Completed,Lunas',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // Hanya blokir jika sudah Completed atau Lunas
        if (in_array($transaksi->status, ['Completed', 'Lunas'])) {
            return redirect()->back()->with('error', 'Status sudah selesai dan tidak bisa diubah lagi.');
        }

        // Validasi transisi status yang diperbolehkan
        $current = $transaksi->status;
        $next = $request->status;

        $validTransitions = [
            'belum_diproses' => ['sedang_diproses'],
            'sedang_diproses' => ['pending'],
            'pending' => ['Completed'],
        ];

        if (!isset($validTransitions[$current]) || !in_array($next, $validTransitions[$current])) {
            return redirect()->back()->with('error', 'Transisi status tidak valid dari ' . $current . ' ke ' . $next);
        }

        $transaksi->status = $next;
        $transaksi->save();

        return redirect()->back()->with('success', 'Status berhasil diubah menjadi ' . $next);
    }
}
