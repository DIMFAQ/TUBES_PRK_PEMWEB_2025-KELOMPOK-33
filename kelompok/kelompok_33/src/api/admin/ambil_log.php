<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['admin']);
header('Content-Type: application/json');
try {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $per_page = 50;
    $offset = ($page - 1) * $per_page;
    $aksi = $_GET['aksi'] ?? '';
    $tanggal = $_GET['tanggal'] ?? '';
    $where = [];
    $params = [];
    if ($aksi) {
        $where[] = "l.aksi = :aksi";
        $params[':aksi'] = $aksi;
    }
    if ($tanggal) {
        $where[] = "DATE(l.dibuat_pada) = :tanggal";
        $params[':tanggal'] = $tanggal;
    }
    $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM log_aktivitas l $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("
        SELECT 
            l.id,
            l.aksi,
            l.target_tipe,
            l.target_id,
            l.detail,
            l.dibuat_pada as created_at,
            p.nama as pengguna_nama,
            p.email as pengguna_email,
            p.role as pengguna_role
        FROM log_aktivitas l
        LEFT JOIN pengguna p ON l.pengguna_id = p.id
        $where_clause
        ORDER BY l.dibuat_pada DESC
        LIMIT $per_page OFFSET $offset
    ");
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    json_response([
        'success' => true,
        'data' => [
            'items' => $items,
            'pagination' => [
                'page' => $page,
                'limit' => $per_page,
                'total' => (int)$total
            ]
        ]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}