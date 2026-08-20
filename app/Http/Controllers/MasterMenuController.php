<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Jenis;
use Illuminate\Http\Request;

class MasterMenuController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'admin']);
    }

    public function index(Request $request)
    {
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 5;

        $order = strtolower($request->input('order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $sort = $request->input('sort') === 'jenis' ? 'jenis' : null;

        $query = Menu::select('menus.*')->with('jenis');

        if ($sort === 'jenis') {
            $query = $query->leftJoin('jenis', 'menus.jenis_id', '=', 'jenis.id')
                           ->orderBy('jenis.nama_jenis', $order);
        } else {
            $query = $query->orderBy('menus.nama', 'asc');
        }

        $menus = $query->paginate($perPage)->appends($request->query());

        return view('pages.master.menu.index', compact('menus', 'sort', 'order'));
    }

    public function create()
    {
        $jenis_list = Jenis::all();
        return view('pages.master.menu.create', compact('jenis_list'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'jenis_id' => 'required|exists:jenis,id',
            'deskripsi' => 'nullable|string',
            'estimasi_waktu' => 'required|in:cepat,sedang,lama',
            'active' => 'required|boolean',
        ]);

        Menu::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'jenis_id' => $request->jenis_id,
            'deskripsi' => $request->deskripsi,
            'estimasi_waktu' => $request->estimasi_waktu,
            'active' => $request->active,
        ]);

        return redirect()->route('master-menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Menu $menu)
    {
        $jenis_list = Jenis::all();
        return view('pages.master.menu.edit', compact('menu', 'jenis_list'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'jenis_id' => 'required|exists:jenis,id',
            'deskripsi' => 'nullable|string',
            'estimasi_waktu' => 'required|in:cepat,sedang,lama',
            'active' => 'required|boolean',
        ]);

        $menu->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'jenis_id' => $request->jenis_id,
            'deskripsi' => $request->deskripsi,
            'estimasi_waktu' => $request->estimasi_waktu,
            'active' => $request->active,
        ]);

        return redirect()->route('master-menu.index')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('master-menu.index')->with('success', 'Menu berhasil dihapus');
    }
}
