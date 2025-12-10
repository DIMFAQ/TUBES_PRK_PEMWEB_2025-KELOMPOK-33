<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - CleanSpot</title>
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
cek_role(['petugas']);
$nama = $_SESSION['nama'] ?? 'Petugas';
$initial = strtoupper(substr($nama, 0, 1));
?>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <!-- Sidebar -->
    <div class="sidebar petugas">
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
            <a href="beranda_petugas.php" class="sidebar-item active">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="tugas_saya.php" class="sidebar-item">
                <i class="fas fa-clipboard-check"></i>
                <span>Tugas Saya</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar"><?= $initial ?></div>
                <div class="user-info">
                    <h4><?= htmlspecialchars($nama) ?></h4>
                    <p>Petugas</p>
                </div>
            </div>
            <a href="../auth/logout.php" class="logout-btn" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Mobile Top Header -->
        <div class="mobile-top-header">
            <div class="mobile-logo">
                <i class="fas fa-leaf"></i>
                <span>CleanSpot</span>
            </div>
            <p class="mobile-subtitle">Sistem Pelaporan Sampah Kota</p>
        </div>

        <!-- Top Navigation -->
        <div class="top-nav">
            <div class="search-box" style="visibility: hidden;">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="">
            </div>
        </div>
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Dashboard Petugas</h1>
            <p>Selamat datang, Petugas Kebersihan! Kelola tugas pembersihan Anda.</p>
        </div>
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Tugas Baru</div>
                        <div class="stat-value" id="stat-ditugaskan">-</div>
                    </div>
                    <div class="stat-icon yellow">
                        <i class="fas fa-inbox"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Sedang Dikerjakan</div>
                        <div class="stat-value" id="stat-dikerjakan">-</div>
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
                        <div class="stat-value" id="stat-selesai">-</div>
                    </div>
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Map Section -->
        <div class="chart-card" style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
                <h3 style="margin: 0;">Peta Lokasi Tugas</h3>
                <div style="display: flex; gap: 12px; font-size: 12px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 12px; height: 12px; background: #f59e0b; border-radius: 50%;"></div>
                        <span>Tugas Baru</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 12px; height: 12px; background: #3b82f6; border-radius: 50%;"></div>
                        <span>Dikerjakan</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div style="width: 12px; height: 12px; background: #10b981; border-radius: 50%;"></div>
                        <span>Selesai</span>
                    </div>
                </div>
            </div>
            <div id="map" style="height: 400px; border-radius: 8px; overflow: hidden;"></div>
        </div>
        <!-- Recent Tasks -->
        <div class="section-header">
            <h3>Tugas Terbaru</h3>
            <a href="tugas_saya.php" class="btn btn-primary">
                <i class="fas fa-clipboard-check"></i> Lihat Semua Tugas
            </a>
        </div>
        <div id="tasks-container" class="tasks-grid">
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat tugas...</p>
            </div>
        </div>
    </div>
    <script src="../aset/js/petugas_dashboard.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>