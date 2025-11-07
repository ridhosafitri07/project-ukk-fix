# Setup Laravel Sanctum untuk API

## Langkah-langkah Instalasi:

### 1. Install Laravel Sanctum
```bash
composer require laravel/sanctum
```

### 2. Publish Sanctum Configuration
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Run Migration
```bash
php artisan migrate
```

### 4. Update .env (Opsional)
Tambahkan atau pastikan setting berikut ada di file .env:
```
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1
SESSION_DRIVER=cookie
```

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 6. Test API
Gunakan Postman atau Thunder Client untuk test API:

**Test Login:**
```
POST http://localhost:8000/api/v1/login
Content-Type: application/json

{
    "username": "test_user",
    "password": "password123"
}
```

**Test Get Pengaduan (dengan token):**
```
GET http://localhost:8000/api/v1/pengaduan
Authorization: Bearer {your_token_here}
```

## Files yang telah dibuat:

1. **routes/api.php** - API Routes
2. **app/Http/Controllers/Api/AuthApiController.php** - Authentication API
3. **app/Http/Controllers/Api/PengaduanApiController.php** - Pengaduan API
4. **app/Http/Controllers/Api/UserProfileApiController.php** - Profile API
5. **app/Http/Middleware/CheckApiRole.php** - Role Middleware
6. **API_DOCUMENTATION.md** - Complete API Documentation

## Testing dengan Postman/Thunder Client

### 1. Register (Guru/Siswa)
```
POST /api/v1/register
Body:
{
    "username": "guru_test",
    "password": "password123",
    "password_confirmation": "password123",
    "nama_pengguna": "Guru Test",
    "role": "guru",
    "telp_user": "081234567890"
}
```

### 2. Login
```
POST /api/v1/login
Body:
{
    "username": "guru_test",
    "password": "password123"
}
```

Response akan berisi token yang harus disimpan.

### 3. Get Pengaduan (Authenticated)
```
GET /api/v1/pengaduan
Headers:
Authorization: Bearer {token_dari_login}
```

### 4. Create Pengaduan
```
POST /api/v1/pengaduan
Headers:
Authorization: Bearer {token_dari_login}
Content-Type: multipart/form-data

Body (form-data):
nama_pengaduan: "Kursi Rusak"
deskripsi: "Kursi di kelas A1 rusak"
lokasi: "Ruang A1"
id_item: 1
foto: [file upload]
```

## Troubleshooting

### Error: Class 'Laravel\Sanctum\HasApiTokens' not found
Jalankan:
```bash
composer require laravel/sanctum
```

### Error: CSRF token mismatch
Pastikan API routes menggunakan `api` middleware group (sudah di-setup di routes/api.php)

### Error: Unauthenticated
- Pastikan token dikirim dengan format: `Bearer {token}`
- Pastikan header `Authorization` ada di request
- Pastikan token valid (belum expired atau di-revoke)

### Error: Route not found
Pastikan routes/api.php sudah di-load. Check file bootstrap/app.php atau app/Providers/RouteServiceProvider.php

## Integration dengan Flutter

Lihat file **API_DOCUMENTATION.md** untuk contoh lengkap penggunaan di Flutter.

Key points:
1. Simpan token setelah login/register
2. Gunakan token di setiap request yang memerlukan authentication
3. Handle error response dengan baik
4. Gunakan FormData untuk upload file
