<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';
header('Content-Type: application/json');
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }
    $input = json_decode(file_get_contents('php://input'), true);
    $nama = $input['nama'] ?? '';
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    $confirm_password = $input['confirm_password'] ?? '';
    if (!$nama || !$email || !$password) {
        throw new Exception('Semua field harus diisi');
    }
    if ($password !== $confirm_password) {
        throw new Exception('Password dan konfirmasi password tidak sama');
    }
    if (strlen($password) < 6) {
        throw new Exception('Password minimal 6 karakter');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Format email tidak valid');
    }
    $stmt = $pdo->prepare("SELECT id FROM pengguna WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        throw new Exception('Email sudah terdaftar');
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("
        INSERT INTO pengguna (nama, email, password_hash, role, created_at, updated_at)
        VALUES (:nama, :email, :password_hash, 'warga', NOW(), NOW())
    ");
    $stmt->execute([
        ':nama' => $nama,
        ':email' => $email,
        ':password_hash' => $hashed_password
    ]);
    $user_id = $pdo->lastInsertId();
    catat_log('register', 'pengguna', $user_id, "Registrasi akun baru: $nama");
    json_response([
        'success' => true,
        'message' => 'Registrasi berhasil! Silakan login.',
        'data' => ['user_id' => $user_id]
    ]);
} catch (Exception $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}