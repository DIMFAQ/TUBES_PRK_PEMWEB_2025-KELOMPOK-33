<?php
// me.php
require __DIR__ . '/helpers.php';

$token = get_bearer_token();
if (!$token) send_json(['error' => 'Missing token'], 401);
$payload = jwt_verify($token);
if (!$payload) send_json(['error' => 'Invalid or expired token'], 401);

$pdo = db();
$stmt = $pdo->prepare("SELECT id,nama,email,role,telepon,alamat,created_at,updated_at FROM pengguna WHERE id = :id");
$stmt->execute(['id' => $payload['sub']]);
$user = $stmt->fetch();
if (!$user) send_json(['error' => 'User not found'], 404);

send_json(['user' => $user]);
