<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - CleanSpot Admin</title>
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
            <a href="pengguna_admin.php" class="sidebar-item active">
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
                <input type="text" id="search-nama" placeholder="Cari nama pengguna...">
            </div>
        </div>
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Kelola Pengguna</h1>
            <p>Kelola semua pengguna sistem CleanSpot</p>
        </div>
        <!-- Filter Section -->
        <div class="chart-card" style="margin-bottom: 24px;">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div class="form-group" style="margin-bottom: 0; flex: 1; max-width: 250px;">
                    <label class="form-label">Filter Role</label>
                    <select id="filter-role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="warga">Warga</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-card">
            <div class="table-header">
                <h3>Daftar Pengguna</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Telepon</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody id="pengguna-table">
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--gray-600);">
                            <i class="fas fa-spinner fa-spin" style="font-size: 24px; margin-bottom: 12px;"></i>
                            <div>Memuat data...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Pagination -->
            <div style="padding: 20px 24px; border-top: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
                <div id="pagination-info" style="font-size: 14px; color: var(--gray-600);"></div>
                <div id="pagination" style="display: flex; gap: 8px;"></div>
            </div>
        </div>
    </div>
    <script>
        let currentPage = 1;
        const limit = 20;
        async function loadPengguna() {
            const role = document.getElementById('filter-role').value;
            const search = document.getElementById('search-nama').value;
            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    limit: limit,
                    ...(role && { role }),
                    ...(search && { search })
                });
                const response = await fetch(`../api/admin/ambil_pengguna.php?${params}`);
                const result = await response.json();
                if (result.success) {
                    renderTable(result.data.items);
                    renderPagination(result.data.pagination);
                } else {
                    document.getElementById('pengguna-table').innerHTML = 
                        `<tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--danger);">${result.message}</td></tr>`;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('pengguna-table').innerHTML = 
                    '<tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--danger);">Terjadi kesalahan</td></tr>';
            }
        }
        function renderTable(items) {
            const tbody = document.getElementById('pengguna-table');
            if (items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: var(--gray-600);">Tidak ada data</td></tr>';
                return;
            }
            tbody.innerHTML = items.map(user => {
                const roleBadge = {
                    'admin': '<span class="badge danger">Admin</span>',
                    'petugas': '<span class="badge info">Petugas</span>',
                    'warga': '<span class="badge success">Warga</span>'
                }[user.role] || '<span class="badge">Unknown</span>';
                return `
                    <tr>
                        <td>${user.id}</td>
                        <td style="font-weight: 600;">${user.nama}</td>
                        <td>${user.email}</td>
                        <td>${roleBadge}</td>
                        <td>${user.telepon || '-'}</td>
                        <td style="font-size: 13px; color: var(--gray-600);">${formatTanggal(user.created_at)}</td>
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
            infoContainer.textContent = `Menampilkan ${start}-${end} dari ${pagination.total} pengguna`;
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
            loadPengguna();
        }
        function formatTanggal(datetime) {
            const date = new Date(datetime);
            return date.toLocaleDateString('id-ID', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        document.getElementById('filter-role').addEventListener('change', () => {
            currentPage = 1;
            loadPengguna();
        });
        document.getElementById('search-nama').addEventListener('input', debounce(() => {
            currentPage = 1;
            loadPengguna();
        }, 500));
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        loadPengguna();
    </script>
</body>
</html>