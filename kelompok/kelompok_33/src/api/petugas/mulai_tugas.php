<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['petugas']);
header('Content-Type: application/json');
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $penugasan_id = $input['penugasan_id'] ?? null;
    if (!$penugasan_id) {
        throw new Exception('ID penugasan tidak ditemukan');
    }
    $stmt = $pdo->prepare("
        SELECT * FROM penugasan 
        WHERE id = :id 
        AND petugas_id = :petugas_id 
        AND status_penugasan = 'ditugaskan'
    ");
    $stmt->execute([
        ':id' => $penugasan_id,
        ':petugas_id' => $_SESSION['pengguna_id']
    ]);
    $penugasan = $stmt->fetch();
    if (!$penugasan) {
        throw new Exception('Penugasan tidak ditemukan');
    }
    $stmt = $pdo->prepare("
        UPDATE penugasan 
        SET status_penugasan = 'dikerjakan', mulai_pada = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $penugasan_id]);
    catat_log('start_task', 'penugasan', $penugasan_id, 
              "Mulai mengerjakan laporan #{$penugasan['laporan_id']}");
    json_response([
        'success' => true,
        'message' => 'Tugas dimulai'
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}