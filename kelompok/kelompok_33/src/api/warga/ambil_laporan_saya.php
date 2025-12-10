<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['warga']);
header('Content-Type: application/json');
try {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    $status = $_GET['status'] ?? '';
    $kategori = $_GET['kategori'] ?? '';
    $where = ["l.pengguna_id = :pengguna_id"];
    $params = [':pengguna_id' => $_SESSION['pengguna_id']];
    if ($status) {
        $where[] = "l.status = :status";
        $params[':status'] = $status;
    }
    if ($kategori) {
        $where[] = "l.kategori = :kategori";
        $params[':kategori'] = $kategori;
    }
    $where_clause = 'WHERE ' . implode(' AND ', $where);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM laporan l $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("
        SELECT 
            l.id,
            l.judul,
            l.deskripsi,
            l.kategori,
            l.status,
            l.alamat,
            l.lat,
            l.lng,
            l.created_at,
            l.updated_at,
            (SELECT COUNT(*) FROM foto_laporan WHERE laporan_id = l.id) as jumlah_foto,
            (SELECT COUNT(*) FROM penugasan WHERE laporan_id = l.id) as jumlah_penugasan,
            (SELECT COUNT(*) FROM komentar WHERE laporan_id = l.id) as jumlah_komentar
        FROM laporan l
        $where_clause
        ORDER BY l.created_at DESC
        LIMIT $per_page OFFSET $offset
    ");
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    json_response([
        'success' => true,
        'data' => $data,
        'pagination' => [
            'current_page' => $page,
            'per_page' => $per_page,
            'total' => (int)$total,
            'total_pages' => ceil($total / $per_page)
        ]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}