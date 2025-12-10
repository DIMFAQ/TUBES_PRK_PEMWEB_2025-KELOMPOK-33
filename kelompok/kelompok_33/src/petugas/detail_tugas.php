<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tugas - CleanSpot</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #detail-map {
            height: 300px;
            width: 100%;
            border-radius: 8px;
            margin-top: 16px;
        }
        .foto-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }
        .foto-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 4/3;
        }
        .foto-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .foto-item img:hover {
            transform: scale(1.05);
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            width: 180px;
            flex-shrink: 0;
        }
        .info-value {
            color: #6b7280;
            flex: 1;
        }
        .action-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            border: 2px dashed #d1d5db;
        }
    </style>
</head>
<body>
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
cek_login();
cek_role(['petugas']);
$nama = $_SESSION['nama'] ?? 'Petugas';
$initial = strtoupper(substr($nama, 0, 1));
$penugasan_id = $_GET['id'] ?? null;
if (!$penugasan_id) {
    header('Location: tugas_saya.php');
    exit;
}
?>
    <!-- Sidebar -->
    <div class="sidebar petugas">
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
            <a href="../auth/logout.php" class="logout-btn" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1>Detail Tugas</h1>
                <p>Informasi lengkap tugas pembersihan</p>
            </div>
            <div>
                <a href="tugas_saya.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div id="loading-container" style="text-align: center; padding: 48px; color: #9ca3af;">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>
        <div id="detail-container" style="display: none;">
            <!-- Informasi Laporan -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Informasi Laporan</h3>
                    <span id="status-badge"></span>
                </div>
                <div>
                    <div class="info-row">
                        <div class="info-label">Judul Laporan</div>
                        <div class="info-value" id="info-judul"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kategori</div>
                        <div class="info-value" id="info-kategori"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value" id="info-deskripsi"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value" id="info-alamat"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Pelapor</div>
                        <div class="info-value" id="info-pelapor"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Ditugaskan</div>
                        <div class="info-value" id="info-tanggal"></div>
                    </div>
                </div>
            </div>
            <!-- Status Penugasan -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Status Penugasan</h3>
                <div>
                    <div class="info-row">
                        <div class="info-label">Status Saat Ini</div>
                        <div class="info-value" id="status-current"></div>
                    </div>
                    <div class="info-row" id="row-mulai" style="display: none;">
                        <div class="info-label">Mulai Dikerjakan</div>
                        <div class="info-value" id="waktu-mulai"></div>
                    </div>
                    <div class="info-row" id="row-selesai" style="display: none;">
                        <div class="info-label">Selesai</div>
                        <div class="info-value" id="waktu-selesai"></div>
                    </div>
                    <div class="info-row" id="row-catatan" style="display: none;">
                        <div class="info-label">Catatan</div>
                        <div class="info-value" id="catatan-petugas"></div>
                    </div>
                </div>
            </div>
            <!-- Foto Laporan -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Foto Laporan</h3>
                <div id="foto-container" class="foto-grid"></div>
            </div>
            <!-- Lokasi -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Lokasi</h3>
                <div id="detail-map"></div>
            </div>
            <!-- Action Buttons -->
            <div id="action-container" class="action-section">
                <h3 style="margin: 0 0 16px 0; font-size: 16px; font-weight: 600; color: #374151;">
                    <i class="fas fa-tasks"></i> Aksi Tugas
                </h3>
                <div id="action-buttons" style="display: flex; gap: 12px;"></div>
            </div>
        </div>
    </div>
    <script>
        const penugasanId = <?= json_encode($penugasan_id) ?>;
        let detailMap = null;
        let currentStatus = '';
        async function loadDetail() {
            try {
                const response = await fetch(`../api/petugas/ambil_tugas.php`);
                const result = await response.json();
                if (result.success && result.data) {
                    const tugas = result.data.find(t => t.id == penugasanId);
                    if (!tugas) {
                        throw new Error('Tugas tidak ditemukan');
                    }
                    currentStatus = tugas.status_penugasan;
                    let statusClass = 'badge-warning';
                    let statusText = 'Ditugaskan';
                    if (tugas.status_penugasan === 'dikerjakan') {
                        statusClass = 'badge-info';
                        statusText = 'Dikerjakan';
                    } else if (tugas.status_penugasan === 'selesai') {
                        statusClass = 'badge-success';
                        statusText = 'Selesai';
                    }
                    document.getElementById('status-badge').innerHTML = 
                        `<span class="badge ${statusClass}">${statusText}</span>`;
                    document.getElementById('info-judul').textContent = tugas.judul_laporan || '-';
                    document.getElementById('info-kategori').textContent = tugas.kategori || '-';
                    document.getElementById('info-deskripsi').textContent = tugas.deskripsi || '-';
                    document.getElementById('info-alamat').textContent = tugas.alamat || '-';
                    document.getElementById('info-pelapor').textContent = tugas.nama_pelapor || '-';
                    document.getElementById('info-tanggal').textContent = 
                        tugas.assigned_at ? new Date(tugas.assigned_at).toLocaleString('id-ID') : '-';
                    document.getElementById('status-current').innerHTML = 
                        `<span class="badge ${statusClass}">${statusText}</span>`;
                    if (tugas.mulai_pada) {
                        document.getElementById('row-mulai').style.display = 'flex';
                        document.getElementById('waktu-mulai').textContent = 
                            new Date(tugas.mulai_pada).toLocaleString('id-ID');
                    }
                    if (tugas.selesai_pada) {
                        document.getElementById('row-selesai').style.display = 'flex';
                        document.getElementById('waktu-selesai').textContent = 
                            new Date(tugas.selesai_pada).toLocaleString('id-ID');
                    }
                    if (tugas.catatan_petugas) {
                        document.getElementById('row-catatan').style.display = 'flex';
                        document.getElementById('catatan-petugas').textContent = tugas.catatan_petugas;
                    }
                    loadFoto(tugas.laporan_id);
                    if (tugas.lat && tugas.lng) {
                        initMap(tugas.lat, tugas.lng);
                    }
                    renderActionButtons(tugas.status_penugasan);
                    document.getElementById('loading-container').style.display = 'none';
                    document.getElementById('detail-container').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading detail:', error);
                document.getElementById('loading-container').innerHTML = `
                    <i class="fas fa-exclamation-triangle fa-2x" style="color: #ef4444; margin-bottom: 16px;"></i>
                    <p>Gagal memuat detail tugas</p>
                `;
            }
        }
        async function loadFoto(laporanId) {
            try {
                const response = await fetch(`../api/admin/detail_laporan.php?id=${laporanId}`);
                const result = await response.json();
                if (result.success && result.data && result.data.foto) {
                    const container = document.getElementById('foto-container');
                    if (result.data.foto.length > 0) {
                        container.innerHTML = result.data.foto.map(foto => `
                            <div class="foto-item">
                                <img src="../${foto.path_file}" alt="Foto laporan" 
                                     onclick="window.open('../${foto.path_file}', '_blank')">
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p style="color: #9ca3af;">Tidak ada foto</p>';
                    }
                }
            } catch (error) {
                console.error('Error loading foto:', error);
            }
        }
        function initMap(lat, lng) {
            detailMap = L.map('detail-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Ã‚Â© OpenStreetMap'
            }).addTo(detailMap);
            L.marker([lat, lng]).addTo(detailMap);
        }
        function renderActionButtons(status) {
            const container = document.getElementById('action-buttons');
            if (status === 'ditugaskan') {
                container.innerHTML = `
                    <button onclick="mulaiTugas()" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-play"></i> Mulai Mengerjakan
                    </button>
                `;
            } else if (status === 'dikerjakan') {
                container.innerHTML = `
                    <button onclick="selesaikanTugas()" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-check-circle"></i> Tandai Selesai
                    </button>
                `;
            } else if (status === 'selesai') {
                container.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #10b981;">
                        <i class="fas fa-check-circle fa-2x" style="margin-bottom: 12px;"></i>
                        <p style="margin: 0; font-weight: 600;">Tugas sudah selesai</p>
                    </div>
                `;
            }
        }
        async function mulaiTugas() {
            if (!confirm('Mulai mengerjakan tugas ini?')) return;
            try {
                const response = await fetch('../api/petugas/mulai_tugas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ penugasan_id: penugasanId })
                });
                const result = await response.json();
                if (result.success) {
                    alert('Tugas dimulai!');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memulai tugas');
            }
        }
        async function selesaikanTugas() {
            if (!confirm('Tandai tugas ini sebagai selesai?')) return;
            try {
                const response = await fetch('../api/petugas/selesaikan_tugas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ penugasan_id: penugasanId })
                });
                const result = await response.json();
                if (result.success) {
                    alert('Tugas selesai!');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal menyelesaikan tugas');
            }
        }
        document.addEventListener('DOMContentLoaded', loadDetail);
    </script>
</body>
</html>