<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index()
    {
        $menus = DB::table('menus')->get();

        return view('pages.menu.index', [
            'menus' => $menus,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
        ]);

        try {
            Menu::create([
                'nama' => $request->nama,
                'harga' => $request->harga,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan',
                'nama' => $request->nama,
                'harga' => $request->harga
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
