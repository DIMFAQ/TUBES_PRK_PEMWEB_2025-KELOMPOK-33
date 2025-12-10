let currentPage = 1;
let petugasList = [];
document.addEventListener('DOMContentLoaded', function() {
    loadPetugas();
    loadLaporan(1);
});
async function loadPetugas() {
    try {
        const response = await fetch('../api/admin/ambil_pengguna.php?role=petugas&limit=100');
        const result = await response.json();
        if (result.success && result.data.items) {
            petugasList = result.data.items;
            const select = document.getElementById('assign-petugas');
            select.innerHTML = '<option value="">-- Pilih Petugas --</option>';
            result.data.items.forEach(petugas => {
                select.innerHTML += `<option value="${petugas.id}">${petugas.nama} (${petugas.email})</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading petugas:', error);
    }
}
async function loadLaporan(page) {
    currentPage = page;
    const status = document.getElementById('filter-status').value;
    const kategori = document.getElementById('filter-kategori').value;
    const search = document.getElementById('filter-search').value;
    let url = `../api/admin/ambil_laporan.php?page=${page}`;
    if (status) url += `&status=${status}`;
    if (kategori) url += `&kategori=${kategori}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    try {
        const response = await fetch(url);
        const result = await response.json();
        if (result.success && result.data) {
            renderTable(result.data.items || result.data);
            renderPagination(result.data.pagination || result.pagination);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error loading laporan:', error);
        alert('Terjadi kesalahan saat memuat data');
    }
}
function renderTable(data) {
    const tbody = document.getElementById('table-body');
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--gray-600);">Tidak ada data</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(row => `
        <tr>
            <td style="font-size: 13px; color: var(--gray-600);">#${row.id}</td>
            <td style="font-weight: 600;">${row.judul}</td>
            <td>${row.nama_pelapor}</td>
            <td>${getKategoriBadge(row.kategori)}</td>
            <td>${getStatusBadge(row.status)}</td>
            <td style="font-size: 13px; color: var(--gray-600);">${formatDate(row.created_at)}</td>
            <td>
                <div style="display: flex; gap: 8px;">
                    <button onclick="window.location.href='detail_laporan_admin.php?id=${row.id}'" class="btn btn-sm btn-secondary">
                        <i class="fas fa-eye"></i> Detail
                    </button>
                    ${row.status === 'baru' ? `<button onclick="openAssignModal(${row.id})" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus"></i> Tugaskan
                    </button>` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}
function renderPagination(pagination) {
    const info = document.getElementById('pagination-info');
    const buttons = document.getElementById('pagination-buttons');
    const currentPage = pagination.page || pagination.current_page || 1;
    const totalPages = pagination.total_pages || 1;
    info.textContent = `Menampilkan ${pagination.total} data`;
    let buttonsHTML = '';
    if (currentPage > 1) {
        buttonsHTML += `<button onclick="loadLaporan(${currentPage - 1})" class="btn btn-sm btn-secondary">Prev</button>`;
    }
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            buttonsHTML += `<button class="btn btn-sm btn-primary">${i}</button>`;
        } else if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 2) {
            buttonsHTML += `<button onclick="loadLaporan(${i})" class="btn btn-sm btn-secondary">${i}</button>`;
        } else if (Math.abs(i - currentPage) === 3) {
            buttonsHTML += `<span style="padding: 0 8px;">...</span>`;
        }
    }
    if (currentPage < totalPages) {
        buttonsHTML += `<button onclick="loadLaporan(${currentPage + 1})" class="btn btn-sm btn-secondary">Next</button>`;
    }
    buttons.innerHTML = buttonsHTML;
}
function getStatusBadge(status) {
    const badges = {
        'baru': '<span class="badge warning">Baru</span>',
        'diproses': '<span class="badge info">Diproses</span>',
        'selesai': '<span class="badge success">Selesai</span>'
    };
    return badges[status] || `<span class="badge">${status}</span>`;
}
function getKategoriBadge(kategori) {
    const badges = {
        'organik': '<span class="badge success">Organik</span>',
        'non-organik': '<span class="badge danger">Non-Organik</span>',
        'lainnya': '<span class="badge" style="background-color: var(--secondary-purple); color: white;">Lainnya</span>'
    };
    return badges[kategori] || `<span class="badge">${kategori}</span>`;
}
function getStatusColor(status) {
    const colors = {
        'baru': 'bg-yellow-100 text-yellow-800',
        'diproses': 'bg-blue-100 text-blue-800',
        'selesai': 'bg-green-100 text-green-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}
function getKategoriColor(kategori) {
    const colors = {
        'organik': 'bg-green-100 text-green-800',
        'non-organik': 'bg-red-100 text-red-800',
        'lainnya': 'bg-purple-100 text-purple-800'
    };
    return colors[kategori] || 'bg-gray-100 text-gray-800';
}
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
function openAssignModal(laporanId) {
    document.getElementById('assign-laporan-id').value = laporanId;
    const modal = document.getElementById('modal-assign');
    modal.style.display = 'flex';
}
function closeModal() {
    const modal = document.getElementById('modal-assign');
    modal.style.display = 'none';
    document.getElementById('form-assign').reset();
}
async function submitAssign(event) {
    event.preventDefault();
    const laporan_id = document.getElementById('assign-laporan-id').value;
    const petugas_id = document.getElementById('assign-petugas').value;
    const catatan = document.getElementById('assign-catatan').value;
    try {
        const response = await fetch('../api/admin/tugaskan_petugas.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                laporan_id: parseInt(laporan_id),
                petugas_id: parseInt(petugas_id),
                catatan: catatan
            })
        });
        const result = await response.json();
        if (result.success) {
            alert('Berhasil menugaskan petugas!');
            closeModal();
            loadLaporan(currentPage);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error assigning petugas:', error);
        alert('Terjadi kesalahan saat menugaskan petugas');
    }
}