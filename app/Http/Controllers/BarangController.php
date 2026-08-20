<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'admin']);
    }

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
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $path = null;
    if ($request->hasFile('gambar')) {
        $path = $request->file('gambar')->store('uploads/menu', 'public');
    }

    Menu::create([
        'nama' => $request->nama,
        'harga' => $request->harga,
        'gambar' => $path
    ]);

    return redirect()->route('barang.index')->with('success', 'Menu berhasil ditambahkan!');
}

    public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'harga' => 'required|numeric|min:0',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $menu = Menu::findOrFail($id);

    $path = $menu->gambar;
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama (jika ada)
        if ($menu->gambar && file_exists(storage_path('app/public/' . $menu->gambar))) {
            unlink(storage_path('app/public/' . $menu->gambar));
        }
        $path = $request->file('gambar')->store('uploads/menu', 'public');
    }

    $menu->update([
        'nama' => $request->nama,
        'harga' => $request->harga,
        'gambar' => $path
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
