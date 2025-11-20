# Project Analysis: Galeri Edu - Sistem Manajemen Galeri Sekolah

## 📋 Executive Summary

**Galeri Edu** adalah aplikasi web berbasis Laravel 12.0 untuk manajemen galeri foto sekolah. Aplikasi ini memiliki sistem autentikasi ganda (User & Petugas), fitur upload multiple foto, sistem like/dislike, download dengan captcha, dan manajemen konten lengkap.

**Repository GitHub**: `https://github.com/tanziljws/slitty.git` (nama repo berbeda dengan nama proyek)

---

## 🏗️ Architecture Overview

### Technology Stack
- **Backend**: Laravel 12.0 (PHP 8.2+)
- **Frontend**: TailwindCSS 4.0, Alpine.js, Blade Templates
- **Database**: SQLite (default) / MySQL (configurable)
- **Build Tool**: Vite 7.0
- **PDF Generation**: barryvdh/laravel-dompdf
- **Authentication**: Laravel Session-based dengan multi-guard system

### Project Structure
```
ujikom-2/
├── app/
│   ├── Http/Controllers/     # 17 controllers
│   ├── Models/               # 14 Eloquent models
│   ├── Middleware/           # Custom middleware
│   └── Providers/            # Service providers
├── database/
│   ├── migrations/           # 22 migration files
│   └── seeders/              # 5 seeder files
├── resources/views/           # 44 Blade templates
├── routes/
│   ├── web.php               # Web routes (public + admin)
│   ├── api.php               # API endpoints
│   └── debug.php             # Debug routes
└── public/uploads/galeri/     # File storage
```

---

## 🔐 Authentication System

### Dual Authentication Guards

1. **Web Guard** (`users` table)
   - Untuk user biasa yang bisa register
   - Dapat melihat galeri, like/dislike foto
   - Redirect ke homepage setelah login

2. **Petugas Guard** (`petugas` table)
   - Untuk staff/admin yang mengelola konten
   - Dapat upload galeri, manage kategori, dll
   - Redirect ke dashboard admin setelah login

### Authentication Features
- ✅ Login dengan email atau username (untuk petugas)
- ✅ Captcha matematika sederhana pada login/register
- ✅ Remember me functionality
- ✅ Session-based authentication
- ✅ Separate redirects berdasarkan role
- ✅ Logout support GET dan POST

### Key Files
- `app/Http/Controllers/AuthController.php` - Login, logout, register
- `app/Models/Petugas.php` - Petugas model dengan custom guard
- `app/Models/User.php` - User model default Laravel
- `config/auth.php` - Konfigurasi guards dan providers

---

## 📸 Gallery Management System

### Database Structure

**Relasi Database:**
```
kategori (1) ──< (N) posts (1) ──< (N) galery (1) ──< (N) foto
                    │
                    └──< (N) petugas
```

### Core Entities

1. **Kategori** (Categories)
   - `id`, `judul`, `deskripsi`, `status`, `timestamps`
   - Organisasi galeri berdasarkan kategori

2. **Posts** (Content Metadata)
   - `id`, `judul`, `kategori_id`, `petugas_id`, `isi`, `status`, `timestamps`
   - Metadata konten galeri (judul, deskripsi, kategori, author)

3. **Galery** (Gallery Container)
   - `id`, `post_id`, `position`, `status` (no timestamps)
   - Container yang menghubungkan post dengan foto

4. **Foto** (Individual Photos)
   - `id`, `galery_id`, `file`, `likes`, `dislikes`, `timestamps`
   - File foto individual dalam galeri

### Gallery Features
- ✅ Upload multiple foto sekaligus (drag & drop)
- ✅ Preview foto sebelum upload
- ✅ Status toggle (aktif/nonaktif)
- ✅ Category-based organization
- ✅ Author tracking (links ke petugas)
- ✅ Like/Dislike system per foto
- ✅ Download dengan captcha protection
- ✅ Edit dan hapus galeri
- ✅ Hapus foto individual dari galeri

