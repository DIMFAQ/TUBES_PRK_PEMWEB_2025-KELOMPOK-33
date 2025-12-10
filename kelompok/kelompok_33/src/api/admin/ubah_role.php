<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../fungsi_helper.php';

header('Content-Type: application/json');
cek_login();
cek_role(['admin']);

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['user_id']) || !isset($input['role'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap'
        ]);
        exit;
    }
    
    $user_id = (int)$input['user_id'];
    $new_role = $input['role'];
    
    // Validasi role
    if (!in_array($new_role, ['admin', 'petugas', 'warga'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Role tidak valid'
        ]);
        exit;
    }
    
    // Cek user yang akan diubah
    $stmt = $pdo->prepare("SELECT id, nama, role FROM pengguna WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ]);
        exit;
    }
    
    // Tidak bisa mengubah role admin
    if ($user['role'] === 'admin') {
        echo json_encode([
            'success' => false,
            'message' => 'Tidak dapat mengubah role admin'
        ]);
        exit;
    }
    
    // Tidak bisa mengubah role diri sendiri
    if ($user_id === $_SESSION['user_id']) {
        echo json_encode([
            'success' => false,
            'message' => 'Tidak dapat mengubah role sendiri'
        ]);
        exit;
    }
    
    // Update role
    $stmt = $pdo->prepare("UPDATE pengguna SET role = ? WHERE id = ?");
    $success = $stmt->execute([$new_role, $user_id]);
    
    if ($success && $stmt->rowCount() > 0) {
        // Log aktivitas
        try {
            catat_log(
                $_SESSION['user_id'],
                'ubah_role',
                "Mengubah role user {$user['nama']} (ID: {$user_id}) dari {$user['role']} menjadi {$new_role}"
            );
        } catch (Exception $logError) {
            // Log gagal, tapi update berhasil
            error_log("Gagal catat log: " . $logError->getMessage());
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Role berhasil diubah'
        ]);
    } else {
        $errorInfo = $stmt->errorInfo();
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengubah role',
            'debug' => $errorInfo
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
