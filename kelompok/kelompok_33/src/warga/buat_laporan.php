<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan - CleanSpot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100">
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';

cek_login();
cek_role(['warga']);

$nama = $_SESSION['nama'] ?? 'Warga';
?>

    <!-- Navigation -->
    <nav class="bg-purple-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold">CleanSpot</h1>
                <span class="text-purple-200">Buat Laporan</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>Halo, <?= htmlspecialchars($nama) ?></span>
                <a href="../auth/logout.php" class="bg-purple-700 hover:bg-purple-800 px-4 py-2 rounded">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Menu -->
    <div class="bg-white shadow">
        <div class="container mx-auto px-4">
            <div class="flex space-x-6 text-sm">
                <a href="beranda_warga.php" class="py-3 hover:text-purple-600">Dashboard</a>
                <a href="buat_laporan.php" class="py-3 border-b-2 border-purple-600 text-purple-600 font-semibold">Buat Laporan</a>
                <a href="laporan_saya.php" class="py-3 hover:text-purple-600">Laporan Saya</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow p-6 max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Buat Laporan Sampah Baru</h2>
            
            <div id="alert" class="hidden mb-4 p-3 rounded"></div>
            
            <form id="form-laporan" onsubmit="submitLaporan(event)">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Judul Laporan *</label>
                    <input type="text" id="judul" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                        placeholder="Contoh: Tumpukan sampah di Jl. Merdeka">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi *</label>
                    <textarea id="deskripsi" required rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                        placeholder="Jelaskan kondisi sampah secara detail..."></textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Kategori *</label>
                        <select id="kategori" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="organik">Organik</option>
                            <option value="non-organik">Non-Organik</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Foto (Opsional)</label>
                        <input type="file" id="foto" accept="image/*" multiple
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Bisa upload beberapa foto</p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Alamat Lokasi *</label>
                    <input type="text" id="alamat" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                        placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Pilih Lokasi di Peta (Opsional)</label>
                    <p class="text-sm text-gray-600 mb-2">Klik pada peta untuk menandai lokasi</p>
                    <div id="map" class="h-96 rounded-lg border border-gray-300"></div>
                    <input type="hidden" id="lat">
                    <input type="hidden" id="lng">
                    <p class="text-xs text-gray-500 mt-1" id="coords-display">Koordinat belum dipilih</p>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" 
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                        Kirim Laporan
                    </button>
                    <a href="beranda_warga.php" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-3 px-6 rounded-lg text-center transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../aset/js/warga_buat_laporan.js"></script>
</body>
</html>
