<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'admin']);
    }

    public function index(Request $request)
    {
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 10;
        $jenis_list = Jenis::paginate($perPage)->withQueryString();
        return view('pages.master.jenis.index', compact('jenis_list'));
    }

    public function create()
    {
        return view('pages.master.jenis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis',
            'deskripsi' => 'nullable|string',
        ]);

        Jenis::create([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis menu berhasil ditambahkan');
    }

    public function edit(Jenis $jenis)
    {
        return view('pages.master.jenis.edit', compact('jenis'));
    }

    public function update(Request $request, Jenis $jenis)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,' . $jenis->id,
            'deskripsi' => 'nullable|string',
        ]);

        $jenis->update([
            'nama_jenis' => $request->nama_jenis,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis menu berhasil diperbarui');
    }

    public function destroy(Jenis $jenis)
    {
        $jenis->delete();
        return redirect()->route('jenis.index')->with('success', 'Jenis menu berhasil dihapus');
    }
}
