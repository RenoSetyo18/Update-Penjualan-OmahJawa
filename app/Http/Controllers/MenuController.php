<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Jenis;
use App\Models\PpnSetting;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'kasir', 'pelayan']);
    }

    public function index()
    {
        $menus = Menu::query()
            ->with('jenis')
            ->where('active', 1)
            ->orderBy('nama')
            ->get();
        $jenisList = Jenis::query()->orderBy('nama_jenis')->get();
        $ppnSetting = PpnSetting::query()->findOrFail(1);

        return view('pages.menu.index', [
            'menus' => $menus,
            'jenisList' => $jenisList,
            'ppnRate' => $ppnSetting->is_enabled ? $ppnSetting->rate : 0,
            'ppnEnabled' => $ppnSetting->is_enabled,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $menu = Menu::create([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'note' => $request->note,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan',
                'id' => $menu->id,
                'nama' => $menu->nama,
                'harga' => $menu->harga,
                'note' => $menu->note,
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving menu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan menu.'
            ]);
        }
    }
}
