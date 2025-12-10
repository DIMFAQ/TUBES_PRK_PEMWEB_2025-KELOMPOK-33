# CleanSpot - Panduan Lengkap Implementasi

## 📁 Struktur Proyek Lengkap

```
kelompok_33/
├── db/
│   ├── schema.sql              ✅ SELESAI - Database schema lengkap
│   ├── config.php.example      ✅ SELESAI - Template konfigurasi
│   └── SETUP.md                ✅ SELESAI - Panduan setup
│
├── src/
│   ├── config.php              ✅ SELESAI - Konfigurasi database
│   ├── fungsi_helper.php       ✅ SELESAI - Fungsi helper
│   ├── login_page.html         ✅ SELESAI - Halaman login
│   ├── register_page.html      ✅ SELESAI - Halaman register
│   ├── seed_users.php          ✅ SELESAI - Seed data user
│   │
│   ├── auth/                   ✅ SELESAI - Authentication system
│   │   ├── login.php           ✅ SELESAI
│   │   ├── register.php        ✅ SELESAI
│   │   └── logout.php          ✅ SELESAI
│   │
│   ├── admin/                  ✅ SELESAI - Dashboard Admin
│   │   ├── beranda_admin.php   ✅ SELESAI - Dashboard & statistik Chart.js
│   │   ├── laporan_admin.php   ✅ SELESAI - Daftar laporan + filter
│   │   ├── pengguna_admin.php  ✅ SELESAI - Manajemen user CRUD
│   │   └── log_admin.php       ✅ SELESAI - Audit log aktivitas
│   │
│   ├── petugas/                ✅ SELESAI - Dashboard Petugas
│   │   ├── beranda_petugas.php ✅ SELESAI - Dashboard tugas + peta
│   │   └── tugas_saya.php      ✅ SELESAI - Kelola tugas + filter
│   │
│   ├── warga/                  ✅ SELESAI - Dashboard Warga
│   │   ├── beranda_warga.php   ✅ SELESAI - Dashboard statistik
│   │   ├── buat_laporan.php    ✅ SELESAI - Form laporan + peta
│   │   └── laporan_saya.php    ✅ SELESAI - Daftar laporan + filter
│   │
│   ├── api/                    ✅ SELESAI - API Endpoints
│   │   ├── map_data.php        ✅ SELESAI - Data untuk peta
│   │   ├── statistik_data.php  ✅ SELESAI - Data chart
│   │   │
│   │   ├── admin/              ✅ SELESAI
│   │   │   ├── ambil_laporan.php     ✅ SELESAI
│   │   │   ├── detail_laporan.php    ✅ SELESAI
│   │   │   ├── tugaskan_petugas.php  ✅ SELESAI
│   │   │   ├── verifikasi_laporan.php ✅ SELESAI
│   │   │   ├── ambil_pengguna.php    ✅ SELESAI
│   │   │   └── ambil_log.php         ✅ SELESAI
│   │   │
│   │   ├── petugas/            ✅ SELESAI
│   │   │   ├── ambil_tugas.php       ✅ SELESAI
│   │   │   ├── mulai_tugas.php       ✅ SELESAI
│   │   │   └── selesaikan_tugas.php  ✅ SELESAI
│   │   │
│   │   └── warga/              ✅ SELESAI
│   │       ├── buat_laporan.php      ✅ SELESAI
│   │       └── ambil_laporan_saya.php ✅ SELESAI
│   │
│   ├── assets/                 ✅ SELESAI - Frontend Assets
│   │   ├── styles.css          ✅ SELESAI - 1350+ lines, responsive
│   │   └── js/
│   │       ├── mobile-menu.js  ✅ SELESAI - Hamburger menu
│   │       ├── admin_dashboard.js    ✅ SELESAI - Chart.js & Leaflet
│   │       ├── petugas_dashboard.js  ✅ SELESAI - Peta & stats
│   │       └── warga_dashboard.js    ✅ SELESAI - Form & map
│   │
│   └── uploads/                File upload directories
       ├── laporan/             Foto laporan warga
       └── bukti/               Bukti penanganan petugas
│
├── DOCUMENTATION.md            ✅ SELESAI - Dokumentasi teknis
├── ROADMAP.md                  ✅ SELESAI - Roadmap pengembangan
└── README.md                   ✅ SELESAI - Project overview
```

---

## 🎯 Prioritas Pengerjaan

### Sprint 1: Infrastruktur ✅ SELESAI
- [x] Database schema
- [x] Config & helper functions
- [x] Folder structure
- [x] Seed users

### Sprint 2: Authentication & Core ✅ SELESAI
- [x] Login system (login_page.html, auth/login.php)
- [x] Register system (register_page.html, auth/register.php)
- [x] Logout handler (auth/logout.php)
- [x] Session management
- [x] Role-based access control

