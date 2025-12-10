let map, marker;
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});
function initMap() {
    map = L.map('map').setView([-2.5, 118], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Ã‚Â© OpenStreetMap contributors'
    }).addTo(map);
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;
        document.getElementById('coords-display').textContent = 
            `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    });
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            map.setView([lat, lng], 13);
        });
    }
}
async function submitLaporan(event) {
    event.preventDefault();
    const alert = document.getElementById('alert');
    const submitBtn = event.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Mengirim...';
    try {
        const formData = new FormData();
        formData.append('judul', document.getElementById('judul').value);
        formData.append('deskripsi', document.getElementById('deskripsi').value);
        formData.append('kategori', document.getElementById('kategori').value);
        formData.append('alamat', document.getElementById('alamat').value);
        formData.append('lat', document.getElementById('lat').value || '');
        formData.append('lng', document.getElementById('lng').value || '');
        const fotoInput = document.getElementById('foto');
        if (fotoInput.files.length > 0) {
            for (let i = 0; i < fotoInput.files.length; i++) {
                formData.append('foto[]', fotoInput.files[i]);
            }
        }
        const response = await fetch('../api/warga/buat_laporan.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert.className = 'mb-4 p-3 rounded bg-green-100 text-green-700';
            alert.textContent = 'Laporan berhasil dibuat! Mengalihkan...';
            alert.classList.remove('hidden');
            setTimeout(() => {
                window.location.href = 'laporan_saya.php';
            }, 1500);
        } else {
            alert.className = 'mb-4 p-3 rounded bg-red-100 text-red-700';
            alert.textContent = 'Error: ' + result.message;
            alert.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Laporan';
        }
    } catch (error) {
        console.error('Error:', error);
        alert.className = 'mb-4 p-3 rounded bg-red-100 text-red-700';
        alert.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        alert.classList.remove('hidden');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Kirim Laporan';
    }
}