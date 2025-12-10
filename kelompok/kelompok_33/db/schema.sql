-- Skema Database untuk Aplikasi CleanSpot

CREATE DATABASE IF NOT EXISTS cleanspot_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE cleanspot_db;

-- 2. Hapus tabel bila sudah ada (urutan terbalik dari pembuatan)
DROP TABLE IF EXISTS log_aktivitas;
DROP TABLE IF EXISTS bukti_penanganan;
DROP TABLE IF EXISTS komentar;
DROP TABLE IF EXISTS penugasan;
DROP TABLE IF EXISTS foto_laporan;
DROP TABLE IF EXISTS laporan;
DROP TABLE IF EXISTS reset_password;
DROP TABLE IF EXISTS pengguna;

-- 3. Tabel: pengguna
CREATE TABLE pengguna (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','petugas','warga') NOT NULL DEFAULT 'warga',
  telepon VARCHAR(30) DEFAULT NULL,
  alamat VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel: laporan (utama)
CREATE TABLE laporan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pengguna_id INT UNSIGNED NOT NULL,
  judul VARCHAR(200) NOT NULL,
  deskripsi TEXT,
  kategori ENUM('organik','non-organik','lainnya') DEFAULT 'lainnya',
  alamat VARCHAR(255) DEFAULT NULL,
  lat DECIMAL(10,7) DEFAULT NULL,
  lng DECIMAL(10,7) DEFAULT NULL,
  status ENUM('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_laporan_pengguna FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel: foto_laporan
CREATE TABLE foto_laporan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  path_file VARCHAR(500) NOT NULL,
  ukuran INT UNSIGNED DEFAULT NULL,
  tipe_file VARCHAR(50) DEFAULT NULL,
  uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_foto_laporan_laporan FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabel: penugasan
CREATE TABLE penugasan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  petugas_id INT UNSIGNED NOT NULL,
  status_penugasan ENUM('ditugaskan','dikerjakan','selesai') NOT NULL DEFAULT 'ditugaskan',
  mulai_pada TIMESTAMP NULL,
  selesai_pada TIMESTAMP NULL,
  catatan_petugas TEXT,
  assigned_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_penugasan_laporan FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  CONSTRAINT fk_penugasan_petugas FOREIGN KEY (petugas_id) REFERENCES pengguna(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabel: komentar
CREATE TABLE komentar (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  laporan_id INT UNSIGNED NOT NULL,
  pengguna_id INT UNSIGNED NOT NULL,
  isi_komentar TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_komentar_laporan FOREIGN KEY (laporan_id) REFERENCES laporan(id) ON DELETE CASCADE,
  CONSTRAINT fk_komentar_pengguna FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Tabel: reset_password
CREATE TABLE reset_password (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pengguna_id INT UNSIGNED NOT NULL,
  token VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reset_pengguna FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Tabel: bukti_penanganan
CREATE TABLE bukti_penanganan (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  penugasan_id INT UNSIGNED NOT NULL,
  nama_file VARCHAR(255) NOT NULL,
  path_file VARCHAR(500) NOT NULL,
  keterangan TEXT,
  diunggah_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_bukti_penugasan FOREIGN KEY (penugasan_id) REFERENCES penugasan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Tabel: log_aktivitas
CREATE TABLE log_aktivitas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pengguna_id INT UNSIGNED NOT NULL,
  aksi VARCHAR(100) NOT NULL,
  target_tipe VARCHAR(50) DEFAULT NULL,
  target_id INT UNSIGNED DEFAULT NULL,
  detail TEXT,
  dibuat_pada TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_log_pengguna FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Index untuk performa
CREATE INDEX idx_laporan_status ON laporan (status);
CREATE INDEX idx_laporan_kategori ON laporan (kategori);
CREATE INDEX idx_laporan_created_at ON laporan (created_at);
CREATE INDEX idx_penugasan_status ON penugasan (status_penugasan);
CREATE INDEX idx_penugasan_petugas ON penugasan (petugas_id);
CREATE INDEX idx_log_pengguna ON log_aktivitas (pengguna_id);
CREATE INDEX idx_log_dibuat ON log_aktivitas (dibuat_pada);