### Key Files
- `app/Http/Controllers/GaleriController.php` - CRUD galeri
- `app/Http/Controllers/FotoController.php` - Like/dislike & delete foto
- `app/Models/Galery.php` - Model dengan relasi
- `app/Models/Post.php` - Model post
- `app/Models/Foto.php` - Model foto

---

## 👍 Like/Dislike System

### Features
- ✅ User dapat like/dislike foto individual
- ✅ Like dan dislike saling eksklusif (mutually exclusive)
- ✅ Counter likes/dislikes di tabel `foto`
- ✅ Tracking di tabel `user_likes` dan `user_dislikes`
- ✅ Logging semua aksi di `gallery_like_logs`
- ✅ Hanya user yang login bisa like/dislike

### Database Tables
- `user_likes` - Track user likes
- `user_dislikes` - Track user dislikes
- `gallery_like_logs` - Log semua interaksi (like, unlike, dislike, undislike)
- `foto.likes` - Counter likes
- `foto.dislikes` - Counter dislikes

### Key Files
- `app/Http/Controllers/FotoController.php` - `toggleLike()`, `toggleDislike()`
- `app/Models/Like.php` - Model like
- `app/Models/Dislike.php` - Model dislike
- `app/Models/GalleryLikeLog.php` - Model log

---

## 📥 Download System

### Features
- ✅ Download foto tanpa login (public access)
- ✅ Captcha verification (pertanyaan matematika)
- ✅ Rate limiting: 5 downloads per IP per jam
- ✅ Download token berlaku 5 menit
- ✅ Token-based secure downloads
- ✅ CORS support untuk captcha generation

### Flow
1. User klik download → Generate captcha (math question)
2. User jawab captcha → Verify answer
3. Check rate limit (max 5 per IP per hour)
4. Generate download token (expires in 5 minutes)
5. Redirect ke `/download/{token}`
6. Download file dengan token
7. Token dihapus setelah digunakan

### Key Files
- `app/Http/Controllers/DownloadController.php` - Download logic
- Routes: `/download/generate-captcha`, `/download/verify`, `/download/{token}`

---

## 📄 Content Management

### 1. Pages System
- Dynamic page creation dengan slug-based URLs
- WYSIWYG content management
- Publishing status control
- Custom slug generation

**Model**: `app/Models/Page.php`
**Controller**: `app/Http/Controllers/PageController.php`
**Route**: `/page/{slug}` (public)

### 2. Informasi System
- School information items ditampilkan di homepage
- Icon support, date-based ordering
- Status management (aktif/nonaktif)
- Order/priority control

**Model**: `app/Models/Informasi.php`
**Controllers**: 
- `InformasiController.php` - Edit school identity (single record)
- `Admin/InformasiAdminController.php` - CRUD informasi items

### 3. Agenda System
- Event scheduling dan management
- Date labels, time ranges, event dates
- Order-based display
- Status control

**Model**: `app/Models/Agenda.php`
**Controller**: `app/Http/Controllers/AgendaController.php`

---

## ⚙️ Site Settings

### Features
- Centralized configuration management
- Group-based settings organization
- Profile images, footer logos
- Homepage hero settings
- Site-wide customization
- Cache support untuk performa

### Key Files
- `app/Models/SiteSetting.php` - Model dengan cache support
- `app/Http/Controllers/SiteSettingController.php` - CRUD settings
- Routes: `/admin/settings/*`

### Methods
- `SiteSetting::get($key, $default)` - Get single setting
- `SiteSetting::set($key, $value)` - Set setting
- `SiteSetting::getAllGrouped()` - Get all grouped by group

---

## 📊 Dashboard & Reporting

### Admin Dashboard
- Real-time statistics dari database
- Quick actions untuk navigasi cepat
- Recent galeri dengan preview foto
- Alert notifikasi untuk galeri pending
- Recent activities tracking

