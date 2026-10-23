<?php

namespace App\Http\Controllers;

use App\Models\PpnSetting;
use Illuminate\Http\Request;

class PpnController extends Controller
{
    public function __construct()
    {
        $this->authorizeRoles(['super_admin', 'admin']);
    }

    public function index()
    {
        $setting = PpnSetting::query()->findOrFail(1);

        return view('pages.master.ppn.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'rate' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d{1,3}(\.\d{1,2})?$/'],
            'is_enabled' => ['required', 'boolean'],
        ]);

        PpnSetting::query()->findOrFail(1)->update([
            'rate' => $validated['rate'],
            'is_enabled' => $validated['is_enabled'],
        ]);

        return redirect()->route('master-ppn.index')->with('success', 'Pengaturan PPN berhasil diperbarui.');
    }
}
