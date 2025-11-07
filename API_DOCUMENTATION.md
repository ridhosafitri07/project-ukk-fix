# API Documentation - UKK Project (Pengguna)

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
Semua endpoint yang memerlukan autentikasi harus menyertakan token di header:
```
Authorization: Bearer {your_token}
```

---

## 1. Authentication Endpoints

### 1.1 Register (Guru & Siswa)
**Endpoint:** `POST /api/v1/register`

**Request Body:**
```json
{
    "username": "john_doe",
    "password": "password123",
    "password_confirmation": "password123",
    "nama_pengguna": "John Doe",
    "role": "guru",
    "telp_user": "081234567890",
    "bio": "Guru Matematika"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id_user": 1,
            "username": "john_doe",
            "nama_pengguna": "John Doe",
            "role": "guru",
            "foto_profil": null,
            "bio": "Guru Matematika",
            "telp_user": "081234567890"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 1.2 Login
**Endpoint:** `POST /api/v1/login`

**Request Body:**
```json
{
    "username": "john_doe",
    "password": "password123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id_user": 1,
            "username": "john_doe",
            "nama_pengguna": "John Doe",
            "role": "guru",
            "foto_profil": "http://domain.com/storage/profile/photo.jpg",
            "bio": "Guru Matematika",
            "telp_user": "081234567890"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### 1.3 Logout
**Endpoint:** `POST /api/v1/logout`
**Auth:** Required

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

### 1.4 Get Current User
**Endpoint:** `GET /api/v1/user`
**Auth:** Required

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id_user": 1,
        "username": "john_doe",
        "nama_pengguna": "John Doe",
        "role": "guru",
        "foto_profil": "http://domain.com/storage/profile/photo.jpg",
        "bio": "Guru Matematika",
        "telp_user": "081234567890",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

---

## 2. Profile Endpoints

### 2.1 Get Profile
**Endpoint:** `GET /api/v1/profile`
**Auth:** Required

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id_user": 1,
        "username": "john_doe",
        "nama_pengguna": "John Doe",
        "role": "guru",
        "foto_profil": "http://domain.com/storage/profile/photo.jpg",
        "bio": "Guru Matematika",
        "telp_user": "081234567890",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### 2.2 Update Profile
**Endpoint:** `POST /api/v1/profile/update`
**Auth:** Required

**Request Body:**
```json
{
    "nama_pengguna": "John Doe Updated",
    "bio": "Guru Matematika SMA",
    "telp_user": "081234567899"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Profile berhasil diupdate",
    "data": {
        "id_user": 1,
        "username": "john_doe",
        "nama_pengguna": "John Doe Updated",
        "role": "guru",
        "foto_profil": "http://domain.com/storage/profile/photo.jpg",
        "bio": "Guru Matematika SMA",
        "telp_user": "081234567899"
    }
}
```

### 2.3 Update Photo Profile
**Endpoint:** `POST /api/v1/profile/update-photo`
**Auth:** Required
**Content-Type:** multipart/form-data

**Request Body:**
- `foto_profil`: File (image: jpeg, png, jpg, max: 2MB)

**Response Success (200):**
```json
{
    "success": true,
    "message": "Foto profil berhasil diupdate",
    "data": {
        "foto_profil": "http://domain.com/storage/profile/123_john_doe.jpg"
    }
}
```

### 2.4 Change Password
**Endpoint:** `POST /api/v1/profile/change-password`
**Auth:** Required

**Request Body:**
```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Password berhasil diubah",
    "data": {
        "token": "2|new_token...",
        "token_type": "Bearer"
    }
}
```

---

## 3. Pengaduan Endpoints (Guru & Siswa Only)

### 3.1 Get All Pengaduan
**Endpoint:** `GET /api/v1/pengaduan`
**Auth:** Required
**Role:** guru, siswa

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id_pengaduan": 1,
            "nama_pengaduan": "Kursi Rusak",
            "deskripsi": "Kursi di kelas A1 rusak",
            "lokasi": "Ruang A1",
            "foto": "http://domain.com/storage/pengaduan/photo.jpg",
            "status": "pending",
            "tgl_pengajuan": "2024-01-01 10:00:00",
            "tgl_verifikasi": null,
            "tgl_selesai": null,
            "catatan_admin": null,
            "saran_petugas": null,
            "item": {
                "id_item": 1,
                "nama_item": "Kursi"
            },
            "petugas": null
        }
    ]
}
```

### 3.2 Get Pengaduan by Status
**Endpoint:** `GET /api/v1/pengaduan/status/{status}`
**Auth:** Required
**Role:** guru, siswa
**Status:** pending, proses, selesai, ditolak

**Example:** `GET /api/v1/pengaduan/status/pending`

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id_pengaduan": 1,
            "nama_pengaduan": "Kursi Rusak",
            "status": "pending",
            ...
        }
    ]
}
```

