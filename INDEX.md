# 📚 Dokumentasi API - Index

Selamat datang di dokumentasi API UKK Project untuk Flutter integration!

## 🎯 Mulai dari Mana?

### Untuk Developer yang Baru Memulai
👉 **Baca file ini terlebih dahulu:** `QUICK_START.md`

### Untuk Developer yang Ingin Detail Lengkap
👉 **Baca file ini:** `README_API.md`

---

## 📖 Daftar File Dokumentasi

| No | File | Deskripsi | Kapan Menggunakan |
|----|------|-----------|-------------------|
| 1️⃣ | **QUICK_START.md** | Quick reference untuk memulai | Ingin langsung test API |
| 2️⃣ | **README_API.md** | Overview lengkap + Flutter examples | Butuh gambaran besar + contoh code |
| 3️⃣ | **API_DOCUMENTATION.md** | Detail semua endpoints | Butuh referensi endpoint spesifik |
| 4️⃣ | **SETUP_API.md** | Setup & installation guide | Butuh install/configure |
| 5️⃣ | **API_TESTING_GUIDE.md** | Testing dengan Postman | Ingin test manual API |
| 6️⃣ | **Postman_Collection_API.json** | Postman collection | Import ke Postman/Thunder Client |

---

## 🚀 Quick Links

### Setup & Installation
```
📄 SETUP_API.md
   - Install Laravel Sanctum
   - Configuration
   - Troubleshooting
```

### API Reference
```
📄 API_DOCUMENTATION.md
   - Authentication endpoints
   - Profile endpoints  
   - Pengaduan endpoints
   - Item endpoints
   - Request/Response examples
   - Error codes
```

### Testing
```
📄 API_TESTING_GUIDE.md
   - Test scenarios
   - Example requests
   - Expected responses
   - Error testing

📄 Postman_Collection_API.json
   - Import ke Postman
   - Pre-configured requests
   - Auto token save
```

### Flutter Integration
```
📄 README_API.md
   - Flutter setup
   - Example code (Login, Get Data, Upload)
   - Tips & best practices
```

---

## ⚡ Quick Start (Super Quick)

### 1. Jalankan Server
```bash
cd "d:\UKK PROJECT\ukkaku"
php artisan serve
```

### 2. Test Login (cURL)
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"username":"test","password":"password123"}'
```

### 3. Import Postman Collection
File: `Postman_Collection_API.json`

---

## 📊 API Summary

| Category | Endpoints | Auth Required | Role |
|----------|-----------|---------------|------|
| Authentication | 4 | No (Login/Register) | - |
| Profile | 4 | Yes | All |
| Items | 2 | Yes | All |
| Pengaduan | 6 | Yes | Guru, Siswa |
| **Total** | **16** | - | - |

---

## 🎓 Learning Path

### Level 1: Beginner
1. Baca `QUICK_START.md`
2. Jalankan server
3. Test dengan Postman (`Postman_Collection_API.json`)
4. Pahami flow: Register → Login → Get Data

### Level 2: Intermediate
1. Baca `API_DOCUMENTATION.md`
2. Pahami semua endpoints
3. Test error scenarios
4. Implement di Flutter (basic)

### Level 3: Advanced
1. Baca `README_API.md` bagian best practices
2. Implement file upload
3. Handle error dengan baik
4. Optimize performance

---

## 🔧 Development Files

### Backend (Laravel)
```
routes/api.php                                      - API routing
app/Http/Controllers/Api/
  ├── AuthApiController.php                        - Authentication
  ├── PengaduanApiController.php                   - Pengaduan CRUD
  ├── UserProfileApiController.php                 - Profile management
  └── ItemApiController.php                        - Items list
app/Http/Middleware/CheckApiRole.php               - Role checking
app/Models/User.php                                - User model (with HasApiTokens)
bootstrap/app.php                                  - API routes config
```

### Documentation
```
INDEX.md                                           - File ini
QUICK_START.md                                     - Quick reference
README_API.md                                      - Complete guide
API_DOCUMENTATION.md                               - API reference
SETUP_API.md                                       - Setup guide
API_TESTING_GUIDE.md                               - Testing guide
Postman_Collection_API.json                        - Postman collection
```

---

## 🎯 Use Cases

### Use Case 1: Testing API
```
1. Baca: QUICK_START.md
2. Import: Postman_Collection_API.json
3. Test: Authentication → Profile → Pengaduan
4. Referensi: API_TESTING_GUIDE.md
```

### Use Case 2: Flutter Development
```
1. Baca: README_API.md (Flutter Integration section)
2. Copy: Example code
3. Referensi: API_DOCUMENTATION.md (untuk detail endpoint)
4. Test: Dengan real device
```

### Use Case 3: Troubleshooting
```
1. Cek: QUICK_START.md (Common Issues section)
2. Atau: SETUP_API.md (Troubleshooting section)
3. Debug: Dengan tips di API_TESTING_GUIDE.md
```

---

## 📞 Support

### Dokumentasi Tidak Jelas?
- Baca bagian "Troubleshooting" di setiap file
- Cek contoh code di `README_API.md`

### Error Saat Testing?
- Lihat bagian "Common Issues" di `QUICK_START.md`
- Cek error scenarios di `API_TESTING_GUIDE.md`

### Ingin Fitur Tambahan?
- Lihat bagian "Next Steps" di `QUICK_START.md`

---

## ✅ Checklist Sebelum Deploy

### Development
- [ ] Semua endpoint sudah di-test
- [ ] Error handling sudah proper
- [ ] File upload berfungsi
- [ ] Token management berfungsi

### Security
- [ ] Update CORS settings
- [ ] Secure .env file
- [ ] Enable rate limiting
- [ ] Use HTTPS (production)

### Performance
- [ ] Add caching where needed
- [ ] Optimize database queries
- [ ] Implement pagination
- [ ] Test dengan load testing

### Documentation
- [ ] API documentation up-to-date
- [ ] Example code tested
- [ ] Postman collection updated

---

## 🎉 Ready to Start?

### Pilih Jalur Anda:

**Jalur A: Quick Testing** (5 menit)
```
QUICK_START.md → Postman_Collection_API.json → Test!
```

**Jalur B: Full Understanding** (30 menit)
```
README_API.md → API_DOCUMENTATION.md → Testing
```

**Jalur C: Direct Flutter** (jika sudah paham API)
```
README_API.md (Flutter section) → Code → Test
```

---

**Selamat Coding! 🚀**

---

*Last Updated: November 7, 2025*  
*Status: ✅ Production Ready*  
*Total Endpoints: 16*
