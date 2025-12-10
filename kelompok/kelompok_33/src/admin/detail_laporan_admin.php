<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - CleanSpot Admin</title>
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
        .penugasan-item {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #10b981;
        }
        .komentar-item {
            background: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .komentar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .komentar-nama {
            font-weight: 600;
            color: #374151;
        }
        .komentar-waktu {
            color: #9ca3af;
            font-size: 12px;
        }
        .komentar-isi {
            color: #6b7280;
            line-height: 1.6;
        }
        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 48px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
cek_login();
cek_role(['admin']);
$nama_admin = $_SESSION['nama'] ?? 'Admin';
$initial = strtoupper(substr($nama_admin, 0, 1));
$laporan_id = $_GET['id'] ?? null;
if (!$laporan_id) {
    header('Location: laporan_admin.php');
    exit;
}
?>
    <!-- Sidebar -->
    <div class="sidebar">
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
        <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <h1>Detail Laporan</h1>
                <p>Informasi lengkap laporan</p>
            </div>
            <div>
                <a href="laporan_admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div id="loading-container" class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>
        <div id="detail-container" style="display: none;">
            <!-- Informasi Utama -->
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
                        <div class="info-label">Tanggal Dilaporkan</div>
                        <div class="info-value" id="info-tanggal"></div>
                    </div>
                </div>
            </div>
            <!-- Informasi Pelapor -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Informasi Pelapor</h3>
                <div>
                    <div class="info-row">
                        <div class="info-label">Nama</div>
                        <div class="info-value" id="pelapor-nama"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value" id="pelapor-email"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Telepon</div>
                        <div class="info-value" id="pelapor-telepon"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value" id="pelapor-alamat"></div>
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
            <!-- Penugasan -->
            <div class="chart-card" style="margin-bottom: 24px;">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Riwayat Penugasan</h3>
                <div id="penugasan-container"></div>
            </div>
            <!-- Komentar -->
            <div class="chart-card">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600;">Komentar</h3>
                <div id="komentar-container"></div>
            </div>
        </div>
    </div>
    <script>
        const laporanId = <?= json_encode($laporan_id) ?>;
        let detailMap = null;
        async function loadDetailLaporan() {
            try {
                const response = await fetch(`../api/admin/detail_laporan.php?id=${laporanId}`);
                const result = await response.json();
                if (result.success && result.data) {
                    const laporan = result.data;
                    let statusClass = 'badge-warning';
                    if (laporan.status === 'selesai') statusClass = 'badge-success';
                    else if (laporan.status === 'diproses') statusClass = 'badge-info';
                    document.getElementById('status-badge').innerHTML = 
                        `<span class="badge ${statusClass}">${laporan.status}</span>`;
                    document.getElementById('info-judul').textContent = laporan.judul || '-';
                    document.getElementById('info-kategori').textContent = laporan.kategori || '-';
                    document.getElementById('info-deskripsi').textContent = laporan.deskripsi || '-';
                    document.getElementById('info-alamat').textContent = laporan.alamat || '-';
                    document.getElementById('info-tanggal').textContent = 
                        new Date(laporan.created_at).toLocaleString('id-ID');
                    document.getElementById('pelapor-nama').textContent = laporan.nama_pelapor || '-';
                    document.getElementById('pelapor-email').textContent = laporan.email_pelapor || '-';
                    document.getElementById('pelapor-telepon').textContent = laporan.telepon_pelapor || '-';
                    document.getElementById('pelapor-alamat').textContent = laporan.alamat_pelapor || '-';
                    const fotoContainer = document.getElementById('foto-container');
                    if (laporan.foto && laporan.foto.length > 0) {
                        fotoContainer.innerHTML = laporan.foto.map(foto => `
                            <div class="foto-item">
                                <img src="../${foto.path_file}" 
                                     alt="Foto laporan"
                                     onclick="window.open(this.src, '_blank')">
                            </div>
                        `).join('');
                    } else {
                        fotoContainer.innerHTML = '<p style="color: #9ca3af;">Tidak ada foto</p>';
                    }
                    if (laporan.lat && laporan.lng) {
                        initMap(parseFloat(laporan.lat), parseFloat(laporan.lng));
                    }
                    const penugasanContainer = document.getElementById('penugasan-container');
                    if (laporan.penugasan && laporan.penugasan.length > 0) {
                        penugasanContainer.innerHTML = laporan.penugasan.map(p => {
                            let statusBadge = '<span class="badge badge-warning">Ditugaskan</span>';
                            if (p.status === 'selesai') statusBadge = '<span class="badge badge-success">Selesai</span>';
                            else if (p.status === 'dikerjakan') statusBadge = '<span class="badge badge-info">Dikerjakan</span>';
                            return `
                                <div class="penugasan-item">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <strong>${p.nama_petugas}</strong>
                                        ${statusBadge}
                                    </div>
                                    <div style="color: #6b7280; font-size: 14px;">
                                        <div>Telepon: ${p.telepon_petugas || '-'}</div>
                                        <div>Ditugaskan: ${new Date(p.created_at).toLocaleString('id-ID')}</div>
                                        ${p.mulai_at ? `<div>Mulai: ${new Date(p.mulai_at).toLocaleString('id-ID')}</div>` : ''}
                                        ${p.selesai_at ? `<div>Selesai: ${new Date(p.selesai_at).toLocaleString('id-ID')}</div>` : ''}
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        penugasanContainer.innerHTML = '<p style="color: #9ca3af;">Belum ada penugasan</p>';
                    }
                    const komentarContainer = document.getElementById('komentar-container');
                    if (laporan.komentar && laporan.komentar.length > 0) {
                        komentarContainer.innerHTML = laporan.komentar.map(k => `
                            <div class="komentar-item">
                                <div class="komentar-header">
                                    <span class="komentar-nama">${k.nama_pengguna}</span>
                                    <span class="komentar-waktu">${new Date(k.created_at).toLocaleString('id-ID')}</span>
                                </div>
                                <div class="komentar-isi">${k.isi}</div>
                            </div>
                        `).join('');
                    } else {
                        komentarContainer.innerHTML = '<p style="color: #9ca3af;">Belum ada komentar</p>';
                    }
                    document.getElementById('loading-container').style.display = 'none';
                    document.getElementById('detail-container').style.display = 'block';
                } else {
                    alert('Laporan tidak ditemukan');
                    window.location.href = 'laporan_admin.php';
                }
            } catch (error) {
                console.error('Error loading detail:', error);
                alert('Gagal memuat detail laporan');
                window.location.href = 'laporan_admin.php';
            }
        }
        function initMap(lat, lng) {
            if (detailMap) {
                detailMap.remove();
            }
            detailMap = L.map('detail-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Ã‚Â© OpenStreetMap contributors'
            }).addTo(detailMap);
            L.marker([lat, lng]).addTo(detailMap)
                .bindPopup('Lokasi Laporan')
                .openPopup();
        }
        document.addEventListener('DOMContentLoaded', loadDetailLaporan);
    </script>
</body>
</html>