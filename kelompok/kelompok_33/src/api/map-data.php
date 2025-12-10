<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
cek_login();
header('Content-Type: application/json');
try {
    $sql = "
        SELECT 
            l.id,
            l.judul,
            l.kategori,
            l.status,
            l.lat,
            l.lng,
            l.created_at,
            p.nama as nama_pelapor
        FROM laporan l
        JOIN pengguna p ON l.pengguna_id = p.id
        WHERE l.lat IS NOT NULL AND l.lng IS NOT NULL
        ORDER BY l.created_at DESC
        LIMIT 100
    ";
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    json_response([
        'success' => true,
        'data' => $data
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ], 500);
}