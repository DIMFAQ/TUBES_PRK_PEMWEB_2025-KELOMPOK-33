<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');
try {
    $stmt = $pdo->prepare("SELECT kategori, COUNT(*) as total FROM laporan GROUP BY kategori");
    $stmt->execute();
    $kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt2 = $pdo->prepare("SELECT status, COUNT(*) as total FROM laporan GROUP BY status");
    $stmt2->execute();
    $status = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $stmt3 = $pdo->prepare("
        SELECT DATE(created_at) AS tanggal, COUNT(*) AS total
        FROM laporan
        GROUP BY DATE(created_at)
        ORDER BY tanggal DESC
        LIMIT 30
    ");
    $stmt3->execute();
    $tanggal = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'kategori' => $kategori,
        'status' => $status,
        'tanggal' => $tanggal
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}