<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class KasirController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi beserta relasi detail dan menu, urut dari yang terbaru
        $transaksis = Transaksi::with('details.menu')->orderByDesc('created_at')->get();

        return view('pages.kasir.index', compact('transaksis'));
    }

    public function bayar(Request $request, $id)
    {
        // Validasi input dari form pembayaran
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'cetak_struk' => 'required|in:ya,tidak',
        ]);

        // Ambil transaksi terkait
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);

        // Jika sudah lunas, tidak perlu proses lagi
        if ($transaksi->status === 'Lunas') {
            return redirect()->route('kasir.index')->with('info', 'Transaksi sudah lunas.');
        }

        // Cek apakah jumlah bayar cukup
        if ($request->jumlah_bayar < $transaksi->totalharga) {
            return redirect()->back()->with('error', 'Jumlah bayar kurang dari total harga.');
        }

        // Tandai transaksi sebagai lunas
        $transaksi->status = 'Lunas';
        $transaksi->save();

        // Jika cetak struk dipilih
        if ($request->cetak_struk === 'ya') {
            return view('pages.kasir.cetak_struk', compact('transaksi', 'request'));
        }

        // Kembali ke halaman kasir dengan notifikasi sukses
        return redirect()->route('kasir.index')->with('success', 'Pembayaran berhasil!');
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

        return view('pages.kasir.cetak_rekap', compact('transaksis'));
    }

}