### Sprint 3: Dashboard Admin ✅ SELESAI
- [x] beranda_admin.php - Statistik & peta dengan Chart.js & Leaflet
- [x] laporan_admin.php - Tabel laporan + filter
- [x] pengguna_admin.php - User management CRUD
- [x] log_admin.php - Activity logs
- [x] admin_dashboard.js - Chart.js & Leaflet integration
- [x] All admin API endpoints

### Sprint 4: Dashboard Petugas ✅ SELESAI
- [x] beranda_petugas.php - Dashboard + peta + stats
- [x] tugas_saya.php - Kelola tugas + filter
- [x] petugas_dashboard.js - Map & interactions
- [x] All petugas API endpoints (3 status system)

### Sprint 5: Dashboard Warga ✅ SELESAI
- [x] beranda_warga.php - Statistics dashboard
- [x] buat_laporan.php - Form + map + drag-drop upload
- [x] laporan_saya.php - List + filter
- [x] warga_dashboard.js - Form & map interactions
- [x] All warga API endpoints

### Sprint 6: UI/UX & Responsive ✅ SELESAI
- [x] Custom CSS design (1350+ lines)
- [x] Plus Jakarta Sans font
- [x] Mobile responsive (3 breakpoints)
- [x] Hamburger menu (mobile-menu.js)
- [x] Touch-friendly UI (44px targets)
- [x] Status color coding
- [x] Clean card layouts

### Sprint 7: Refinement & Fixes ✅ SELESAI
- [x] Fix mobile sidebar overlap
- [x] Simplify to 3-status system
- [x] Fix SQL syntax errors
- [x] Update all documentation
- [x] Improve status labels

### Sprint 8: Testing & Deployment 🚧 ONGOING
- [ ] Cross-browser testing
- [ ] Performance optimization
- [ ] Security audit
- [ ] User acceptance testing
- [ ] Production deployment

### Sprint 5: Dashboard Warga
- [ ] `src/warga/buat_laporan.php`
- [ ] `src/warga/laporan_saya.php`
- [ ] `src/aset/js/warga_laporan.js` - Leaflet untuk lokasi

---

## 📝 Template & Contoh Kode

### Template Halaman Dashboard

```php
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';

// Cek role
cek_role('admin'); // atau 'petugas' / 'warga'

$user = get_user_info();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CleanSpot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php tampilkan_pesan_flash(); ?>
    
    <nav class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">CleanSpot</h1>
            <div>
                <span><?= htmlspecialchars($user['nama']) ?></span>
                <a href="/src/logout.php" class="ml-4 text-white">Logout</a>
            </div>
        </div>
    </nav>
    
    <main class="container mx-auto p-6">
        <!-- Konten halaman di sini -->
    </main>
    
    <script src="/src/aset/js/[nama-file].js"></script>
</body>
</html>
```

### Template API Endpoint

```php
<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';

// Cek role
cek_role('admin'); // sesuaikan

header('Content-Type: application/json');

try {
    // Validasi input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Proses data
    // ... kode logika ...
    
    // Catat log aktivitas
    catat_log($pdo, $_SESSION['user_id'], 'AKSI_DILAKUKAN', 'laporan', $id);
    
    // Response sukses
    json_response([
        'success' => true,
        'message' => 'Berhasil',
        'data' => $result
    ]);
    
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}
```

### Template JavaScript untuk Chart

```javascript
// admin_dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    // Load statistik
    fetch('/src/api/statistik_data.php')
        .then(res => res.json())
        .then(data => {
            // Render chart dengan Chart.js
            const ctx = document.getElementById('chartStatus').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Baru', 'Diproses', 'Selesai'],
                    datasets: [{
                        data: [data.baru, data.diproses, data.selesai],
                        backgroundColor: ['#EF4444', '#F59E0B', '#10B981']
                    }]
                }
            });
        });
    
    // Load data peta
    fetch('/src/api/map_data.php')
        .then(res => res.json())
        .then(data => {
            // Render peta dengan Leaflet
            const map = L.map('map').setView([-5.45, 105.26], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            
            data.forEach(laporan => {
                L.marker([laporan.lat, laporan.lng])
                    .bindPopup(laporan.judul)
                    .addTo(map);
            });
        });
});
```

---

## 🔧 Yang Sudah Dibuat

### 1. Database Schema (`db/schema.sql`)
- ✅ Tabel pengguna
- ✅ Tabel laporan
- ✅ Tabel foto_laporan
- ✅ Tabel penugasan (dengan status_penugasan)
- ✅ Tabel bukti_penanganan
- ✅ Tabel log_aktivitas
- ✅ Tabel komentar
- ✅ Semua foreign keys & indexes

