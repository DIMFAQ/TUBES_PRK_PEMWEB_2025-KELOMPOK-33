<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../fungsi_helper.php';

// Catat log logout
if (isset($_SESSION['pengguna_id'])) {
    catat_log('logout', 'pengguna', $_SESSION['pengguna_id'], 'Logout dari sistem');
}

// Hapus session
session_unset();
session_destroy();

// Redirect ke index.php
header('Location: ../index.php');
exit;
