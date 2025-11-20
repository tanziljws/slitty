# Dashboard Access Troubleshooting

## 🔴 Masalah: Tidak Bisa Akses Dashboard Setelah Login

## 🔍 Debugging Steps

### 1. Cek Railway Logs

Setelah login, cek Railway logs untuk melihat:
- Apakah login berhasil? (cari "Petugas login successful")
- Apakah session tersimpan? (cek session_id)
- Apakah guard check berhasil? (cek guard_check: true/false)
- Apakah ada error saat akses dashboard? (cari "Dashboard accessed without petugas authentication")

**Cara cek logs:**
1. Buka Railway Dashboard
2. Pilih project
3. Klik "View Logs" atau "Deployments" → pilih deployment terbaru → "View Logs"

### 2. Cek Browser Cookies

Setelah login, cek browser DevTools:
1. Buka DevTools (F12)
2. Tab "Application" → "Cookies"
3. Cek apakah ada cookie `laravel-session`
4. Cek domain cookie (harus sesuai dengan domain Railway)
5. Cek apakah cookie "Secure" dan "SameSite" sesuai

**Expected:**
- Cookie name: `laravel-session`
- Domain: `.up.railway.app` atau `slitty-production.up.railway.app`
- Secure: true (karena HTTPS)
- SameSite: Lax

### 3. Cek Session Configuration di Railway

Pastikan environment variables di Railway sudah benar:

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

**Cara cek:**
1. Railway Dashboard → Project → Variables
2. Cek apakah semua variable di atas sudah ada dan benar

### 4. Test Manual dengan Browser

1. **Clear semua cookies dan cache**
2. **Buka halaman login**: `https://slitty-production.up.railway.app/login`
3. **Login dengan credentials**:
   - Email: `adminK4@gmail.com`
   - Password: (password yang benar)
   - Captcha: (jawab pertanyaan)
4. **Setelah login, cek Network tab**:
   - Lihat response dari POST `/login`
   - Cek apakah redirect ke `/dashboard` (status 302)
   - Cek apakah ada Set-Cookie header
5. **Cek apakah redirect ke `/dashboard`**:
   - Lihat response dari GET `/dashboard`
   - Jika status 302 dan redirect ke `/login`, berarti session tidak tersimpan

### 5. Cek Guard Configuration

Pastikan guard `petugas` dikonfigurasi dengan benar:

**File**: `config/auth.php`

```php
'guards' => [
    'petugas' => [
        'driver' => 'session',
        'provider' => 'petugas',
    ],
],

'providers' => [
    'petugas' => [
        'driver' => 'eloquent',
        'model' => App\Models\Petugas::class,
    ],
],
```

## 🛠️ Solusi yang Sudah Diterapkan

### 1. Re-login Fallback
Jika session tidak tersimpan setelah `regenerate()`, akan re-login secara explicit.

### 2. Better Logging
Logging detail untuk tracking:
- Login success dengan session_id
- Guard check status
- Dashboard access attempts

### 3. Double Check
Setelah session save, double check apakah guard masih check.

## 🔧 Jika Masih Tidak Bisa

### Option 1: Cek Session Storage

Pastikan session storage writable di Railway:
- Jika `SESSION_DRIVER=file`, pastikan folder `storage/framework/sessions` writable
- Jika `SESSION_DRIVER=database`, pastikan tabel `sessions` ada

### Option 2: Test dengan Session Driver Database

Coba ubah session driver ke database:

1. **Di Railway Variables**, ubah:
   ```env
   SESSION_DRIVER=database
   ```

2. **Pastikan migration sudah jalan**:
   ```bash
   php artisan migrate
   ```

3. **Test lagi login**

### Option 3: Cek Cookie Domain

Mungkin ada masalah dengan cookie domain. Cek di `.env` atau Railway Variables:

```env
SESSION_DOMAIN=null
```

Atau coba set ke domain Railway:
```env
SESSION_DOMAIN=.up.railway.app
```

### Option 4: Clear All Cache

Setelah perubahan, clear cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📝 Testing Checklist

Setelah fix, test dengan:

- [ ] Login dengan `adminK4@gmail.com` berhasil
- [ ] Setelah login, redirect ke `/dashboard` (tidak loop)
- [ ] Dashboard tampil dengan data
- [ ] Cookie `laravel-session` ada di browser
- [ ] Railway logs menunjukkan "Petugas login successful"
- [ ] Railway logs menunjukkan "Dashboard accessed successfully"

## 🚨 Jika Masih Error

1. **Cek Railway logs** untuk error detail
2. **Cek browser console** untuk JavaScript errors
3. **Cek Network tab** untuk melihat redirect chain
4. **Test dengan browser lain** (Chrome/Firefox)
5. **Test dengan incognito/private mode**

## 📞 Informasi untuk Debug

Jika masih tidak bisa, siapkan informasi berikut:
1. Railway logs (setelah login attempt)
2. Browser console errors
3. Network tab screenshot (redirect chain)
4. Cookie details dari browser DevTools
5. Environment variables dari Railway (tanpa password)

