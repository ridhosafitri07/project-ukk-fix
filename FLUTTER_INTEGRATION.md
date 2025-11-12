# Flutter Integration Guide - SAPRAS API

## Base URL
```
http://127.0.0.1:8000
```
Untuk production, ganti dengan domain/IP server Anda.

## Authentication
Semua endpoint API (kecuali login & register) memerlukan Bearer token.

### Headers Required:
```dart
{
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer YOUR_TOKEN_HERE'
}
```

## API Endpoints

### 1. Authentication

#### Login
```
POST /api/v1/login
Body: {
  "username": "string",
  "password": "string"
}
Response: {
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {...},
    "token": "your-bearer-token"
  }
}
```

#### Register
```
POST /api/v1/register
Body: {
  "username": "string",
  "nama_pengguna": "string",
  "password": "string",
  "password_confirmation": "string"
}
```

#### Logout
```
POST /api/v1/logout
Headers: Authorization: Bearer {token}
```

### 2. Pengaduan (Complaints)

#### Get All Pengaduan
```
GET /api/v1/pengaduan
Headers: Authorization: Bearer {token}
Response: {
  "success": true,
  "data": [...]
}
```

#### Get Pengaduan Detail
```
GET /api/v1/pengaduan/{id}
Headers: Authorization: Bearer {token}
```

#### Create Pengaduan
```
POST /api/v1/pengaduan
Headers: 
  Authorization: Bearer {token}
  Content-Type: multipart/form-data
  
Body (FormData):
  - nama_pengaduan: string
  - deskripsi: string
  - id_lokasi: integer
  - id_item: integer
  - foto: file (image)

Response: {
  "success": true,
  "message": "Pengaduan berhasil dibuat",
  "data": {...}
}
```

#### Update Pengaduan
```
POST /api/v1/pengaduan/{id}
Note: Use POST with _method=PUT for multipart/form-data

Body (FormData):
  - _method: "PUT"
  - nama_pengaduan: string
  - deskripsi: string
  - id_lokasi: integer
  - id_item: integer
  - foto: file (optional)
```

#### Delete Pengaduan
```
DELETE /api/v1/pengaduan/{id}
Headers: Authorization: Bearer {token}
```

### 3. Items (Barang)

#### Get All Items
```
GET /api/v1/items
Headers: Authorization: Bearer {token}
```

#### Get Items by Location
```
GET /api/v1/items/by-location/{id_lokasi}
Headers: Authorization: Bearer {token}
```

### 4. Locations (Lokasi)

#### Get All Locations
```
GET /api/v1/lokasi
Headers: Authorization: Bearer {token}
Response: {
  "success": true,
  "data": [
    {
      "id_lokasi": 1,
      "nama_lokasi": "Lab Komputer",
      "kategori": "Lab",
      ...
    }
  ]
}
```

### 5. Profile

#### Get Profile
```
GET /api/v1/profile
Headers: Authorization: Bearer {token}
```

#### Update Profile
```
POST /api/v1/profile
Headers: 
  Authorization: Bearer {token}
  Content-Type: multipart/form-data

Body (FormData):
  - nama_pengguna: string
  - email: string (optional)
  - no_hp: string (optional)
  - alamat: string (optional)
  - foto_profil: file (optional)
```

#### Change Password
```
POST /api/v1/profile/change-password
Headers: Authorization: Bearer {token}
Body: {
  "current_password": "string",
  "new_password": "string",
  "new_password_confirmation": "string"
}
```

### 6. Permintaan Barang Baru (Item Requests)

#### Create Request
```
POST /api/v1/item-requests
Headers: 
  Authorization: Bearer {token}
  Content-Type: multipart/form-data

Body (FormData):
  - id_pengaduan: integer
  - nama_barang_baru: string
  - lokasi_barang_baru: string
  - alasan_permintaan: string
  - foto_permintaan: file (image)
```

## File URLs (Storage)

### Accessing Uploaded Files

Semua file yang diupload disimpan di `storage/app/public` dan dapat diakses melalui symbolic link `public/storage`.

#### Format URL untuk File:

1. **Foto Pengaduan**
   ```
   {BASE_URL}/storage/pengaduan/{filename}
   Contoh: http://127.0.0.1:8000/storage/pengaduan/1234567890_abc123.jpg
   ```

2. **Foto Profil**
   ```
   {BASE_URL}/storage/profile/{filename}
   atau
   {BASE_URL}/storage/profile-photos/{filename}
   ```

3. **Foto Permintaan Barang**
   ```
   {BASE_URL}/storage/item-requests/{filename}
   ```

