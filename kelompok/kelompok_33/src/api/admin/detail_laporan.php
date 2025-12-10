<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_role('admin');
header('Content-Type: application/json');
try {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        json_response(['success' => false, 'message' => 'ID laporan diperlukan'], 400);
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
    $stmt_penugasan = $pdo->prepare("
        SELECT 
            pg.*,
            pt.nama as nama_petugas,
            pt.telepon as telepon_petugas
        FROM penugasan pg
        JOIN pengguna pt ON pg.petugas_id = pt.id
        WHERE pg.laporan_id = :id
    ");
    $stmt_penugasan->execute([':id' => $id]);
    $laporan['penugasan'] = $stmt_penugasan->fetchAll(PDO::FETCH_ASSOC);
    $stmt_komentar = $pdo->prepare("
        SELECT 
            k.*,
            p.nama as nama_pengguna
        FROM komentar k
        JOIN pengguna p ON k.pengguna_id = p.id
        WHERE k.laporan_id = :id
        ORDER BY k.created_at DESC
    ");
    $stmt_komentar->execute([':id' => $id]);
    $laporan['komentar'] = $stmt_komentar->fetchAll(PDO::FETCH_ASSOC);
    json_response(['success' => true, 'data' => $laporan]);
} catch (Exception $e) {
    json_response(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
}