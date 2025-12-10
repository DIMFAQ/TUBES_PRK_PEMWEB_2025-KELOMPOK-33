-- CleanSpot DB Final (With Status Tracking & Proof Upload)
-- Engine: InnoDB, Charset: utf8mb4

CREATE DATABASE IF NOT EXISTS cleanspot_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE cleanspot_db;

-- DROP TABLES existing
DROP TABLE IF EXISTS bukti_penanganan;
DROP TABLE IF EXISTS riwayat_status;
DROP TABLE IF EXISTS komentar;
DROP TABLE IF EXISTS penugasan;
DROP TABLE IF EXISTS foto_laporan;
DROP TABLE IF EXISTS laporan;
DROP TABLE IF EXISTS reset_password;
DROP TABLE IF EXISTS pengguna;

-- ========== 1. pengguna ==========
CREATE TABLE pengguna (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','petugas','warga') NOT NULL DEFAULT 'warga',
  telepon VARCHAR(30),
  alamat VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========== 2. laporan ==========
CREATE TABLE laporan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pengguna_id INT UNSIGNED NOT NULL,
  judul VARCHAR(200) NOT NULL,
  deskripsi TEXT,
  kategori ENUM('organik','non-organik','lainnya') DEFAULT 'lainnya',
  alamat VARCHAR(255),
  lat DECIMAL(10,7),
  lng DECIMAL(10,7),
  status ENUM('baru','diproses','selesai') DEFAULT 'baru',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- ========== 3. foto_laporan ==========
CREATE TABLE foto_laporan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  path_file VARCHAR(500) NOT NULL,
  ukuran INT UNSIGNED,
  tipe_file VARCHAR(50),
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE
);

-- ========== 4. penugasan ==========
CREATE TABLE penugasan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  petugas_id INT UNSIGNED NOT NULL,
  assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  FOREIGN KEY (petugas_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- ========== 5. komentar ==========
CREATE TABLE komentar (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  pengguna_id INT UNSIGNED NOT NULL,
  isi_komentar TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- ========== 6. reset_password ==========
CREATE TABLE reset_password (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pengguna_id INT UNSIGNED NOT NULL,
  token VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- Riwayat perubahan status
CREATE TABLE riwayat_status (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  status_lama ENUM('baru','diproses','selesai'),
  status_baru ENUM('baru','diproses','selesai') NOT NULL,
  pengguna_id INT UNSIGNED NOT NULL,
  catatan TEXT,
  waktu_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- Bukti foto penyelesaian
CREATE TABLE bukti_penanganan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  pengguna_id INT UNSIGNED NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  path_file VARCHAR(500) NOT NULL,
  deskripsi TEXT,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
);

-- Index tambahan
CREATE INDEX idx_laporan_status ON laporan (status);
CREATE INDEX idx_laporan_created_at ON laporan (created_at);
