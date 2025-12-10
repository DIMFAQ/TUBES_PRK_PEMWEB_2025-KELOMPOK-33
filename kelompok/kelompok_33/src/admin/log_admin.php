<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - CleanSpot Admin</title>
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
            <a href="laporan_admin.php" class="sidebar-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Laporan</span>
            </a>
            <a href="pengguna_admin.php" class="sidebar-item">
                <i class="fas fa-users"></i>
                <span>Kelola Pengguna</span>
            </a>
            <a href="log_admin.php" class="sidebar-item active">
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
        <div class="top-nav">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari aktivitas...">
            </div>
        </div>
        <div class="content-wrapper">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Aktivitas Sistem</h1>
                    <p class="page-subtitle">Pantau semua aktivitas dan perubahan dalam sistem</p>
                </div>
            </div>
            <div class="chart-card" style="margin-bottom: 24px;">
                <div class="chart-header">
                    <h3>Filter Log</h3>
                </div>
                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: var(--gray-700);">Tipe Aksi</label>
                        <select id="filter-aksi" class="form-input">
                            <option value="">Semua Aksi</option>
                            <option value="login">Login</option>
                            <option value="logout">Logout</option>
                            <option value="create">Create</option>
                            <option value="update">Update</option>
                            <option value="delete">Delete</option>
                            <option value="assign">Assign</option>
                        </select>
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: var(--gray-700);">Tanggal</label>
                        <input type="date" id="filter-tanggal" class="form-input">
                    </div>
                </div>
            </div>
            <div class="table-card">
                <div style="overflow-x: auto;">
                    <table class="data-table admin-log-table">
                        <thead>
                            <tr>
                                <th class="col-id">ID</th>
                                <th class="col-waktu">Waktu</th>
                                <th class="col-pengguna">Pengguna</th>
                                <th class="col-aksi">Aksi</th>
                                <th class="col-target">Target</th>
                                <th class="col-detail">Detail</th>
                            </tr>
                        </thead>
                        <tbody id="log-table">
                            <tr>
                                <td colspan="6" class="desktop-loading" style="text-align: center; padding: 40px; color: var(--gray-600);">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                                </td>
                                <td colspan="3" class="mobile-loading" style="text-align: center; padding: 40px; color: var(--gray-600); display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <div id="pagination-info" class="pagination-info">Memuat...</div>
                    <div id="pagination" class="pagination"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let currentPage = 1;
        const limit = 50;
        async function loadLogs() {
            const aksi = document.getElementById('filter-aksi').value;
            const tanggal = document.getElementById('filter-tanggal').value;
            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    limit: limit,
                    ...(aksi && { aksi }),
                    ...(tanggal && { tanggal })
                });
                const response = await fetch(`../api/admin/ambil_log.php?${params}`);
                const result = await response.json();
                if (result.success) {
                    renderTable(result.data.items);
                    renderPagination(result.data.pagination);
                } else {
                    document.getElementById('log-table').innerHTML = `
                        <tr>
                            <td colspan="6" class="desktop-loading" style="text-align: center; padding: 40px; color: var(--danger);">${result.message}</td>
                            <td colspan="3" class="mobile-loading" style="text-align: center; padding: 40px; color: var(--danger); display: none;">${result.message}</td>
                        </tr>`;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('log-table').innerHTML = `
                    <tr>
                        <td colspan="6" class="desktop-loading" style="text-align: center; padding: 40px; color: var(--danger);">Terjadi kesalahan</td>
                        <td colspan="3" class="mobile-loading" style="text-align: center; padding: 40px; color: var(--danger); display: none;">Terjadi kesalahan</td>
                    </tr>`;
            }
        }
        function renderTable(items) {
            const tbody = document.getElementById('log-table');
            if (items.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="desktop-loading" style="text-align: center; padding: 40px; color: var(--gray-600);">Tidak ada data</td>
                        <td colspan="3" class="mobile-loading" style="text-align: center; padding: 40px; color: var(--gray-600); display: none;">Tidak ada data</td>
                    </tr>`;
                return;
            }
            tbody.innerHTML = items.map(log => {
                const aksiBadge = {
                    'login': '<span class="badge success">login</span>',
                    'logout': '<span class="badge">logout</span>',
                    'create': '<span class="badge info">create</span>',
                    'update': '<span class="badge warning">update</span>',
                    'delete': '<span class="badge danger">delete</span>',
                    'assign': '<span class="badge" style="background-color: var(--secondary-purple); color: white;">assign</span>'
                }[log.aksi] || `<span class="badge">${log.aksi}</span>`;
                return `
                    <tr>
                        <td class="col-id" style="font-size: 13px; color: var(--gray-600);">${log.id}</td>
                        <td class="col-waktu" style="font-size: 13px;">${formatTanggal(log.created_at)}</td>
                        <td class="col-pengguna" style="font-weight: 500;">${log.pengguna_nama || 'System'}</td>
                        <td class="col-aksi">${aksiBadge}</td>
                        <td class="col-target" style="font-size: 13px; color: var(--gray-700);">
                            ${log.target_tipe ? `${log.target_tipe} #${log.target_id}` : '-'}
                        </td>
                        <td class="col-detail" style="font-size: 13px; color: var(--gray-600); max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${log.detail || '-'}</td>
                    </tr>
                `;
            }).join('');
        }
        function renderPagination(pagination) {
            const container = document.getElementById('pagination');
            const infoContainer = document.getElementById('pagination-info');
            const totalPages = Math.ceil(pagination.total / pagination.limit);
            const start = (currentPage - 1) * pagination.limit + 1;
            const end = Math.min(currentPage * pagination.limit, pagination.total);
            infoContainer.textContent = `Menampilkan ${start}-${end} dari ${pagination.total} log aktivitas`;
            let html = '';
            if (currentPage > 1) {
                html += `<button onclick="changePage(${currentPage - 1})" class="btn btn-sm btn-secondary">
                    <i class="fas fa-chevron-left"></i> Prev
                </button>`;
            }
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `<button onclick="changePage(${i})" 
                        class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-secondary'}">${i}</button>`;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += '<span style="padding: 0 4px; color: var(--gray-600);">...</span>';
                }
            }
            if (currentPage < totalPages) {
                html += `<button onclick="changePage(${currentPage + 1})" class="btn btn-sm btn-secondary">
                    Next <i class="fas fa-chevron-right"></i>
                </button>`;
            }
            container.innerHTML = html;
        }
        function changePage(page) {
            currentPage = page;
            loadLogs();
        }
        function formatTanggal(datetime) {
            const date = new Date(datetime);
            return date.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        document.getElementById('filter-aksi').addEventListener('change', () => {
            currentPage = 1;
            loadLogs();
        });
        document.getElementById('filter-tanggal').addEventListener('change', () => {
            currentPage = 1;
            loadLogs();
        });
        loadLogs();
    </script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>