# 🚀 API Laravel untuk Flutter - Role Pengguna (Guru & Siswa)

## 📋 Summary

API ini telah berhasil dibuat untuk menghubungkan aplikasi Laravel dengan Flutter, khusus untuk role **Pengguna** (Guru dan Siswa). API ini menggunakan **Laravel Sanctum** untuk authentication dengan token-based system.

---

## ✅ Files yang Telah Dibuat

### 1. **Routes**
- `routes/api.php` - Routing untuk semua API endpoints

### 2. **Controllers API**
- `app/Http/Controllers/Api/AuthApiController.php` - Authentication (Login, Register, Logout, Get User)
- `app/Http/Controllers/Api/PengaduanApiController.php` - CRUD Pengaduan (khusus Guru & Siswa)
- `app/Http/Controllers/Api/UserProfileApiController.php` - Profile Management
- `app/Http/Controllers/Api/ItemApiController.php` - Get Items/Kategori Barang

### 3. **Middleware**
- `app/Http/Middleware/CheckApiRole.php` - Role checking untuk API

### 4. **Model Updates**
- `app/Models/User.php` - Ditambahkan `HasApiTokens` trait untuk Sanctum

### 5. **Kernel Updates**
- `app/Http/Kernel.php` - Ditambahkan middleware alias untuk role checking

### 6. **Documentation**
- `API_DOCUMENTATION.md` - Dokumentasi lengkap API dengan contoh request/response
- `SETUP_API.md` - Panduan setup dan instalasi
- `API_TESTING_GUIDE.md` - Panduan testing dengan Postman/Thunder Client
- `README_API.md` - Summary lengkap (file ini)

---

## 🎯 Features API

### Authentication
✅ Register (Guru & Siswa only)  
✅ Login  
✅ Logout  
✅ Get Current User  

### Profile Management
✅ Get Profile  
✅ Update Profile  
✅ Update Photo Profile  
✅ Change Password  

### Pengaduan (Guru & Siswa Only)
✅ Get All Pengaduan (by user)  
✅ Get Pengaduan by Status  
✅ Get Single Pengaduan  
✅ Create Pengaduan (with photo upload)  
✅ Update Pengaduan (only if status = pending)  
✅ Delete Pengaduan (only if status = pending)  

### Items
✅ Get All Items (kategori barang)  
✅ Get Single Item  

---

## 🔑 API Endpoints

### Base URL
```
http://your-domain.com/api/v1
```

### Authentication (No Auth Required)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/login` | Login user |
| POST | `/register` | Register guru/siswa |

### Authenticated Endpoints
| Method | Endpoint | Description | Role |
|--------|----------|-------------|------|
| POST | `/logout` | Logout current user | All |
| GET | `/user` | Get current user data | All |
| GET | `/profile` | Get user profile | All |
| POST | `/profile/update` | Update profile | All |
| POST | `/profile/update-photo` | Update photo profile | All |
| POST | `/profile/change-password` | Change password | All |
| GET | `/items` | Get all items | All |
| GET | `/items/{id}` | Get single item | All |
| GET | `/pengaduan` | Get all pengaduan | Guru, Siswa |
| GET | `/pengaduan/{id}` | Get single pengaduan | Guru, Siswa |
| POST | `/pengaduan` | Create pengaduan | Guru, Siswa |
| PUT | `/pengaduan/{id}` | Update pengaduan | Guru, Siswa |
| DELETE | `/pengaduan/{id}` | Delete pengaduan | Guru, Siswa |
| GET | `/pengaduan/status/{status}` | Get by status | Guru, Siswa |

---

## 🛠️ Setup Instructions

### 1. Install Laravel Sanctum ✅ (Sudah selesai)
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Clear Cache ✅ (Sudah selesai)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 3. Storage Link ✅ (Sudah selesai)
```bash
php artisan storage:link
```

### 4. Start Server
```bash
php artisan serve
```

---

## 📱 Integration dengan Flutter

### 1. Dependencies Flutter
Tambahkan di `pubspec.yaml`:
```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2
```

### 2. Example Code

#### Login
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> login(String username, String password) async {
  final response = await http.post(
    Uri.parse('http://your-domain.com/api/v1/login'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'username': username,
      'password': password,
    }),
  );
  
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    String token = data['data']['token'];
    // Save token to SharedPreferences
  }
}
```

#### Get Pengaduan
```dart
Future<void> getPengaduan(String token) async {
  final response = await http.get(
    Uri.parse('http://your-domain.com/api/v1/pengaduan'),
    headers: {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
    },
  );
  
  if (response.statusCode == 200) {
    final data = jsonDecode(response.body);
    List pengaduans = data['data'];
    // Process data
  }
}
```

#### Create Pengaduan with Photo
```dart
import 'dart:io';

