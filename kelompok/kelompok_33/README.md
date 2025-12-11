# CleanSpot - Sistem Laporan & Pemetaan Titik Sampah Kota

**Final Project Praktikum Pemrograman Web 2025**  
Laboratorium Teknik Komputer - Universitas Lampung

---

## 👥 Anggota Kelompok 33
- **Dimas Faqih Nur Aulia Rohman (2317051007) - Ketua** - Dashboard (Admin, Warga, Petugas), Statistik & Peta (Chart.js, Leaflet/OpenStreetMap), landing page UI/UX Design  
- **Aldanti Cahyani Putri (2317051050)** - User Management (register, login, role-based access, profil)  
- **Nabila Zahra (2317051023)** - Laporan Sampah (form, upload foto, CRUD, pagination)  
- **Alyaa zahra taufiq (2317051051)** - Penanganan (petugas): update status, assign, komentar penanganan

---

## 📋 Tentang Proyek

### Judul
**CleanSpot - Sistem Pelaporan dan Pemetaan Titik Sampah Berbasis Web**

### Summary
CleanSpot adalah aplikasi web responsif yang memungkinkan masyarakat untuk melaporkan dan memantau titik sampah/TPS di kota secara real-time. Sistem ini menghubungkan tiga role pengguna:

- **Warga**: Membuat laporan sampah dengan foto dan lokasi GPS menggunakan peta interaktif
- **Petugas**: Mengelola tugas pembersihan dengan sistem tracking status (Baru → Dikerjakan → Selesai)
- **Admin**: Memantau seluruh sistem melalui dashboard analytics dengan grafik dan peta, serta mengelola pengguna dan penugasan

Aplikasi ini menerapkan konsep **Smart City** untuk meningkatkan efisiensi pengelolaan kebersihan kota dan partisipasi masyarakat dalam menjaga lingkungan.

**Tema:** Smart City & Environment

---

## �️ Database Design (ERD)

![ERD CleanSpot](./src/assets/ERD.jpg)

### Database Schema Overview
CleanSpot menggunakan 4 tabel utama yang saling berelasi:

1. **pengguna** - Menyimpan data user (admin, petugas, warga)
2. **laporan** - Data laporan sampah dari warga
3. **penugasan** - Tracking penugasan petugas ke laporan
4. **log_aktivitas** - Audit trail semua aktivitas sistem

**Relasi:**
- Satu user bisa membuat banyak laporan (1:M)
- Satu laporan bisa memiliki banyak penugasan/history (1:M)
- Satu petugas bisa menerima banyak tugas (1:M)
- Semua aktivitas user tercatat di log (1:M)

