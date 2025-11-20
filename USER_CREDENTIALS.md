# Credentials User Biasa

## ✅ User Biasa BISA Login!

Setelah perbaikan, **user biasa tetap bisa login** dari halaman `/login` yang sama.

## 🔐 Credentials User Biasa

### 1. Administrator (User)
- **Email**: `admin@galeri-edu.com`
- **Password**: `admin123`
- **Redirect**: Homepage (`/` atau `user.dashboard`)

### 2. Siti Nuraeni (User)
- **Email**: `siti@galeri-edu.com`
- **Password**: `siti123`
- **Redirect**: Homepage (`/` atau `user.dashboard`)

### 3. Kepala Sekolah (User)
- **Email**: `kepsek@galeri-edu.com` atau `kepsek@gmail.com`
- **Password**: `kepsek123`
- **Redirect**: Homepage (`/` atau `user.dashboard`)

### 4. Test User
- **Email**: `test@example.com`
- **Password**: `password` (default dari factory)
- **Redirect**: Homepage (`/` atau `user.dashboard`)

## 🔄 Flow Login

1. **User buka** `/login`
2. **Masukkan** email dan password
3. **Jawab** captcha
4. **Backend cek**:
   - Coba login sebagai **Petugas** dulu (jika berhasil → redirect ke `/dashboard`)
   - Jika gagal, coba login sebagai **User** (jika berhasil → redirect ke `user.dashboard`)
   - Jika keduanya gagal → error message

## 📋 Perbedaan Admin vs User

### Admin/Petugas:
- Login dengan **email ATAU username**
- Redirect ke `/dashboard` (admin dashboard)
- Bisa akses semua fitur admin

### User Biasa:
- Login dengan **email saja** (tidak bisa pakai username)
- Redirect ke `user.dashboard` (homepage)
- Hanya bisa akses fitur publik (like/dislike foto, dll)

## 🧪 Test Login User

### Test dengan Browser:
1. Buka: `https://slitty-production.up.railway.app/login`
2. Masukkan:
   - **Email**: `admin@galeri-edu.com`
   - **Password**: `admin123`
   - **Captcha**: Jawab pertanyaan matematika
3. Harus redirect ke homepage (bukan dashboard admin)

### Test dengan Tinker:
```bash
php artisan tinker
>>> Auth::attempt(['email' => 'admin@galeri-edu.com', 'password' => 'admin123']);
# Result: true ✅
>>> Auth::user();
# Result: User object dengan email admin@galeri-edu.com
```

## ⚠️ Catatan Penting

1. **User biasa TIDAK bisa akses dashboard admin** (`/dashboard`)
   - Middleware `auth:petugas` akan memblokir
   - Redirect ke login jika mencoba akses

2. **Admin/Petugas TIDAK bisa login sebagai user**
   - Login admin akan redirect ke `/dashboard`
   - Tidak akan redirect ke homepage

3. **Email harus unique**
   - Satu email tidak bisa ada di tabel `users` dan `petugas` sekaligus
   - Atau jika ada, prioritas ke `petugas` dulu

## 📊 Daftar User di Database

Berdasarkan query database production:
- `admin@galeri-edu.com` - Administrator
- `siti@galeri-edu.com` - Siti Nuraeni
- `kepsek@galeri-edu.com` - Kepala Sekolah
- `test@example.com` - Test User
- `sitinuraenst7@gmail.com` - siti nuraeni
- `YUYUNINISA@gmail.com` - YUNISA
- `yuli167@gmail.com` - yulia
- `udindindun@gmail.com` - udin
- `adminK4@gmail.com` - Siti (⚠️ ini juga ada di tabel petugas!)
- `een@gmail.com` - een

## 🔒 Security

- ✅ User biasa tetap bisa login
- ✅ User biasa tidak bisa akses dashboard admin
- ✅ Admin tidak bisa login sebagai user (prioritas petugas)
- ✅ HTTPS required untuk login/register

