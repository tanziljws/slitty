# Fix "Request Body Stream Exhausted" Error

## 🔴 Error yang Terjadi

Error: **"request body stream exhausted"** di Safari saat mengakses `https://slitty-production.up.railway.app/login`

## 🔍 Penyebab

Error ini biasanya terjadi karena:

1. **Response terlalu besar** - Query database yang terlalu berat
2. **Timeout** - Query memakan waktu terlalu lama
3. **Memory issue** - Server kehabisan memory
4. **Query yang tidak optimal** - N+1 query problem atau query tanpa limit

## ✅ Solusi

### 1. Optimasi Query di Homepage

Route `/` melakukan banyak query dengan relasi kompleks. Perlu optimasi:

**Masalah:**
- Query galeri dengan relasi `post.kategori`, `fotos`
- Filter dan sort di memory (bukan di database)
- Tidak ada pagination

**Solusi:**
- Tambahkan limit yang lebih ketat
- Optimasi query dengan eager loading yang benar
- Gunakan database query untuk sorting, bukan collection

### 2. Optimasi Query di Login Page

Login page seharusnya ringan, tapi mungkin ada query yang tidak perlu.

### 3. Cek Memory Limit

Pastikan Railway instance punya memory yang cukup.

### 4. Cek Timeout Settings

Pastikan timeout settings di Railway cukup untuk query yang berat.

## 🛠️ Langkah Perbaikan

### Step 1: Optimasi Homepage Query

```php
// Di routes/web.php, route '/'
// Ganti query yang kompleks dengan yang lebih sederhana
$latestGaleriIds = \App\Models\Galery::join('posts', 'galery.post_id', '=', 'posts.id')
    ->where('galery.status', 'aktif')
    ->orderBy('posts.created_at', 'desc')
    ->select('galery.id')
    ->limit(5) // Pastikan ada limit
    ->pluck('id');

// Load dengan relasi yang diperlukan saja
$latestGalleries = \App\Models\Galery::with(['post:id,judul,kategori_id,created_at', 'fotos:id,galery_id,file'])
    ->whereIn('id', $latestGaleriIds)
    ->get();
```

### Step 2: Tambahkan Error Handling yang Lebih Baik

Jika query gagal, return response cepat tanpa error.

### Step 3: Cek Railway Logs

1. Buka Railway Dashboard
2. Pilih project
3. Klik "View Logs"
4. Cari error atau warning terkait memory/timeout

### Step 4: Cek Environment Variables

Pastikan:
- `APP_DEBUG=false` (production)
- `LOG_LEVEL=error` (untuk mengurangi log)
- Memory limit cukup

## 🧪 Testing

Setelah fix, test dengan:

```bash
# Test homepage
curl -v https://slitty-production.up.railway.app/ 2>&1 | grep -E "HTTP|Content-Length|time"

# Test login page
curl -v https://slitty-production.up.railway.app/login 2>&1 | grep -E "HTTP|Content-Length|time"
```

## 📝 Catatan

- Error ini berbeda dari redirect loop
- Server bisa respond (HTTP 200), tapi response body tidak lengkap
- Biasanya terjadi di Safari karena Safari lebih strict dengan incomplete responses

