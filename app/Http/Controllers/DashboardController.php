<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use App\Models\Kategori;
use App\Models\Petugas;
use App\Models\Page;
use App\Models\User;
use App\Models\Agenda;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Debug: Cek apakah user sudah login sebagai petugas
        $isAuthenticated = \Illuminate\Support\Facades\Auth::guard('petugas')->check();
        $petugas = \Illuminate\Support\Facades\Auth::guard('petugas')->user();
        
        if (!$isAuthenticated) {
            \Log::warning('Dashboard accessed without petugas authentication', [
                'petugas_user' => $petugas ? $petugas->toArray() : null,
                'web_user' => \Illuminate\Support\Facades\Auth::user(),
                'session_id' => request()->session()->getId(),
                'all_guards' => [
                    'petugas_check' => \Illuminate\Support\Facades\Auth::guard('petugas')->check(),
                    'web_check' => \Illuminate\Support\Facades\Auth::check(),
                ],
            ]);
            // Redirect ke login jika tidak authenticated
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Log untuk debugging (hapus di production jika tidak perlu)
        \Log::info('Dashboard accessed successfully', [
            'petugas_id' => $petugas->id ?? null,
            'email' => $petugas->email ?? null,
        ]);
        // Get real statistics
        $totalGaleri = Galery::count();
        $galeriAktif = Galery::where('status', 'aktif')->count();
        $totalKategori = Kategori::count();
        $totalPetugas = Petugas::count();
        $totalPages = Page::count();
        $totalUsers = User::count();
        
        // Get recent galeri (last 5) - order by posts created_at
        $recentGaleri = Galery::with(['post.kategori', 'fotos'])
            ->join('posts', 'galery.post_id', '=', 'posts.id')
            ->orderBy('posts.created_at', 'desc')
            ->select('galery.*')
            ->limit(5)
            ->get();
        
        // Get galeri pending approval (if any) - order by posts created_at
        $pendingGaleri = Galery::where('galery.status', 'nonaktif')
            ->with(['post.kategori', 'fotos'])
            ->join('posts', 'galery.post_id', '=', 'posts.id')
            ->orderBy('posts.created_at', 'desc')
            ->select('galery.*')
            ->limit(5)
            ->get();
        
        // Get recent agenda (last 5) - order by created_at
        $recentAgenda = Agenda::where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent activities (last 10)
        $recentActivities = collect();
        
        // Add recent galeri as activities
        foreach ($recentGaleri as $galeri) {
            $recentActivities->push([
                'type' => 'galeri',
                'title' => $galeri->post->judul ?? 'Galeri tanpa judul',
                'description' => 'Galeri baru ditambahkan',
                'time' => $galeri->post->created_at ?? now(),
                'icon' => 'fas fa-images',
                'color' => 'text-blue-600'
            ]);
        }
        
        // Sort by time
        $recentActivities = $recentActivities->sortByDesc('time')->take(10);
        
        // Calculate growth (mock data for now)
        $galeriGrowth = $totalGaleri > 0 ? '+12%' : '0%';
        $visitorGrowth = '+5%';
        $categoryGrowth = '+8%';
        $pageGrowth = '+3%';
        
        return view('dashboard', compact(
            'totalGaleri',
            'galeriAktif',
            'totalKategori',
            'totalPetugas',
            'totalPages',
            'totalUsers',
            'recentGaleri',
            'pendingGaleri',
            'recentAgenda',
            'recentActivities',
            'galeriGrowth',
            'visitorGrowth',
            'categoryGrowth',
            'pageGrowth'
        ));
    }
}