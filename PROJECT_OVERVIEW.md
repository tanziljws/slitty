# Project Overview: Galeri Edu - Sistem Manajemen Galeri Sekolah

## Executive Summary

**Galeri Edu** is a comprehensive Laravel 12.0 web application designed for managing school photo galleries. The system allows administrators and staff (petugas) to manage galleries, categories, content pages, information, and events with features like multi-image uploads, user likes/dislikes, and download capabilities with captcha protection.

---

## Technology Stack

- **Backend Framework**: Laravel 12.0 (PHP 8.2+)
- **Frontend**: TailwindCSS 4.0, Alpine.js
- **Database**: MySQL/SQLite (default: SQLite)
- **Build Tool**: Vite 7.0
- **PDF Generation**: barryvdh/laravel-dompdf
- **Authentication**: Laravel Session-based with multi-guard system

---

## Project Structure

### Core Directories

```
app/
├── Http/Controllers/        # 17 controllers handling all business logic
├── Models/                  # 14 Eloquent models
├── Middleware/              # Authentication middleware
└── Providers/               # Service providers

database/
├── migrations/              # 22 migration files
└── seeders/                 # 5 seeder files

resources/views/             # 44 Blade templates
├── auth/                    # Login/Register views
├── dashboard/               # Admin dashboard
├── galeri/                  # Gallery management
├── kategori/                # Category management
├── user/                    # Public-facing pages
└── layouts/                 # Base templates

routes/
├── web.php                  # Web routes (public + admin)
├── api.php                  # API endpoints
└── debug.php                # Debug routes (when app.debug=true)
```

---

## Key Features

### 1. Multi-User Authentication System

**Two Authentication Guards:**
- **Web Guard** (`users` table): Regular users who can register, view galleries, and like/dislike photos
- **Petugas Guard** (`petugas` table): Staff/admin who can manage content, upload galleries, and access admin dashboard

**Features:**
- Dual login support (email/username for petugas, email for users)
- Captcha protection on login/register (simple math verification)
- Remember me functionality
- Session-based authentication
- Separate redirects: Users → Public homepage, Petugas → Admin dashboard

### 2. Gallery Management System

**Core Entities:**
- **Kategori** (Categories): Organize galleries by category
- **Posts**: Content metadata (title, description, category, author)
- **Galery**: Gallery container linking to posts
- **Foto**: Individual image files within a gallery

**Features:**
- Multiple image upload per gallery (drag & drop support)
- Image preview before upload
- Status toggle (aktif/nonaktif)
- Category-based organization
- Author tracking (links to petugas who created it)
- Like/Dislike system for photos
- Download functionality with captcha protection

**Database Relations:**
```
kategori → hasMany → posts
petugas → hasMany → posts
posts → belongsTo → kategori, petugas
posts → hasMany → galery
galery → belongsTo → posts
galery → hasMany → foto
```

### 3. Content Management

**Pages System:**
- Dynamic page creation with slug-based URLs
- WYSIWYG content management
- Publishing status control
- Custom slug generation

**Information System (Informasi):**
- School information items displayed on homepage
- Icon support, date-based ordering
- Status management (aktif/nonaktif)
- Order/priority control

**Agenda System:**
- Event scheduling and management
- Date labels, time ranges, event dates
- Order-based display
- Status control

### 4. Site Settings

**SiteSettings Model:**
- Centralized configuration management
- Group-based settings organization
- Profile images, footer logos
- Homepage hero settings
- Site-wide customization

### 5. User Interaction Features

**Like/Dislike System:**
- Users can like/dislike individual photos
- Like/dislike counts tracked on `foto` table
- User action logging in `gallery_like_logs` table
- Rate limiting and spam protection

**Download System:**
- Public photo downloads without login
- Captcha verification (math question)
- Download token system (5-minute expiry)
- Rate limiting: 5 downloads per IP per hour
- Secure token-based downloads

### 6. Reporting & Analytics

**Gallery Reports:**
- PDF export functionality
- Gallery statistics
- Filtering and search capabilities

**Like/Dislike Logs:**
- Track user interactions
- Reset functionality for logs
- Admin monitoring of user engagement

---

## Database Schema

### Core Tables

1. **users** - Regular users (can register)
   - id, name, email, password, email_verified_at, remember_token, timestamps

2. **petugas** - Staff/admin users
   - id, username, email, password, remember_token, timestamps

3. **kategori** - Gallery categories
   - id, judul, deskripsi, status, timestamps

4. **posts** - Content metadata
   - id, judul, kategori_id (FK), petugas_id (FK), isi, status, timestamps

5. **galery** - Gallery containers
   - id, post_id (FK), position, status (no timestamps)

6. **foto** - Individual photos
   - id, galery_id (FK), file, likes, dislikes, timestamps

7. **user_likes** - Track user likes
   - id, user_id, foto_id (FK), timestamps

8. **user_dislikes** - Track user dislikes
   - id, user_id, foto_id (FK), timestamps

9. **gallery_like_logs** - Interaction logs
   - id, user_id, foto_id, action (like/dislike), timestamps

10. **informasi** - Information items
    - id, title, description, icon, date, status, order, timestamps

11. **agenda** - Event/agenda items
    - id, title, description, date_label, time, event_date, status, order, timestamps

