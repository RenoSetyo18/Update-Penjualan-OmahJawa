<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'admin']);
    }

    private function formatIndonesianMonth(string $yearMonth): string
    {
        [$year, $month] = explode('-', $yearMonth);

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return ($months[(int) $month] ?? $month) . ' ' . $year;
    }

    private function buildMonthlySalesSummary($transaksis)
    {
        return $transaksis
            ->groupBy(fn ($transaksi) => $transaksi->created_at->format('Y-m'))
            ->map(function ($items, $yearMonth) {
                $totalPenjualan = $items->sum('totalharga');
                $jumlahTransaksi = $items->count();

                return [
                    'periode' => $this->formatIndonesianMonth($yearMonth),
                    'jumlah_transaksi' => $jumlahTransaksi,
                    'total_penjualan' => $totalPenjualan,
                    'rata_rata' => $jumlahTransaksi > 0 ? $totalPenjualan / $jumlahTransaksi : 0,
                ];
            })
            ->values();
    }

    /**
     * Convert image file to base64 data URI
     */
    private function getImageBase64($imagePath)
    {
        $fullPath = public_path($imagePath);
        if (file_exists($fullPath)) {
            $imageData = file_get_contents($fullPath);
            $base64 = base64_encode($imageData);

            // Detect MIME type
            if (function_exists('mime_content_type')) {
                $mime = mime_content_type($fullPath);
            } else {
                // Fallback untuk PHP versi terbaru
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
        return redirect()->route('laporan.penjualan');
    }

    public function harian(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'sometimes|required|date_format:Y-m-d',
        ]);

        $tanggal = $validated['tanggal'] ?? Carbon::today()->toDateString();
        $transaksis = Transaksi::with('details.menu')
            ->where('status', Transaksi::STATUS_LUNAS)
            ->whereDate('paid_at', $tanggal)
            ->orderBy('paid_at')
            ->get();

        $totalPendapatan = $transaksis->sum('totalharga');

        return view('pages.laporan.harian', compact('transaksis', 'tanggal', 'totalPendapatan'));
    }

    public function transaksi(Request $request)
    {
        $query = Transaksi::query();

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transaksis = $query->get();

        return view('pages.laporan.transaksi', compact('transaksis'));
    }

    public function trenPenjualan(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->subDays(30)->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());

        $dailySales = Transaksi::selectRaw('DATE(created_at) as date, SUM(totalharga) as total')
            ->where('status', 'lunas')
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $totalSales = array_sum($dailySales);
        $averageSales = $dailySales ? ($totalSales / count($dailySales)) : 0;

        $periodLengthDays = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
        $previousStart = Carbon::parse($start)->subDays($periodLengthDays);
        $previousEnd = Carbon::parse($start)->subDay();

        $previousTotalSales = Transaksi::where('status', 'lunas')
            ->whereDate('created_at', '>=', $previousStart->toDateString())
            ->whereDate('created_at', '<=', $previousEnd->toDateString())
            ->sum('totalharga');

        $trendValue = $totalSales - $previousTotalSales;
        $trendPercent = $previousTotalSales > 0 ? ($trendValue / $previousTotalSales) * 100 : ($totalSales > 0 ? 100 : 0);

        return view('pages.laporan.tren-penjualan', compact(
            'dailySales',
            'totalSales',
            'averageSales',
            'previousTotalSales',
            'trendValue',
            'trendPercent',
            'start',
            'end'
        ));
    }

    public function perbandinganPeriode(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());

        $currentTotal = Transaksi::where('status', 'lunas')
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->sum('totalharga');

        $periodLengthDays = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
        $previousStart = Carbon::parse($start)->subDays($periodLengthDays);
        $previousEnd = Carbon::parse($start)->subDay();

        $previousTotal = Transaksi::where('status', 'lunas')
            ->whereDate('created_at', '>=', $previousStart->toDateString())
            ->whereDate('created_at', '<=', $previousEnd->toDateString())
            ->sum('totalharga');

        $differenceValue = $currentTotal - $previousTotal;
        $differencePercent = $previousTotal > 0 ? ($differenceValue / $previousTotal) * 100 : ($currentTotal > 0 ? 100 : 0);

        return view('pages.laporan.perbandingan-periode', compact(
            'start',
            'end',
            'currentTotal',
            'previousTotal',
            'differenceValue',
            'differencePercent',
            'previousStart',
            'previousEnd'
        ));
    }

    public function jamSibuk(Request $request)
    {
        $date = $request->get('date', Carbon::now()->toDateString());

        $hourlyData = Transaksi::selectRaw('HOUR(created_at) as hour, COUNT(*) as jumlah_transaksi, SUM(totalharga) as total_omzet')
            ->where('status', 'lunas')
            ->whereDate('created_at', $date)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour')
            ->toArray();

        $peakHour = collect($hourlyData)->sortByDesc('jumlah_transaksi')->first();

        return view('pages.laporan.jam-sibuk', compact('hourlyData', 'date', 'peakHour'));
    }

    public function cetak(Request $request)
{
    $query = Transaksi::query();

    if ($request->has('start_date') && $request->start_date) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->has('end_date') && $request->end_date) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    $transaksis = $query->get();

    return view('pages.laporan.cetak', compact('transaksis', 'request'));
}

    // Laporan Penjualan dengan filter tanggal
    public function penjualan(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 10;

        $query = Transaksi::where('status', Transaksi::STATUS_LUNAS);
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $summaryQuery = clone $query;
        $transaksis = $query->orderByDesc('created_at')->paginate($perPage)->appends($request->query());
        $totalPenjualan = $summaryQuery->sum('totalharga');
        $jumlahTransaksi = $summaryQuery->count();

        return view('pages.laporan.penjualan', compact('transaksis', 'totalPenjualan', 'jumlahTransaksi', 'start', 'end'));
    }

    // Laporan Menu Terlaris
    public function menuTerlaris(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 10;

        $query = DetailTransaksi::selectRaw('menu_id, SUM(jumlah) as total_jumlah, SUM(subtotal) as total_pendapatan')
            ->with(['menu.jenis'])
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->where('transaksis.status', Transaksi::STATUS_LUNAS)
            ->groupBy('menu_id')
            ->orderByDesc('total_jumlah');

        if ($request->filled('start_date')) {
            $query->whereDate('transaksis.created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaksis.created_at', '<=', $end);
        }

        $topMenus = $query->paginate($perPage)->appends($request->query());

        return view('pages.laporan.menu-terlaris', compact('topMenus', 'start', 'end'));
    }

    // Laporan Pendapatan per Meja
    public function pendapatanMeja(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());

        $query = Transaksi::selectRaw('nomeja, SUM(totalharga) as total_pendapatan, COUNT(*) as jumlah_transaksi')
            ->where('status', Transaksi::STATUS_LUNAS)
            ->groupBy('nomeja')
            ->orderByDesc('total_pendapatan');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $mejaPendapatan = $query->get();

        return view('pages.laporan.pendapatan-meja', compact('mejaPendapatan', 'start', 'end'));
    }

    public function metodePembayaran(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->subDays(30)->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());

        $query = Transaksi::whereNotNull('metode_pembayaran');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $metodeStats = $query->selectRaw('metode_pembayaran, COUNT(*) as jumlah_transaksi, SUM(totalharga) as total_pendapatan')
            ->groupBy('metode_pembayaran')
            ->orderBy('jumlah_transaksi', 'desc')
            ->get();

        $totalTransaksi = $metodeStats->sum('jumlah_transaksi');
        $totalPendapatan = $metodeStats->sum('total_pendapatan');

        return view('pages.laporan.metode-pembayaran', compact('metodeStats', 'start', 'end', 'totalTransaksi', 'totalPendapatan'));
    }

    // Method Cetak Laporan Penjualan (HTML print like Rekap Transaksi)
    public function cetakPenjualan(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());
        $namaPencetak = auth()->user()->name ?? auth()->user()->nama ?? 'Administrator';

        $query = Transaksi::where('status', Transaksi::STATUS_LUNAS);
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $summaryQuery = clone $query;
    $transaksis = $query->orderBy('created_at')->get();
        $totalPenjualan = $summaryQuery->sum('totalharga');
        $jumlahTransaksi = $summaryQuery->count();
    $rataRataPenjualan = $jumlahTransaksi > 0 ? $totalPenjualan / $jumlahTransaksi : 0;
    $jumlahTransaksiLunas = $summaryQuery->count();
    $monthlySummary = $this->buildMonthlySalesSummary($transaksis);
        $logoBase64 = $this->getImageBase64('dataset/dist/img/LogoOmahJawa.png');

        if ($request->query('download') === 'pdf') {
            if (!extension_loaded('gd')) {
                return redirect()->route('laporan.penjualan', [
                    'start_date' => $start,
                    'end_date' => $end,
                    'per_page' => $request->get('per_page', 10),
                ])->with('error', 'PHP GD extension diperlukan untuk download PDF. Silakan aktifkan ekstensi gd di php.ini.');
            }
            try {
                $fileName = 'laporan_penjualan_' . $start . '_sampai_' . $end . '.pdf';
                $pdf = Pdf::loadView('pages.laporan.pdf-penjualan', compact('monthlySummary', 'totalPenjualan', 'jumlahTransaksi', 'rataRataPenjualan', 'jumlahTransaksiLunas', 'start', 'end', 'logoBase64', 'namaPencetak'));
                return $pdf->download($fileName);
            } catch (\Exception $e) {
                return redirect()->route('laporan.penjualan', [
                    'start_date' => $start,
                    'end_date' => $end,
                    'per_page' => $request->get('per_page', 10),
                ])->with('error', 'Gagal generate PDF: ' . $e->getMessage());
            }
        }

        return view('pages.laporan.cetak-penjualan', compact('monthlySummary', 'totalPenjualan', 'jumlahTransaksi', 'rataRataPenjualan', 'jumlahTransaksiLunas', 'start', 'end', 'logoBase64', 'namaPencetak'));
    }

    // Method Cetak Laporan Menu Terlaris (PDF download or HTML print)
    public function cetakMenuTerlaris(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());
        $namaPencetak = auth()->user()->name ?? auth()->user()->nama ?? 'Administrator';

        $query = DetailTransaksi::selectRaw('menu_id, SUM(jumlah) as total_jumlah, SUM(subtotal) as total_pendapatan')
            ->with(['menu.jenis'])
            ->join('transaksis', 'detail_transaksis.transaksi_id', '=', 'transaksis.id')
            ->groupBy('menu_id')
            ->orderByDesc('total_jumlah');

        if ($request->filled('start_date')) {
            $query->whereDate('transaksis.created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaksis.created_at', '<=', $end);
        }

    $topMenus = $query->limit(5)->get();
    $totalMenuTerjual = $topMenus->sum('total_jumlah');
    $totalPendapatanMenu = $topMenus->sum('total_pendapatan');
    $menuTerlaris = $topMenus->first()?->menu?->nama ?? '-';
        $logoBase64 = $this->getImageBase64('dataset/dist/img/LogoOmahJawa.png');

        if ($request->query('download') === 'pdf') {
            if (!extension_loaded('gd')) {
                return redirect()->route('laporan.menu-terlaris', [
                    'start_date' => $start,
                    'end_date' => $end,
                ])->with('error', 'PHP GD extension diperlukan untuk download PDF. Silakan aktifkan ekstensi gd di php.ini.');
            }
            try {
                $fileName = 'laporan_menu_terlaris_' . $start . '_sampai_' . $end . '.pdf';
                $pdf = Pdf::loadView('pages.laporan.pdf-menu-terlaris', compact('topMenus', 'totalMenuTerjual', 'totalPendapatanMenu', 'menuTerlaris', 'start', 'end', 'logoBase64', 'namaPencetak'));
                return $pdf->download($fileName);
            } catch (\Exception $e) {
                return redirect()->route('laporan.menu-terlaris', [
                    'start_date' => $start,
                    'end_date' => $end,
                ])->with('error', 'Gagal generate PDF: ' . $e->getMessage());
            }
        }

        return view('pages.laporan.cetak-menu-terlaris', compact('topMenus', 'totalMenuTerjual', 'totalPendapatanMenu', 'menuTerlaris', 'start', 'end', 'namaPencetak'));
    }

    // Method Cetak Laporan Metode Pembayaran (PDF download or HTML print)
    public function cetakMetodePembayaran(Request $request)
    {
        $start = $request->get('start_date', Carbon::now()->subDays(30)->toDateString());
        $end = $request->get('end_date', Carbon::now()->toDateString());

        $query = Transaksi::whereNotNull('metode_pembayaran');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $metodeStats = $query->selectRaw('metode_pembayaran, COUNT(*) as jumlah_transaksi, SUM(totalharga) as total_pendapatan')
            ->groupBy('metode_pembayaran')
            ->orderBy('jumlah_transaksi', 'desc')
            ->get();

        $totalTransaksi = $metodeStats->sum('jumlah_transaksi');
        $totalPendapatan = $metodeStats->sum('total_pendapatan');
        $logoBase64 = $this->getImageBase64('dataset/dist/img/LogoOmahJawa.png');

        if ($request->query('download') === 'pdf') {
            if (!extension_loaded('gd')) {
                return redirect()->route('laporan.metode-pembayaran', [
                    'start_date' => $start,
                    'end_date' => $end,
                ])->with('error', 'PHP GD extension diperlukan untuk download PDF. Silakan aktifkan ekstensi gd di php.ini.');
            }
            try {
                $fileName = 'laporan_metode_pembayaran_' . $start . '_sampai_' . $end . '.pdf';
                $pdf = Pdf::loadView('pages.laporan.pdf-metode-pembayaran', compact('metodeStats', 'start', 'end', 'logoBase64', 'totalTransaksi', 'totalPendapatan'));
                return $pdf->download($fileName);
            } catch (\Exception $e) {
                return redirect()->route('laporan.metode-pembayaran', [
                    'start_date' => $start,
                    'end_date' => $end,
                ])->with('error', 'Gagal generate PDF: ' . $e->getMessage());
            }
        }

        return view('pages.laporan.cetak-metode-pembayaran', compact('metodeStats', 'start', 'end', 'totalTransaksi', 'totalPendapatan'));
    }

}
