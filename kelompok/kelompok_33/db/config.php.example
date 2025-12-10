<?php
// config.php.example - TEMPLATE
// Copy file ini menjadi config.php dan sesuaikan dengan environment lokal Anda

session_start();

// Database configuration
$config = [
    'db' => [
        'host' => 'localhost',
        'dbname' => 'cleanspot_db',
        'user' => 'root',
        'pass' => '', // ISI PASSWORD MYSQL ANDA DI SINI
        'charset' => 'utf8mb4',
    ],
    // Replace with a long random string in production
    'jwt_secret' => 'ganti_dengan_secret_panjang_dan_random_2025!',
    // Optional: code required to register admin via API. Keep blank to disable.
    'admin_code' => '123'
];

// Create PDO connection
try {
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset={$config['db']['charset']}", 
        $config['db']['user'], 
        $config['db']['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

function is_admin(): bool {
    return isset($_SESSION['user_id']) && (($_SESSION['role'] ?? '') === 'admin');
}
