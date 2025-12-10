<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Saya - CleanSpot</title>
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
            <a href="beranda_petugas.php" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="tugas_saya.php" class="sidebar-item active">
                <i class="fas fa-tasks"></i>
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
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Mobile Top Header -->
        <div class="mobile-top-header">
            <a href="beranda_petugas.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="mobile-logo">
                <i class="fas fa-leaf"></i>
                <span>CleanSpot</span>
            </div>
            <p class="mobile-subtitle">Sistem Pelaporan Sampah Kota</p>
        </div>

        <div class="page-header">
            <div>
                <h1 class="page-title">Daftar Tugas</h1>
                <p class="page-subtitle">Kelola semua tugas pembersihan yang ditugaskan kepada Anda</p>
            </div>
        </div>
        <!-- Filter Tabs -->
        <div style="display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid #e5e7eb; padding-bottom: 0; overflow-x: auto;">
            <button class="tab-btn active" data-status="all" onclick="filterTugas('all')">
                <i class="fas fa-list"></i> Semua
            </button>
            <button class="tab-btn" data-status="ditugaskan" onclick="filterTugas('ditugaskan')">
                <i class="fas fa-clipboard-list"></i> Tugas Baru
            </button>
            <button class="tab-btn" data-status="dikerjakan" onclick="filterTugas('dikerjakan')">
                <i class="fas fa-tools"></i> Dikerjakan
            </button>
            <button class="tab-btn" data-status="selesai" onclick="filterTugas('selesai')">
                <i class="fas fa-check-circle"></i> Selesai
            </button>
        </div>
        
        <!-- Summary Info -->
        <div id="summary-info" style="color: #6b7280; font-size: 14px; margin-bottom: 16px; font-weight: 500;"></div>
        
        <!-- Desktop Table -->
        <div class="table-card desktop-only">
            <table>
                <thead>
                    <tr>
                        <th>Laporan</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Alamat</th>
                        <th>Ditugaskan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: #9ca3af;">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p style="margin-top: 16px;">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards -->
        <div id="tasks-container" class="tasks-grid mobile-only">
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat tugas...</p>
            </div>
        </div>
    </div>
    <!-- Modal Detail -->
    <div id="detail-modal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3>Detail Laporan</h3>
                <button onclick="closeDetailModal()" class="modal-close">&times;</button>
            </div>
            <div class="modal-body" id="detail-content">
                <div style="text-align: center; padding: 48px;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <script>
        let currentFilter = 'all';
        async function loadTugas() {
            try {
                const response = await fetch('../api/petugas/ambil_tugas.php');
                const result = await response.json();
                const tbody = document.getElementById('table-body');
                const container = document.getElementById('tasks-container');
                
                if (result.success && result.data && result.data.length > 0) {
                    let filteredData = result.data;
                    if (currentFilter !== 'all') {
                        filteredData = result.data.filter(t => t.status_penugasan === currentFilter);
                    }
                    
                    if (filteredData.length === 0) {
                        // Desktop table empty
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px; color: #9ca3af;">
                                    <i class="fas fa-inbox fa-3x" style="margin-bottom: 16px; opacity: 0.5;"></i>
                                    <p>Tidak ada tugas dengan status ini</p>
                                </td>
                            </tr>
                        `;
                        // Mobile cards empty
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Tidak ada tugas dengan status ini</p>
                            </div>
                        `;
                        document.getElementById('summary-info').textContent = '';
                        return;
                    }
                    
                    // Render Desktop Table
                    tbody.innerHTML = filteredData.map(tugas => {
                        let statusClass = 'badge-warning';
                        let statusText = 'Ditugaskan';
                        if (tugas.status_penugasan === 'dikerjakan') {
                            statusClass = 'badge-info';
                            statusText = 'Dikerjakan';
                        } else if (tugas.status_penugasan === 'selesai') {
                            statusClass = 'badge-success';
                            statusText = 'Selesai';
                        }
                        
                        let actionButtons = '';
                        if (tugas.status_penugasan === 'ditugaskan') {
                            actionButtons = `
                                <button onclick="viewDetail(${tugas.laporan_id})" class="btn btn-sm btn-secondary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="mulaiTugas(${tugas.id})" class="btn btn-sm btn-info" title="Mulai">
                                    <i class="fas fa-play"></i>
                                </button>
                            `;
                        } else if (tugas.status_penugasan === 'dikerjakan') {
                            actionButtons = `
                                <button onclick="viewDetail(${tugas.laporan_id})" class="btn btn-sm btn-secondary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="selesaikanTugas(${tugas.id})" class="btn btn-sm btn-primary" title="Selesaikan">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            `;
                        } else {
                            actionButtons = `
                                <button onclick="viewDetail(${tugas.laporan_id})" class="btn btn-sm btn-secondary" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            `;
                        }
                        
                        return `
                            <tr>
                                <td><strong>${tugas.judul_laporan || 'Tanpa Judul'}</strong></td>
                                <td>${tugas.kategori || '-'}</td>
                                <td><span class="badge ${statusClass}">${statusText}</span></td>
                                <td>${tugas.alamat || '-'}</td>
                                <td>${tugas.assigned_at ? new Date(tugas.assigned_at).toLocaleDateString('id-ID') : '-'}</td>
                                <td>${actionButtons}</td>
                            </tr>
                        `;
                    }).join('');
                    
                    // Render Mobile Cards
                    container.innerHTML = filteredData.map(tugas => {
                        let statusClass = 'warning';
                        let statusText = 'Ditugaskan';
                        if (tugas.status_penugasan === 'dikerjakan') {
                            statusClass = 'info';
                            statusText = 'Dikerjakan';
                        } else if (tugas.status_penugasan === 'selesai') {
                            statusClass = 'success';
                            statusText = 'Selesai';
                        }
                        
                        let actionButtons = '';
                        if (tugas.status_penugasan === 'ditugaskan') {
                            actionButtons = `
                                <button onclick="viewDetail(${tugas.laporan_id})" class="btn-action btn-outline">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <button onclick="mulaiTugas(${tugas.id})" class="btn-action btn-primary">
                                    <i class="fas fa-play"></i> Mulai
                                </button>
                            `;
                        } else if (tugas.status_penugasan === 'dikerjakan') {
                            actionButtons = `
                                <button onclick="viewDetail(${tugas.laporan_id})" class="btn-action btn-outline">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <button onclick="selesaikanTugas(${tugas.id})" class="btn-action btn-success">
                                    <i class="fas fa-check"></i> Selesaikan
                                </button>
                            `;
                        } else {
                            actionButtons = `
                                <button onclick="viewDetail(${tugas.laporan_id})" class="btn-action btn-outline" style="flex: 1;">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </button>
                            `;
                        }
                        
                        return `
                            <div class="task-card">
                                <div class="task-header">
                                    <span class="task-id">#${tugas.id}</span>
                                    <span class="badge ${statusClass}">${statusText}</span>
                                </div>
                                <div class="task-content">
                                    <h4 class="task-title">${tugas.judul_laporan || 'Tanpa Judul'}</h4>
                                    <p class="task-category"><i class="fas fa-tag"></i> ${tugas.kategori || '-'}</p>
                                    <p class="task-description">${tugas.deskripsi || '-'}</p>
                                    <div class="task-meta">
                                        <span><i class="fas fa-map-marker-alt"></i> ${tugas.alamat || 'Lokasi tidak tersedia'}</span>
                                        <span><i class="fas fa-calendar"></i> ${tugas.assigned_at ? new Date(tugas.assigned_at).toLocaleDateString('id-ID') : '-'}</span>
                                    </div>
                                </div>
                                <div class="task-actions">
                                    ${actionButtons}
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    document.getElementById('summary-info').textContent = 
                        `Menampilkan ${filteredData.length} tugas`;
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px; color: #9ca3af;">
                                <i class="fas fa-inbox fa-3x" style="margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>Belum ada tugas</p>
                            </td>
                        </tr>
                    `;
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Belum ada tugas</p>
                        </div>
                    `;
                    document.getElementById('summary-info').textContent = '';
                }
            } catch (error) {
                console.error('Error loading tugas:', error);
                document.getElementById('table-body').innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 48px; color: #ef4444;">
                            <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 16px;"></i>
                            <p>Gagal memuat data</p>
                        </td>
                    </tr>
                `;
                document.getElementById('tasks-container').innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p style="color: #ef4444;">Gagal memuat data</p>
                    </div>
                `;
            }
        }
        function filterTugas(status) {
            currentFilter = status;
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-status="${status}"]`).classList.add('active');
            loadTugas();
        }
        async function terimaTugas(id) {
            if (!confirm('Terima tugas ini?')) return;
            try {
                const response = await fetch('../api/petugas/terima_tugas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ penugasan_id: id })
                });
                const result = await response.json();
                if (result.success) {
                    alert('Tugas berhasil diterima!');
                    loadTugas();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal menerima tugas');
            }
        }
        async function mulaiTugas(id) {
            if (!confirm('Mulai mengerjakan tugas ini?')) return;
            try {
                const response = await fetch('../api/petugas/mulai_tugas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ penugasan_id: id })
                });
                const result = await response.json();
                if (result.success) {
                    alert('Tugas dimulai!');
                    loadTugas();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memulai tugas');
            }
        }
        async function selesaikanTugas(id) {
            if (!confirm('Tandai tugas ini sebagai selesai?')) return;
            try {
                const response = await fetch('../api/petugas/selesaikan_tugas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ penugasan_id: id })
                });
                const result = await response.json();
                if (result.success) {
                    alert('Tugas selesai!');
                    loadTugas();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal menyelesaikan tugas');
            }
        }
        async function viewDetail(laporanId) {
            const modal = document.getElementById('detail-modal');
            const content = document.getElementById('detail-content');
            content.innerHTML = `
                <div style="text-align: center; padding: 48px;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            `;
            modal.classList.add('show');
            try {
                const response = await fetch(`../api/petugas/detail_laporan.php?id=${laporanId}`);
                const result = await response.json();
                if (result.success && result.data) {
                    const laporan = result.data;
                    let statusClass = 'badge-warning';
                    if (laporan.status === 'selesai') statusClass = 'badge-success';
                    else if (laporan.status === 'diproses') statusClass = 'badge-info';
                    content.innerHTML = `
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <div>
                                <h4 style="margin: 0 0 16px 0; font-size: 16px; color: #374151;">Informasi Laporan</h4>
                                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                                    <div style="margin-bottom: 12px;">
                                        <strong style="color: #374151;">Judul:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${laporan.judul}</p>
                                    </div>
                                    <div style="margin-bottom: 12px;">
                                        <strong style="color: #374151;">Kategori:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${laporan.kategori}</p>
                                    </div>
                                    <div style="margin-bottom: 12px;">
                                        <strong style="color: #374151;">Status:</strong>
                                        <p style="margin: 4px 0 0 0;"><span class="badge ${statusClass}">${laporan.status}</span></p>
                                    </div>
                                    <div style="margin-bottom: 12px;">
                                        <strong style="color: #374151;">Alamat:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${laporan.alamat || '-'}</p>
                                        ${laporan.lat && laporan.lng ? `
                                            <a href="https://www.google.com/maps/search/?api=1&query=${laporan.lat},${laporan.lng}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-primary" 
                                               style="margin-top: 8px; display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="fas fa-map-marker-alt"></i> Buka di Google Maps
                                            </a>
                                        ` : ''}
                                    </div>
                                    <div>
                                        <strong style="color: #374151;">Tanggal:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${new Date(laporan.created_at).toLocaleString('id-ID')}</p>
                                    </div>
                                </div>
                                <h4 style="margin: 0 0 16px 0; font-size: 16px; color: #374151;">Deskripsi</h4>
                                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                                    <p style="margin: 0; color: #6b7280; line-height: 1.6;">${laporan.deskripsi}</p>
                                </div>
                            </div>
                            <div>
                                <h4 style="margin: 0 0 16px 0; font-size: 16px; color: #374151;">Pelapor</h4>
                                <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 16px;">
                                    <div style="margin-bottom: 12px;">
                                        <strong style="color: #374151;">Nama:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${laporan.nama_pelapor}</p>
                                    </div>
                                    <div style="margin-bottom: 12px;">
                                        <strong style="color: #374151;">Email:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${laporan.email_pelapor || '-'}</p>
                                    </div>
                                    <div>
                                        <strong style="color: #374151;">Telepon:</strong>
                                        <p style="margin: 4px 0 0 0; color: #6b7280;">${laporan.telepon_pelapor || '-'}</p>
                                    </div>
                                </div>
                                ${laporan.foto && laporan.foto.length > 0 ? `
                                    <h4 style="margin: 0 0 16px 0; font-size: 16px; color: #374151;">Foto Laporan (${laporan.foto.length})</h4>
                                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                        ${laporan.foto.map(foto => `
                                            <div style="border-radius: 8px; overflow: hidden; cursor: pointer;" onclick="window.open('../${foto.path_file}', '_blank')">
                                                <img src="../${foto.path_file}" alt="Foto" style="width: 100%; height: 150px; object-fit: cover;">
                                            </div>
                                        `).join('')}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        ${laporan.lat && laporan.lng ? `
                            <div>
                                <h4 style="margin: 0 0 16px 0; font-size: 16px; color: #374151;">Lokasi</h4>
                                <div id="detail-map" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
                            </div>
                        ` : ''}
                    `;
                    if (laporan.lat && laporan.lng) {
                        setTimeout(() => {
                            const detailMap = L.map('detail-map').setView([laporan.lat, laporan.lng], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: 'Ã‚Â© OpenStreetMap'
                            }).addTo(detailMap);
                            L.marker([laporan.lat, laporan.lng])
                                .addTo(detailMap)
                                .bindPopup(`<strong>${laporan.judul}</strong><br>${laporan.alamat}`)
                                .openPopup();
                        }, 100);
                    }
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
        document.addEventListener('DOMContentLoaded', loadTugas);
    </script>
    <style>
        .tab-btn {
            background: none;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.2s ease;
            border-bottom: 3px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .tab-btn:hover {
            color: #3b82f6;
        }
        .tab-btn.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }
        .tab-btn i {
            font-size: 14px;
        }
    </style>
    <script src="../assets/js/mobile-menu.js"></script>
</body>
</html>