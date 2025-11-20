# Validasi Tabel Users dan Petugas

## ✅ Validasi yang Ada

### 1. Petugas (Tabel `petugas`)

#### Store (Create) - `PetugasController@store`
```php
'username' => 'required|string|max:255',
'email' => 'required|email|unique:petugas',
'password' => 'required|string|min:6',
```

**Validasi:**
- ✅ Username: Wajib, string, maksimal 255 karakter
- ✅ Email: Wajib, format email valid, harus unique di tabel petugas
- ✅ Password: Wajib, string, minimal 6 karakter

#### Update - `PetugasController@update`
```php
'username' => 'required|string|max:255',
'email' => 'required|email|unique:petugas,email,' . $petuga->id,
'password' => 'nullable|string|min:6',
```

**Validasi:**
- ✅ Username: Wajib, string, maksimal 255 karakter
- ✅ Email: Wajib, format email valid, unique kecuali untuk record yang sedang di-update
- ✅ Password: Optional (nullable), jika diisi minimal 6 karakter

### 2. User (Tabel `users`)

#### Register - `AuthController@register`
```php
'name' => ['required', 'string', 'max:255'],
'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
'password' => ['required', 'string', 'min:6', 'confirmed'],
'captcha' => ['required', 'numeric'],
```

**Validasi:**
- ✅ Name: Wajib, string, maksimal 255 karakter
- ✅ Email: Wajib, format email valid, maksimal 255 karakter, harus unique di tabel users
- ✅ Password: Wajib, string, minimal 6 karakter, harus dikonfirmasi (password_confirmation)
- ✅ Captcha: Wajib, numeric

## 📋 Model Fillable

### Petugas Model
```php
protected $fillable = [
    'username',
    'email',
    'password',
    'remember_token',
];
```

### User Model
```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```

## 🔒 Security Features

1. **Password Hashing**: Semua password di-hash dengan `Hash::make()` sebelum disimpan
2. **Email Unique**: Email harus unique untuk mencegah duplikasi
3. **Password Min Length**: Minimal 6 karakter untuk keamanan
4. **Password Confirmation**: User register memerlukan konfirmasi password

## 🧪 Testing Validasi

### Test Petugas Store (Invalid Data)
```bash
# Test tanpa username
curl -X POST https://slitty-production.up.railway.app/api/petugas \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test@test.com","password":"123456"}'
# Expected: 422 Validation Error - username required

# Test dengan email duplikat
curl -X POST https://slitty-production.up.railway.app/api/petugas \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"username":"test","email":"adminK4@gmail.com","password":"123456"}'
# Expected: 422 Validation Error - email already exists

# Test dengan password terlalu pendek
curl -X POST https://slitty-production.up.railway.app/api/petugas \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"username":"test","email":"test@test.com","password":"123"}'
# Expected: 422 Validation Error - password min 6 characters
```

### Test User Register (Invalid Data)
```bash
# Test tanpa name
curl -X POST https://slitty-production.up.railway.app/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"123456","password_confirmation":"123456"}'
# Expected: 422 Validation Error - name required

# Test dengan email duplikat
curl -X POST https://slitty-production.up.railway.app/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"admin@galeri-edu.com","password":"123456","password_confirmation":"123456"}'
# Expected: 422 Validation Error - email already exists

# Test dengan password tidak match
curl -X POST https://slitty-production.up.railway.app/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123456","password_confirmation":"654321"}'
# Expected: 422 Validation Error - password confirmation does not match
```

## ⚠️ Catatan

1. **API Routes**: Menggunakan middleware `auth` (default guard `web`), bukan `auth:petugas`
2. **Validasi**: Semua validasi sudah ada dan bekerja dengan baik
3. **Error Response**: Validasi error akan return 422 dengan detail error messages

## 🔧 Perbaikan yang Sudah Diterapkan

1. ✅ Tambahkan method `show()` untuk API
2. ✅ Tambahkan JSON response untuk semua method (index, show, store, update, destroy)
3. ✅ Validasi sudah lengkap dan benar

