<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input tanggal manual
        $startDateInput = $request->input('startDate');
        $endDateInput = $request->input('endDate');

        if ($startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } else {
            $periode = $request->input('periode', 'hari_ini');
            switch ($periode) {
                case 'bulan_ini':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
                case 'tahun_ini':
                    $startDate = now()->startOfYear();
                    $endDate = now()->endOfYear();
                    break;
                case 'bulan_kemarin':
                    $startDate = now()->subMonth()->startOfMonth();
                    $endDate = now()->subMonth()->endOfMonth();
                    break;
                default:
                    $startDate = now()->startOfDay();
                    $endDate = now()->endOfDay();
                    break;
            }
        }

        // Total transaksi masuk (semua)
        $totalTransaksi = Transaksi::whereBetween('created_at', [$startDate, $endDate])->count();

        // Total transaksi yang sudah lunas
        $totalTransaksiLunas = Transaksi::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Lunas')
            ->count();

        // Total pendapatan dari yang lunas
        $totalPendapatanLunas = Transaksi::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Lunas')
            ->sum('totalharga');

        // Jumlah pesanan (total item dari transaksi yang lunas)
        $jumlahPesanan = DetailTransaksi::whereHas('transaksi', function ($query) use ($startDate, $endDate) {
            $query->where('status', 'Lunas')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })->sum('jumlah');


        // Status lainnya (opsional)
        $transaksiPending = Transaksi::whereBetween('created_at', [$startDate, $endDate])->where('status', 'Pending')->count();
        $transaksiProcessing = Transaksi::whereBetween('created_at', [$startDate, $endDate])->where('status', 'Processing')->count();
        $transaksiCompleted = Transaksi::whereBetween('created_at', [$startDate, $endDate])->where('status', 'Completed')->count();

        // Grafik pendapatan harian
        $pendapatanHarian = Transaksi::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'Lunas')
            ->selectRaw('DATE(created_at) as date, SUM(totalharga) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date')
            ->toArray();

        // Grafik jumlah transaksi harian
        $penjualanHarian = Transaksi::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date')
            ->toArray();

        return view('pages.dashboard.index', compact(
            'totalTransaksi',
            'totalTransaksiLunas',
            'totalPendapatanLunas',
            'jumlahPesanan',
            'transaksiPending',
            'transaksiProcessing',
            'transaksiCompleted',
            'pendapatanHarian',
            'penjualanHarian',
            'startDate',
            'endDate'
        ));
    }
}
