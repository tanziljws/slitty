<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input dari form (email atau username)
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $login = $request->input('email');
        $remember = $request->filled('remember');

        // Coba login sebagai User (web guard) hanya jika input berupa email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $login, 'password' => $request->password], $remember)) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboard');
            }
        }

        // Coba login sebagai Petugas (boleh email atau username)
        $petugasCredentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $request->password]
            : ['username' => $login, 'password' => $request->password];

        if (Auth::guard('petugas')->attempt($petugasCredentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal, tampilkan error
        return back()->withErrors([
            'email' => 'Email/username atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();  // Logout user
        $request->session()->invalidate();  // Hapus session
        $request->session()->regenerateToken();  // Regenerate token untuk keamanan
        return redirect('/login');  // Arahkan kembali ke halaman login
    }
}
