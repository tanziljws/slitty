# Informasi Login

## ✅ Login Page SAMA untuk Admin dan User

**Ya, login page-nya sama!** Admin dan user menggunakan **1 halaman login yang sama** di `/login`.

### Perbedaan di Backend:

1. **Admin/Petugas** (Login ke Dashboard):
   - Bisa login dengan **username** atau **email**
   - Contoh: `admin` atau `admin@gmail.com`
   - Password: `admin123`
   - Setelah login → redirect ke `/dashboard`

2. **User Biasa** (Login ke Homepage):
   - Hanya bisa login dengan **email**
   - Contoh: `admin@galeri-edu.com`
   - Password: `admin123`
   - Setelah login → redirect ke `/` (homepage)

## 🔐 Credentials

### Petugas/Admin:
- **Username**: `admin`
- **Email**: `admin@gmail.com`
- **Password**: `admin123`

### User Biasa:
- **Email**: `admin@galeri-edu.com`
- **Password**: `admin123`

## ⚠️ Catatan Penting

1. **Captcha**: Login memerlukan captcha matematika (contoh: 5 + 3 = 8)
2. **Session**: Pastikan session driver dikonfigurasi dengan benar
3. **Database**: Pastikan sudah di-seed dengan `php artisan db:seed`

## 🐛 Troubleshooting Login

### Jika login tidak bisa:

1. **Cek captcha**:
   - Pastikan jawaban captcha benar
   - Captcha di-generate dari session, jadi pastikan session berfungsi

2. **Cek database**:
   ```bash
   php artisan db:seed --class=PetugasSeeder
   ```

3. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

4. **Cek session**:
   - Pastikan `SESSION_DRIVER=file` di `.env`
   - Pastikan folder `storage/framework/sessions` writable

5. **Cek error**:
   - Buka browser DevTools → Network tab
   - Lihat response dari POST `/login`
   - Cek error message yang muncul

## 📝 Flow Login

1. User buka `/login`
2. Masukkan email/username dan password
3. Jawab captcha (contoh: 5 + 3 = 8)
4. Submit form
5. Backend cek:
   - Jika email → coba login sebagai User (web guard)
   - Jika berhasil → redirect ke homepage
   - Jika gagal → coba login sebagai Petugas (petugas guard)
   - Jika berhasil → redirect ke dashboard
   - Jika semua gagal → tampilkan error

