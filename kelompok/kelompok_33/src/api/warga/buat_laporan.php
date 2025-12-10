<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';
cek_login();
cek_role(['warga']);
header('Content-Type: application/json');
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;
    if (!$judul || !$deskripsi || !$kategori || !$alamat) {
        throw new Exception('Data tidak lengkap');
    }
    $allowed_kategori = ['organik', 'non-organik', 'lainnya'];
    if (!in_array($kategori, $allowed_kategori)) {
        throw new Exception('Kategori tidak valid');
    }
    $stmt = $pdo->prepare("
        INSERT INTO laporan (pengguna_id, judul, deskripsi, kategori, alamat, lat, lng, status)
        VALUES (:pengguna_id, :judul, :deskripsi, :kategori, :alamat, :lat, :lng, 'baru')
    ");
    $stmt->execute([
        ':pengguna_id' => $_SESSION['pengguna_id'],
        ':judul' => $judul,
        ':deskripsi' => $deskripsi,
        ':kategori' => $kategori,
        ':alamat' => $alamat,
        ':lat' => $lat,
        ':lng' => $lng
    ]);
    $laporan_id = $pdo->lastInsertId();
    $foto_count = 0;
    if (!empty($_FILES['foto'])) {
        $files = $_FILES['foto'];
        if (is_array($files['name'])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                $result = upload_file($file, 'laporan');
                if ($result['success']) {
                    $stmt = $pdo->prepare("
                        INSERT INTO foto_laporan (laporan_id, nama_file, path_file, ukuran, tipe_file)
                        VALUES (:laporan_id, :nama_file, :path_file, :ukuran, :tipe_file)
                    ");
                    $stmt->execute([
                        ':laporan_id' => $laporan_id,
                        ':nama_file' => $result['nama_file'],
                        ':path_file' => $result['path_file'],
                        ':ukuran' => $result['ukuran'],
                        ':tipe_file' => $result['tipe_file']
                    ]);
                    $foto_count++;
                }
            }
        } else {
            $result = upload_file($files, 'laporan');
            if ($result['success']) {
                $stmt = $pdo->prepare("
                    INSERT INTO foto_laporan (laporan_id, nama_file, path_file, ukuran, tipe_file)
                    VALUES (:laporan_id, :nama_file, :path_file, :ukuran, :tipe_file)
                ");
                $stmt->execute([
                    ':laporan_id' => $laporan_id,
                    ':nama_file' => $result['nama_file'],
                    ':path_file' => $result['path_file'],
                    ':ukuran' => $result['ukuran'],
                    ':tipe_file' => $result['tipe_file']
                ]);
                $foto_count++;
            }
        }
    }
    catat_log('create', 'laporan', $laporan_id, "Membuat laporan: $judul");
    json_response([
        'success' => true,
        'message' => 'Laporan berhasil dibuat',
        'data' => [
            'laporan_id' => $laporan_id,
            'foto_count' => $foto_count
        ]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}