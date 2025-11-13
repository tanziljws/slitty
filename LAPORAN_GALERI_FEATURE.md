# Fitur Laporan Statistik Galeri

## Deskripsi
Fitur ini memungkinkan admin untuk melihat dan mengekspor laporan statistik galeri dalam format PDF. Laporan mencakup berbagai metrik dan dapat difilter berdasarkan periode, kategori, dan status.

## Fitur Utama

### 1. Dashboard Laporan
- **URL**: `/galeri/report`
- **Menu**: Sidebar → "Laporan Galeri" atau dari halaman Galeri → tombol "Laporan"
- **Statistik yang ditampilkan**:
  - Total Galeri
  - Total Foto
  - Galeri Aktif
  - Rata-rata Foto per Galeri
  - Statistik per Kategori dengan visualisasi persentase
  - Detail list semua galeri

### 2. Filter Laporan
Filter yang tersedia:
- **Tanggal Mulai & Akhir**: Filter berdasarkan periode waktu
- **Kategori**: Filter berdasarkan kategori galeri tertentu
- **Status**: Filter berdasarkan status (Aktif/Nonaktif)

### 3. Export PDF
- Klik tombol "Download PDF" untuk mengekspor laporan
- PDF akan berisi:
  - Header dengan informasi tanggal cetak
  - Informasi filter yang aktif
  - Ringkasan statistik dengan kartu visual
  - Kesimpulan lengkap
  - Tabel statistik per kategori
  - Detail list semua galeri (dengan page break jika data banyak)

## Struktur File

### Controller
- `app/Http/Controllers/GalleryReportController.php`
  - `index()`: Menampilkan halaman laporan
  - `exportPdf()`: Generate dan download PDF
  - `calculateStatistics()`: Menghitung statistik

### Views
- `resources/views/admin/reports/galeri.blade.php`: Halaman web laporan
- `resources/views/admin/reports/galeri-pdf.blade.php`: Template PDF

### Routes
```php
Route::get('/galeri/report', [GalleryReportController::class, 'index'])->name('galeri.report');
Route::get('/galeri/report/pdf', [GalleryReportController::class, 'exportPdf'])->name('galeri.report.pdf');
```

## Cara Menggunakan

1. **Akses Laporan**:
   - Login sebagai admin
   - Klik menu "Laporan Galeri" di sidebar, atau
   - Dari halaman Galeri, klik tombol "Laporan"

2. **Filter Data** (Opsional):
   - Pilih tanggal mulai dan akhir untuk periode tertentu
   - Pilih kategori spesifik (atau "Semua Kategori")
   - Pilih status (Aktif/Nonaktif atau "Semua Status")
   - Klik tombol "Filter"
   - Untuk reset filter, klik tombol "Reset"

3. **Export ke PDF**:
   - Setelah melihat laporan (dengan atau tanpa filter)
   - Klik tombol "Download PDF" di bagian bawah
   - PDF akan otomatis terdownload dengan nama: `laporan-galeri-YYYY-MM-DD-HHMMSS.pdf`

## Dependencies

- **barryvdh/laravel-dompdf**: ^3.1 - Library untuk generate PDF
  - Sudah terinstall via composer
  - Config file: `config/dompdf.php`

## Statistik yang Dihitung

1. **Total Galeri**: Jumlah total galeri (sesuai filter)
2. **Total Foto**: Jumlah total foto di semua galeri
3. **Galeri Aktif**: Jumlah galeri dengan status aktif
4. **Galeri Nonaktif**: Jumlah galeri dengan status nonaktif
5. **Rata-rata Foto/Galeri**: Total foto dibagi total galeri
6. **Statistik per Kategori**: 
   - Jumlah galeri per kategori
   - Persentase per kategori
7. **Kategori Paling Populer**: Kategori dengan jumlah galeri terbanyak

## Tampilan

### Web View
- Modern dashboard dengan gradient cards untuk statistik
- Tabel responsif untuk statistik per kategori dengan progress bar
- Form filter yang user-friendly
- Tombol export yang jelas dengan icon PDF

### PDF View
- Header dengan logo dan informasi
- Layout profesional dengan typography yang baik
- Tabel dengan styling alternating rows
- Badge berwarna untuk status
- Footer dengan informasi copyright

## Akses

- **Requirement**: User harus login (middleware: `auth`)
- **Role**: Semua user yang terautentikasi dapat mengakses
- **Permission**: Tidak ada permission khusus diperlukan

## Troubleshooting

Jika ada masalah:

1. **PDF tidak ter-generate**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   composer dump-autoload
   ```

2. **Error font di PDF**:
   - Font default menggunakan DejaVu Sans (sudah include di DomPDF)
   - Jika perlu font custom, tambahkan di `config/dompdf.php`

3. **Timeout saat generate PDF dengan banyak data**:
   - Sesuaikan `max_execution_time` di php.ini
   - Atau tambahkan pagination di laporan

## Future Improvements

Beberapa ide pengembangan:
- Export ke Excel/CSV
- Chart/grafik visual (menggunakan Chart.js)
- Schedule email laporan berkala
- Perbandingan periode (bulan ini vs bulan lalu)
- Filter tambahan (by petugas, by upload date range)
- Dashboard widget untuk quick stats