#### Menggunakan di Flutter:

```dart
// Contoh menampilkan foto pengaduan
String getFotoUrl(String? fotoPath) {
  if (fotoPath == null || fotoPath.isEmpty) {
    return 'https://via.placeholder.com/150'; // placeholder
  }
  
  const String baseUrl = 'http://127.0.0.1:8000';
  
  // Jika path sudah lengkap dari server
  if (fotoPath.startsWith('http')) {
    return fotoPath;
  }
  
  // Jika path relatif dari storage
  return '$baseUrl/storage/$fotoPath';
}

// Penggunaan di Widget
Image.network(
  getFotoUrl(pengaduan.foto),
  fit: BoxFit.cover,
  errorBuilder: (context, error, stackTrace) {
    return Icon(Icons.broken_image);
  },
  loadingBuilder: (context, child, loadingProgress) {
    if (loadingProgress == null) return child;
    return CircularProgressIndicator();
  },
)
```

## Upload File di Flutter

### Contoh Upload Pengaduan dengan Foto:

```dart
import 'package:http/http.dart' as http;
import 'dart:io';

Future<Map<String, dynamic>> createPengaduan({
  required String token,
  required String namaPengaduan,
  required String deskripsi,
  required int idLokasi,
  required int idItem,
  required File foto,
}) async {
  const String baseUrl = 'http://127.0.0.1:8000';
  final uri = Uri.parse('$baseUrl/api/v1/pengaduan');
  
  var request = http.MultipartRequest('POST', uri);
  
  // Add headers
  request.headers['Authorization'] = 'Bearer $token';
  request.headers['Accept'] = 'application/json';
  
  // Add fields
  request.fields['nama_pengaduan'] = namaPengaduan;
  request.fields['deskripsi'] = deskripsi;
  request.fields['id_lokasi'] = idLokasi.toString();
  request.fields['id_item'] = idItem.toString();
  
  // Add file
  request.files.add(
    await http.MultipartFile.fromPath(
      'foto',
      foto.path,
      filename: foto.path.split('/').last,
    ),
  );
  
  // Send request
  final streamedResponse = await request.send();
  final response = await http.Response.fromStream(streamedResponse);
  
  if (response.statusCode == 201 || response.statusCode == 200) {
    return json.decode(response.body);
  } else {
    throw Exception('Failed to create pengaduan: ${response.body}');
  }
}
```

## Error Handling

API mengembalikan format error standar:

```json
{
  "success": false,
  "message": "Error message here",
  "errors": {
    "field_name": ["Error detail"]
  }
}
```

## Important Notes

1. **Symbolic Link**: Pastikan symbolic link sudah dibuat dengan command:
   ```bash
   php artisan storage:link
   ```

2. **CORS**: Jika Flutter web atau akses dari domain berbeda, tambahkan CORS headers di Laravel.

3. **File Permissions**: Pastikan folder `storage/app/public` memiliki permission write (755).

4. **Production**: 
   - Ganti `http://127.0.0.1:8000` dengan domain/IP server production
   - Gunakan HTTPS untuk keamanan
   - Setup proper CORS policy

5. **Token Management**: 
   - Simpan token di secure storage (flutter_secure_storage)
   - Handle token expiration
   - Refresh token jika diperlukan

## Testing dengan Postman

1. Import collection dengan endpoint di atas
2. Set environment variable `BASE_URL` = `http://127.0.0.1:8000`
3. Set `TOKEN` setelah login
4. Test semua endpoint sebelum integrasi ke Flutter

## File Structure di Storage

```
storage/app/public/
├── pengaduan/           # Foto pengaduan
├── profile/             # Foto profil user
├── profile-photos/      # Alternative foto profil
└── item-requests/       # Foto permintaan barang
```

## Response Format dari API

Semua response API mengikuti format:

### Success Response:
```json
{
  "success": true,
  "message": "Success message",
  "data": { ... }
}
```

### Error Response:
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["validation error"]
  }
}
```

### Pengaduan Object:
```json
{
  "id_pengaduan": 1,
  "nama_pengaduan": "AC Rusak",
  "deskripsi": "AC tidak dingin",
  "lokasi": "Lab Komputer",
  "foto": "pengaduan/1234567890_abc.jpg",
  "status": "Diajukan",
  "tgl_pengajuan": "2025-11-09",
  "user": {
    "id_user": 1,
    "nama_pengguna": "John Doe",
    "foto_profil": "profile/user1.jpg"
  }
}
```

**Full URL untuk foto:** `http://127.0.0.1:8000/storage/pengaduan/1234567890_abc.jpg`
