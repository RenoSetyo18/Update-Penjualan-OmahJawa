<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'kasir', 'pelayan']);
    }

    public function index()
    {
        $menus = DB::table('menus')->where('active', 1)->get();

        return view('pages.menu.index', [
            'menus' => $menus,
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
