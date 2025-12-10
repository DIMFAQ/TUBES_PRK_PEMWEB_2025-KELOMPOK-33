<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
header('Content-Type: application/json');
try {
    $status = $_GET['status'] ?? '';
    $kategori = $_GET['kategori'] ?? '';
    $where = ["l.lat IS NOT NULL", "l.lng IS NOT NULL"];
    $params = [];
    if ($status) {
        $where[] = "l.status = :status";
        $params[':status'] = $status;
    }
    if ($kategori) {
        $where[] = "l.kategori = :kategori";
        $params[':kategori'] = $kategori;
    }
    $where_clause = 'WHERE ' . implode(' AND ', $where);
    $stmt = $pdo->prepare("
        SELECT 
            l.id,
            l.judul,
            l.kategori,
            l.status,
            l.lat,
            l.lng,
            l.alamat,
            l.created_at,
            p.nama as nama_pelapor
        FROM laporan l
        JOIN pengguna p ON l.pengguna_id = p.id
        $where_clause
        ORDER BY l.created_at DESC
        LIMIT 500
    ");
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    json_response([
        'success' => true,
        'data' => $data,
        'total' => count($data)
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}