<?php
// seed_admin.php
// Run from CLI: php seed_admin.php
require __DIR__ . '/helpers.php';

if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.\n";
    exit(1);
}

echo "Create admin user\n";
$stdin = fopen('php://stdin', 'r');
echo "Nama: "; $nama = trim(fgets($stdin));
echo "Email: "; $email = trim(fgets($stdin));
echo "Password: ";
// hide input on unix-like systems; on Windows this will not hide but still works
if (strncasecmp(PHP_OS, 'WIN', 3) !== 0) {
    system('stty -echo'); $password = trim(fgets($stdin)); system('stty echo'); echo "\n";
} else {
    $password = trim(fgets($stdin));
}

if (empty($nama) || empty($email) || empty($password)) {
    echo "All fields are required\n"; exit(1);
}

$pdo = db();
$stmt = $pdo->prepare("SELECT id FROM pengguna WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetch()) { echo "Email already registered\n"; exit(1); }

$hash = password_hash($password, PASSWORD_DEFAULT);
$ins = $pdo->prepare("INSERT INTO pengguna (nama,email,password_hash,role,created_at,updated_at) VALUES (:nama,:email,:hash,'admin', NOW(), NOW())");
$ins->execute(['nama'=>$nama,'email'=>$email,'hash'=>$hash]);
echo "Admin created with id: " . $pdo->lastInsertId() . "\n";
