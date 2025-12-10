<?php
// api/admin/ambil_log.php - Daftar log aktivitas
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
    $tabel = $_GET['tabel'] ?? '';
    
    $where = [];
    $params = [];
    
    if ($aksi) {
        $where[] = "l.aksi = :aksi";
        $params[':aksi'] = $aksi;
    }
    
    if ($tabel) {
        $where[] = "l.tabel = :tabel";
        $params[':tabel'] = $tabel;
    }
    
    $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Hitung total
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM log_aktivitas l $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    
    // Ambil data
    $stmt = $pdo->prepare("
        SELECT 
            l.id,
            l.aksi,
            l.tabel,
            l.record_id,
            l.deskripsi,
            l.created_at,
            p.nama as pengguna_nama,
            p.email as pengguna_email,
            p.role as pengguna_role
        FROM log_aktivitas l
        LEFT JOIN pengguna p ON l.pengguna_id = p.id
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
