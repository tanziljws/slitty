# Troubleshooting Login Admin

## ✅ Status Akun

**Akun sudah dibuat dan diverifikasi:**
- **ID**: 8
- **Username**: `admin_new`
- **Email**: `admin_new@gmail.com`
- **Password**: `admin123`
- **Password Hash**: ✅ Sudah benar dan diverifikasi
- **Login Test**: ✅ Berhasil dengan tinker

## 🔍 Kemungkinan Masalah

### 1. Captcha Salah
**Gejala**: Error "Jawaban captcha tidak sesuai"

**Solusi**:
- Pastikan jawab captcha dengan benar
- Contoh: Jika captcha menampilkan "5 + 3", jawabannya adalah "8"
- Captcha harus dijawab dengan angka

### 2. Input Ada Spasi
**Gejala**: Login gagal meskipun credentials benar

**Solusi**:
- Pastikan tidak ada spasi di awal/akhir email/username
- Pastikan tidak ada spasi di awal/akhir password
- Coba copy-paste langsung dari sini:
  - Email: `admin_new@gmail.com`
  - Password: `admin123`

### 3. Case Sensitive
**Gejala**: Login gagal dengan email yang mirip

**Solusi**:
- Email tidak case sensitive, tapi pastikan format benar
- Username tidak case sensitive
- Password **CASE SENSITIVE** - pastikan huruf besar/kecil benar

### 4. Browser Cache/Session
**Gejala**: Login gagal meskipun credentials benar

**Solusi**:
- Clear browser cache
- Clear cookies untuk domain Railway
- Coba di browser lain atau incognito mode
- Coba logout dulu (jika sudah login sebelumnya)

## 🧪 Test Login

### Test dengan Tinker (SUDAH BERHASIL ✅)
```bash
php artisan tinker
>>> Auth::guard('petugas')->attempt(['email' => 'admin_new@gmail.com', 'password' => 'admin123']);
# Result: true ✅

>>> Auth::guard('petugas')->attempt(['username' => 'admin_new', 'password' => 'admin123']);
# Result: true ✅
```

## 📋 Credentials yang Benar

```
Email: admin_new@gmail.com
Username: admin_new
Password: admin123
```

## 🔧 Langkah Troubleshooting

1. **Cek Captcha**
   - Pastikan jawaban captcha benar
   - Captcha adalah pertanyaan matematika sederhana

2. **Cek Input**
   - Email: `admin_new@gmail.com` (tanpa spasi)
   - Password: `admin123` (tanpa spasi, case sensitive)

3. **Clear Browser**
   - Clear cache
   - Clear cookies
   - Coba incognito mode

4. **Cek Network Tab**
   - Buka Developer Tools (F12)
   - Tab Network
   - Lihat request POST ke `/login`
   - Cek response error yang detail

5. **Cek Logs**
   - Cek log Laravel untuk error detail
   - Railway logs: Railway dashboard → Deployments → View Logs

## 🚨 Jika Masih Gagal

1. **Cek Log Laravel**:
   ```bash
   # Di Railway, cek logs untuk error detail
   ```

2. **Test dengan Akun Lain**:
   - Coba login dengan akun yang sudah ada:
     - Email: `adminK4@gmail.com`
     - Password: `admin123`

3. **Reset Password**:
   - Jika masih gagal, bisa reset password dengan:
   ```bash
   php artisan tinker
   >>> $petugas = App\Models\Petugas::where('username', 'admin_new')->first();
   >>> $petugas->password = Hash::make('admin123');
   >>> $petugas->save();
   ```

## ✅ Verifikasi Akun

Akun sudah diverifikasi:
- ✅ Password hash benar
- ✅ Login test dengan tinker berhasil
- ✅ Email dan username benar
- ✅ Akun ada di database

**Masalah kemungkinan besar di:**
- Captcha salah
- Input ada spasi
- Browser cache/session
- CSRF token expired

