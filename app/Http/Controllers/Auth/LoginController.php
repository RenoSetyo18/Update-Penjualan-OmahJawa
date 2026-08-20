<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Ambil user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cocokkan password dengan hash (bcrypt) yang disimpan
        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            // Jika ada param tab_session, simpan mapping user per-tab pada session
            $tabSession = $request->query('tab_session')
                ?? $request->input('tab_session')
                ?? $request->header('X-Tab-Session');

            if ($tabSession) {
                $tabSession = \Illuminate\Support\Str::slug($tabSession, '_');

                $tabUsers = $request->session()->get('tab_users', []);
                $tabUsers[$tabSession] = $user->id;
                $request->session()->put('tab_users', $tabUsers);

                // Set user for current request only
                Auth::onceUsingId($user->id);

                // regenerate to avoid fixation
                $request->session()->regenerate();
            } else {
                // default global login behavior
                Auth::login($user);
                $request->session()->regenerate();
            }

            // Redirect berdasarkan role
            if ($user->role === 'pelayan') {
                return redirect()->route('menu.index');
            } elseif ($user->role === 'kasir') {
                return redirect()->route('dashboard.index');
            } elseif ($user->role === 'koki') {
                return redirect()->route('dashboard.index');
            } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                return redirect()->route('admin.index');
            } else {
                return redirect()->route('dashboard.index'); // Default
            }
        }

        // Jika gagal login
        return redirect()->route('login')->with('error-unauth', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        $tabSession = $request->query('tab_session')
            ?? $request->input('tab_session')
            ?? $request->header('X-Tab-Session');

        if ($tabSession) {
            $tabSession = \Illuminate\Support\Str::slug($tabSession, '_');
            $tabUsers = $request->session()->get('tab_users', []);

            if (isset($tabUsers[$tabSession])) {
                unset($tabUsers[$tabSession]);
                $request->session()->put('tab_users', $tabUsers);
            }

            // For the current request, clear the user instance
            if (Auth::check()) {
                Auth::logout();
            }

            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
