# Fitur: Pengaduan dengan Permintaan Barang Baru

## Deskripsi Fitur
Fitur ini memungkinkan pengguna untuk membuat pengaduan dengan meminta barang yang tidak tersedia di daftar items master. Pengaduan dan permintaan barang baru akan tersimpan dengan status menunggu persetujuan dari admin. Pengaduan akan tetap terlihat di sidebar pengguna meski barang belum disetujui dan dimasukkan ke items master.

---

## Alur Kerja Lengkap

### 1. **Pengguna Membuat Pengaduan dengan Barang Baru**
- Pengguna membuka halaman "Buat Pengaduan"
- Mengisi form pengaduan:
  - ✅ Judul Pengaduan
  - ✅ Lokasi / Ruangan
  - ✅ Deskripsi Masalah
  - ✅ Foto Bukti
  
- Pada bagian **Item/Barang**:
  - Memilih lokasi terlebih dahulu
  - Jika barang ada di daftar → **Pilih barang yang ada**
  - Jika barang tidak ada → **Centang "Barang Lainnya"** dan ketik nama barang baru

- Pengguna submit form
- Data disimpan ke dalam 2 tabel:
  - `pengaduan` → dengan status "Diajukan" dan `id_item = NULL`
  - `temporary_item` → dengan `status_permintaan = "Menunggu Persetujuan"`

### 2. **Pengguna Melihat Pengaduan di Sidebar**
- Pengaduan muncul di halaman "Daftar Pengaduan" pengguna
- **Badge tambahan ditampilkan**:
  - 🟣 **"[N] Barang Baru (Menunggu Persetujuan)"** jika ada temporary items yang pending
  
- Pengguna bisa klik pengaduan untuk melihat detail

### 3. **Pengguna Melihat Detail Pengaduan**
Di halaman detail pengaduan, pengguna dapat melihat:

- **Informasi Pengaduan**: Judul, Lokasi, Tanggal, Status
- **Detail Pengaduan**: Deskripsi, Foto Bukti
- **Permintaan Barang Baru Section** (jika ada):
  - Nama barang baru yang diminta
  - Lokasi barang
  - Alasan permintaan (deskripsi)
  - Foto kerusakan/bukti
  - Status permintaan: `Menunggu Persetujuan` / `Disetujui` / `Ditolak`
  - Tanggal permintaan & tanggal persetujuan (jika sudah disetujui)
  - Catatan admin (jika ada)

- **Status Timeline**: Menunjukkan progress pengaduan

### 4. **Admin Melihat Pengaduan**
- Admin membuka halaman "Manajemen Pengaduan"
- Admin dapat filter pengaduan berdasarkan status, tanggal, lokasi, petugas
- Admin klik detail pengaduan untuk melihat informasi lengkap

### 5. **Admin Menyetujui Permintaan Barang Baru**
Di halaman detail pengaduan, admin melihat section **"Permintaan Barang Baru"**:

- Kartu per-barang baru dengan informasi lengkap:
  - ✅ Nama barang & lokasi
  - ✅ Alasan permintaan
  - ✅ Foto kerusakan (clickable untuk lihat full size)
  - ✅ Tanggal permintaan
  - ✅ Status badge: `Menunggu Persetujuan` (kuning), `Disetujui` (hijau), `Ditolak` (merah)

- **Jika status = "Menunggu Persetujuan"**:
  - Admin dapat mengisi **"Catatan Persetujuan"** (opsional, default: "Barang baru ditambahkan ke inventaris")
  - Admin klik button **"Setujui & Tambah ke Master Items"** (hijau)

- **Proses Approval**:
  1. Barang ditambahkan ke tabel `items` (master items)
  2. `temporary_item.status_permintaan` diubah menjadi `"Disetujui"`
  3. `temporary_item.tanggal_persetujuan` diset ke waktu sekarang
  4. `temporary_item.catatan_admin` disimpan
  5. `pengaduan.id_item` diupdate ke ID barang baru di master items

- **Jika status = "Disetujui" atau "Ditolak"**:
  - Tombol approval tidak muncul
  - Hanya ditampilkan message: "Permintaan ini sudah [status]"

### 6. **Pengaduan Tetap Terlihat di Sidebar Pengguna**
- **Sebelum approval**: Pengaduan terlihat dengan badge "Barang Baru (Menunggu Persetujuan)"
- **Sesudah approval**: Pengaduan tetap terlihat, status badge "Barang Baru (Menunggu Persetujuan)" hilang
- **Permanent**: Pengaduan akan tetap terlihat di sidebar pengguna hingga status keseluruhan pengaduan berubah

