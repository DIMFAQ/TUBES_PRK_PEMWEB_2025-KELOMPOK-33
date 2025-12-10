<?php
// config.php
// Copy this file and update values for production / local environment
return [
    'db' => [
        'host' => '127.0.0.1',
        'dbname' => 'ppw',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    // Replace with a long random string in production
    'jwt_secret' => 'ganti_dengan_secret_panjang_dan_random_2025!',
    // Optional: code required to register admin via API. Keep blank to disable.
    'admin_code' => '123'
];
