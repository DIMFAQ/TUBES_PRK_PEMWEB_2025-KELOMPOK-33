<?php
// api/admin/ambil_pengguna.php - Daftar pengguna dengan filter role
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';

cek_login();
cek_role(['admin']);

header('Content-Type: application/json');

try {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    
    $role = $_GET['role'] ?? '';
    $search = $_GET['search'] ?? '';
    
    $where = [];
    $params = [];
    
    if ($role) {
        $where[] = "role = :role";
        $params[':role'] = $role;
    }
    
    if ($search) {
        $where[] = "(nama LIKE :search OR email LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    $where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Hitung total
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengguna $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    
    // Ambil data
    $stmt = $pdo->prepare("
        SELECT 
            id,
            nama,
            email,
            role,
            created_at,
            (SELECT COUNT(*) FROM laporan WHERE pengguna_id = pengguna.id) as total_laporan
        FROM pengguna
        $where_clause
        ORDER BY created_at DESC
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
