document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadLaporan();
});
async function loadStats() {
    try {
        const response = await fetch('../api/warga/ambil_laporan_saya.php?per_page=1000');
        const result = await response.json();
        if (result.success) {
            const stats = {
                total: result.data.length,
                baru: 0,
                diproses: 0,
                selesai: 0
            };
            result.data.forEach(laporan => {
                if (laporan.status === 'baru') stats.baru++;
                if (laporan.status === 'diproses') stats.diproses++;
                if (laporan.status === 'selesai') stats.selesai++;
            });
            document.getElementById('stat-total').textContent = stats.total;
            document.getElementById('stat-diproses').textContent = stats.diproses;
            document.getElementById('stat-selesai').textContent = stats.selesai;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}
async function loadLaporan() {
    try {
        const response = await fetch('../api/warga/ambil_laporan_saya.php?per_page=10');
        const result = await response.json();
        if (result.success) {
            renderCards(result.data);
        }
    } catch (error) {
        console.error('Error loading laporan:', error);
    }
}

function renderCards(data) {
    const container = document.getElementById('reports-container');
    
    if (!container) {
        console.error('reports-container element not found');
        return;
    }
    
    if (data.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada laporan</p>
            </div>
        `;
        return;
    }

    container.innerHTML = data.map(row => {
        let statusClass = 'badge-warning';
        let statusText = 'Sedang Diproses';
        
        if (row.status === 'selesai') {
            statusClass = 'badge-success';
            statusText = 'Selesai';
        } else if (row.status === 'diproses') {
            statusClass = 'badge-info';
            statusText = 'Sedang Diproses';
        } else if (row.status === 'baru') {
            statusClass = 'badge-warning';
            statusText = 'Baru';
        }

        let imageHTML = '';
        if (row.foto_laporan) {
            imageHTML = `<img src="../uploads/laporan/${row.foto_laporan}" alt="${row.judul}" class="report-image" loading="lazy">`;
        } else {
            imageHTML = `
                <div class="report-image report-placeholder">
                    <i class="fas fa-trash-alt"></i>
                    <span>No Photo</span>
                </div>
            `;
        }

        return `
            <div class="report-card">
                ${imageHTML}
                <div class="report-content">
                    <div class="report-header">
                        <span class="report-id">LP-${String(row.id_laporan).padStart(3, '0')}</span>
                        <span class="badge ${statusClass}">${statusText}</span>
                    </div>
                    <h4 class="report-title">${row.judul}</h4>
                    <p class="report-description">${row.deskripsi.substring(0, 80)}${row.deskripsi.length > 80 ? '...' : ''}</p>
                    <div class="report-meta">
                        <div class="report-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${row.alamat || 'Lokasi tidak tersedia'}</span>
                        </div>
                        <div class="report-meta-item">
                            <i class="fas fa-clock"></i>
                            <span>${formatDateTime(row.created_at)}</span>
                        </div>
                    </div>
                    <div class="report-actions">
                        <a href="laporan_saya.php?id=${row.id_laporan}" class="btn-detail">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    const day = date.getDate();
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${day} ${month} ${year}, ${hours}:${minutes}`;
}