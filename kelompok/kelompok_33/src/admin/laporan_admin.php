<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Laporan - CleanSpot Admin</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
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
            <a href="beranda_admin.php" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="laporan_admin.php" class="sidebar-item active">
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
                    <h4><?= htmlspecialchars($nama_admin) ?></h4>
                    <p>Admin</p>
                </div>
            </div>
            <a href="../auth/logout.php" class="logout-btn" title="Keluar">
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
                <input type="text" id="filter-search" placeholder="Cari laporan...">
            </div>
        </div>
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Kelola Laporan</h1>
            <p>Kelola semua laporan sampah dari warga</p>
        </div>
        <!-- Filter Section -->
        <div class="chart-card" style="margin-bottom: 24px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Status</label>
                    <select id="filter-status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="baru">Baru</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Kategori</label>
                    <select id="filter-kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="organik">Organik</option>
                        <option value="non-organik">Non-Organik</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; display: flex; align-items: flex-end;">
                    <button onclick="loadLaporan(1)" class="btn btn-primary w-full">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-card">
            <div class="table-header">
                <h3>Daftar Laporan</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-600);">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 12px;"></i>
                            <div>Memuat data...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <div style="padding: 20px 24px; border-top: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
                <div id="pagination-info" style="font-size: 14px; color: var(--gray-600);"></div>
                <div id="pagination-buttons" style="display: flex; gap: 8px;"></div>
            </div>
        </div>
    </div>
    <!-- Modal Assign Petugas -->
    <div id="modal-assign" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="chart-card" style="max-width: 500px; width: 90%; margin: 20px;">
            <h3 style="margin-bottom: 20px;">Tugaskan ke Petugas</h3>
            <form id="form-assign" onsubmit="submitAssign(event)">
                <input type="hidden" id="assign-laporan-id">
                <div class="form-group">
                    <label class="form-label">Pilih Petugas</label>
                    <select id="assign-petugas" class="form-select" required>
                        <option value="">-- Pilih Petugas --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea id="assign-catatan" class="form-textarea" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-check"></i> Tugaskan
                    </button>
                    <button type="button" onclick="closeModal()" class="btn btn-secondary" style="flex: 1;">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="../aset/js/admin_laporan.js"></script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>