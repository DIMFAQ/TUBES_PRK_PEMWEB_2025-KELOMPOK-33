<?php
require_once __DIR__ . '/../config.php';
if (!is_admin()) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard Admin - CleanSpot</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100 p-6">
  <h1 class="text-2xl font-bold mb-4">Dashboard Admin Ã¢â‚¬â€ CleanSpot</h1>
  <!-- CARD STATISTIC -->
  <div class="grid grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white shadow rounded">
      <div class="text-sm text-gray-500">Total Laporan</div>
      <div class="text-2xl font-semibold mt-2" id="totalReports">Ã¢â‚¬â€</div>
    </div>
    <div class="p-4 bg-white shadow rounded">
      <div class="text-sm text-gray-500">Laporan Baru</div>
      <div class="text-2xl font-semibold mt-2" id="newReports">Ã¢â‚¬â€</div>
    </div>
    <div class="p-4 bg-white shadow rounded">
      <div class="text-sm text-gray-500">Laporan Selesai</div>
      <div class="text-2xl font-semibold mt-2" id="doneReports">Ã¢â‚¬â€</div>
    </div>
  </div>
  <!-- CHART & MAP -->
  <div class="grid grid-cols-2 gap-6">
    <div class="bg-white p-4 shadow rounded">
      <h2 class="font-semibold mb-2">Grafik Per Kategori</h2>
      <canvas id="chartKategori"></canvas>
    </div>
    <div class="bg-white p-4 shadow rounded">
      <h2 class="font-semibold mb-2">Peta Lokasi Laporan</h2>
      <div id="map" style="height:450px;"></div>
    </div>
  </div>
  <script src="../assets/js/dashboard.js"></script>
</body>
</html>