12. **pages** - Dynamic pages
    - id, title, slug, content, status, timestamps

13. **site_settings** - Configuration
    - id, key, value, group, type, timestamps

---

## Routing Structure

### Public Routes (No Authentication)

- `/` - Homepage (user.dashboard)
- `/user/gallery` - Public gallery listing
- `/user/agenda` - Public agenda page
- `/user/informasi` - Public information page
- `/page/{slug}` - Dynamic page display
- `/login` - Login page (guest only)
- `/register` - Registration page (guest only)
- `/download/*` - Download routes with captcha

### Protected Routes (Requires auth:petugas)

**Dashboard:**
- `/dashboard` - Admin dashboard with statistics

**Resource Management:**
- `kategori` - CRUD for categories
- `galeri` - CRUD for galleries
- `petugas` - CRUD for staff
- `agenda` - CRUD for agenda items
- `admin/informasi-items` - CRUD for information items

**Features:**
- `galeri/{galeri}/toggle-status` - Toggle gallery status
- `galeri/report` - Gallery reports
- `galeri/report/pdf` - PDF export
- `galeri/like-logs` - View like/dislike logs
- `informasi` - Edit school information (single record)

**Settings:**
- `/admin/profile` - Admin profile management
- `/admin/password` - Change password
- `/admin/settings` - Site settings management
- `/admin/users` - View registered users (read-only)

### API Routes (Requires auth middleware)

- `/api/galeri` - Gallery API endpoints
- `/api/kategori` - Category API endpoints
- `/api/petugas` - Staff API endpoints
- `/api/pages` - Pages API endpoints

### Debug Routes (When app.debug=true)

- `/test-homepage` - Test homepage queries
- `/test-data` - Check database data
- `/test-db` - Test database connection
- `/test-files-vs-db` - Compare file system vs database

---

## Key Controllers

1. **DashboardController** - Admin dashboard statistics and overview
2. **GaleriController** - Gallery CRUD operations, multi-image upload
3. **KategoriController** - Category management
4. **PetugasController** - Staff management
5. **AuthController** - Login, logout, registration with captcha
6. **PageController** - Dynamic page management
7. **AgendaController** - Event/agenda management
8. **InformasiController** - School information management
9. **InformasiAdminController** - Information items CRUD
10. **DownloadController** - Download functionality with captcha
11. **FotoController** - Photo like/dislike handling
12. **SiteSettingController** - Site configuration management
13. **AdminProfileController** - Admin profile and password management
14. **GalleryReportController** - Reporting functionality
15. **GalleryLikeLogController** - Like/dislike log management

---

## Models & Relationships

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

### User Model
```php
- Default Laravel User model
- Guard: 'web'
```

---

## File Storage

- **Upload Location**: `public/uploads/galeri/`
- **Max File Size**: 10MB per image
- **Allowed Types**: jpeg, png, jpg, gif, webp
- **File Naming**: `timestamp_index.extension`

---

## Security Features

1. **CSRF Protection** - All forms protected
2. **Captcha Verification** - Math captcha on login/register/download
3. **Rate Limiting** - Download rate limiting (5 per hour per IP)
4. **Password Hashing** - Bcrypt hashing for all passwords
5. **Session Security** - Session regeneration on login
6. **Authorization** - Middleware-based route protection
7. **Input Validation** - Request validation on all endpoints

---

## Deployment Notes

The project includes multiple deployment-related markdown files:
- `RAILWAY_DEPLOY.md` - Railway deployment guide
- `RAILWAY_MIGRATION_GUIDE.md` - Database migration guide
- `RAILWAY_ENV_SETUP.md` - Environment configuration
- `DEPLOYMENT_SUCCESS.md` - Deployment notes

**Default Configuration:**
- Database: SQLite (can be changed to MySQL)
- File storage: Local filesystem
- Session driver: File-based

---

## Error Handling

The application includes comprehensive error handling:
- Graceful degradation when database tables don't exist
- Empty collection fallbacks for missing data
- Detailed logging for debugging
- User-friendly error pages
- Debug routes for troubleshooting

---

## Frontend Technologies

- **TailwindCSS 4.0** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework for interactivity
- **Blade Templates** - Laravel's templating engine
- Responsive design for mobile and desktop

---

## Key Features Summary

✅ Multi-user authentication (Users + Petugas)
✅ Gallery management with multiple photos
✅ Category organization
✅ Like/Dislike system
✅ Download with captcha protection
✅ Content management (Pages, Informasi, Agenda)
✅ Site settings management
✅ PDF reporting
✅ Admin dashboard with statistics
✅ Public-facing user pages
✅ Rate limiting and security measures

---

## Development Notes

- Uses Laravel 12.0 with modern PHP features
- Follows MVC architecture
- Eloquent ORM for database operations
- Service-oriented controller design
- Comprehensive error handling
- Debug routes for development
- Extensive logging for troubleshooting

---

## Next Steps for Development

Potential enhancements:
1. Image optimization/thumbnails
2. Search functionality
3. Advanced filtering
4. Email notifications
5. Social sharing
6. Comment system
7. Advanced analytics
8. Image tagging
9. Bulk operations
10. Export/Import functionality

