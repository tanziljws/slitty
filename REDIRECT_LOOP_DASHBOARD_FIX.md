# Fix Redirect Loop di Dashboard

## 🔴 Masalah

Error: **"Load cannot follow more than 20 redirections"** saat akses `/dashboard` setelah login.

## 🔍 Analisis Loop

1. User login sebagai petugas → redirect ke `/dashboard` ✅
2. `/dashboard` menggunakan middleware `auth:petugas` ✅
3. Jika session tidak tersimpan/terkenali → middleware redirect ke `/login` ❌
4. `/login` menggunakan middleware `guest` (RedirectIfAuthenticated) ✅
5. RedirectIfAuthenticated cek apakah sudah login → jika ya, redirect ke `/dashboard` ✅
6. **LOOP**: `/dashboard` → `/login` → `/dashboard` → `/login` → ...

## 🐛 Root Cause

**Session tidak tersimpan atau tidak dikenali** setelah login sebagai petugas.

Kemungkinan penyebab:
1. Session driver tidak bekerja dengan benar di Railway
2. Guard `petugas` tidak menyimpan session dengan benar
3. Session configuration salah
4. Cookie/Session tidak tersimpan karena domain/secure cookie issue

## ✅ Solusi yang Sudah Diterapkan

### 1. Tambahkan Logging
- Log ketika petugas berhasil login
- Log ketika dashboard diakses tanpa authentication
- Ini membantu debug masalah session

### 2. Verifikasi Session di DashboardController
- Cek apakah user sudah login sebelum render dashboard
- Jika tidak, redirect ke login dengan jelas

## 🛠️ Langkah Troubleshooting

### Step 1: Cek Railway Logs
Setelah deploy, cek Railway logs untuk melihat:
- Apakah login berhasil?
- Apakah session tersimpan?
- Apakah middleware mengenali session?

### Step 2: Cek Session Configuration
Pastikan di Railway environment variables:
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### Step 3: Cek Browser Cookies
Setelah login, cek browser DevTools → Application → Cookies:
- Apakah `laravel-session` cookie ada?
- Apakah cookie domain benar?
- Apakah cookie secure?

### Step 4: Test dengan Browser Lain
Coba dengan Chrome/Firefox untuk memastikan bukan masalah Safari-specific.

## 🔧 Perbaikan Tambahan yang Mungkin Diperlukan

### 1. Pastikan Session Tersimpan Setelah Login
Jika masih loop, mungkin perlu:
- Explicitly save session setelah login
- Cek session driver configuration
- Pastikan session storage writable

### 2. Cek Guard Configuration
Pastikan guard `petugas` dikonfigurasi dengan benar di `config/auth.php`.

### 3. Clear Cache
Setelah fix, clear cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 📝 Testing

Setelah fix, test dengan:
1. Login dengan `adminK4@gmail.com`
2. Cek browser cookies (harus ada `laravel-session`)
3. Akses `/dashboard` (harus tampil, tidak loop)
4. Cek Railway logs untuk error/warning

## ⚠️ Catatan

- Error ini terjadi karena session tidak tersimpan atau tidak dikenali
- Perlu cek Railway logs untuk detail lebih lanjut
- Mungkin perlu adjust session configuration di Railway

