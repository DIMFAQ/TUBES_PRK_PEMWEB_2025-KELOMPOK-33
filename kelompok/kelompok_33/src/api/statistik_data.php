<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
header('Content-Type: application/json');
try {
    $stmt_status = $pdo->query("
        SELECT status, COUNT(*) as jumlah 
        FROM laporan 
        GROUP BY status
    ");
    $data_status = [];
    while ($row = $stmt_status->fetch()) {
        $data_status[$row['status']] = (int)$row['jumlah'];
    }
    $stmt_kategori = $pdo->query("
        SELECT kategori, COUNT(*) as jumlah 
        FROM laporan 
        GROUP BY kategori
    ");
    $data_kategori = [];
    while ($row = $stmt_kategori->fetch()) {
        $data_kategori[$row['kategori']] = (int)$row['jumlah'];
    }
    $stmt_bulan = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as bulan,
            COUNT(*) as jumlah
        FROM laporan
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY bulan
        ORDER BY bulan ASC
    ");
    $data_bulan = $stmt_bulan->fetchAll(PDO::FETCH_ASSOC);
    $stmt_total = $pdo->query("
        SELECT 
            COUNT(*) as total_laporan,
            COUNT(DISTINCT pengguna_id) as total_pelapor,
            (SELECT COUNT(*) FROM pengguna WHERE role = 'petugas') as total_petugas,
            (SELECT COUNT(*) FROM penugasan) as total_penugasan
        FROM laporan
    ");
    $total = $stmt_total->fetch();
    json_response([
        'success' => true,
        'data' => [
            'status' => [
                'baru' => $data_status['baru'] ?? 0,
                'diproses' => $data_status['diproses'] ?? 0,
                'selesai' => $data_status['selesai'] ?? 0
            ],
            'kategori' => [
                'organik' => $data_kategori['organik'] ?? 0,
                'non-organik' => $data_kategori['non-organik'] ?? 0,
                'lainnya' => $data_kategori['lainnya'] ?? 0
            ],
            'trend_bulan' => $data_bulan,
            'total' => $total
        ]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}