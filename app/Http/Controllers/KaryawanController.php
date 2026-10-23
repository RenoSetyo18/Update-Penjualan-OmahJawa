<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'admin']);
    }

    public function index(Request $request)
    {
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 10;
        $karyawans = User::paginate($perPage)->withQueryString();
        return view('pages.master.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('pages.master.karyawan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,kasir,pelayan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function edit(User $karyawan)
    {
        return view('pages.master.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, User $karyawan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $karyawan->id,
            'role' => 'required|in:super_admin,admin,kasir,pelayan',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        \Log::info('Update karyawan', [
            'id' => $karyawan->id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password_filled' => $request->filled('password'),
        ]);

        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->role = $request->role;

        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
            \Log::info('Password updated for user ' . $karyawan->id);
        }

        $karyawan->save();

        \Log::info('Karyawan updated successfully', ['id' => $karyawan->id]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui');
    }

    public function destroy(User $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
