# Setup Database CleanSpot

## Langkah Setup untuk Development

### 1. Import Database
Jalankan file SQL schema untuk membuat database dan tabel:
```bash
mysql -u root -p < schema.sql
```

Atau buka phpMyAdmin dan import file `schema.sql`

### 2. Konfigurasi Database
Copy template config dan edit sesuai environment Anda:
```bash
cp db/config.php.example src/config.php
```

Edit file `src/config.php`:
```php
$config = [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'cleanspot_db',
        'user' => 'root',
        'pass' => '', // ganti sesuai password MySQL Anda
        'charset' => 'utf8mb4',
    ],
];
```

### 3. Buat Admin User
Jalankan script untuk membuat akun admin:
```bash
php src/seed_admin.php
```

Ikuti prompt untuk memasukkan:
- Nama
- Email
- Password

## Untuk Anggota Tim

### Setup Pertama Kali
1. Clone repository
2. Import `db/schema.sql` ke MySQL lokal Anda
3. Copy `db/config.php.example` ke `src/config.php` dan sesuaikan credentials
4. Jalankan `php src/seed_admin.php` untuk buat admin

### Sinkronisasi Perubahan Database
Jika ada perubahan struktur database:
1. Pull perubahan terbaru dari GitHub
2. Cek apakah `db/schema.sql` berubah
3. Jika berubah, **backup database lokal Anda dulu**
4. Drop database dan import ulang `db/schema.sql`, ATAU
5. Jalankan query ALTER TABLE secara manual untuk update struktur

### Tips
- **JANGAN** commit file `config.php` dengan password asli
- **JANGAN** commit database dump dengan data sensitif
- Gunakan `schema.sql` hanya untuk struktur, bukan data
- Setiap anggota setup database lokal sendiri

## Catatan Penting
- File `db/schema.sql` adalah **source of truth** untuk struktur database
- Setiap perubahan struktur database HARUS diupdate di `schema.sql`
- Gunakan migration script (SQL ALTER) jika ingin update tanpa drop database
