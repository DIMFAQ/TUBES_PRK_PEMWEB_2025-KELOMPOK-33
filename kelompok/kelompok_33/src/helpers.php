<?php
// helpers.php
// DB connection, JSON responses, simple JWT (HS256) helpers

$config = require __DIR__ . '/config.php';

function db() {
    static $pdo = null;
    global $config;
    if ($pdo) return $pdo;
    $conf = $config['db'];
    $dsn = "mysql:host={$conf['host']};dbname={$conf['dbname']};charset={$conf['charset']}";
    $pdo = new PDO($dsn, $conf['user'], $conf['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}

function send_json($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_create($payload, $expSeconds = 3600) {
    global $config;
    $secret = $config['jwt_secret'];
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload['iat'] = time();
    $payload['exp'] = time() + $expSeconds;
    $segments = [
        base64url_encode(json_encode($header)),
        base64url_encode(json_encode($payload))
    ];
    $signing_input = implode('.', $segments);
    $signature = hash_hmac('sha256', $signing_input, $secret, true);
    $segments[] = base64url_encode($signature);
    return implode('.', $segments);
}

function jwt_verify($token) {
    global $config;
    $secret = $config['jwt_secret'];
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;
    list($h64, $p64, $s64) = $parts;
    $signing_input = "$h64.$p64";
    $signature = base64url_decode($s64);
    $expected = hash_hmac('sha256', $signing_input, $secret, true);
    if (!hash_equals($expected, $signature)) return false;
    $payload = json_decode(base64url_decode($p64), true);
    if (!$payload) return false;
    if (isset($payload['exp']) && time() > $payload['exp']) return false;
    return $payload;
}

function get_bearer_token() {
    $hdr = null;
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) $hdr = $_SERVER['HTTP_AUTHORIZATION'];
    if (!$hdr && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) $hdr = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    if (!$hdr && function_exists('apache_request_headers')) {
        $r = apache_request_headers();
        if (isset($r['Authorization'])) $hdr = $r['Authorization'];
    }
    if (!$hdr) return null;
    if (preg_match('/Bearer\s(\S+)/', $hdr, $m)) return $m[1];
    return null;
}