**Controller**: `app/Http/Controllers/DashboardController.php`
**View**: `resources/views/dashboard.blade.php`

### Gallery Reports
- PDF export functionality
- Gallery statistics
- Filtering dan search capabilities
- Like/Dislike logs viewing
- Reset logs functionality

**Controllers**:
- `app/Http/Controllers/GalleryReportController.php`
- `app/Http/Controllers/GalleryLikeLogController.php`

---

## 🌐 Routing Structure

### Public Routes (No Authentication)
```
GET  /                          # Homepage (user.dashboard)
GET  /user/gallery              # Public gallery listing
GET  /user/agenda              # Public agenda page
GET  /user/informasi           # Public information page
GET  /page/{slug}              # Dynamic page display
GET  /login                    # Login page
POST /login                    # Login submit
GET  /register                 # Registration page
POST /register                 # Register submit
GET  /logout                   # Logout (GET)
POST /logout                   # Logout (POST)
GET  /download/generate-captcha # Generate captcha
POST /download/verify          # Verify captcha
GET  /download/{token}         # Download file
POST /foto/{id}/toggle-like    # Toggle like
POST /foto/{id}/toggle-dislike # Toggle dislike
```

### Protected Routes (Requires auth:petugas)
```
GET  /dashboard                           # Admin dashboard
GET  /galeri                              # Gallery index
POST /galeri                              # Create gallery
GET  /galeri/{galeri}/edit                # Edit gallery
PUT  /galeri/{galeri}                     # Update gallery
DELETE /galeri/{galeri}                   # Delete gallery
PATCH /galeri/{galeri}/toggle-status      # Toggle status
DELETE /galeri/foto/{foto}                # Delete individual photo
GET  /galeri/report                       # Gallery report
GET  /galeri/report/pdf                   # PDF export
GET  /galeri/like-logs                    # Like/dislike logs
POST /galeri/like-logs/reset              # Reset logs
GET  /kategori                            # Category index
POST /kategori                            # Create category
PUT  /kategori/{kategori}                 # Update category
DELETE /kategori/{kategori}                # Delete category
GET  /petugas                             # Staff index
POST /petugas                             # Create staff
PUT  /petugas/{petugas}                   # Update staff
DELETE /petugas/{petugas}                 # Delete staff
GET  /agenda                              # Agenda index
POST /agenda                              # Create agenda
PUT  /agenda/{agenda}                     # Update agenda
DELETE /agenda/{agenda}                   # Delete agenda
GET  /admin/informasi-items                # Information items index
POST /admin/informasi-items                # Create information item
PUT  /admin/informasi-items/{item}        # Update information item
DELETE /admin/informasi-items/{item}       # Delete information item
GET  /admin/profile                       # Admin profile
PUT  /admin/profile                       # Update profile
PUT  /admin/password                      # Change password
GET  /admin/settings                      # Site settings
GET  /admin/users                         # View registered users
```

### API Routes (Requires auth middleware)
```
GET    /api/galeri                        # List galleries
POST   /api/galeri                        # Create gallery
PUT    /api/galeri/{galeri}               # Update gallery
DELETE /api/galeri/{galeri}               # Delete gallery
PATCH  /api/galeri/{galeri}/toggle-status # Toggle status
GET    /api/kategori                      # List categories
POST   /api/kategori                      # Create category
PUT    /api/kategori/{kategori}           # Update category
DELETE /api/kategori/{kategori}           # Delete category
GET    /api/petugas                       # List staff
POST   /api/petugas                       # Create staff
PUT    /api/petugas/{petugas}             # Update staff
DELETE /api/petugas/{petugas}             # Delete staff
GET    /api/pages                         # List pages
POST   /api/pages                         # Create page
PUT    /api/pages/{page}                  # Update page
DELETE /api/pages/{page}                  # Delete page
PATCH  /api/pages/{page}/toggle-status    # Toggle status
```

