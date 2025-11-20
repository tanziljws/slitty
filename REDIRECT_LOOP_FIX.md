# Fix Redirect Loop (ERR_TOO_MANY_REDIRECTS)

## Masalah yang Ditemukan

Error "ERR_TOO_MANY_REDIRECTS" disebabkan oleh beberapa masalah di middleware:

### 1. AuthPetugas Middleware Salah
**File**: `app/Http/Middleware/AuthPetugas.php`

**Masalah**: 
- Menggunakan `Session::has('petugas_id')` yang tidak pernah di-set oleh Laravel auth
- Laravel auth tidak menyimpan session dengan cara ini

**Solusi**:
- Ganti dengan `Auth::guard('petugas')->check()` yang merupakan cara Laravel untuk cek authentication

### 2. RedirectIfAuthenticated Middleware Tidak Handle Multiple Guards
**File**: `app/Http/Middleware/RedirectIfAuthenticated.php`

**Masalah**:
- Redirect semua guard ke `/dashboard` 
- User biasa yang login akan di-redirect ke `/dashboard` yang memerlukan `auth:petugas`
- Ini menyebabkan loop: user login → redirect ke `/dashboard` → tidak authenticated → redirect ke `/login` → sudah login → redirect ke `/dashboard` → LOOP

**Solusi**:
- Handle multiple guards dengan benar
- Jika login sebagai `petugas` → redirect ke `/dashboard`
- Jika login sebagai `web` (user biasa) → redirect ke `user.dashboard`

### 3. Bootstrap App Configuration
**File**: `bootstrap/app.php`

**Perubahan**:
- Hapus alias `auth.petugas` yang tidak diperlukan
- `auth:petugas` di route akan otomatis menggunakan built-in Laravel auth middleware dengan guard `petugas`

## File yang Diperbaiki

1. ✅ `app/Http/Middleware/AuthPetugas.php`
   - Ganti `Session::has('petugas_id')` dengan `Auth::guard('petugas')->check()`

2. ✅ `app/Http/Middleware/RedirectIfAuthenticated.php`
   - Handle multiple guards dengan benar
   - Redirect berdasarkan guard yang digunakan

3. ✅ `bootstrap/app.php`
   - Hapus alias `auth.petugas` yang tidak diperlukan
   - Tambahkan komentar untuk dokumentasi

4. ✅ `app/Http/Middleware/Authenticate.php`
   - Pastikan redirect ke login page dengan benar

## Testing

Setelah fix, test dengan:

1. **Test Login Page**:
   - Buka `/login` saat belum login → harus tampil form login
   - Buka `/login` saat sudah login sebagai user → harus redirect ke homepage
   - Buka `/login` saat sudah login sebagai petugas → harus redirect ke dashboard

2. **Test Dashboard**:
   - Buka `/dashboard` saat belum login → harus redirect ke `/login`
   - Buka `/dashboard` saat sudah login sebagai petugas → harus tampil dashboard
   - Buka `/dashboard` saat sudah login sebagai user biasa → harus redirect ke `/login` (karena tidak punya akses)

3. **Test Homepage**:
   - Buka `/` saat belum login → harus tampil homepage
   - Buka `/` saat sudah login sebagai user → harus tampil homepage
   - Buka `/` saat sudah login sebagai petugas → harus tampil homepage

## Cara Kerja Setelah Fix

### Flow Login Petugas:
1. User buka `/login` → middleware `guest` cek → belum login → tampil form
2. User submit login → AuthController cek credentials → berhasil → set session guard `petugas`
3. Redirect ke `/dashboard` → middleware `auth:petugas` cek → sudah login → tampil dashboard

### Flow Login User Biasa:
1. User buka `/login` → middleware `guest` cek → belum login → tampil form
2. User submit login → AuthController cek credentials → berhasil → set session guard `web`
3. Redirect ke `user.dashboard` (homepage) → tidak perlu auth → tampil homepage

### Flow Akses Dashboard Tanpa Login:
1. User buka `/dashboard` → middleware `auth:petugas` cek → belum login → redirect ke `/login`
2. User buka `/login` → middleware `guest` cek → belum login → tampil form

### Flow Akses Login Saat Sudah Login:
1. User (sudah login sebagai petugas) buka `/login` → middleware `guest` cek → sudah login → redirect ke `/dashboard`
2. User (sudah login sebagai user biasa) buka `/login` → middleware `guest` cek → sudah login → redirect ke `user.dashboard`

## Catatan Penting

- `auth:petugas` di route menggunakan built-in Laravel auth middleware dengan guard `petugas`
- `guest` middleware menggunakan `RedirectIfAuthenticated` yang sudah diperbaiki
- Pastikan guard `petugas` sudah dikonfigurasi dengan benar di `config/auth.php`
- Session driver harus dikonfigurasi dengan benar di `.env`

## Jika Masih Ada Masalah

1. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan cache:clear
   ```

2. **Cek session driver**:
   - Pastikan `SESSION_DRIVER` di `.env` sesuai (file, database, atau redis)
   - Pastikan session bisa disimpan dengan benar

3. **Cek guard configuration**:
   - Pastikan guard `petugas` ada di `config/auth.php`
   - Pastikan provider `petugas` ada di `config/auth.php`

4. **Test di browser**:
   - Clear cookies dan cache browser
   - Test di incognito/private mode
   - Cek Network tab di DevTools untuk melihat redirect chain

