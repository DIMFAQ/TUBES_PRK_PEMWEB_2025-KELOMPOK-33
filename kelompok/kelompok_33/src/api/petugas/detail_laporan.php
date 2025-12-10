<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['petugas']);
header('Content-Type: application/json');
try {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        json_response(['success' => false, 'message' => 'ID laporan diperlukan'], 400);
    }
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM penugasan 
        WHERE laporan_id = :laporan_id AND petugas_id = :petugas_id
    ");
    $stmt->execute([
        ':laporan_id' => $id,
        ':petugas_id' => $_SESSION['pengguna_id']
    ]);
    if ($stmt->fetchColumn() == 0) {
        json_response(['success' => false, 'message' => 'Anda tidak memiliki akses ke laporan ini'], 403);
    }
    $stmt = $pdo->prepare("
        SELECT 
            l.*,
            p.nama as nama_pelapor,
            p.email as email_pelapor,
            p.telepon as telepon_pelapor,
            p.alamat as alamat_pelapor
        FROM laporan l
        JOIN pengguna p ON l.pengguna_id = p.id
        WHERE l.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $laporan = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$laporan) {
        json_response(['success' => false, 'message' => 'Laporan tidak ditemukan'], 404);
    }
    $stmt_foto = $pdo->prepare("SELECT * FROM foto_laporan WHERE laporan_id = :id");
    $stmt_foto->execute([':id' => $id]);
    $laporan['foto'] = $stmt_foto->fetchAll(PDO::FETCH_ASSOC);
    json_response(['success' => true, 'data' => $laporan]);
} catch (Exception $e) {
    json_response(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}