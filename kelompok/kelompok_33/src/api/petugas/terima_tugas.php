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
        throw new Exception('Penugasan tidak ditemukan atau sudah diproses');
    }
    $stmt = $pdo->prepare("
        UPDATE penugasan 
        SET status_penugasan = 'diterima', accepted_at = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $penugasan_id]);
    catat_log('accept_task', 'penugasan', $penugasan_id, 
              "Menerima penugasan laporan #{$penugasan['laporan_id']}");
    json_response([
        'success' => true,
        'message' => 'Tugas berhasil diterima'
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}