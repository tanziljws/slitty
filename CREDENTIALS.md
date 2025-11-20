# Credentials untuk Testing

## 🔐 Default Credentials

### Petugas/Admin (Login ke Dashboard Admin)

**Admin:**
- **Username**: `admin`
- **Email**: `admin@gmail.com`
- **Password**: `admin123`

**Staff:**
- **Username**: `staff`
- **Email**: `siti@gmail.com`
- **Password**: `staff123`

### User Biasa (Login ke Homepage)

**Admin User:**
- **Email**: `admin@galeri-edu.com`
- **Password**: `admin123`

**Staff User:**
- **Email**: `siti@galeri-edu.com`
- **Password**: `siti123`

**Kepala Sekolah:**
- **Email**: `kepsek@gmail.com`
- **Password**: `kepsek123`

**Test User:**
- **Email**: `test@example.com`
- **Password**: `password` (default dari factory)

---

## 📍 Routes

### Public Routes (Tidak Perlu Login)
- `GET /` - Homepage
- `GET /login` - Login page
- `POST /login` - Login submit
- `GET /register` - Register page
- `POST /register` - Register submit
- `GET /user/gallery` - Public gallery
- `GET /user/agenda` - Public agenda
- `GET /user/informasi` - Public information

### Admin Routes (Perlu Login sebagai Petugas)
- `GET /dashboard` - Admin dashboard
- `GET /galeri` - Gallery management
- `GET /kategori` - Category management
- `GET /petugas` - Staff management
- `GET /agenda` - Agenda management
- `GET /admin/profile` - Admin profile
- `GET /admin/settings` - Site settings

---

## 🧪 Testing dengan Browser

### Test Login Petugas/Admin:
1. Buka `http://localhost:8000/login`
2. Masukkan:
   - Email/Username: `admin` atau `admin@gmail.com`
   - Password: `admin123`
   - Captcha: Jawab pertanyaan matematika
3. Harus redirect ke `/dashboard`

### Test Login User Biasa:
1. Buka `http://localhost:8000/login`
2. Masukkan:
   - Email: `admin@galeri-edu.com`
   - Password: `admin123`
   - Captcha: Jawab pertanyaan matematika
3. Harus redirect ke `/` (homepage)

### Test Register:
1. Buka `http://localhost:8000/register`
2. Isi form:
   - Name: `Test User`
   - Email: `testuser@example.com`
   - Password: `password123`
   - Password Confirmation: `password123`
   - Captcha: Jawab pertanyaan matematika
3. Harus redirect ke `/` (homepage) dan auto login

---

## 🔄 Guard System

### Petugas Guard (`auth:petugas`)
- Menggunakan tabel `petugas`
- Login dengan username atau email
- Redirect ke `/dashboard` setelah login
- Digunakan untuk admin/staff

### Web Guard (`auth` atau `auth:web`)
- Menggunakan tabel `users`
- Login dengan email
- Redirect ke `user.dashboard` (homepage) setelah login
- Digunakan untuk user biasa

---

## 📝 Notes

- **Captcha**: Login dan register memerlukan captcha matematika sederhana (contoh: 5 + 3 = ?)
- **Session**: Menggunakan session-based authentication
- **Remember Me**: Support remember me functionality
- **CSRF**: Semua form protected dengan CSRF token

---

## 🚨 Troubleshooting

### Jika login gagal:
1. Pastikan database sudah di-migrate: `php artisan migrate`
2. Pastikan seeder sudah dijalankan: `php artisan db:seed`
3. Clear cache: `php artisan config:clear && php artisan cache:clear`
4. Cek session driver di `.env`: `SESSION_DRIVER=file`

### Jika redirect loop:
1. Clear browser cookies
2. Cek middleware configuration
3. Pastikan guard configuration benar di `config/auth.php`

