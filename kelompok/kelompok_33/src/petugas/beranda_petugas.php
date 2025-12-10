<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - CleanSpot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';

cek_login();
cek_role(['petugas']);

$nama = $_SESSION['nama'] ?? 'Petugas';
?>

    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <h1 class="text-2xl font-bold">CleanSpot Petugas</h1>
                <span class="text-blue-200">Dashboard</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>Halo, <?= htmlspecialchars($nama) ?></span>
                <a href="../auth/logout.php" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Menu -->
    <div class="bg-white shadow">
        <div class="container mx-auto px-4">
            <div class="flex space-x-6 text-sm">
                <a href="beranda_petugas.php" class="py-3 border-b-2 border-blue-600 text-blue-600 font-semibold">Dashboard</a>
                <a href="tugas_saya.php" class="py-3 hover:text-blue-600">Tugas Saya</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="stats-cards">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Tugas Ditugaskan</div>
                <div class="text-3xl font-bold text-yellow-600" id="stat-ditugaskan">-</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Tugas Diterima</div>
                <div class="text-3xl font-bold text-blue-600" id="stat-diterima">-</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Sedang Dikerjakan</div>
                <div class="text-3xl font-bold text-orange-600" id="stat-dikerjakan">-</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-gray-600 text-sm">Selesai</div>
                <div class="text-3xl font-bold text-green-600" id="stat-selesai">-</div>
            </div>
        </div>

        <!-- Tugas Terbaru -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold">Tugas Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laporan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="table-tugas">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../aset/js/petugas_dashboard.js"></script>
</body>
</html>
