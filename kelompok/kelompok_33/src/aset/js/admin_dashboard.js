let chartStatus = null;
let chartKategori = null;
let adminMap = null;

async function loadStatistics() {
    try {
        const response = await fetch('../api/statistik_data.php');
        const result = await response.json();
        if (result.success) {
            const data = result.data;
            document.getElementById('total-laporan').textContent = data.total.total_laporan || 0;
            document.getElementById('laporan-baru').textContent = data.status.baru || 0;
            document.getElementById('laporan-diproses').textContent = data.status.diproses || 0;
            document.getElementById('laporan-selesai').textContent = data.status.selesai || 0;
        }
    } catch (error) {
        console.error('Error loading statistics:', error);
        document.getElementById('total-laporan').textContent = '0';
        document.getElementById('laporan-baru').textContent = '0';
        document.getElementById('laporan-diproses').textContent = '0';
        document.getElementById('laporan-selesai').textContent = '0';
    }
}
async function loadCharts() {
    try {
        const response = await fetch('../api/statistik_data.php');
        const result = await response.json();

        if (result.success) {
            const data = result.data;

            const statusCanvas = document.getElementById('chart-status');
            const kategoriCanvas = document.getElementById('chart-kategori');

            if (!statusCanvas || !kategoriCanvas) return;

            if (chartStatus) {
                chartStatus.destroy();
            }
            if (chartKategori) {
                chartKategori.destroy();
            }

            chartStatus = new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: ['Baru', 'Diproses', 'Selesai'],
                    datasets: [{
                        data: [
                            data.status.baru || 0,
                            data.status.diproses || 0,
                            data.status.selesai || 0
                        ],
                        backgroundColor: ['#f59e0b', '#3b82f6', '#10b981']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            chartKategori = new Chart(kategoriCanvas, {
                type: 'pie',
                data: {
                    labels: ['Organik', 'Non-Organik', 'Lainnya'],
                    datasets: [{
                        data: [
                            data.kategori.organik || 0,
                            data.kategori['non-organik'] || 0,
                            data.kategori.lainnya || 0
                        ],
                        backgroundColor: ['#22c55e', '#ef4444', '#8b5cf6']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error loading charts:', error);
    }
}
async function loadMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    if (adminMap) {
        adminMap.remove();
    }

    adminMap = L.map('map').setView([-2.5, 118], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Ã‚Â© OpenStreetMap contributors'
    }).addTo(adminMap);
    try {
        const response = await fetch('../api/map-data.php');
        const result = await response.json();
        if (result.success && result.data && result.data.length > 0) {
            const markers = [];
            result.data.forEach(laporan => {
                if (laporan.lat && laporan.lng) {
                    let color = '#f59e0b';
                    if (laporan.status === 'selesai') color = '#10b981';
                    else if (laporan.status === 'diproses') color = '#3b82f6';
                    const marker = L.circleMarker([parseFloat(laporan.lat), parseFloat(laporan.lng)], {
                        radius: 8,
                        fillColor: color,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(adminMap);

                    marker.bindPopup(`
                        <div style="padding: 8px;">
                            <h4 style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">${laporan.judul}</h4>
                            <p style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">${laporan.kategori}</p>
                            <p style="font-size: 12px; margin-bottom: 2px;"><strong>Status:</strong> ${laporan.status}</p>
                            <p style="font-size: 12px; margin-bottom: 2px;"><strong>Pelapor:</strong> ${laporan.nama_pelapor}</p>
                            <p style="font-size: 12px; color: #9ca3af;">${new Date(laporan.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                    `);
                    markers.push(marker);
                }
            });
            if (markers.length > 0) {
                const group = L.featureGroup(markers);
                adminMap.fitBounds(group.getBounds().pad(0.1));
            }
        }
    } catch (error) {
        console.error('Error loading map data:', error);
    }
}
async function loadRecentReports() {
    try {
        const response = await fetch('../api/admin/ambil_laporan.php?limit=5');
        const result = await response.json();

        const tbody = document.getElementById('table-laporan-terbaru');
        if (!tbody) return;

        if (result.success && result.data && result.data.items && result.data.items.length > 0) {
            tbody.innerHTML = result.data.items.map(laporan => {
                let statusClass = 'badge-warning';
                if (laporan.status === 'selesai') statusClass = 'badge-success';
                else if (laporan.status === 'diproses') statusClass = 'badge-info';

                return `
                    <tr>
                        <td class="col-id">#${laporan.id}</td>
                        <td class="col-pelapor">${laporan.nama_pelapor}</td>
                        <td class="col-judul">${laporan.judul}</td>
                        <td class="col-kategori">${laporan.kategori}</td>
                        <td class="col-status"><span class="badge ${statusClass}">${laporan.status}</span></td>
                        <td class="col-tanggal">${new Date(laporan.created_at).toLocaleDateString('id-ID')}</td>
                        <td class="col-aksi">
                            <a href="detail_laporan_admin.php?id=${laporan.id}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="desktop-loading" style="text-align: center; color: #9ca3af; padding: 24px;">
                        Tidak ada laporan terbaru
                    </td>
                    <td colspan="3" class="mobile-loading" style="text-align: center; color: #9ca3af; padding: 24px; display: none;">
                        Tidak ada laporan terbaru
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error loading recent reports:', error);
        const tbody = document.getElementById('table-laporan-terbaru');
        if (!tbody) return;

        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; color: #ef4444; padding: 24px;">
                    Gagal memuat data
                </td>
            </tr>
        `;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadCharts();
    loadMap();
    loadRecentReports();
});