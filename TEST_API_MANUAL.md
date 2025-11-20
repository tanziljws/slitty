# Cara Test API dengan Authentication Admin

## 🔐 Credentials Admin

- **Email**: `adminK4@gmail.com`
- **Password**: `admin123`
- **Username**: `admin`

## 🧪 Cara Test Manual

### Method 1: Menggunakan Browser (Paling Mudah)

1. **Login di Browser**:
   - Buka: https://slitty-production.up.railway.app/login
   - Login dengan: `adminK4@gmail.com` / `admin123`
   - Pastikan sudah redirect ke dashboard

2. **Buka Developer Tools** (F12):
   - Tab **Application** → **Cookies**
   - Copy cookie `laravel_session`

3. **Test API dengan curl**:
```bash
# Ganti YOUR_SESSION_COOKIE dengan cookie yang di-copy
curl -H "Cookie: laravel_session=YOUR_SESSION_COOKIE" \
     -H "Accept: application/json" \
     https://slitty-production.up.railway.app/api/petugas
```

### Method 2: Menggunakan Postman/Insomnia

1. **Login Request**:
   - Method: `POST`
   - URL: `https://slitty-production.up.railway.app/login`
   - Headers:
     - `Content-Type: application/x-www-form-urlencoded`
   - Body (form-data):
     - `email`: `adminK4@gmail.com`
     - `password`: `admin123`
     - `captcha`: `[jumlah dari captcha di halaman login]`
     - `_token`: `[CSRF token dari halaman login]`

2. **Copy Session Cookie** dari response headers

3. **Test API**:
   - Method: `GET`
   - URL: `https://slitty-production.up.railway.app/api/petugas`
   - Headers:
     - `Cookie: laravel_session=[session_cookie]`
     - `Accept: application/json`

### Method 3: Test dengan curl (Advanced)

```bash
# 1. Get login page
curl -c cookies.txt https://slitty-production.up.railway.app/login > login.html

# 2. Extract CSRF token (manual atau dengan script)
# Buka login.html dan cari <input name="_token" value="...">

# 3. Login
curl -b cookies.txt -c cookies.txt \
     -X POST https://slitty-production.up.railway.app/login \
     -H "Content-Type: application/x-www-form-urlencoded" \
     -d "email=adminK4@gmail.com" \
     -d "password=admin123" \
     -d "captcha=12" \
     -d "_token=YOUR_CSRF_TOKEN"

# 4. Test API
curl -b cookies.txt \
     -H "Accept: application/json" \
     https://slitty-production.up.railway.app/api/petugas
```

## 📋 API Endpoints yang Bisa Di-test

### 1. GET /api/petugas
**Description**: Get all petugas

**Request**:
```bash
curl -b cookies.txt \
     -H "Accept: application/json" \
     https://slitty-production.up.railway.app/api/petugas
```

**Expected Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "username": "admin",
      "email": "adminK4@gmail.com"
    }
  ],
  "count": 1
}
```

### 2. GET /api/petugas/{id}
**Description**: Get specific petugas

**Request**:
```bash
curl -b cookies.txt \
     -H "Accept: application/json" \
     https://slitty-production.up.railway.app/api/petugas/1
```

**Expected Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "admin",
    "email": "adminK4@gmail.com"
  }
}
```

### 3. POST /api/petugas (Test Validation)
**Description**: Create new petugas (test validation)

**Request** (Invalid data):
```bash
curl -b cookies.txt \
     -X POST \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"username":"test","email":"invalid-email","password":"123"}' \
     https://slitty-production.up.railway.app/api/petugas
```

**Expected Response** (422):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email must be a valid email address."],
    "password": ["The password must be at least 6 characters."]
  }
}
```

### 4. POST /api/petugas (Valid Data)
**Request** (Valid data):
```bash
curl -b cookies.txt \
     -X POST \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"username":"testuser","email":"test@example.com","password":"test123456"}' \
     https://slitty-production.up.railway.app/api/petugas
```

**Expected Response** (201):
```json
{
  "success": true,
  "message": "Petugas berhasil ditambahkan",
  "data": {
    "id": 10,
    "username": "testuser",
    "email": "test@example.com"
  }
}
```

### 5. PUT /api/petugas/{id}
**Description**: Update petugas

**Request**:
```bash
curl -b cookies.txt \
     -X PUT \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{"username":"updateduser","email":"updated@example.com"}' \
     https://slitty-production.up.railway.app/api/petugas/10
```

### 6. DELETE /api/petugas/{id}
**Description**: Delete petugas

**Request**:
```bash
curl -b cookies.txt \
     -X DELETE \
     -H "Accept: application/json" \
     https://slitty-production.up.railway.app/api/petugas/10
```

### 7. GET /api/galeri
**Description**: Get all galeri

**Request**:
```bash
curl -b cookies.txt \
     -H "Accept: application/json" \
     https://slitty-production.up.railway.app/api/galeri
```

## ✅ Validasi yang Di-test

### Petugas Store Validation:
- ✅ `username`: required|string|max:255
- ✅ `email`: required|email|unique:petugas
- ✅ `password`: required|string|min:6

### Petugas Update Validation:
- ✅ `username`: required|string|max:255
- ✅ `email`: required|email|unique:petugas,email,{id}
- ✅ `password`: nullable|string|min:6

## 🔒 Security Notes

1. **API memerlukan authentication** - semua endpoint memerlukan session cookie
2. **CSRF protection** - web forms memerlukan CSRF token
3. **API menggunakan session-based auth** - bukan token-based (Sanctum/Passport)

## 📊 Expected Results

- ✅ **GET /api/petugas**: Return list of petugas (JSON)
- ✅ **GET /api/petugas/{id}**: Return specific petugas (JSON)
- ✅ **POST /api/petugas** (invalid): Return 422 with validation errors
- ✅ **POST /api/petugas** (valid): Return 201 with created data
- ✅ **PUT /api/petugas/{id}**: Return 200 with updated data
- ✅ **DELETE /api/petugas/{id}**: Return 200 with success message