### 2. Helper Functions (`src/fungsi_helper.php`)
- ✅ `catat_log()` - Logging aktivitas
- ✅ `cek_login()` - Cek autentikasi
- ✅ `cek_role()` - Cek authorization
- ✅ `upload_file()` - Upload dengan validasi
- ✅ `format_tanggal()` - Format tanggal Indonesia
- ✅ `json_response()` - Response API
- ✅ `redirect_dengan_pesan()` - Flash message
- ✅ `tampilkan_pesan_flash()` - Display flash

### 3. API Admin (Partial)
- ✅ `ambil_laporan.php` - Pagination & filter
- ✅ `detail_laporan.php` - Detail lengkap

---

## 🚀 Cara Lanjutkan Pengerjaan

### Untuk Dimas (Dashboard Admin):
1. Buat `src/admin/beranda_admin.php` - gunakan template di atas
2. Buat `src/api/statistik_data.php` - query COUNT laporan per status/kategori
3. Buat `src/api/map_data.php` - query semua laporan dengan lat/lng
4. Buat `src/aset/js/admin_dashboard.js` - integrate Chart.js & Leaflet
5. Buat halaman laporan & detail

### Untuk Alda (User Management):
1. Update `src/login.php` & `register.php` 
2. Buat `src/admin/kelola_pengguna.php`
3. Buat `src/api/admin/ambil_pengguna.php`
4. Tambah fitur ubah role user

### Untuk Nabila (Laporan):
1. Sudah ada `LaporanSampah.php` - bisa digabungkan
2. Buat `src/warga/buat_laporan.php` dengan upload foto
3. Buat `src/api/warga/buat_laporan.php`
4. Integrate Leaflet untuk pilih lokasi

### Untuk Alyaa (Penanganan):
1. Buat semua file di `src/petugas/`
2. Buat semua API di `src/api/petugas/`
3. Fitur upload bukti penanganan
4. Update status penugasan

---

## 📚 Library yang Digunakan

### CSS Framework
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### Chart.js untuk Grafik
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

### Leaflet untuk Peta
```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

---

## ✅ Checklist Fitur

### Admin ✅
- [x] Login/logout
- [x] Dashboard statistik (Chart.js)
- [x] Peta semua laporan (Leaflet + OSM)
- [x] Daftar laporan (tabel + filter)
- [x] Detail laporan
- [x] Assign petugas
- [x] Verifikasi selesai
- [x] Kelola pengguna
- [x] Audit log
- [x] Mobile responsive dengan hamburger menu

### Petugas ✅
- [x] Login/logout
- [x] Dashboard tugas dengan peta
- [x] Statistics cards (Tugas Baru, Dikerjakan, Selesai)
- [x] List tugas dengan filter
- [x] Mulai tugas (ditugaskan -> dikerjakan)
- [x] Upload bukti penanganan
- [x] Selesaikan tugas (dikerjakan -> selesai)
- [x] Riwayat tugas
- [x] Mobile responsive dengan hamburger menu

### Warga ✅
- [x] Register
- [x] Login/logout
- [x] Buat laporan + multiple foto (drag & drop)
- [x] Pilih lokasi di peta (Leaflet)
- [x] List laporan saya
- [x] Lihat status laporan
- [x] Dashboard statistik
- [x] Filter & search
- [x] Mobile responsive dengan hamburger menu

---

## 🎨 Design System

- **Primary Color:** Hijau #10B981 (Emerald-500)
- **Status Colors:**
  - Merah (#EF4444) untuk "Baru"
  - Kuning (#F59E0B) untuk "Diproses"/"Dikerjakan"
  - Hijau (#10B981) untuk "Selesai"
- **Font:** Plus Jakarta Sans (Google Fonts)
- **Layout:** Card-based dengan clean shadows
- **Responsive:** Mobile-first dengan 3 breakpoints (1024px, 768px, 480px)
- **Icons:** Font Awesome 6
- **Touch Targets:** Minimum 44px untuk mobile

---

**Status Proyek:** 85% selesai (Database + Helper + UI + API + Mobile Responsive)  
**Next Step:** Testing, optimization, dan deployment preparation

**Completed Features:**
- ✅ Custom CSS design system (1350+ lines)
- ✅ Leaflet maps integration
- ✅ Chart.js statistics
- ✅ Mobile responsive UI
- ✅ 3-status system (ditugaskan, dikerjakan, selesai)
- ✅ Role-based dashboards
- ✅ File upload system
- ✅ Activity logging

**Remaining Tasks:**
- [ ] Cross-browser testing
- [ ] Performance optimization
- [ ] Security audit
- [ ] User acceptance testing
- [ ] Production deployment

---

*Last Updated: Januari 2025 - Kelompok 33*
