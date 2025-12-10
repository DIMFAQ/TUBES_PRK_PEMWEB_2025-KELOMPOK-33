document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadCharts();
    loadMap();
});
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
            new Chart(document.getElementById('chart-status'), {
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
            new Chart(document.getElementById('chart-kategori'), {
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
    const map = L.map('map').setView([-2.5, 118], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Ã‚Â© OpenStreetMap contributors'
    }).addTo(map);
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
                    }).addTo(map);
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
                map.fitBounds(group.getBounds().pad(0.1));
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
        const tbody = document.querySelector('#table-laporan-terbaru tbody');
        if (result.success && result.data && result.data.items && result.data.items.length > 0) {
            tbody.innerHTML = result.data.items.map(laporan => {
                let statusClass = 'badge-warning';
                if (laporan.status === 'selesai') statusClass = 'badge-success';
                else if (laporan.status === 'diproses') statusClass = 'badge-info';
                return `
                    <tr>
                        <td>${laporan.judul}</td>
                        <td>${laporan.kategori}</td>
                        <td>${laporan.nama_pelapor}</td>
                        <td><span class="badge ${statusClass}">${laporan.status}</span></td>
                        <td>${new Date(laporan.created_at).toLocaleDateString('id-ID')}</td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align: center; color: #9ca3af; padding: 24px;">
                        Tidak ada laporan
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error loading recent reports:', error);
        const tbody = document.querySelector('#table-laporan-terbaru tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; color: #ef4444; padding: 24px;">
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