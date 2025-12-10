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
            renderTable(result.data);
        }
    } catch (error) {
        console.error('Error loading laporan:', error);
    }
}
function renderTable(data) {
    const tbody = document.getElementById('table-laporan');
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align: center; padding: 48px; color: #9ca3af;">
                    <i class="fas fa-inbox fa-3x" style="margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Belum ada laporan</p>
                    <a href="buat_laporan.php" class="btn btn-primary" style="margin-top: 16px;">
                        <i class="fas fa-plus"></i> Buat Laporan Pertama
                    </a>
                </td>
            </tr>
        `;
        return;
    }
    tbody.innerHTML = data.map(row => {
        let statusClass = 'badge-warning';
        if (row.status === 'selesai') statusClass = 'badge-success';
        else if (row.status === 'diproses') statusClass = 'badge-info';
        let statusText = row.status.charAt(0).toUpperCase() + row.status.slice(1);
        return `
            <tr>
                <td><strong>${row.judul}</strong></td>
                <td>${row.kategori}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td>${formatDate(row.created_at)}</td>
            </tr>
        `;
    }).join('');
}
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric'
    });
}