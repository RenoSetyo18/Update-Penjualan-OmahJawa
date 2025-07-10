<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Cocokkan password secara manual TANPA bcrypt
        if ($user && $request->password === $user->password) {
            Auth::login($user); // Login manual tanpa bcrypt
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if ($user->role === 'pelayan') {
                return redirect()->route('dashboard.index');
            } elseif ($user->role === 'kasir') {
                return redirect()->route('kasir.index');
            } elseif ($user->role === 'koki') {
                return redirect()->route('koki.index');
            } elseif ($user->role === 'admin') {
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
