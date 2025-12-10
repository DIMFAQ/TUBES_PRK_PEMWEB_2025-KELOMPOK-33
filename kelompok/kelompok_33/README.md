# CleanSpot - Sistem Laporan & Pemetaan Titik Sampah Kota
**Final Project Praktikum Pemrograman Web 2025**  
Laboratorium Teknik Komputer - Universitas Lampung

---

## Deskripsi Singkat
CleanSpot adalah aplikasi web responsif untuk melaporkan dan memantau titik sampah/TPS di kota.  
Warga dapat mengirim laporan dengan foto dan lokasi; petugas mengelola tugas pembersihan; admin memantau lewat dashboard interaktif dengan grafik dan peta real-time.

**Tema:** Smart City & Environment

---

## Anggota Kelompok 33
- **Dimas Faqih (Ketua)** - Dashboard Admin, Statistik & Peta (Chart.js, Leaflet/OpenStreetMap), UI/UX Design  
- **Alda** - User Management (register, login, role-based access, profil)  
- **Nabila** - Laporan Sampah (form, upload foto, CRUD, pagination)  
- **Alyaa** - Penanganan (petugas): update status, assign, komentar penanganan

---

## Teknologi
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

## Contributing

Project ini adalah tugas kelompok. Untuk kontribusi:

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## License

Academic Project - Praktikum Pemrograman Web 2025  
Universitas Lampung

---

## Contact

**Kelompok 33**
- Repository: [GitHub](https://github.com/DIMFAQ/TUBES_PRK_PEMWEB_2025-KELOMPOK-33)
- Email: cleanspot33@example.com

---

**Dibuat dengan ❤️ oleh Kelompok 33**  
*Last Updated: Januari 2025*