### Debug Routes (When app.debug=true)
```
GET /test-homepage        # Test homepage queries
GET /test-data            # Check database data
GET /test-db              # Test database connection
GET /test-files-vs-db     # Compare file system vs database
```

---

## 🗄️ Database Schema

### Core Tables

1. **users** - Regular users
   - `id`, `name`, `email`, `password`, `email_verified_at`, `remember_token`, `timestamps`

2. **petugas** - Staff/admin users
   - `id`, `username`, `email`, `password`, `remember_token`, `timestamps`

3. **kategori** - Gallery categories
   - `id`, `judul`, `deskripsi`, `status`, `timestamps`

4. **posts** - Content metadata
   - `id`, `judul`, `kategori_id` (FK), `petugas_id` (FK), `isi`, `status`, `timestamps`

5. **galery** - Gallery containers
   - `id`, `post_id` (FK), `position`, `status` (no timestamps)

6. **foto** - Individual photos
   - `id`, `galery_id` (FK), `file`, `likes`, `dislikes`, `timestamps`

7. **user_likes** - Track user likes
   - `id`, `user_id`, `foto_id` (FK), `timestamps`

8. **user_dislikes** - Track user dislikes
   - `id`, `user_id`, `foto_id` (FK), `timestamps`

9. **gallery_like_logs** - Interaction logs
   - `id`, `user_id`, `foto_id`, `action` (like/dislike/unlike/undislike), `timestamps`

10. **informasi** - Information items
    - `id`, `title`, `description`, `icon`, `date`, `status`, `order`, `timestamps`

11. **agenda** - Event/agenda items
    - `id`, `title`, `description`, `date_label`, `time`, `event_date`, `status`, `order`, `timestamps`

12. **pages** - Dynamic pages
    - `id`, `title`, `slug`, `content`, `status`, `timestamps`

13. **site_settings** - Configuration
    - `id`, `key`, `value`, `group`, `type`, `label`, `description`, `order`, `timestamps`

---

## 🔒 Security Features

1. **CSRF Protection** - All forms protected
2. **Captcha Verification** - Math captcha pada login/register/download
3. **Rate Limiting** - Download rate limiting (5 per hour per IP)
4. **Password Hashing** - Bcrypt hashing untuk semua password
5. **Session Security** - Session regeneration pada login
6. **Authorization** - Middleware-based route protection
7. **Input Validation** - Request validation pada semua endpoints
8. **Token-based Downloads** - Secure download dengan token expiry

---

## 📁 File Storage

### Upload Configuration
- **Location**: `public/uploads/galeri/`
- **Max File Size**: 10MB per image
- **Allowed Types**: jpeg, png, jpg, gif, webp
- **File Naming**: `timestamp_index.extension`

### Key Files
- `config/filesystems.php` - File storage configuration

---

## 🎨 Frontend Structure

### Technologies
- **TailwindCSS 4.0** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript untuk interactivity
- **Blade Templates** - Laravel templating engine
- **Vite 7.0** - Build tool

### View Structure
```
resources/views/
├── layouts/
│   ├── app.blade.php          # Base layout
│   ├── dashboard.blade.php    # Admin dashboard layout
│   └── guest.blade.php        # Guest layout
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── choose-login.blade.php
├── dashboard.blade.php         # Admin dashboard
├── galeri/                     # Gallery management
├── kategori/                   # Category management
├── petugas/                    # Staff management
├── pages/                      # Page management
├── admin/                      # Admin pages
│   ├── agenda/
│   ├── informasi/
│   ├── informasi-items/
│   ├── reports/
│   ├── site-settings/
│   └── users/
└── user/                       # Public-facing pages
    ├── dashboard.blade.php     # Homepage
    ├── gallery.blade.php       # Public gallery
    ├── agenda.blade.php        # Public agenda
    └── informasi.blade.php     # Public information
```

