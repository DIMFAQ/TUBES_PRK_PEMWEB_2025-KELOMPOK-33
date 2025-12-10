<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Saya - CleanSpot</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
cek_login();
cek_role(['warga']);
$nama_warga = $_SESSION['nama'] ?? 'Warga';
$initial = strtoupper(substr($nama_warga, 0, 1));
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
            <a href="buat_laporan.php" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                <span>Buat Laporan</span>
            </a>
            <a href="laporan_saya.php" class="sidebar-item active">
                <i class="fas fa-file-alt"></i>
                <span>Laporan Saya</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar"><?= $initial ?></div>
                <div class="user-info">
                    <h4><?= htmlspecialchars($nama_warga) ?></h4>
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
            <h1>Laporan Saya</h1>
            <p>Kelola dan pantau laporan yang telah Anda buat</p>
        </div>
        <div style="text-align: right; margin-bottom: 24px;">
            <a href="buat_laporan.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Laporan Baru
            </a>
        </div>
        <!-- Filters -->
        <div class="chart-card" style="margin-bottom: 24px;">
            <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="margin: 0; min-width: 200px;">
                    <label class="form-label">Status</label>
                    <select id="filter-status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="baru">Baru</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0; min-width: 200px;">
                    <label class="form-label">Kategori</label>
                    <select id="filter-kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="sampah">Sampah</option>
                        <option value="organik">Organik</option>
                        <option value="anorganik">Anorganik</option>
                        <option value="drainase">Drainase</option>
                        <option value="jalan">Jalan</option>
                        <option value="taman">Taman</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <button onclick="loadLaporan()" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-card">
            <div class="table-header">
                <h3>Daftar Laporan</h3>
                <div id="summary-info" style="color: #6b7280; font-size: 14px;"></div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th>Tanggal</th>
                        <th>Info</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: #9ca3af;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p style="margin-top: 16px;">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div id="pagination-container" style="margin-top: 24px;"></div>
    </div>
    <!-- Detail Modal -->
    <div id="detail-modal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3 class="modal-title">Detail Laporan</h3>
                <button class="modal-close" onclick="closeDetailModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="detail-content">
                <div style="text-align: center; padding: 48px;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <script>
        let currentPage = 1;
        let totalPages = 1;
        async function loadLaporan(page = 1) {
            currentPage = page;
            const status = document.getElementById('filter-status').value;
            const kategori = document.getElementById('filter-kategori').value;
            const params = new URLSearchParams({
                page: page,
                ...(status && { status }),
                ...(kategori && { kategori })
            });
            try {
                const response = await fetch(`../api/warga/ambil_laporan_saya.php?${params}`);
                const result = await response.json();
                const tbody = document.getElementById('table-body');
                if (result.success && result.data && result.data.length > 0) {
                    tbody.innerHTML = result.data.map(laporan => {
                        let statusClass = 'badge-warning';
                        if (laporan.status === 'selesai') statusClass = 'badge-success';
                        else if (laporan.status === 'diproses') statusClass = 'badge-info';
                        let statusText = laporan.status.charAt(0).toUpperCase() + laporan.status.slice(1);
                        return `
                            <tr>
                                <td><strong>${laporan.judul}</strong></td>
                                <td>${laporan.kategori}</td>
                                <td><span class="badge ${statusClass}">${statusText}</span></td>
                                <td>${laporan.alamat || '-'}</td>
                                <td>${new Date(laporan.created_at).toLocaleDateString('id-ID')}</td>
                                <td>
                                    <div style="font-size: 12px; color: #6b7280; white-space: nowrap;">
                                        <i class="fas fa-image" style="width: 14px;"></i> ${laporan.jumlah_foto || 0} | 
                                        <i class="fas fa-user-check" style="width: 14px;"></i> ${laporan.jumlah_penugasan || 0} | 
                                        <i class="fas fa-comment" style="width: 14px;"></i> ${laporan.jumlah_komentar || 0}
                                    </div>
                                </td>
                                <td>
                                    <button onclick="viewDetail(${laporan.id})" class="btn-action btn-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                    document.getElementById('summary-info').textContent = 
                        `Menampilkan ${result.data.length} dari ${result.pagination.total} laporan`;
                    totalPages = result.pagination.total_pages;
                    renderPagination();
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px; color: #9ca3af;">
                                <i class="fas fa-inbox fa-3x" style="margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>Belum ada laporan</p>
                                <a href="buat_laporan.php" class="btn btn-primary" style="margin-top: 16px;">
                                    <i class="fas fa-plus"></i> Buat Laporan Pertama
                                </a>
                            </td>
                        </tr>
                    `;
                    document.getElementById('summary-info').textContent = '';
                    document.getElementById('pagination-container').innerHTML = '';
                }
            } catch (error) {
                console.error('Error loading laporan:', error);
                const tbody = document.getElementById('table-body');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 48px; color: #ef4444;">
                            <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 16px;"></i>
                            <p>Gagal memuat data</p>
                        </td>
                    </tr>
                `;
            }
        }
        function renderPagination() {
            if (totalPages <= 1) {
                document.getElementById('pagination-container').innerHTML = '';
                return;
            }
            let html = '<div style="display: flex; justify-content: center; gap: 8px;">';
            html += `
                <button onclick="loadLaporan(${currentPage - 1})" 
                        class="btn btn-secondary" 
                        ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    html += `
                        <button onclick="loadLaporan(${i})" 
                                class="btn ${i === currentPage ? 'btn-primary' : 'btn-secondary'}">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    html += '<span style="padding: 8px;">...</span>';
                }
            }
            html += `
                <button onclick="loadLaporan(${currentPage + 1})" 
                        class="btn btn-secondary" 
                        ${currentPage === totalPages ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
            html += '</div>';
            document.getElementById('pagination-container').innerHTML = html;
        }
        async function viewDetail(id) {
            const modal = document.getElementById('detail-modal');
            const content = document.getElementById('detail-content');
            content.innerHTML = `
                <div style="text-align: center; padding: 48px;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            `;
            modal.classList.add('show');
            try {
                const response = await fetch(`../api/warga/ambil_laporan_saya.php?id=${id}`);
                const result = await response.json();
                if (result.success && result.data && result.data.length > 0) {
                    const laporan = result.data[0];
                    let statusClass = 'badge-warning';
                    if (laporan.status === 'selesai') statusClass = 'badge-success';
                    else if (laporan.status === 'diproses') statusClass = 'badge-info';
                    content.innerHTML = `
                        <div style="margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                                <h3 style="margin: 0;">${laporan.judul}</h3>
                                <span class="badge ${statusClass}">${laporan.status}</span>
                            </div>
                            <div style="color: #6b7280; font-size: 14px; margin-bottom: 8px;">
                                <i class="fas fa-tag"></i> ${laporan.kategori} | 
                                <i class="fas fa-calendar"></i> ${new Date(laporan.created_at).toLocaleString('id-ID')}
                            </div>
                            ${laporan.alamat ? `
                                <div style="color: #6b7280; font-size: 14px;">
                                    <i class="fas fa-map-marker-alt"></i> ${laporan.alamat}
                                </div>
                            ` : ''}
                        </div>
                        <div style="padding: 16px; background: #f9fafb; border-radius: 8px; margin-bottom: 16px;">
                            <h4 style="margin: 0 0 8px 0; font-size: 14px; color: #374151;">Deskripsi</h4>
                            <p style="margin: 0; color: #6b7280; line-height: 1.6;">${laporan.deskripsi}</p>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;">
                            <div style="text-align: center;">
                                <div style="font-size: 24px; font-weight: 700; color: #8b5cf6;">${laporan.jumlah_foto || 0}</div>
                                <div style="font-size: 12px; color: #6b7280;">Foto</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 24px; font-weight: 700; color: #8b5cf6;">${laporan.jumlah_penugasan || 0}</div>
                                <div style="font-size: 12px; color: #6b7280;">Penugasan</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 24px; font-weight: 700; color: #8b5cf6;">${laporan.jumlah_komentar || 0}</div>
                                <div style="font-size: 12px; color: #6b7280;">Komentar</div>
                            </div>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div style="text-align: center; padding: 48px; color: #ef4444;">
                            <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 16px;"></i>
                            <p>Detail tidak ditemukan</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading detail:', error);
                content.innerHTML = `
                    <div style="text-align: center; padding: 48px; color: #ef4444;">
                        <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 16px;"></i>
                        <p>Gagal memuat detail</p>
                    </div>
                `;
            }
        }
        function closeDetailModal() {
            document.getElementById('detail-modal').classList.remove('show');
        }
        document.getElementById('detail-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            loadLaporan();
        });
    </script>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>