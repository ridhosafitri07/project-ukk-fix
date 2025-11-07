# Testing API dengan Postman / Thunder Client

## Setup Awal
1. Pastikan Laravel server berjalan: `php artisan serve`
2. Base URL: `http://localhost:8000/api/v1`

---

## 1. Test Register (Guru)

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/register`  
**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "username": "guru_test",
    "password": "password123",
    "password_confirmation": "password123",
    "nama_pengguna": "Guru Testing",
    "role": "guru",
    "telp_user": "081234567890",
    "bio": "Guru Matematika"
}
```

**Expected Response (201):**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id_user": 1,
            "username": "guru_test",
            "nama_pengguna": "Guru Testing",
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

**✅ Save the token** untuk request selanjutnya!

---

## 2. Test Login

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/login`  
**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "username": "guru_test",
    "password": "password123"
}
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": { ... },
        "token": "2|xyz789...",
        "token_type": "Bearer"
    }
}
```

---

## 3. Test Get Current User

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/user`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": {
        "id_user": 1,
        "username": "guru_test",
        "nama_pengguna": "Guru Testing",
        "role": "guru",
        ...
    }
}
```

---

## 4. Test Get Items (Kategori Barang)

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/items`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id_item": 1,
            "nama_item": "Kursi",
            "deskripsi": null
        },
        {
            "id_item": 2,
            "nama_item": "Meja",
            "deskripsi": null
        }
    ]
}
```

---

## 5. Test Create Pengaduan (dengan foto)

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/pengaduan`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Body (form-data):**
```
nama_pengaduan: "Kursi Rusak"
deskripsi: "Kursi di kelas A1 kaki nya patah"
lokasi: "Ruang Kelas A1"
id_item: 1
foto: [pilih file gambar]
```

**Expected Response (201):**
```json
{
    "success": true,
    "message": "Pengaduan berhasil dibuat",
    "data": {
        "id_pengaduan": 1,
        "nama_pengaduan": "Kursi Rusak",
        "deskripsi": "Kursi di kelas A1 kaki nya patah",
        "lokasi": "Ruang Kelas A1",
        "foto": "http://localhost:8000/storage/pengaduan/123456_photo.jpg",
        "status": "pending",
        "tgl_pengajuan": "2024-01-01 10:00:00",
        "item": {
            "id_item": 1,
            "nama_item": "Kursi"
        },
        "petugas": null
    }
}
```

---

## 6. Test Get All Pengaduan

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/pengaduan`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Expected Response (200):**
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

---

## 7. Test Get Pengaduan by Status

**Method:** GET  
**URL:** `http://localhost:8000/api/v1/pengaduan/status/pending`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Expected Response (200):**
```json
{
    "success": true,
    "data": [...]
}
```

---

## 8. Test Update Pengaduan

**Method:** PUT  
**URL:** `http://localhost:8000/api/v1/pengaduan/1`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "nama_pengaduan": "Kursi Rusak - Updated",
    "deskripsi": "Deskripsi sudah diupdate",
    "lokasi": "Ruang A2"
}
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Pengaduan berhasil diupdate",
    "data": { ... }
}
```

**Note:** Hanya bisa update jika status masih "pending"

---

## 9. Test Update Profile

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/profile/update`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "nama_pengguna": "Guru Testing Updated",
    "bio": "Guru Matematika dan Fisika",
    "telp_user": "081234567899"
}
```

---

## 10. Test Update Photo Profile

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/profile/update-photo`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Body (form-data):**
```
foto_profil: [pilih file gambar]
```

---

## 11. Test Change Password

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/profile/change-password`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
    "current_password": "password123",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}
```

**Note:** Akan mendapat token baru setelah berhasil

---

## 12. Test Delete Pengaduan

**Method:** DELETE  
**URL:** `http://localhost:8000/api/v1/pengaduan/1`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Pengaduan berhasil dihapus"
}
```

**Note:** Hanya bisa delete jika status masih "pending"

---

## 13. Test Logout

**Method:** POST  
**URL:** `http://localhost:8000/api/v1/logout`  
**Headers:**
```
Authorization: Bearer {your_token_here}
Accept: application/json
```

**Expected Response (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

## Error Scenarios to Test

### 1. Login with Wrong Password
```json
{
    "success": false,
    "message": "Username atau password salah"
}
```

### 2. Access Without Token
```json
{
    "message": "Unauthenticated."
}
```

### 3. Access with Invalid Role
```json
{
    "success": false,
    "message": "Forbidden - Anda tidak memiliki akses"
}
```

### 4. Validation Error
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "nama_pengaduan": ["The nama pengaduan field is required."]
    }
}
```

---

## Tips Testing

1. **Simpan Token**: Setiap kali login/register, simpan token yang diberikan
2. **Environment Variables**: Di Postman, buat variable untuk `base_url` dan `token`
3. **Test Sequence**: Lakukan testing secara berurutan (register → login → get data → create → update → delete)
4. **Check Database**: Verifikasi data di database setelah create/update/delete
5. **Test Edge Cases**: Coba akses dengan role yang salah, token expired, dll

---

## Troubleshooting

### Error: "Route [login] not defined"
- Pastikan routes/api.php sudah ada
- Jalankan: `php artisan route:clear`

### Error: "Class 'Laravel\Sanctum\HasApiTokens' not found"
- Jalankan: `composer require laravel/sanctum`
- Jalankan: `php artisan migrate`

### Error: "SQLSTATE[42S02]: Base table or view not found"
- Jalankan: `php artisan migrate`

### Storage link belum dibuat
- Jalankan: `php artisan storage:link`

### File upload tidak berfungsi
- Pastikan folder `storage/app/public` ada
- Pastikan `storage/app/public/pengaduan` ada
- Pastikan `storage/app/public/profile` ada
- Jalankan: `php artisan storage:link`