### 3.3 Get Single Pengaduan
**Endpoint:** `GET /api/v1/pengaduan/{id}`
**Auth:** Required
**Role:** guru, siswa

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id_pengaduan": 1,
        "nama_pengaduan": "Kursi Rusak",
        "deskripsi": "Kursi di kelas A1 rusak",
        "lokasi": "Ruang A1",
        "foto": "http://domain.com/storage/pengaduan/photo.jpg",
        "status": "pending",
        "tgl_pengajuan": "2024-01-01 10:00:00",
        "tgl_verifikasi": null,
        "tgl_selesai": null,
        "catatan_admin": null,
        "saran_petugas": null,
        "item": {
            "id_item": 1,
            "nama_item": "Kursi"
        },
        "petugas": null
    }
}
```

### 3.4 Create Pengaduan
**Endpoint:** `POST /api/v1/pengaduan`
**Auth:** Required
**Role:** guru, siswa
**Content-Type:** multipart/form-data

**Request Body:**
- `nama_pengaduan`: String (required)
- `deskripsi`: String (required)
- `lokasi`: String (required)
- `id_item`: Integer (required, must exist in item table)
- `foto`: File (optional, image: jpeg, png, jpg, max: 2MB)

**Response Success (201):**
```json
{
    "success": true,
    "message": "Pengaduan berhasil dibuat",
    "data": {
        "id_pengaduan": 1,
        "nama_pengaduan": "Kursi Rusak",
        "deskripsi": "Kursi di kelas A1 rusak",
        "lokasi": "Ruang A1",
        "foto": "http://domain.com/storage/pengaduan/photo.jpg",
        "status": "pending",
        "tgl_pengajuan": "2024-01-01 10:00:00",
        ...
    }
}
```

### 3.5 Update Pengaduan
**Endpoint:** `PUT /api/v1/pengaduan/{id}`
**Auth:** Required
**Role:** guru, siswa
**Note:** Hanya bisa update jika status = pending

**Request Body:**
```json
{
    "nama_pengaduan": "Kursi Rusak Updated",
    "deskripsi": "Deskripsi updated",
    "lokasi": "Ruang A2",
    "id_item": 2
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Pengaduan berhasil diupdate",
    "data": {
        "id_pengaduan": 1,
        "nama_pengaduan": "Kursi Rusak Updated",
        ...
    }
}
```

### 3.6 Delete Pengaduan
**Endpoint:** `DELETE /api/v1/pengaduan/{id}`
**Auth:** Required
**Role:** guru, siswa
**Note:** Hanya bisa delete jika status = pending

**Response Success (200):**
```json
{
    "success": true,
    "message": "Pengaduan berhasil dihapus"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "username": ["The username field is required."],
        "password": ["The password field is required."]
    }
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Username atau password salah"
}
```

### Forbidden (403)
```json
{
    "success": false,
    "message": "Forbidden - Anda tidak memiliki akses"
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "Pengaduan tidak ditemukan"
}
```

---

## Status Pengaduan
- `pending`: Pengaduan baru, menunggu verifikasi admin
- `proses`: Pengaduan sedang ditangani petugas
- `selesai`: Pengaduan telah selesai ditangani
- `ditolak`: Pengaduan ditolak oleh admin

---

## Role User
- `admin`: Administrator sistem
- `petugas`: Petugas yang menangani pengaduan
- `guru`: Guru yang dapat membuat pengaduan
- `siswa`: Siswa yang dapat membuat pengaduan

---

## Notes untuk Flutter Developer

1. **Base URL**: Ganti dengan domain server Laravel Anda
2. **Token Storage**: Simpan token di SharedPreferences atau Secure Storage
3. **Token Header**: Setiap request yang memerlukan auth harus include header: `Authorization: Bearer {token}`
4. **Multipart Upload**: Gunakan FormData untuk upload foto
5. **Role Access**: Endpoint pengaduan hanya bisa diakses oleh role `guru` dan `siswa`
6. **Status Update**: User tidak bisa mengubah status pengaduan, hanya admin/petugas yang bisa
7. **Edit/Delete**: Pengaduan hanya bisa di-edit/delete jika statusnya masih `pending`

## Example Flutter HTTP Request

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

// Login Example
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
    // Save token to storage
  }
}

// Get Pengaduan with Auth
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
    // Process data
  }
}

// Upload with Photo
Future<void> createPengaduan(String token, Map<String, dynamic> data, File? photo) async {
  var request = http.MultipartRequest(
    'POST',
    Uri.parse('http://your-domain.com/api/v1/pengaduan'),
  );
  
  request.headers['Authorization'] = 'Bearer $token';
  request.fields['nama_pengaduan'] = data['nama_pengaduan'];
  request.fields['deskripsi'] = data['deskripsi'];
  request.fields['lokasi'] = data['lokasi'];
  request.fields['id_item'] = data['id_item'].toString();
  
  if (photo != null) {
    request.files.add(await http.MultipartFile.fromPath('foto', photo.path));
  }
  
  var response = await request.send();
  // Handle response
}
```
