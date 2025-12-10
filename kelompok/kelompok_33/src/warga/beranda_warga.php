<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Warga - CleanSpot</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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
            <a href="beranda_warga.php" class="sidebar-item active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="buat_laporan.php" class="sidebar-item">
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
            <h1>Dashboard Warga</h1>
            <p>Selamat datang, <?= htmlspecialchars($nama) ?>! Laporkan sampah di sekitar Anda.</p>
        </div>
        <div style="text-align: right; margin-bottom: 24px;">
            <a href="buat_laporan.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Laporan Baru
            </a>
        </div>
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Total Laporan Saya</div>
                        <div class="stat-value" id="stat-total">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%); color: #8b5cf6;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Sedang Diproses</div>
                        <div class="stat-value" id="stat-diproses">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    <div class="stat-icon orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-label">Selesai</div>
                        <div class="stat-value" id="stat-selesai">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    <div class="stat-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Reports Table -->
        <div class="table-card">
            <div class="table-header">
                <h3>Laporan Terbaru Saya</h3>
                <a href="laporan_saya.php" class="btn btn-secondary">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody id="table-laporan">
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 48px; color: #9ca3af;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p style="margin-top: 16px;">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../aset/js/warga_dashboard.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>