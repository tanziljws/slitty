# Cara Membuat Akun Petugas Baru

## 🚀 Cara Cepat (Recommended)

### Method 1: Menggunakan Script Bash (Paling Mudah)

```bash
./create_petugas.sh [username] [email] [password]
```

**Contoh:**
```bash
./create_petugas.sh admin_new admin_new@gmail.com admin123
```

Script ini akan:
1. Generate password hash otomatis
2. Insert ke database production
3. Menampilkan credentials untuk login

---

### Method 2: Menggunakan MySQL Langsung

#### Step 1: Generate Password Hash
```bash
php artisan tinker --execute="echo Hash::make('admin123');"
```

Atau:
```bash
php create_petugas_helper.php
```

#### Step 2: Insert ke Database
```bash
mysql -h yamanote.proxy.rlwy.net -u root -pDmAxrbVXaioQUfuttWoIIRCjlkMPzqJD --port 54511 --protocol=TCP railway
```

Kemudian jalankan SQL:
```sql
USE railway;

INSERT INTO `petugas` (`username`, `email`, `password`, `created_at`, `updated_at`) 
VALUES 
('admin_new', 'admin_new@gmail.com', '$2y$12$YOUR_PASSWORD_HASH', NOW(), NOW());
```

**Contoh lengkap dengan password hash:**
```sql
INSERT INTO `petugas` (`username`, `email`, `password`, `created_at`, `updated_at`) 
VALUES 
('admin_new', 'admin_new@gmail.com', '$2y$12$k9MXDGUVFTK9lmzhCNgRF.ZzxxyJ2JrHNVgu3/.INRfXz5OYOnkoa', NOW(), NOW());
```

---

### Method 3: Menggunakan SQL File

1. Edit file `create_petugas.sql`
2. Ganti username, email, dan password hash
3. Jalankan:
```bash
mysql -h yamanote.proxy.rlwy.net -u root -pDmAxrbVXaioQUfuttWoIIRCjlkMPzqJD --port 54511 --protocol=TCP railway < create_petugas.sql
```

---

## 📋 Database Connection Info

- **Host**: `yamanote.proxy.rlwy.net`
- **Port**: `54511`
- **User**: `root`
- **Password**: `DmAxrbVXaioQUfuttWoIIRCjlkMPzqJD`
- **Database**: `railway`
- **Protocol**: `TCP`

## 🔐 Field yang Diperlukan

Tabel `petugas` memerlukan:
- `username` (string, required)
- `email` (string, required, unique)
- `password` (string, hashed dengan bcrypt)
- `created_at` (timestamp)
- `updated_at` (timestamp)

## ✅ Validasi

Pastikan:
- ✅ Username tidak kosong
- ✅ Email format valid dan unique
- ✅ Password minimal 6 karakter
- ✅ Password sudah di-hash dengan bcrypt

## 🧪 Test Login

Setelah membuat akun, test login dengan:
- **Email**: `admin_new@gmail.com` **ATAU**
- **Username**: `admin_new`
- **Password**: `admin123`

## 📝 Contoh Akun yang Sudah Ada

Berdasarkan database production:
1. **admin** / `adminK4@gmail.com` / `admin123`
2. **staf** / `sitiyuyur@gmail.com` / `staff123`
3. **staff** / `sipitsipit@gmail.com` / `staff123`

## ⚠️ Catatan Penting

1. **Password harus di-hash** - Jangan insert password plain text!
2. **Email harus unique** - Pastikan email belum digunakan
3. **Username bisa duplikat** - Tapi lebih baik unique juga
4. **Gunakan script helper** untuk generate password hash yang benar

## 🔧 Troubleshooting

### Error: Duplicate entry for email
```sql
-- Cek email yang sudah ada
SELECT id, username, email FROM petugas WHERE email = 'email_anda@gmail.com';

-- Jika sudah ada, gunakan email lain atau update yang sudah ada
```

### Error: Password tidak bisa login
- Pastikan password sudah di-hash dengan bcrypt
- Gunakan `Hash::make()` dari Laravel, bukan hash manual
- Test dengan: `php create_petugas_helper.php`

### Error: Connection refused
- Pastikan host, port, dan protocol benar
- Cek firewall/network connection
- Pastikan database Railway masih aktif

