<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        // Ambil input tanggal manual untuk filter admin
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

        $kasirTodayRevenue = 0;
        $kasirTodayTransactions = 0;
        $kasirTodayLunas = 0;
        $kasirTodayBelumLunas = 0;
        $kasirUnpaidTransactions = collect();

        $totalTransaksi = 0;
        $totalTransaksiLunas = 0;
        $totalPendapatanLunas = 0;
        $jumlahPesanan = 0;
        $weeklyTrendValue = 0;
        $weeklyTrendPercent = 0;
        $currentWeekSales = 0;
        $previousWeekSales = 0;
        $monthlyTrendValue = 0;
        $monthlyTrendPercent = 0;
        $currentMonthSales = 0;
        $previousMonthSales = 0;
        $transaksiBelumBayar = 0;
        $pendapatanHarian = [];
        $penjualanHarian = [];
        $bestSellingByJenis = collect();
        $bestSellingOverall = collect();
        $paymentMethodDistribution = collect();
        $weeklyTrendLabels = [];
        $weeklyTrendCurrent = [];
        $weeklyTrendPrevious = [];
        $monthlyComparisonLabels = ['Bulan Lalu', 'Bulan Ini'];
        $monthlyComparisonValues = [0, 0];

        if ($user && $user->role === 'kasir') {
            $kasirTodayRevenue = Transaksi::whereDate('paid_at', today())
                ->where('status', Transaksi::STATUS_LUNAS)
                ->sum('totalharga');

            $kasirTodayTransactions = Transaksi::whereDate('paid_at', today())
                ->where('status', Transaksi::STATUS_LUNAS)
                ->count();

            $kasirTodayLunas = $kasirTodayTransactions;

            $kasirTodayBelumLunas = Transaksi::whereBetween('created_at', [$todayStart, $todayEnd])
                ->where('status', '<>', Transaksi::STATUS_LUNAS)
                ->count();

            $kasirUnpaidSource = Transaksi::with('details.menu')
                ->where('status', Transaksi::STATUS_BELUM_BAYAR)
                ->orderByDesc('created_at')
                ->get();

            $processedMejas = [];
            foreach ($kasirUnpaidSource as $item) {
                /** @var Transaksi $transaksi */
                $transaksi = $item;

                if ($transaksi->nomeja === null) {
                    $kasirUnpaidTransactions->push($transaksi);
                    continue;
                }

                if (!in_array($transaksi->nomeja, $processedMejas, true)) {
                    $processedMejas[] = $transaksi->nomeja;
                    $relatedTransaksis = $this->getKasirUnpaidGroupTransaksis($transaksi, $kasirUnpaidSource);

                    if ($relatedTransaksis->count() > 1) {
                        $kasirUnpaidTransactions->push($this->combineKasirTransactions($relatedTransaksis));
                    } else {
                        $kasirUnpaidTransactions->push($transaksi);
                    }
                }
            }
        }

        if ($user && in_array($user->role, ['admin', 'super_admin'], true)) {
            $totalTransaksi = Transaksi::whereBetween('created_at', [$startDate, $endDate])->count();
            $totalTransaksiLunas = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->count();
            $totalPendapatanLunas = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->sum('totalharga');
            $jumlahPesanan = DetailTransaksi::whereHas('transaksi', function ($query) use ($startDate, $endDate) {
                $query->where('status', Transaksi::STATUS_LUNAS)
                    ->whereBetween('created_at', [$startDate, $endDate]);
            })->sum('jumlah');

            $currentWeekStart = $endDate->copy()->subDays(6)->startOfDay();
            $currentWeekEnd = $endDate->copy()->endOfDay();
            $previousWeekStart = $currentWeekStart->copy()->subDays(7)->startOfDay();
            $previousWeekEnd = $currentWeekStart->copy()->subDay()->endOfDay();

            $currentWeekSales = Transaksi::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->sum('totalharga');
            $previousWeekSales = Transaksi::whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->sum('totalharga');

            $weeklyTrendValue = $currentWeekSales - $previousWeekSales;
            $weeklyTrendPercent = $previousWeekSales > 0 ? ($weeklyTrendValue / $previousWeekSales) * 100 : ($currentWeekSales > 0 ? 100 : 0);

            $weeklySalesByDay = Transaksi::whereBetween('created_at', [$currentWeekStart, $currentWeekEnd])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->selectRaw('DATE(created_at) as date, SUM(totalharga) as total')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('total', 'date')
                ->toArray();

            $previousWeeklySalesByDay = Transaksi::whereBetween('created_at', [$previousWeekStart, $previousWeekEnd])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->selectRaw('DATE(created_at) as date, SUM(totalharga) as total')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('total', 'date')
                ->toArray();

            $dayIterator = $currentWeekStart->copy();
            while ($dayIterator->lte($currentWeekEnd)) {
                $weeklyTrendLabels[] = $dayIterator->translatedFormat('D, d M');
                $dateKey = $dayIterator->toDateString();
                $weeklyTrendCurrent[] = $weeklySalesByDay[$dateKey] ?? 0;
                $previousWeekDate = $dayIterator->copy()->subDays(7)->toDateString();
                $weeklyTrendPrevious[] = $previousWeeklySalesByDay[$previousWeekDate] ?? 0;
                $dayIterator->addDay();
            }

            $currentMonthStart = $endDate->copy()->startOfMonth();
            $currentMonthEnd = $endDate->copy()->endOfMonth();
            $previousMonthStart = $currentMonthStart->copy()->subMonth()->startOfMonth();
            $previousMonthEnd = $currentMonthStart->copy()->subMonth()->endOfMonth();

            $currentMonthSales = Transaksi::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->sum('totalharga');
            $previousMonthSales = Transaksi::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->sum('totalharga');

            $monthlyTrendValue = $currentMonthSales - $previousMonthSales;
            $monthlyTrendPercent = $previousMonthSales > 0 ? ($monthlyTrendValue / $previousMonthSales) * 100 : ($currentMonthSales > 0 ? 100 : 0);
            $monthlyComparisonValues = [$previousMonthSales, $currentMonthSales];

            $transaksiBelumBayar = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Transaksi::STATUS_BELUM_BAYAR)
                ->count();

            $pendapatanHarian = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->where('totalharga', '>', 0)
                ->selectRaw('DATE(created_at) as date, SUM(totalharga) as total')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('total', 'date')
                ->toArray();

            $penjualanHarian = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->pluck('total', 'date')
                ->toArray();

            $bestSellingByJenis = DetailTransaksi::join('menus', 'detail_transaksis.menu_id', '=', 'menus.id')
                ->join('jenis', 'menus.jenis_id', '=', 'jenis.id')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->where('transaksis.status', Transaksi::STATUS_LUNAS)
                ->whereBetween('transaksis.created_at', [$startDate, $endDate])
                ->groupBy('jenis.id', 'jenis.nama_jenis', 'menus.id', 'menus.nama')
                ->selectRaw('jenis.id as jenis_id, jenis.nama_jenis, menus.id as menu_id, menus.nama as menu_nama, SUM(detail_transaksis.jumlah) as total_jumlah, SUM(detail_transaksis.subtotal) as total_revenue')
                ->orderBy('jenis_id')
                ->orderByDesc('total_jumlah')
                ->get()
                ->groupBy('jenis_id')
                ->map(function ($items) {
                    return $items->first();
                })
                ->values();

            $bestSellingOverall = DetailTransaksi::join('menus', 'detail_transaksis.menu_id', '=', 'menus.id')
                ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
                ->where('transaksis.status', Transaksi::STATUS_LUNAS)
                ->whereBetween('transaksis.created_at', [$startDate, $endDate])
                ->groupBy('menus.id', 'menus.nama')
                ->selectRaw('menus.id as menu_id, menus.nama as menu_nama, SUM(detail_transaksis.jumlah) as total_jumlah')
                ->orderByDesc('total_jumlah')
                ->limit(3)
                ->get();

            $paymentMethodDistribution = Transaksi::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', Transaksi::STATUS_LUNAS)
                ->whereNotNull('metode_pembayaran')
                ->selectRaw('metode_pembayaran, COUNT(*) as count')
                ->groupBy('metode_pembayaran')
                ->orderByDesc('count')
                ->get();
        }

        return view('pages.dashboard.index', compact(
            'startDate',
            'endDate',
            'kasirTodayRevenue',
            'kasirTodayTransactions',
            'kasirTodayLunas',
            'kasirTodayBelumLunas',
            'kasirUnpaidTransactions',
            'totalTransaksi',
            'totalTransaksiLunas',
            'totalPendapatanLunas',
            'jumlahPesanan',
            'weeklyTrendValue',
            'weeklyTrendPercent',
            'currentWeekSales',
            'previousWeekSales',
            'monthlyTrendValue',
            'monthlyTrendPercent',
            'currentMonthSales',
            'previousMonthSales',
            'transaksiBelumBayar',
            'pendapatanHarian',
            'penjualanHarian',
            'bestSellingByJenis',
            'bestSellingOverall',
            'weeklyTrendLabels',
            'weeklyTrendCurrent',
            'weeklyTrendPrevious',
            'monthlyComparisonLabels',
            'monthlyComparisonValues',
            'paymentMethodDistribution'
        ));
    }

    private function getKasirUnpaidGroupTransaksis(Transaksi $transaksi, $transaksis = null)
    {
        $source = $transaksis ?? Transaksi::query()->where('status', '<>', Transaksi::STATUS_LUNAS)->orderByDesc('created_at')->get();

        if ($transaksi->nomeja === null) {
            return $source->filter(function ($item) use ($transaksi) {
                return $item->id === $transaksi->id
                    && $item->status === Transaksi::STATUS_BELUM_BAYAR;
            })->values();
        }

        return $source->filter(function ($item) use ($transaksi) {
            return $item->nomeja === $transaksi->nomeja
                && $item->status === Transaksi::STATUS_BELUM_BAYAR;
        })->values();
    }

    private function combineKasirTransactions($transaksis)
    {
        $baseTransaksi = $transaksis->first();
        $combinedDetails = collect();
        $totalHarga = 0;
        $latestCreatedAt = $baseTransaksi->created_at;

        foreach ($transaksis as $transaksi) {
            foreach ($transaksi->details as $detail) {
                $existingDetail = $combinedDetails->first(function ($item) use ($detail) {
                    return $item->menu_id === $detail->menu_id;
                });

                if ($existingDetail) {
                    $existingDetail->jumlah += $detail->jumlah;
                    $existingDetail->subtotal += $detail->subtotal;

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
                    $combinedDetails->push($detail);
                }
            }

            $totalHarga += $transaksi->totalharga;

            if ($transaksi->created_at > $latestCreatedAt) {
                $latestCreatedAt = $transaksi->created_at;
            }
        }

        $combinedTransaksi = new Transaksi();
        $combinedTransaksi->id = $baseTransaksi->id;
        $combinedTransaksi->kode = $baseTransaksi->kode;
        $combinedTransaksi->user_id = $baseTransaksi->user_id;
        $combinedTransaksi->nomeja = $baseTransaksi->nomeja;
        $combinedTransaksi->atasnama = $baseTransaksi->atasnama;
        $combinedTransaksi->totalharga = $totalHarga;
        $combinedTransaksi->status = $baseTransaksi->status;
        $combinedTransaksi->created_at = $latestCreatedAt;
        $combinedTransaksi->updated_at = $baseTransaksi->updated_at;
        $combinedTransaksi->setRelation('details', $combinedDetails);

        return $combinedTransaksi;
    }
}
