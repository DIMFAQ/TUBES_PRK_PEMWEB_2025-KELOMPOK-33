# CleanSpot - Dokumentasi Lengkap

## 🚀 Quick Start

### Instalasi
```bash
# 1. Clone repo
git clone https://github.com/DIMFAQ/TUBES_PRK_PEMWEB_2025-KELOMPOK-33.git
cd TUBES_PRK_PEMWEB_2025-KELOMPOK-33/kelompok/kelompok_33

# 2. Import database
# - Buka phpMyAdmin
# - Buat database: cleanspot_db
# - Import: db/schema.sql

# 3. Setup config
cp db/config.php.example src/config.php
# Edit src/config.php sesuai environment

# 4. Seed admin
# Akses: http://localhost/.../src/seed_admin.php

# 5. Login
# Akses: http://localhost/.../src/login_page.html
# Email: admin@cleanspot.com
# Password: admin123
```

## ✨ Fitur Lengkap

### 👤 Warga
- Buat laporan dengan upload foto (multiple)
- Pilih lokasi di map interaktif (click to pin)
- Track status laporan real-time
- Dashboard statistik pribadi

### 👷 Petugas
- Kelola tugas (terima → mulai → selesaikan)
- Upload bukti penanganan
- Dashboard statistik tugas
- Filter tugas (status, prioritas)

### 🔧 Admin
- Dashboard analytics (Chart.js)
- Peta laporan (Leaflet + OSM)
- Assign petugas dengan prioritas
- User management (CRUD)
- Activity log system

## 🗄 Database Schema

### Tabel & Relasi
```
pengguna (10 tabel total)
├── laporan (1:N)
│   ├── foto_laporan (1:N)
│   ├── penugasan (1:N)
│   │   └── bukti_penanganan (1:N)
│   └── komentar (1:N)
├── log_aktivitas (1:N)
└── reset_password (1:N)
```

## 🔌 API Documentation

### Response Format
```json
{
  "success": true,
  "message": "Success message",
  "data": {...},
  "pagination": {...}  // Optional
}
```

### Admin Endpoints

#### GET /api/admin/ambil_laporan.php
**Query Params:**
- `page` (int): Page number
- `status` (string): baru|diproses|selesai
- `kategori` (string): organik|non-organik|lainnya
- `search` (string): Search term

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "judul": "Sampah menumpuk",
      "kategori": "organik",
      "status": "baru",
      "nama_pelapor": "John Doe",
      "created_at": "2025-01-15 10:00:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 50,
    "total_pages": 3
  }
}
```

#### POST /api/admin/tugaskan_petugas.php
**Body (JSON):**
```json
{
  "laporan_id": 1,
  "petugas_id": 5,
  "prioritas": "tinggi",
  "catatan": "Segera ditangani"
}
```

### Petugas Endpoints

#### GET /api/petugas/ambil_tugas.php
**Query Params:**
- `status` (string): ditugaskan|diterima|sedang_dikerjakan|selesai
- `prioritas` (string): tinggi|sedang|rendah

#### POST /api/petugas/selesaikan_tugas.php
**Body (FormData):**
- `penugasan_id` (int)
- `catatan` (string)
- `foto[]` (file): Multiple photos

### Warga Endpoints

#### POST /api/warga/buat_laporan.php
**Body (FormData):**
- `judul` (string)
- `deskripsi` (string)
- `kategori` (string): organik|non-organik|lainnya
- `alamat` (string)
- `lat` (float): Optional
- `lng` (float): Optional
- `foto[]` (file): Multiple photos

### Global Endpoints

#### GET /api/map_data.php
Returns GeoJSON-like array for map markers.

#### GET /api/statistik_data.php
Returns comprehensive statistics for charts.

## 🛠 Development Guide

### File Upload
Fungsi `upload_file()` di `fungsi_helper.php`:
```php
$result = upload_file($_FILES['foto'], 'laporan');
// Returns: ['success' => true, 'url' => '/uploads/laporan/filename.jpg']
```

**Validasi:**
- Max size: 5MB
- Allowed types: jpg, jpeg, png, gif
- Auto rename dengan timestamp

### Logging System
Semua aksi penting di-log:
```php
catat_log('create', 'laporan', $laporan_id, 'Membuat laporan baru');
```

### Authentication
Session-based auth dengan helper:
```php
cek_login();              // Cek sudah login
cek_role(['admin']);      // Cek role specific
```

## 🎨 Frontend Integration

### Chart.js Example
```javascript
new Chart(document.getElementById('chart-status'), {
    type: 'doughnut',
    data: {
        labels: ['Baru', 'Diproses', 'Selesai'],
        datasets: [{
            data: [10, 5, 15],
            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981']
        }]
    }
});
```

### Leaflet Map Example
```javascript
const map = L.map('map').setView([-2.5, 118], 5);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

// Add marker on click
map.on('click', (e) => {
    L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
});
```

## 🔐 Security Best Practices

1. **Password Hashing**
```php
$hash = password_hash($password, PASSWORD_BCRYPT);
password_verify($input, $hash);
```

2. **SQL Injection Prevention**
```php
$stmt = $pdo->prepare("SELECT * FROM pengguna WHERE email = :email");
$stmt->execute([':email' => $email]);
```

3. **XSS Prevention**
```php
echo htmlspecialchars($user_input);
```

4. **File Upload Validation**
```php
$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed)) throw new Exception('Invalid file type');
```

## 🐛 Common Issues

### Issue: Map tidak muncul
**Solusi:** Pastikan CDN Leaflet ter-load (cek network tab)

### Issue: Upload foto gagal
**Solusi:** 
1. Buat folder `uploads/` di root
2. Set permission 777 (Linux/Mac)
3. Cek `php.ini` → `upload_max_filesize = 10M`

### Issue: Session timeout
**Solusi:** Edit `php.ini`:
```ini
session.gc_maxlifetime = 3600
session.cookie_lifetime = 3600
```

## 📊 Performa Tips

1. **Database Indexing**
   - Schema sudah include 7 indexes
   - Foreign keys untuk relasi

2. **Query Optimization**
   - Use JOIN instead of multiple queries
   - LIMIT pagination results

3. **Asset Loading**
   - Use CDN untuk libraries (Chart.js, Leaflet)
   - Compress images before upload

## 🚀 Deployment Checklist

- [ ] Update `src/config.php` dengan production credentials
- [ ] Set `display_errors = Off` di `php.ini`
- [ ] Enable HTTPS
- [ ] Setup backup database (cronjob)
- [ ] Change default admin password
- [ ] Set proper file permissions
- [ ] Test semua API endpoints
- [ ] Verify upload folder writable

## 📚 Resources

- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [Chart.js Docs](https://www.chartjs.org/docs/latest/)
- [Leaflet Docs](https://leafletjs.com/reference.html)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

---

**Last Updated:** 2025
**Maintained by:** Kelompok 33 - Praktikum Pemweb 2025
