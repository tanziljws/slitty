# Fix: Admin Login Hanya dari Tabel Petugas

## ✅ Perubahan yang Diterapkan

### Masalah
- Sebelumnya, login mencoba `users` dulu, baru `petugas`
- Tabel `users` tidak punya validasi role
- Admin bisa login dari `users` yang seharusnya hanya untuk user biasa

### Solusi
**Login sekarang prioritas `petugas` dulu** untuk memastikan admin hanya dari tabel `petugas`.

## 🔄 Flow Login Baru

### 1. Prioritas Login Petugas (Admin)
```php
// Coba login sebagai Petugas DULU (untuk admin)
$petugasCredentials = filter_var($login, FILTER_VALIDATE_EMAIL)
    ? ['email' => $login, 'password' => $request->password]
    : ['username' => $login, 'password' => $request->password];

if (Auth::guard('petugas')->attempt($petugasCredentials, $remember)) {
    // Login berhasil → redirect ke /dashboard
    return redirect('/dashboard');
}
```

### 2. Fallback Login User (Hanya jika Petugas Gagal)
```php
// Jika login sebagai Petugas gagal, coba login sebagai User
// User biasa tidak bisa akses dashboard admin
if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
    if (Auth::attempt(['email' => $login, 'password' => $request->password], $remember)) {
        // Login berhasil → redirect ke user.dashboard (bukan /dashboard)
        return redirect()->route('user.dashboard');
    }
}
```

## 🔒 Security

### Route Protection
- **Dashboard Admin** (`/dashboard`): 
  - Middleware: `auth:petugas`
  - Hanya bisa diakses oleh user yang login dari tabel `petugas`
  
- **User Dashboard** (`user.dashboard`):
  - Middleware: `auth` (web guard)
  - Bisa diakses oleh user yang login dari tabel `users`

### Validasi
- ✅ Admin login **hanya dari tabel `petugas`**
- ✅ User biasa login dari tabel `users` (tidak bisa akses dashboard admin)
- ✅ Petugas bisa login dengan **email atau username**
- ✅ User hanya bisa login dengan **email**

## 📋 Credentials

### Admin (Petugas)
- **Email**: `adminK4@gmail.com`
- **Username**: `admin`
- **Password**: `admin123`
- **Redirect**: `/dashboard` (admin dashboard)

### User Biasa
- **Email**: `admin@galeri-edu.com`
- **Password**: `admin123`
- **Redirect**: `user.dashboard` (homepage)

## 🧪 Testing

### Test 1: Login Admin dengan Email
```
Input: adminK4@gmail.com / admin123
Expected: Login sebagai petugas → redirect ke /dashboard
```

### Test 2: Login Admin dengan Username
```
Input: admin / admin123
Expected: Login sebagai petugas → redirect ke /dashboard
```

### Test 3: Login User Biasa
```
Input: admin@galeri-edu.com / admin123
Expected: Login sebagai user → redirect ke user.dashboard (bukan /dashboard)
```

### Test 4: Akses Dashboard Admin sebagai User
```
Scenario: User biasa login, coba akses /dashboard
Expected: Redirect ke login (karena middleware auth:petugas)
```

## 📝 Perubahan File

### `app/Http/Controllers/AuthController.php`
- ✅ Prioritas login `petugas` dulu
- ✅ Fallback ke `users` hanya jika `petugas` gagal
- ✅ User biasa tidak bisa akses dashboard admin

## ⚠️ Catatan Penting

1. **Tabel `users` tidak punya role field** - jadi tidak bisa membedakan admin/user
2. **Solusi**: Admin hanya dari tabel `petugas`, user dari tabel `users`
3. **Middleware `auth:petugas`** memastikan hanya petugas yang bisa akses dashboard admin
4. **User biasa** tetap bisa login untuk akses fitur publik (like/dislike foto, dll)

## 🚀 Deployment

Perubahan sudah di-push dan siap untuk deployment. Pastikan:
- ✅ Database sudah ada data di tabel `petugas`
- ✅ Middleware `auth:petugas` aktif di route dashboard
- ✅ Session driver dikonfigurasi dengan benar

