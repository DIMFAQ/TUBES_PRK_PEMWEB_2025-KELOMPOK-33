// warga_dashboard.js
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
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada laporan</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(row => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm">#${row.id}</td>
            <td class="px-6 py-4 text-sm font-medium">${row.judul}</td>
            <td class="px-6 py-4 text-sm">
                <span class="px-2 py-1 text-xs rounded ${getKategoriColor(row.kategori)}">
                    ${row.kategori}
                </span>
            </td>
            <td class="px-6 py-4 text-sm">
                <span class="px-2 py-1 text-xs rounded ${getStatusColor(row.status)}">
                    ${row.status}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${formatDate(row.created_at)}</td>
        </tr>
    `).join('');
}

function getKategoriColor(kategori) {
    const colors = {
        'organik': 'bg-green-100 text-green-800',
        'non-organik': 'bg-red-100 text-red-800',
        'lainnya': 'bg-purple-100 text-purple-800'
    };
    return colors[kategori] || 'bg-gray-100 text-gray-800';
}

function getStatusColor(status) {
    const colors = {
        'baru': 'bg-yellow-100 text-yellow-800',
        'diproses': 'bg-blue-100 text-blue-800',
        'selesai': 'bg-green-100 text-green-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric'
    });
}
