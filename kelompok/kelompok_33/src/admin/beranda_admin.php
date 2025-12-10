<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CleanSpot</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
cek_login();
cek_role(['admin']);
$nama_admin = $_SESSION['nama'] ?? 'Admin';
$initial = strtoupper(substr($nama_admin, 0, 1));
?>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Sidebar -->
    <div class="sidebar">
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
            <a href="beranda_admin.php" class="sidebar-item active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="laporan_admin.php" class="sidebar-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Laporan</span>
            </a>
            <a href="pengguna_admin.php" class="sidebar-item">
                <i class="fas fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>
            <a href="log_admin.php" class="sidebar-item">
                <i class="fas fa-history"></i>
                <span>Log Aktivitas</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar"><?= $initial ?></div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($nama_admin) ?></div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <a href="../auth/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari laporan, petugas, atau warga...">
            </div>
        </div>
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
            <p>Ringkasan data sistem CleanSpot</p>
        </div>
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Total Laporan</div>
                        <div class="stat-value" id="total-laporan">-</div>
                    </div>
                    <div class="stat-icon blue">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Laporan Baru</div>
                        <div class="stat-value" id="laporan-baru">-</div>
                    </div>
                    <div class="stat-icon yellow">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Diproses</div>
                        <div class="stat-value" id="laporan-diproses">-</div>
                    </div>
                    <div class="stat-icon orange">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Selesai</div>
                        <div class="stat-value" id="laporan-selesai">-</div>
                    </div>
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
            <div class="chart-card">
                <h3>Laporan per Kategori</h3>
                <canvas id="chart-kategori" style="max-height: 300px;"></canvas>
            </div>
            <div class="chart-card">
                <h3>Status Laporan</h3>
                <canvas id="chart-status" style="max-height: 300px;"></canvas>
            </div>
        </div>
        <!-- Map -->
        <div class="chart-card" style="margin-bottom: 24px;">
            <h3>Peta Lokasi Laporan</h3>
            <div id="map" style="height: 400px; border-radius: 12px; overflow: hidden;"></div>
        </div>
        <!-- Recent Reports Table -->
        <div class="table-card">
            <div class="table-header">
                <h3>Laporan Terbaru</h3>
            </div>
            <table class="admin-dashboard-table">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-pelapor">Pelapor</th>
                        <th class="col-judul">Judul</th>
                        <th class="col-kategori">Kategori</th>
                        <th class="col-status">Status</th>
                        <th class="col-tanggal">Tanggal</th>
                        <th class="col-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-laporan-terbaru">
                    <tr>
                        <td colspan="7" class="desktop-loading" style="text-align: center; padding: 40px; color: var(--gray-600);">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 12px;"></i>
                            <div>Memuat data...</div>
                        </td>
                        <td colspan="3" class="mobile-loading" style="text-align: center; padding: 40px; color: var(--gray-600); display: none;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 12px;"></i>
                            <div>Memuat data...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../aset/js/admin_dashboard.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>