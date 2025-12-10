<?php
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
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pengguna $where_clause");
    $stmt->execute($params);
    $total = $stmt->fetchColumn();
    $stmt = $pdo->prepare("
        SELECT 
            id,
            nama,
            email,
            role,
            telepon,
            created_at
        FROM pengguna
        $where_clause
        ORDER BY created_at DESC
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