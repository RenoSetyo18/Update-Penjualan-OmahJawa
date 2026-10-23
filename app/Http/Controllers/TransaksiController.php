<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\PpnSetting;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'pelayan', 'kasir']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomeja' => 'nullable|integer|min:1|max:999',
            'atasnama' => 'required|string|min:2|max:255',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string|max:255',
            'bayar' => 'sometimes|boolean',
            'jumlah_bayar' => 'required_if:bayar,1|numeric|min:0.01',
            'metode_pembayaran' => 'required_if:bayar,1|in:Cash,Dana,BCA,BRI,Qris',
        ]);

        $transaksi = DB::transaction(function () use ($validated, $request) {
            $ppnSetting = PpnSetting::query()->whereKey(1)->lockForUpdate()->firstOrFail();
            $ppnRate = $ppnSetting->is_enabled ? (float) $ppnSetting->rate : 0;
            $menus = Menu::whereIn(
                'id',
                collect($validated['items'])->pluck('menu_id')->unique()
            )->get()->keyBy('id');

            $items = collect($validated['items'])->map(function (array $item) use ($menus) {
                $menu = $menus->get((int) $item['menu_id']);
                $harga = (float) $menu->harga;

                return [
                    'menu_id' => $menu->id,
                    'jumlah' => $item['jumlah'],
                    'harga' => $harga,
                    'subtotal' => $harga * $item['jumlah'],
                    'note' => $item['note'] ?? null,
                ];
            });

            $subtotal = round($items->sum('subtotal'), 2);
            $ppnAmount = round($subtotal * $ppnRate / 100, 2);
            $totalHarga = $subtotal + $ppnAmount;
            $isPaid = (bool) ($validated['bayar'] ?? false);

            if ($isPaid && (float) $validated['jumlah_bayar'] < $totalHarga) {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Jumlah bayar kurang dari total harga pesanan.',
                ]);
            }

            $transaksi = Transaksi::create([
                'kode' => 'TRX-' . Str::upper(Str::random(8)),
                'user_id' => auth()->id(),
                'nomeja' => $validated['nomeja'] ?? null,
                'atasnama' => $validated['atasnama'],
                'totalharga' => $totalHarga,
                'ppn_rate' => $ppnRate,
                'ppn_amount' => $ppnAmount,
                'status' => $isPaid ? Transaksi::STATUS_LUNAS : Transaksi::STATUS_BELUM_BAYAR,
                'jumlah_bayar' => $isPaid ? $validated['jumlah_bayar'] : null,
                'metode_pembayaran' => $isPaid ? $validated['metode_pembayaran'] : null,
                'paid_at' => $isPaid ? now() : null,
                ...($isPaid && Schema::hasColumn('transaksis', 'kasir_id') && in_array($request->user()->role, ['super_admin', 'kasir'], true)
                    ? ['kasir_id' => $request->user()->id]
                    : []),
            ]);

            foreach ($items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    ...$item,
                ]);
            }

            return $transaksi;
        });

        return response()->json([
            'success' => true,
            'message' => ($validated['bayar'] ?? false)
                ? 'Pembayaran berhasil. Transaksi telah masuk ke tab Lunas.'
                : 'Pesanan berhasil disimpan dan masuk ke daftar Kasir.',
            'receiptUrl' => route('transaksi.cetak.struk', [
                'id' => $transaksi->id,
                'print' => 1,
            ]),
            'receiptDataUrl' => route('transaksi.struk.data', $transaksi->id),
            'redirect' => route('menu.index'),
        ]);
    }

    public function cetakStruk(Request $request, int $id)
    {
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);

        if (auth()->user()->role === 'pelayan' && $transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $jumlah_bayar = $transaksi->status === Transaksi::STATUS_LUNAS
            ? (float) ($transaksi->jumlah_bayar ?? $transaksi->totalharga)
            : 0;
        $kembalian = max(0, $jumlah_bayar - (float) $transaksi->totalharga);

        return view('pages.kasir.cetak_struk', compact('transaksi', 'jumlah_bayar', 'kembalian'));
    }

    public function strukData(int $id)
    {
        $transaksi = Transaksi::with('details.menu')->findOrFail($id);

        if (auth()->user()->role === 'pelayan' && $transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $isPaid = $transaksi->status === Transaksi::STATUS_LUNAS;
        $ppnAmount = (float) ($transaksi->ppn_amount ?? 0);
        $paidAmount = $isPaid
            ? (float) ($transaksi->jumlah_bayar ?? $transaksi->totalharga)
            : 0;
        $paidAt = $isPaid
            ? ($transaksi->paid_at ?? $transaksi->created_at)
            : $transaksi->created_at;

        return response()->json([
            'success' => true,
            'receipt' => [
                'transaction_code' => $transaksi->kode,
                'table_number' => $transaksi->nomeja,
                'customer_name' => $transaksi->atasnama,
                'cashier_name' => 'Andik Satriadi',
                'paid_at' => $paidAt
                    ? \Illuminate\Support\Carbon::parse($paidAt)->format('d-m-Y H:i')
                    : null,
                'status' => $transaksi->status,
                'status_label' => Transaksi::kasirStatusLabel($transaksi->status),
                'payment_method' => $isPaid ? $transaksi->metode_pembayaran : null,
                'subtotal' => (float) $transaksi->totalharga - $ppnAmount,
                'ppn_rate' => $transaksi->ppn_rate !== null ? (float) $transaksi->ppn_rate : null,
                'ppn_amount' => $ppnAmount,
                'items' => $transaksi->details->map(function ($detail) {
                    return [
                        'name' => $detail->menu?->nama ?? 'Menu dihapus',
                        'quantity' => (int) $detail->jumlah,
                        'price' => (float) $detail->harga,
                        'subtotal' => (float) $detail->subtotal,
                        'note' => $detail->note,
                    ];
                })->values()->all(),
                'total' => (float) $transaksi->totalharga,
                'paid' => $paidAmount,
                'change' => $isPaid ? max(0, $paidAmount - (float) $transaksi->totalharga) : 0,
            ],
        ]);
    }

    public function getTransactionByTable(Request $request)
    {
        $validated = $request->validate([
            'nomeja' => 'required|integer|min:1|max:999',
        ]);

        $transaksi = Transaksi::where('nomeja', $validated['nomeja'])
            ->where('status', Transaksi::STATUS_BELUM_BAYAR)
            ->latest()
            ->first();

        return response()->json([
            'found' => $transaksi !== null,
            'atasnama' => $transaksi?->atasnama,
            'message' => $transaksi
                ? 'Data nama ditemukan dari pesanan sebelumnya'
                : 'Nomor meja belum memiliki pesanan yang belum lunas',
        ]);
    }
}
