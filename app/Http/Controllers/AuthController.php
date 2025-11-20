<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Cek petugas guard dulu (prioritas lebih tinggi)
        if (Auth::guard('petugas')->check()) {
            \Log::info('showLogin: Petugas already logged in, redirecting to dashboard', [
                'petugas_id' => Auth::guard('petugas')->id(),
            ]);
            return redirect('/dashboard');
        }

        // Jika sudah login sebagai user biasa, arahkan ke beranda user
        if (Auth::check()) {
            \Log::info('showLogin: Web user already logged in, redirecting to homepage', [
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('user.dashboard');
        }
        
        // Generate captcha sederhana untuk login (dan register di halaman yang sama)
        $a = rand(1, 9);
        $b = rand(1, 9);
        $sum = $a + $b;
        session([
            'login_captcha_answer' => $sum,
            'register_captcha_answer' => $sum,
        ]);

        return view('auth.login', compact('a', 'b'));
    }

    public function login(Request $request)
    {
        // Validasi input dari form (email atau username)
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
            'captcha' => ['required', 'numeric'],
        ]);

        // Validasi captcha
        $expected = (int) session('login_captcha_answer');
        if ((int) $request->input('captcha') !== $expected) {
            return back()->withErrors([
                'captcha' => 'Jawaban captcha tidak sesuai.',
            ])->onlyInput('email');
        }

        $login = $request->input('email');
        $remember = $request->filled('remember');

        // Coba login sebagai User (web guard) hanya jika input berupa email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $login, 'password' => $request->password], $remember)) {
                $request->session()->regenerate();
                // User biasa diarahkan ke beranda user
                return redirect()->route('user.dashboard');
            }
        }

        // Coba login sebagai Petugas (boleh email atau username)
        $petugasCredentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $request->password]
            : ['username' => $login, 'password' => $request->password];

        if (Auth::guard('petugas')->attempt($petugasCredentials, $remember)) {
            // Get user setelah attempt berhasil
            $petugas = Auth::guard('petugas')->user();
            
            if ($petugas) {
                // CRITICAL FIX: Jangan regenerate session dulu, save dulu untuk memastikan guard tersimpan
                // Regenerate bisa menghapus guard, jadi kita save dulu, baru regenerate
                
                // Step 1: Save session dengan guard yang sudah authenticated
                $request->session()->save();
                
                // Step 2: Verify guard masih check sebelum regenerate
                $guardCheckBeforeRegenerate = Auth::guard('petugas')->check();
                \Log::info('Before regenerate', [
                    'petugas_id' => $petugas->id,
                    'guard_check' => $guardCheckBeforeRegenerate,
                    'session_id' => $request->session()->getId(),
                ]);
                
                // Step 3: Regenerate session (ini bisa menghapus guard, jadi kita handle dengan hati-hati)
                $oldSessionId = $request->session()->getId();
                $request->session()->regenerate();
                $newSessionId = $request->session()->getId();
                
                // Step 4: CRITICAL - Re-authenticate setelah regenerate karena regenerate bisa menghapus guard
                // Ini adalah workaround untuk masalah Laravel session regenerate dengan custom guards
                if (!Auth::guard('petugas')->check()) {
                    \Log::warning('Guard lost after regenerate (expected), re-authenticating', [
                        'petugas_id' => $petugas->id,
                        'old_session' => $oldSessionId,
                        'new_session' => $newSessionId,
                    ]);
                    
                    // Re-authenticate dengan explicit login
                    Auth::guard('petugas')->login($petugas, $remember);
                }
                
                // Step 5: Save lagi setelah re-authenticate
                $request->session()->save();
                
                // Step 6: Final verification
                $finalCheck = Auth::guard('petugas')->check();
                \Log::info('Petugas login successful - Final check', [
                    'id' => $petugas->id,
                    'email' => $petugas->email,
                    'username' => $petugas->username ?? 'N/A',
                    'old_session_id' => $oldSessionId,
                    'new_session_id' => $newSessionId,
                    'guard_check_before_regenerate' => $guardCheckBeforeRegenerate,
                    'guard_check_after_regenerate' => Auth::guard('petugas')->check(),
                    'final_guard_check' => $finalCheck,
                    'final_petugas_id' => Auth::guard('petugas')->id(),
                ]);
                
                if (!$finalCheck) {
                    \Log::error('CRITICAL: Guard check failed after all attempts', [
                        'petugas_id' => $petugas->id,
                    ]);
                    return back()->withErrors([
                        'email' => 'Terjadi kesalahan saat menyimpan session. Silakan coba lagi.',
                    ])->onlyInput('email');
                }
            }
            
            // Petugas/admin tetap diarahkan ke dashboard admin
            // Gunakan redirect langsung
            return redirect('/dashboard');
        }

        // Jika login gagal, tampilkan error
        return back()->withErrors([
            'email' => 'Email/username atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout semua guard yang relevan
        Auth::logout();  // guard default (web)
        if (Auth::guard('petugas')->check()) {
            Auth::guard('petugas')->logout();
        }
        
        // Invalidate session dan regenerate token
        try {
            $request->session()->invalidate();  // Hapus session
            $request->session()->regenerateToken();  // Regenerate token untuk keamanan
        } catch (\Exception $e) {
            // Jika session sudah expired atau invalid, tetap lanjutkan logout
            \Log::warning('Session invalidate error during logout: ' . $e->getMessage());
        }
        
        return redirect()->route('login')->with('message', 'Anda telah berhasil logout.');  // Arahkan kembali ke halaman login utama
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        // Generate captcha sederhana untuk register
        $a = rand(1, 9);
        $b = rand(1, 9);
        session(['register_captcha_answer' => $a + $b]);

        return view('auth.register', compact('a', 'b'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'captcha' => ['required', 'numeric'],
        ]);

        // Validasi captcha
        $expected = (int) session('register_captcha_answer');
        if ((int) $request->input('captcha') !== $expected) {
            return back()->withErrors([
                'captcha' => 'Jawaban captcha tidak sesuai.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Auto login setelah register
        Auth::login($user);
        $request->session()->regenerate();

        // Arahkan user baru ke beranda user, bukan dashboard admin
        return redirect()->route('user.dashboard');
    }
}
