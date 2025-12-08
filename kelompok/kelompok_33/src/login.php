<?php
// login.php
require __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || empty($input['email']) || empty($input['password'])) send_json(['error' => 'email and password required'], 400);

$email = trim($input['email']);
$password = $input['password'];

$pdo = db();
$stmt = $pdo->prepare("SELECT id,nama,email,password_hash,role,telepon,alamat,created_at FROM pengguna WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();
if (!$user) send_json(['error' => 'Invalid credentials'], 401);

if (!password_verify($password, $user['password_hash'])) {
    send_json(['error' => 'Invalid credentials'], 401);
}

$payload = [
    'sub' => (int)$user['id'],
    'email' => $user['email'],
    'role' => $user['role']
];
$token = jwt_create($payload, 8 * 3600);

unset($user['password_hash']);
send_json(['token' => $token, 'user' => $user]);
