<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('pages.barang.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $menu = Menu::create([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'nama' => $request->nama,
            'harga' => $request->harga
        ]);

        return redirect()->route('barang.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
{
    $menu = Menu::findOrFail($id);
    $menu->delete();

    return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus']);
}


}