Detail lengkap struktur database ada di [Database Design Section](#️-database-design-erd-1) di bawah.

---

## �🚀 Cara Menjalankan Aplikasi

### Prerequisites
- PHP 8.0 atau lebih tinggi
- MySQL 8.0+ atau MariaDB 10.5+
- Apache web server (XAMPP/LAMP/MAMP)
- Browser modern (Chrome, Firefox, Edge, Safari)

### Langkah-langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/DIMFAQ/TUBES_PRK_PEMWEB_2025-KELOMPOK-33.git
   cd TUBES_PRK_PEMWEB_2025-KELOMPOK-33/kelompok/kelompok_33
   ```

2. **Setup Database**
   - Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
   - Buat database baru dengan nama: `cleanspot_db`
   - Import file `db/schema.sql` ke database tersebut
   - Database akan otomatis membuat semua tabel dan struktur yang diperlukan

3. **Konfigurasi Database**
   - Copy file `db/config.php.example` menjadi `src/config.php`
   - Edit `src/config.php` sesuai dengan konfigurasi MySQL Anda:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');          // Sesuaikan username MySQL
   define('DB_PASS', '');              // Sesuaikan password MySQL
   define('DB_NAME', 'cleanspot_db');
   ```

4. **Setup Folder Upload**
   - Pastikan folder `src/uploads/laporan/` dan `src/uploads/bukti/` memiliki permission write (chmod 777 di Linux/Mac)

5. **Seed Data (Opsional)**
   - Buka browser dan akses: `http://localhost/TUBES_PRK_PEMWEB_2025-KELOMPOK-33/kelompok/kelompok_33/src/seed_users.php`
   - Ini akan membuat user sample untuk testing:
     - Admin: `admin@cleanspot.com` / `admin123`
     - Petugas: `petugas@cleanspot.com` / `petugas123`
     - Warga: `warga@cleanspot.com` / `warga123`

6. **Akses Aplikasi**
   - Buka browser dan akses: `http://localhost/TUBES_PRK_PEMWEB_2025-KELOMPOK-33/kelompok/kelompok_33/src/`
   - Login dengan salah satu akun di atas atau register akun baru

---

## 💻 Teknologi
- **Frontend:** HTML5, Custom CSS (Plus Jakarta Sans), JavaScript ES6+
- **Backend:** PHP 8.0+ (native, tanpa framework)
- **Database:** MySQL 8.0+ / MariaDB 10.5+
- **Maps:** Leaflet.js + OpenStreetMap (no API key needed)
- **Charts:** Chart.js 4.x
- **Version Control:** Git & GitHub
- **Server:** Apache (XAMPP/LAMP/MAMP)

---

## Fitur Utama

### Warga
- Buat laporan dengan upload multiple foto (drag & drop)
- Pilih lokasi di peta interaktif (Leaflet + OSM)
- Track status laporan real-time (Baru, Diproses, Selesai)
- Dashboard statistik laporan pribadi
- Filter & search laporan

### Petugas
- Dashboard dengan peta lokasi tugas
- Kelola tugas (Tugas Baru -> Dikerjakan -> Selesai)
- Upload bukti penanganan dengan foto
- View detail laporan dengan navigasi Google Maps
- Filter tugas berdasarkan status
- Statistics cards dengan icon

### Admin
- Dashboard analytics dengan Chart.js
- Peta semua laporan dengan Leaflet + OpenStreetMap
- Assign petugas ke laporan
- User management lengkap (CRUD)
- Activity log system
- Filter & search advanced

### Responsive Design
- Fully responsive untuk desktop, tablet, dan mobile
- Mobile-first approach dengan hamburger menu
- Touch-friendly UI (min 44px touch targets)
- Optimized untuk berbagai ukuran layar (1024px, 768px, 480px breakpoints)

---

## Struktur Folder

```
kelompok_33/
├── db/
│   ├── schema.sql              # Database schema MySQL
│   ├── config.php.example      # Template konfigurasi
│   └── SETUP.md                # Panduan setup database
│
├── src/
│   ├── config.php              # Konfigurasi database
│   ├── fungsi_helper.php       # Helper functions
│   ├── login_page.html         # Halaman login
│   ├── register_page.html      # Halaman register
│   ├── seed_users.php          # Seed data user
│   │
│   ├── auth/                   # Authentication
│   │   ├── login.php
│   │   ├── register.php
│   │   └── logout.php
│   │
│   ├── admin/                  # Dashboard Admin
│   │   ├── beranda_admin.php   # Dashboard & statistik
│   │   ├── laporan_admin.php   # Kelola laporan
│   │   ├── pengguna_admin.php  # User management
│   │   └── log_admin.php       # Activity log
│   │
│   ├── petugas/                # Dashboard Petugas
│   │   ├── beranda_petugas.php # Dashboard dengan peta
│   │   └── tugas_saya.php      # Kelola tugas
│   │
│   ├── warga/                  # Dashboard Warga
│   │   ├── beranda_warga.php   # Dashboard statistik
│   │   ├── buat_laporan.php    # Form laporan
│   │   └── laporan_saya.php    # Daftar laporan
│   │
│   ├── api/                    # REST API Endpoints
│   │   ├── admin/              # Admin APIs
│   │   ├── petugas/            # Petugas APIs
│   │   └── warga/              # Warga APIs
│   │
│   ├── assets/                 # Frontend Assets
│   │   ├── styles.css          # Custom CSS (1350+ lines)
│   │   └── js/
│   │       ├── mobile-menu.js
│   │       ├── admin_dashboard.js
│   │       └── petugas_dashboard.js
│   │
│   └── uploads/                # File uploads
│       ├── laporan/            # Foto laporan
│       └── bukti/              # Bukti penanganan
│
├── DOCUMENTATION.md            # Dokumentasi lengkap
├── ROADMAP.md                  # Roadmap pengembangan
└── README.md                   # File ini
```

---

## Instalasi & Setup

### Prerequisites
- PHP 8.0 atau lebih tinggi
- MySQL 8.0+ atau MariaDB 10.5+
- Apache web server (XAMPP/LAMP/MAMP)
- Browser modern (Chrome, Firefox, Edge, Safari)

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/DIMFAQ/TUBES_PRK_PEMWEB_2025-KELOMPOK-33.git
   cd TUBES_PRK_PEMWEB_2025-KELOMPOK-33/kelompok/kelompok_33
   ```

2. **Setup Database**
   ```bash
   # Buka phpMyAdmin atau MySQL client
   # Buat database: cleanspot_db
   # Import file: db/schema.sql
   ```

3. **Konfigurasi**
   ```bash
   # Copy config template
   cp db/config.php.example src/config.php
   
   # Edit src/config.php sesuai environment Anda
   # Sesuaikan: DB_HOST, DB_USER, DB_PASS, DB_NAME
   ```

4. **Seed Data (Optional)**
   ```bash
   # Akses via browser: http://localhost/path-to-project/src/seed_users.php
   # Ini akan membuat user default:
   # - Admin: admin@cleanspot.com / admin123
   # - Petugas: petugas@cleanspot.com / petugas123
   # - Warga: warga@cleanspot.com / warga123
   ```

5. **Akses Aplikasi**
   ```
   Login: http://localhost/path-to-project/src/login_page.html
   ```

---

## Design System

### Colors
- **Primary Green:** `#10b981` (Emerald-500)
- **Dark Green:** `#059669` (Emerald-600)
- **Warning:** `#f59e0b` (Amber-500)
- **Info:** `#3b82f6` (Blue-500)
- **Success:** `#10b981` (Emerald-500)
- **Danger:** `#ef4444` (Red-500)
- **Gray Scale:** `#f9fafb` to `#111827`

### Typography
- **Font Family:** Plus Jakarta Sans (Google Fonts)
- **Base Size:** 16px
- **Heading Scale:** 18px - 28px
- **Body:** 14px - 16px

### Components
- Clean card design dengan shadow
- Status badges dengan color coding
- Icon set dari Font Awesome 6
- Responsive grid system
- Touch-friendly buttons (min 44px)

---

## Database

### Tables (10 total)
1. `pengguna` - User accounts
2. `laporan` - Reports dari warga
3. `foto_laporan` - Photos untuk laporan
4. `penugasan` - Task assignments
5. `bukti_penanganan` - Completion proofs
6. `komentar` - Comments on reports
7. `log_aktivitas` - Activity logs
8. `reset_password` - Password reset tokens

### Status Flow
```
Laporan: baru -> diproses -> selesai
Penugasan: ditugaskan -> dikerjakan -> selesai
Labels: "Tugas Baru" -> "Sedang Dikerjakan" -> "Selesai"
```

---

## 🗄️ Database Design (ERD)

### Entity Relationship Diagram

![ERD CleanSpot](./src/assets/ERD.jpg)

### Tabel Utama

#### 1. pengguna
Menyimpan data user dengan role-based access
```sql
- id_pengguna (PK)
- nama_lengkap
- email (UNIQUE)
- password (BCRYPT)
- role (admin/petugas/warga)
- no_telepon
- alamat
- foto_profil
- tanggal_registrasi
```

#### 2. laporan
Menyimpan data laporan sampah dari warga
```sql
- id_laporan (PK)
- id_pengguna (FK -> pengguna)
- judul
- deskripsi
- latitude, longitude
- foto_path
- status (baru/diproses/selesai)
- tanggal_laporan
```

#### 3. penugasan
Tracking penugasan petugas ke laporan
```sql
- id_penugasan (PK)
- id_laporan (FK -> laporan)
- id_petugas (FK -> pengguna)
- status (ditugaskan/dikerjakan/selesai)
- tanggal_ditugaskan
- tanggal_selesai
- keterangan
- foto_bukti
```

#### 4. log_aktivitas
Audit trail untuk semua aktivitas sistem
```sql
- id_log (PK)
- id_pengguna (FK -> pengguna)
- aktivitas
- detail
- tanggal
```

### Relasi Antar Tabel
- `pengguna` (1) ----< (M) `laporan` : Satu user bisa membuat banyak laporan
- `laporan` (1) ----< (M) `penugasan` : Satu laporan bisa memiliki banyak penugasan (history)
- `pengguna` (1) ----< (M) `penugasan` : Satu petugas bisa menerima banyak tugas
- `pengguna` (1) ----< (M) `log_aktivitas` : Semua aktivitas user tercatat

---

## Security Features

- Password hashing dengan `password_hash()` (BCRYPT)
- Prepared statements untuk SQL injection prevention
- XSS protection dengan `htmlspecialchars()`
- Role-based access control (RBAC)
- Session management
- File upload validation (type, size, extension)

---

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

---

## License

Academic Project - Praktikum Pemrograman Web 2025  
Universitas Lampung

---

## Contact

**Kelompok 33**
- Repository: [GitHub](https://github.com/DIMFAQ/TUBES_PRK_PEMWEB_2025-KELOMPOK-33)

---
