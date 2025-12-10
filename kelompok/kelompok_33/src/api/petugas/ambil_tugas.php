<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['petugas']);
header('Content-Type: application/json');
try {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    $status_penugasan = $_GET['status'] ?? '';
    $where = ["pg.petugas_id = :petugas_id"];
    $params = [':petugas_id' => $_SESSION['pengguna_id']];
    if ($status_penugasan) {
        $where[] = "pg.status_penugasan = :status";
        $params[':status'] = $status_penugasan;
    }
    $where_clause = 'WHERE ' . implode(' AND ', $where);
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM penugasan pg $where_clause
    ");
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("
        SELECT 
            pg.id,
            pg.laporan_id,
            pg.status_penugasan,
            pg.catatan_petugas,
            pg.assigned_at,
            pg.mulai_pada,
            pg.selesai_pada,
            l.judul as judul_laporan,
            l.deskripsi,
            l.kategori,
            l.status as status_laporan,
            l.alamat,
            l.lat,
            l.lng,
            p.nama as nama_pelapor,
            (SELECT COUNT(*) FROM foto_laporan WHERE laporan_id = l.id) as jumlah_foto
        FROM penugasan pg
        JOIN laporan l ON pg.laporan_id = l.id
        JOIN pengguna p ON l.pengguna_id = p.id
        $where_clause
        ORDER BY 
            FIELD(pg.status_penugasan, 'ditugaskan', 'dikerjakan', 'selesai'),
            pg.assigned_at DESC
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