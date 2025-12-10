<?php
function catat_log($aksi, $target_tipe = null, $target_id = null, $detail = null) {
    global $pdo;
    $pengguna_id = $_SESSION['pengguna_id'] ?? null;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO log_aktivitas (pengguna_id, aksi, target_tipe, target_id, detail)
            VALUES (:pengguna_id, :aksi, :target_tipe, :target_id, :detail)
        ");
        $stmt->execute([
            ':pengguna_id' => $pengguna_id,
            ':aksi' => $aksi,
            ':target_tipe' => $target_tipe,
            ':target_id' => $target_id,
            ':detail' => $detail
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Error logging activity: " . $e->getMessage());
        return false;
    }
}
function cek_login() {
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            json_response(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        header('Location: /cleanspot/src/login_page.html');
        exit;
    }
}
function cek_role($role_required) {
    cek_login();
    if (is_array($role_required)) {
        if (!in_array($_SESSION['role'], $role_required)) {
            http_response_code(403);
            die('Akses ditolak');
        }
    } else {
        if ($_SESSION['role'] !== $role_required) {
            http_response_code(403);
            die('Akses ditolak');
        }
    }
}
function redirect_dengan_pesan($url, $pesan, $tipe = 'success') {
    $_SESSION['flash_message'] = $pesan;
    $_SESSION['flash_type'] = $tipe;
    header("Location: $url");
    exit;
}
function tampilkan_pesan_flash() {
    if (isset($_SESSION['flash_message'])) {
        $pesan = htmlspecialchars($_SESSION['flash_message']);
        $tipe = $_SESSION['flash_type'] ?? 'success';
        $bg_color = $tipe === 'success' ? 'bg-green-500' : 'bg-red-500';
        echo "<div class='fixed top-4 right-4 $bg_color text-white px-6 py-3 rounded-lg shadow-lg z-50' id='flash-message'>
                $pesan
              </div>";
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        echo "<script>
                setTimeout(() => {
                    const msg = document.getElementById('flash-message');
                    if (msg) msg.remove();
                }, 3000);
              </script>";
    }
}
function upload_file($file, $upload_dir, $allowed_types = ['image/jpeg', 'image/png', 'image/jpg']) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error upload file'];
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipe file tidak diizinkan'];
    }
    $base_dir = __DIR__ . '/uploads/' . $upload_dir;
    if (!file_exists($base_dir)) {
        mkdir($base_dir, 0777, true);
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nama_file = uniqid() . '_' . time() . '.' . $ext;
    $path_lengkap = $base_dir . '/' . $nama_file;
    if (!move_uploaded_file($file['tmp_name'], $path_lengkap)) {
        return ['success' => false, 'message' => 'Gagal memindahkan file'];
    }
    $path_relatif = 'uploads/' . $upload_dir . '/' . $nama_file;
    return [
        'success' => true,
        'nama_file' => $nama_file,
        'path_file' => $path_relatif,
        'ukuran' => $file['size'],
        'tipe_file' => $mime
    ];
}
function format_tanggal($datetime) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($datetime);
    $tgl = date('j', $timestamp);
    $bln = $bulan[(int)date('n', $timestamp)];
    $thn = date('Y', $timestamp);
    $jam = date('H:i', $timestamp);
    return "$tgl $bln $thn, $jam";
}
function sanitasi($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
function json_response($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
function get_user_info() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'nama' => $_SESSION['nama'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? null
    ];
}