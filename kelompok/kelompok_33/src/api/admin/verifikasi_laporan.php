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
    $status = $input['status'] ?? null;
    $catatan = $input['catatan'] ?? '';
    if (!$laporan_id || !$status) {
        throw new Exception('Data tidak lengkap');
    }
    $allowed_status = ['baru', 'diproses', 'selesai'];
    if (!in_array($status, $allowed_status)) {
        throw new Exception('Status tidak valid');
    }
    $stmt = $pdo->prepare("SELECT * FROM laporan WHERE id = :id");
    $stmt->execute([':id' => $laporan_id]);
    $laporan = $stmt->fetch();
    if (!$laporan) {
        throw new Exception('Laporan tidak ditemukan');
    }
    $status_lama = $laporan['status'];
    $stmt = $pdo->prepare("
        UPDATE laporan 
        SET status = :status, updated_at = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([
        ':status' => $status,
        ':id' => $laporan_id
    ]);
    if ($catatan) {
        $stmt = $pdo->prepare("
            INSERT INTO komentar (laporan_id, pengguna_id, komentar)
            VALUES (:laporan_id, :pengguna_id, :komentar)
        ");
        $stmt->execute([
            ':laporan_id' => $laporan_id,
            ':pengguna_id' => $_SESSION['pengguna_id'],
            ':komentar' => $catatan
        ]);
    }
    catat_log('update_status', 'laporan', $laporan_id, 
              "Mengubah status dari '$status_lama' ke '$status'");
    json_response([
        'success' => true,
        'message' => 'Status laporan berhasil diperbarui'
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}