---

## 🚀 Deployment

### Default Configuration
- **Database**: SQLite (dapat diubah ke MySQL)
- **File Storage**: Local filesystem
- **Session Driver**: File-based

### Deployment Files
- `RAILWAY_DEPLOY.md` - Railway deployment guide
- `RAILWAY_MIGRATION_GUIDE.md` - Database migration guide
- `RAILWAY_ENV_SETUP.md` - Environment configuration
- `nixpacks.toml` - Nixpacks configuration
- `Procfile` - Process file untuk deployment

---

## 🐛 Error Handling

### Features
- Graceful degradation ketika database tables tidak ada
- Empty collection fallbacks untuk missing data
- Detailed logging untuk debugging
- User-friendly error pages
- Debug routes untuk troubleshooting
- Try-catch blocks pada semua query database

### Key Implementation
- Schema checks sebelum query
- Try-catch pada semua database operations
- Logging semua errors
- Fallback ke empty collections
- Error views untuk user-friendly messages

---

## 📝 Key Controllers Summary

1. **DashboardController** - Admin dashboard statistics
2. **GaleriController** - Gallery CRUD, multi-image upload
3. **KategoriController** - Category management
4. **PetugasController** - Staff management
5. **AuthController** - Login, logout, register dengan captcha
6. **PageController** - Dynamic page management
7. **AgendaController** - Event/agenda management
8. **InformasiController** - School information management
9. **InformasiAdminController** - Information items CRUD
10. **DownloadController** - Download dengan captcha
11. **FotoController** - Photo like/dislike handling
12. **SiteSettingController** - Site configuration
13. **AdminProfileController** - Admin profile & password
14. **GalleryReportController** - Reporting functionality
15. **GalleryLikeLogController** - Like/dislike log management

---

## 🔄 Key Models & Relationships

### Galery Model
```php
- belongsTo(Post)
- hasMany(Foto)
- hasOneThrough(Kategori, Post)
```

### Post Model
```php
- belongsTo(Kategori)
- belongsTo(Petugas)
- hasMany(Galery)
```

### Foto Model
```php
- belongsTo(Galery)
- hasMany(Like)
- hasMany(Dislike)
```

### Petugas Model
```php
- Extends Authenticatable
- Custom guard: 'petugas'
```

---

## ✅ Feature Checklist

- ✅ Multi-user authentication (Users + Petugas)
- ✅ Gallery management dengan multiple photos
- ✅ Category organization
- ✅ Like/Dislike system
- ✅ Download dengan captcha protection
- ✅ Content management (Pages, Informasi, Agenda)
- ✅ Site settings management
- ✅ PDF reporting
- ✅ Admin dashboard dengan statistics
- ✅ Public-facing user pages
- ✅ Rate limiting dan security measures
- ✅ Error handling yang comprehensive
- ✅ Debug routes untuk development

---

## 📚 Development Notes

- Uses Laravel 12.0 dengan modern PHP features
- Follows MVC architecture
- Eloquent ORM untuk database operations
- Service-oriented controller design
- Comprehensive error handling
- Debug routes untuk development
- Extensive logging untuk troubleshooting
- Cache support untuk performa (SiteSettings)

---

## 🔮 Potential Enhancements

1. Image optimization/thumbnails
2. Advanced search functionality
3. Advanced filtering
4. Email notifications
5. Social sharing
6. Comment system
7. Advanced analytics
8. Image tagging
9. Bulk operations
10. Export/Import functionality
11. Multi-language support
12. Advanced user roles & permissions

---

## 📞 Project Information

- **Project Name**: Galeri Edu - Sistem Manajemen Galeri Sekolah
- **GitHub Repository**: https://github.com/tanziljws/slitty.git
- **Framework**: Laravel 12.0
- **PHP Version**: 8.2+
- **License**: MIT

---

*Document generated from comprehensive codebase analysis*

