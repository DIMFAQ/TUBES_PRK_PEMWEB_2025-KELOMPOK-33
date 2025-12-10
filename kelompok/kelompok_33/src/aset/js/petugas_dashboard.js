// petugas_dashboard.js
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadTugas();
});

async function loadStats() {
    try {
        const response = await fetch('../api/petugas/ambil_tugas.php?per_page=1000');
        const result = await response.json();
        
        if (result.success) {
            const stats = {
                ditugaskan: 0,
                diterima: 0,
                sedang_dikerjakan: 0,
                selesai: 0
            };
            
            result.data.forEach(tugas => {
                if (stats.hasOwnProperty(tugas.status_penugasan)) {
                    stats[tugas.status_penugasan]++;
                }
            });
            
            document.getElementById('stat-ditugaskan').textContent = stats.ditugaskan;
            document.getElementById('stat-diterima').textContent = stats.diterima;
            document.getElementById('stat-dikerjakan').textContent = stats.sedang_dikerjakan;
            document.getElementById('stat-selesai').textContent = stats.selesai;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
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
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada tugas</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(row => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm">#${row.id}</td>
            <td class="px-6 py-4 text-sm font-medium">${row.judul}</td>
            <td class="px-6 py-4 text-sm">
                <span class="px-2 py-1 text-xs rounded ${getPrioritasColor(row.prioritas)}">
                    ${row.prioritas}
                </span>
            </td>
            <td class="px-6 py-4 text-sm">
                <span class="px-2 py-1 text-xs rounded ${getStatusColor(row.status_penugasan)}">
                    ${row.status_penugasan.replace('_', ' ')}
                </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-600">${formatDate(row.tanggal_penugasan)}</td>
            <td class="px-6 py-4 text-sm">
                <a href="detail_tugas.php?id=${row.id}" class="text-blue-600 hover:text-blue-800">Detail</a>
            </td>
        </tr>
    `).join('');
}

function getPrioritasColor(prioritas) {
    const colors = {
        'tinggi': 'bg-red-100 text-red-800',
        'sedang': 'bg-yellow-100 text-yellow-800',
        'rendah': 'bg-green-100 text-green-800'
    };
    return colors[prioritas] || 'bg-gray-100 text-gray-800';
}

function getStatusColor(status) {
    const colors = {
        'ditugaskan': 'bg-yellow-100 text-yellow-800',
        'diterima': 'bg-blue-100 text-blue-800',
        'sedang_dikerjakan': 'bg-orange-100 text-orange-800',
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
