<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - CleanSpot</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
cek_login();
cek_role(['warga']);
$nama = $_SESSION['nama'] ?? 'Warga';
$initial = strtoupper(substr($nama, 0, 1));
?>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Sidebar -->
    <div class="sidebar warga">
        <button class="sidebar-close">
            <i class="fas fa-times"></i>
        </button>
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-leaf"></i>
                <span>CleanSpot</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="beranda_warga.php" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="buat_laporan.php" class="sidebar-item active">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Laporan</span>
            </a>
            <a href="laporan_saya.php" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                <span>Laporan Saya</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar"><?= $initial ?></div>
                <div class="user-info">
                    <h4><?= htmlspecialchars($nama) ?></h4>
                    <p>Warga</p>
                </div>
            </div>
            <a href="../auth/logout.php" class="logout-btn" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Buat Laporan Baru</h1>
            <p>Laporkan sampah di sekitar Anda untuk lingkungan yang lebih bersih</p>
        </div>
        <!-- Form Card -->
        <div class="chart-card" style="max-width: 900px; margin: 0 auto;">
            <div id="alert" style="display: none; padding: 12px; border-radius: 8px; margin-bottom: 20px;"></div>
            <form id="form-laporan" onsubmit="submitLaporan(event)">
                <div class="form-group">
                    <label class="form-label">Judul Laporan *</label>
                    <input type="text" id="judul" required class="form-input"
                        placeholder="Contoh: Tumpukan sampah di Jl. Merdeka">
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi *</label>
                    <textarea id="deskripsi" required class="form-textarea" rows="4"
                        placeholder="Jelaskan kondisi sampah secara detail..."></textarea>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Kategori *</label>
                        <select id="kategori" required class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="organik">Organik</option>
                            <option value="non-organik">Non-Organik</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Foto (Opsional)</label>
                        <input type="file" id="foto" accept="image/*" multiple class="form-input">
                        <p style="font-size: 12px; color: #6b7280; margin-top: 4px;">Bisa upload beberapa foto</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lokasi *</label>
                    <input type="text" id="alamat" required class="form-input"
                        placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">
                </div>
                <div class="form-group">
                    <label class="form-label">Pilih Lokasi di Peta (Opsional)</label>
                    <p style="font-size: 14px; color: #6b7280; margin-bottom: 8px;">Klik pada peta untuk menandai lokasi</p>
                    <div id="map" style="height: 400px; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb;"></div>
                    <input type="hidden" id="lat">
                    <input type="hidden" id="lng">
                    <p id="coords-display" style="font-size: 12px; color: #6b7280; margin-top: 8px;">Koordinat belum dipilih</p>
                </div>
                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Laporan
                    </button>
                    <a href="beranda_warga.php" class="btn btn-secondary" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="../aset/js/warga_buat_laporan.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>