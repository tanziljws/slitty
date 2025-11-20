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
        // Jika sudah login sebagai petugas/admin, arahkan ke dashboard admin
        if (Auth::guard('petugas')->check()) {
            return redirect('/dashboard');
        }

        // Jika sudah login sebagai user biasa, arahkan ke beranda user
        if (Auth::check()) {
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
                // Regenerate session untuk security (setelah attempt berhasil)
                $oldSessionId = $request->session()->getId();
                $request->session()->regenerate();
                $newSessionId = $request->session()->getId();
                
                // Log untuk debugging
                \Log::info('Petugas login successful', [
                    'id' => $petugas->id,
                    'email' => $petugas->email,
                    'username' => $petugas->username ?? 'N/A',
                    'old_session_id' => $oldSessionId,
                    'new_session_id' => $newSessionId,
                    'guard_check_after_regenerate' => Auth::guard('petugas')->check(),
                ]);
                
                // CRITICAL: Pastikan guard masih authenticated setelah regenerate
                // Jika tidak, re-login dengan explicit login method
                if (!Auth::guard('petugas')->check()) {
                    \Log::warning('Guard lost after regenerate, re-authenticating', [
                        'petugas_id' => $petugas->id,
                    ]);
                    
                    // Re-authenticate dengan explicit login
                    Auth::guard('petugas')->login($petugas, $remember);
                    
                    // Verify lagi
                    if (!Auth::guard('petugas')->check()) {
                        \Log::error('Failed to re-authenticate petugas after regenerate', [
                            'petugas_id' => $petugas->id,
                        ]);
                        // Fallback: return error
                        return back()->withErrors([
                            'email' => 'Login berhasil tapi session tidak tersimpan. Silakan coba lagi.',
                        ])->onlyInput('email');
                    }
                }
                
                // Final save untuk memastikan session tersimpan
                $request->session()->save();
                
                // Final verification
                $finalCheck = Auth::guard('petugas')->check();
                \Log::info('Final authentication check', [
                    'guard_check' => $finalCheck,
                    'petugas_id' => Auth::guard('petugas')->id(),
                    'session_id' => $request->session()->getId(),
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
            // Gunakan redirect langsung dengan absolute URL untuk memastikan
            return redirect()->to('/dashboard');
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
