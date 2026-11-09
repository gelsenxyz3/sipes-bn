<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'terms' => ['accepted'],
        ], [
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Format penulisan email tidak valid (harus menyertakan karakter "@" dan domain yang benar).',
            'password.required' => 'Kolom password wajib diisi.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan terlebih dahulu.',
        ]);

        $cred = $request->only('email', 'password');

        if (Auth::attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login berhasil, selamat datang!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function registerStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Perubahan di sini: Data akun dibuat, tapi tidak langsung di-login-kan,
        // melainkan diarahkan ke halaman login dengan pesan sukses.
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun yang baru saja didaftarkan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
