<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Warga - CleanSpot</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <span class="text-purple-200">Dashboard Warga</span>
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
                <a href="beranda_warga.php" class="py-3 border-b-2 border-purple-600 text-purple-600 font-semibold">Dashboard</a>
                <a href="buat_laporan.php" class="py-3 hover:text-purple-600">Buat Laporan</a>
                <a href="laporan_saya.php" class="py-3 hover:text-purple-600">Laporan Saya</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6" id="stats-cards">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Total Laporan Saya</div>
                <div class="text-3xl font-bold text-purple-600" id="stat-total">-</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Sedang Diproses</div>
                <div class="text-3xl font-bold text-blue-600" id="stat-diproses">-</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Selesai</div>
                <div class="text-3xl font-bold text-green-600" id="stat-selesai">-</div>
            </div>
        </div>

        <!-- Quick Action -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-2">Laporkan Sampah Sekarang!</h2>
            <p class="mb-4">Bantu bersihkan lingkungan dengan melaporkan sampah di sekitar Anda.</p>
            <a href="buat_laporan.php" class="inline-block bg-white text-purple-600 font-semibold px-6 py-2 rounded hover:bg-gray-100 transition">
                Buat Laporan Baru
            </a>
        </div>

        <!-- Laporan Terbaru -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Laporan Terbaru Saya</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="table-laporan">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../aset/js/warga_dashboard.js"></script>
</body>
</html>
