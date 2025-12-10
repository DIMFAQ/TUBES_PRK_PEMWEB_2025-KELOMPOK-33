<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['admin']);
header('Content-Type: application/json');
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $laporan_id = $input['laporan_id'] ?? null;
    $petugas_id = $input['petugas_id'] ?? null;
    $catatan = $input['catatan'] ?? '';
    if (!$laporan_id || !$petugas_id) {
        throw new Exception('Data tidak lengkap');
    }
    $stmt = $pdo->prepare("SELECT * FROM laporan WHERE id = :id");
    $stmt->execute([':id' => $laporan_id]);
    $laporan = $stmt->fetch();
    if (!$laporan) {
        throw new Exception('Laporan tidak ditemukan');
    }
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE id = :id AND role = 'petugas'");
    $stmt->execute([':id' => $petugas_id]);
    $petugas = $stmt->fetch();
    if (!$petugas) {
        throw new Exception('Petugas tidak ditemukan');
    }
    $stmt = $pdo->prepare("
        SELECT id FROM penugasan 
        WHERE laporan_id = :laporan_id 
        AND status_penugasan IN ('ditugaskan', 'diterima', 'sedang_dikerjakan')
    ");
    $stmt->execute([':laporan_id' => $laporan_id]);
    if ($stmt->fetch()) {
        throw new Exception('Laporan sudah memiliki penugasan aktif');
    }
    $stmt = $pdo->prepare("
        INSERT INTO penugasan (laporan_id, petugas_id, catatan_petugas, status_penugasan)
        VALUES (:laporan_id, :petugas_id, :catatan, 'ditugaskan')
    ");
    $stmt->execute([
        ':laporan_id' => $laporan_id,
        ':petugas_id' => $petugas_id,
        ':catatan' => $catatan
    ]);
    $penugasan_id = $pdo->lastInsertId();
    $pdo->prepare("UPDATE laporan SET status = 'diproses' WHERE id = :id")
         ->execute([':id' => $laporan_id]);
    catat_log('create_penugasan', 'penugasan', $penugasan_id, 
              "Menugaskan laporan #{$laporan_id} ke {$petugas['nama']}");
    json_response([
        'success' => true,
        'message' => 'Berhasil menugaskan petugas',
        'data' => ['penugasan_id' => $penugasan_id]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}