---

## Fitur yang Diimplementasikan

### ✅ Backend (Sudah Tersedia)
1. **Models**:
   - `Pengaduan` → relasi `temporary_items()`
   - `TemporaryItem` → relasi `pengaduan()` dan `petugas()`

2. **Database**:
   - Tabel `temporary_item` dengan kolom: `id_item`, `id_pengaduan`, `nama_barang_baru`, `lokasi_barang_baru`, `alasan_permintaan`, `foto_kerusakan`, `status_permintaan`, `tanggal_permintaan`, `tanggal_persetujuan`, `catatan_admin`

3. **Controllers**:
   - `PengaduanController.store()` → handle pengaduan dengan barang baru (sudah ada)
   - `AdminPengaduanController.approveTemporaryItem()` → approve permintaan barang baru (sudah ada)

### ✅ Frontend (Baru Ditambahkan)
1. **Pengguna - Index View** (`pengguna/pengaduan/index.blade.php`):
   - Tambahkan badge untuk temporary items yang pending
   - Badge: 🟣 "[N] Barang Baru (Menunggu Persetujuan)"

2. **Pengguna - Detail View** (`pengguna/pengaduan/show.blade.php`):
   - Tambahkan section "Permintaan Barang Baru"
   - Tampilkan kartu untuk setiap temporary item
   - Tampilkan status, foto, tanggal, dan catatan admin

3. **Admin - Detail View** (`admin/pengaduan/show.blade.php`):
   - Enhanced section "Permintaan Barang Baru" dengan UI yang lebih baik
   - Tampilkan foto kerusakan dengan zoom capability
   - Tambahkan form untuk input catatan persetujuan
   - Enhanced button untuk approval dengan better styling

---

## User Stories

### 1. User (Pengguna) Story
**Sebagai pengguna, saya ingin:**
- Membuat pengaduan untuk barang yang tidak ada di daftar
- Melihat pengaduan saya di sidebar meski barang belum disetujui
- Melihat status persetujuan barang yang saya minta
- Melihat catatan admin untuk barang yang saya minta

**Acceptance Criteria:**
- ✅ Pengaduan berhasil dibuat dengan barang baru
- ✅ Pengaduan muncul di sidebar dengan badge "Barang Baru (Menunggu Persetujuan)"
- ✅ Detail pengaduan menampilkan informasi barang baru dan status persetujuannya
- ✅ Catatan admin (jika ada) ditampilkan untuk pengguna

### 2. Admin Story
**Sebagai admin, saya ingin:**
- Melihat semua permintaan barang baru
- Menyetujui permintaan barang baru dan menambahkannya ke master items
- Memberikan catatan untuk setiap persetujuan
- Melihat foto kerusakan untuk evaluasi

**Acceptance Criteria:**
- ✅ Admin dapat melihat daftar temporary items di detail pengaduan
- ✅ Admin dapat input catatan dan menyetujui barang baru
- ✅ Barang baru berhasil ditambah ke master items setelah approval
- ✅ Foto kerusakan dapat di-zoom untuk evaluasi lebih detail

---

## Testing Checklist

### ✅ Pengguna Flow
- [ ] Pengguna login dan buat pengaduan dengan barang baru
  - [ ] Isi form lengkap
  - [ ] Centang "Barang Lainnya" dan ketik nama barang
  - [ ] Upload foto bukti
  - [ ] Submit form → berhasil dengan pesan success
  
- [ ] Pengguna membuka halaman "Daftar Pengaduan"
  - [ ] Pengaduan muncul di list
  - [ ] Badge "Barang Baru (Menunggu Persetujuan)" muncul
  
- [ ] Pengguna klik detail pengaduan
  - [ ] Section "Permintaan Barang Baru" muncul
  - [ ] Informasi lengkap ditampilkan: nama, lokasi, alasan, foto, status, tanggal
  
- [ ] Admin approve pengaduan
  - [ ] Pengguna kembali ke detail pengaduan
  - [ ] Status barang berubah menjadi "Disetujui" (hijau)
  - [ ] Catatan admin ditampilkan
  - [ ] Pengaduan tetap terlihat di sidebar pengguna

### ✅ Admin Flow
- [ ] Admin login dan buka halaman "Manajemen Pengaduan"
  - [ ] Pengaduan dengan temporary items terlihat
  
