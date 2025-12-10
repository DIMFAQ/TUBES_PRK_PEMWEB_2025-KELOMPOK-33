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
    if (!$input) {
        $input = $_POST;
    }
    $penugasan_id = $input['penugasan_id'] ?? null;
    $catatan = $input['catatan'] ?? '';
    if (!$penugasan_id) {
        throw new Exception('ID penugasan tidak ditemukan');
    }
    $stmt = $pdo->prepare("
        SELECT * FROM penugasan 
        WHERE id = :id 
        AND petugas_id = :petugas_id 
        AND status_penugasan = 'dikerjakan'
    ");
    $stmt->execute([
        ':id' => $penugasan_id,
        ':petugas_id' => $_SESSION['pengguna_id']
    ]);
    $penugasan = $stmt->fetch();
    if (!$penugasan) {
        throw new Exception('Penugasan tidak ditemukan atau belum dikerjakan');
    }
    $foto_urls = [];
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
                $result = upload_file($file, 'bukti');
                if ($result['success']) {
                    $foto_urls[] = $result['url'];
                }
            }
        } else {
            $result = upload_file($files, 'bukti');
            if ($result['success']) {
                $foto_urls[] = $result['url'];
            }
        }
    }
    $stmt = $pdo->prepare("
        UPDATE penugasan 
        SET status_penugasan = 'selesai', selesai_pada = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([':id' => $penugasan_id]);
    $pdo->prepare("UPDATE laporan SET status = 'selesai' WHERE id = :id")
         ->execute([':id' => $penugasan['laporan_id']]);
    if ($foto_urls || $catatan) {
        $stmt = $pdo->prepare("
            INSERT INTO bukti_penanganan (penugasan_id, catatan, foto_url)
            VALUES (:penugasan_id, :catatan, :foto_url)
        ");
        if ($foto_urls) {
            foreach ($foto_urls as $url) {
                $stmt->execute([
                    ':penugasan_id' => $penugasan_id,
                    ':catatan' => $catatan,
                    ':foto_url' => $url
                ]);
            }
        } else {
            $stmt->execute([
                ':penugasan_id' => $penugasan_id,
                ':catatan' => $catatan,
                ':foto_url' => null
            ]);
        }
    }
    catat_log('complete_task', 'penugasan', $penugasan_id, 
              "Menyelesaikan laporan #{$penugasan['laporan_id']}");
    json_response([
        'success' => true,
        'message' => 'Tugas berhasil diselesaikan',
        'data' => ['foto_count' => count($foto_urls)]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}