Future<void> createPengaduan(
  String token, 
  String nama, 
  String deskripsi, 
  String lokasi, 
  int idItem,
  File? photo
) async {
  var request = http.MultipartRequest(
    'POST',
    Uri.parse('http://your-domain.com/api/v1/pengaduan'),
  );
  
  request.headers['Authorization'] = 'Bearer $token';
  request.fields['nama_pengaduan'] = nama;
  request.fields['deskripsi'] = deskripsi;
  request.fields['lokasi'] = lokasi;
  request.fields['id_item'] = idItem.toString();
  
  if (photo != null) {
    request.files.add(await http.MultipartFile.fromPath('foto', photo.path));
  }
  
  var response = await request.send();
  var responseData = await response.stream.bytesToString();
  // Handle response
}
```

---

## 🔐 Security Features

✅ Token-based authentication dengan Laravel Sanctum  
✅ Password hashing dengan bcrypt  
✅ Role-based access control  
✅ CSRF protection (for web)  
✅ API rate limiting  
✅ Input validation  

---

## 📊 Status Pengaduan

| Status | Description |
|--------|-------------|
| `pending` | Pengaduan baru, menunggu verifikasi admin |
| `proses` | Pengaduan sedang ditangani petugas |
| `selesai` | Pengaduan telah selesai ditangani |
| `ditolak` | Pengaduan ditolak oleh admin |

---

## 🎭 User Roles

| Role | Description | API Access |
|------|-------------|------------|
| `admin` | Administrator sistem | No (khusus web) |
| `petugas` | Petugas penanganan | No (khusus web) |
| `guru` | Guru yang membuat pengaduan | Yes ✅ |
| `siswa` | Siswa yang membuat pengaduan | Yes ✅ |

---

## 🧪 Testing

### Quick Test dengan cURL

#### 1. Register
```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "guru_test",
    "password": "password123",
    "password_confirmation": "password123",
    "nama_pengguna": "Guru Test",
    "role": "guru"
  }'
```

#### 2. Login
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "guru_test",
    "password": "password123"
  }'
```

#### 3. Get Pengaduan (replace YOUR_TOKEN)
```bash
curl -X GET http://localhost:8000/api/v1/pengaduan \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Untuk testing lengkap, lihat file **API_TESTING_GUIDE.md**

---

## ⚠️ Important Notes

1. **Base URL**: Ganti `http://your-domain.com` dengan domain server Anda
2. **Token Storage**: Simpan token dengan aman di SharedPreferences atau Secure Storage
3. **Token Format**: Selalu gunakan format `Bearer {token}` di header Authorization
4. **File Upload**: Gunakan `multipart/form-data` untuk upload foto
5. **Role Access**: Endpoint pengaduan hanya untuk role `guru` dan `siswa`
6. **Edit/Delete**: Pengaduan hanya bisa diedit/dihapus jika status = `pending`
7. **Status Change**: User tidak bisa mengubah status pengaduan

---

## 📞 Troubleshooting

### Error: Class 'Laravel\Sanctum\HasApiTokens' not found
```bash
composer require laravel/sanctum
php artisan config:clear
```

### Error: Route not found
```bash
php artisan route:clear
php artisan route:list | grep api
```

### Error: Unauthenticated
- Pastikan token valid
- Pastikan format header: `Authorization: Bearer {token}`
- Pastikan token belum expired atau revoked

### Error: Storage link
```bash
php artisan storage:link
```

### Error: Permission denied (upload)
```bash
# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Windows: Pastikan folder memiliki write permission
```

---

## 📚 Additional Resources

- **Laravel Sanctum Documentation**: https://laravel.com/docs/sanctum
- **API Documentation**: Lihat file `API_DOCUMENTATION.md`
- **Testing Guide**: Lihat file `API_TESTING_GUIDE.md`
- **Setup Guide**: Lihat file `SETUP_API.md`

---

## ✨ Success!

API sudah siap digunakan! 🎉

Untuk memulai testing:
1. Jalankan `php artisan serve`
2. Test dengan Postman/Thunder Client menggunakan panduan di `API_TESTING_GUIDE.md`
3. Integrate dengan Flutter menggunakan contoh code di atas

**Happy Coding!** 🚀

---

**Created by:** GitHub Copilot  
**Date:** November 7, 2025  
**Laravel Version:** 11.x  
**Sanctum Version:** 4.2.0