- [ ] Admin klik detail pengaduan
  - [ ] Section "Permintaan Barang Baru" muncul dengan UI yang enhanced
  - [ ] Informasi barang ditampilkan dengan lengkap
  - [ ] Foto kerusakan bisa di-zoom
  
- [ ] Admin input catatan dan approve
  - [ ] Isi form catatan persetujuan
  - [ ] Klik "Setujui & Tambah ke Master Items"
  - [ ] Proses berhasil → pesan success
  
- [ ] Verify data
  - [ ] Barang muncul di tabel `items` master
  - [ ] `temporary_item.status_permintaan` berubah ke "Disetujui"
  - [ ] `pengaduan.id_item` ter-update ke barang baru

---

## Database Schema

### Tabel: `pengaduan`
```
id_pengaduan (PK)
nama_pengaduan
deskripsi
lokasi
foto
status → ["Diajukan", "Disetujui", "Diproses", "Selesai", "Ditolak"]
id_user (FK)
id_petugas (FK) → nullable
id_item (FK) → nullable (akan ter-update setelah approval barang baru)
tgl_pengajuan
tgl_selesai
tgl_verifikasi
catatan_admin
saran_petugas
ditangani_admin
nama_admin
ditolak_oleh
tgl_ditolak
diproses_oleh
tgl_mulai_proses
```

### Tabel: `temporary_item`
```
id_item (PK)
id_pengaduan (FK)
id_petugas (FK) → nullable
nama_barang_baru
lokasi_barang_baru
alasan_permintaan
foto_kerusakan
status_permintaan → ["Menunggu Persetujuan", "Disetujui", "Ditolak"]
tanggal_permintaan
tanggal_persetujuan → nullable
catatan_admin → nullable
catatan_petugas → nullable
```

---

## Endpoints & Routes

### Pengguna Routes (Sudah Ada)
- `GET /pengaduan` → List pengaduan pengguna (with temporary items info)
- `GET /pengaduan/create` → Form create pengaduan
- `POST /pengaduan` → Submit pengaduan (auto-create temporary item jika barang baru)
- `GET /pengaduan/{id}` → Detail pengaduan (with temporary items detail)

### Admin Routes (Sudah Ada)
- `GET /admin/pengaduan` → List semua pengaduan
- `GET /admin/pengaduan/{id}` → Detail pengaduan (with enhanced temporary items UI)
- `POST /admin/pengaduan/temporary-item/{id}/approve` → Approve temporary item
- `PUT /admin/pengaduan/{id}/status` → Update status pengaduan

---

## Catatan Penting

1. **Data Sementara vs Master**: Pengaduan dengan barang baru akan memiliki `id_item = NULL` sampai barang disetujui dan dipromosikan ke master items.

2. **Visibility**: Pengaduan tetap terlihat di sidebar pengguna karena pengaduan disimpan di tabel `pengaduan`, bukan hanya di temporary_item.

3. **Relasi**: Satu pengaduan dapat memiliki banyak temporary items (jika permintaan barang baru lebih dari satu).

4. **Approval Process**: Admin tidak perlu mengedit form complex - cukup input catatan dan klik "Setujui", sistem otomatis akan:
   - Buat barang baru di `items` table
   - Update status `temporary_item`
   - Update `pengaduan.id_item`

5. **Photo Storage**: Foto disimpan di `/storage/pengaduan/` dan di-serve melalui symbolic link.

---

## Troubleshooting

### Temporary items tidak muncul di pengaduan detail
- Pastikan `temporary_items` relationship sudah di-load: `$pengaduan->load('temporary_items')`
- Check apakah `temporary_item` entries ada di database untuk pengaduan tersebut

### Approval button tidak muncul
- Pastikan `status_permintaan = "Menunggu Persetujuan"`
- Jika status lain, tombol tidak akan muncul (by design)

### Barang tidak masuk ke master items setelah approval
- Check error log di `/storage/logs/laravel.log`
- Pastikan form diklik dengan benar dan tidak ada validation error
- Verify database transaction completed successfully

---

## Future Enhancements

1. **Bulk Approval**: Admin bisa approve multiple temporary items sekaligus
2. **Rejection**: Tambahkan fitur admin untuk menolak permintaan barang baru
3. **Notification**: Email notification ketika temporary item disetujui/ditolak
4. **Approval Workflow**: Tambahkan approval dari supervisor sebelum barang ke master
5. **Item Quantity**: Track berapa banyak barang baru yang diminta vs yang disetujui

