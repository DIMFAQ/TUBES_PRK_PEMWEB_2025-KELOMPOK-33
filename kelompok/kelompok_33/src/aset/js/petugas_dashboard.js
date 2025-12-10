let map;
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    loadStats();
    loadTugas();
});
function initMap() {
    if (document.getElementById('map')) {
        map = L.map('map').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Ã‚Â© OpenStreetMap contributors'
        }).addTo(map);
        loadMapMarkers();
    }
}
async function loadMapMarkers() {
    try {
        const response = await fetch('../api/petugas/ambil_tugas.php?per_page=1000');
        const result = await response.json();
        if (result.success && result.data.length > 0) {
            const bounds = [];
            result.data.forEach(tugas => {
                if (tugas.lat && tugas.lng) {
                    const lat = parseFloat(tugas.lat);
                    const lng = parseFloat(tugas.lng);
                    let markerColor = '#f59e0b';
                    if (tugas.status_penugasan === 'dikerjakan') {
                        markerColor = '#3b82f6';
                    } else if (tugas.status_penugasan === 'selesai') {
                        markerColor = '#10b981';
                    }
                    const markerIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background: ${markerColor}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });
                    const marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);
                    let statusText = 'Tugas Baru';
                    if (tugas.status_penugasan === 'dikerjakan') statusText = 'Dikerjakan';
                    else if (tugas.status_penugasan === 'selesai') statusText = 'Selesai';
                    marker.bindPopup(`
                        <strong>${tugas.judul_laporan || 'Tanpa Judul'}</strong><br>
                        <span style="color: #6b7280; font-size: 12px;">${tugas.kategori}</span><br>
                        <span class="badge badge-${getStatusBadge(tugas.status_penugasan)}" style="font-size: 11px; margin-top: 4px; display: inline-block;">${statusText}</span><br>
                        <small style="color: #9ca3af;">${tugas.alamat || '-'}</small><br>
                        <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" 
                           target="_blank" 
                           style="display: inline-flex; align-items: center; gap: 4px; margin-top: 6px; padding: 4px 8px; background: #3b82f6; color: white; text-decoration: none; border-radius: 4px; font-size: 11px;">
                            <i class="fas fa-map-marker-alt"></i> Buka di Google Maps
                        </a>
                    `);
                    bounds.push([lat, lng]);
                }
            });
            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        }
    } catch (error) {
        console.error('Error loading map markers:', error);
    }
}
function getStatusBadge(status) {
    if (status === 'dikerjakan') return 'info';
    if (status === 'selesai') return 'success';
    return 'warning';
}
async function loadStats() {
    try {
        const response = await fetch('../api/petugas/ambil_tugas.php?per_page=1000');
        const result = await response.json();
        console.log('Stats data:', result);
        if (result.success) {
            const stats = {
                ditugaskan: 0,
                dikerjakan: 0,
                selesai: 0
            };
            result.data.forEach(tugas => {
                console.log('Tugas status:', tugas.status_penugasan);
                if (tugas.status_penugasan === 'ditugaskan') {
                    stats.ditugaskan++;
                } else if (tugas.status_penugasan === 'dikerjakan') {
                    stats.dikerjakan++;
                } else if (tugas.status_penugasan === 'selesai') {
                    stats.selesai++;
                }
            });
            console.log('Stats result:', stats);
            document.getElementById('stat-ditugaskan').textContent = stats.ditugaskan;
            document.getElementById('stat-dikerjakan').textContent = stats.dikerjakan;
            document.getElementById('stat-selesai').textContent = stats.selesai;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        document.getElementById('stat-ditugaskan').textContent = '0';
        document.getElementById('stat-dikerjakan').textContent = '0';
        document.getElementById('stat-selesai').textContent = '0';
    }
}
async function loadTugas() {
    try {
        const response = await fetch('../api/petugas/ambil_tugas.php?per_page=10');
        const result = await response.json();
        if (result.success) {
            renderTable(result.data);
        }
    } catch (error) {
        console.error('Error loading tugas:', error);
    }
}
function renderTable(data) {
    const tbody = document.getElementById('table-tugas');
    if (!data || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 48px; color: #9ca3af;">Tidak ada tugas</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(row => {
        let statusClass = 'badge-warning';
        let statusText = 'Ditugaskan';
        if (row.status_penugasan === 'dikerjakan') {
            statusClass = 'badge-info';
            statusText = 'Dikerjakan';
        } else if (row.status_penugasan === 'selesai') {
            statusClass = 'badge-success';
            statusText = 'Selesai';
        }
        console.log('Row data:', row);
        return `
            <tr>
                <td><strong>${row.judul_laporan || 'Tanpa Judul'}</strong></td>
                <td>${row.kategori || '-'}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${(row.deskripsi || '-').replace(/"/g, '&quot;')}">${row.deskripsi || '-'}</td>
                <td>${row.assigned_at ? new Date(row.assigned_at).toLocaleDateString('id-ID') : '-'}</td>
            </tr>
        `;
    }).join('');
}
function getStatusColor(status) {
    const colors = {
        'ditugaskan': 'badge-warning',
        'dikerjakan': 'badge-info',
        'selesai': 'badge-success'
    };
    return colors[status] || 'badge-warning';
}
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric'
    });
}