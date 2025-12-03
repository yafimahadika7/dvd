<?php
session_start();

$host     = 'localhost';
$dbname   = 'rental_dvd';
$username = 'root';
$password = '';

// Hitung base_url otomatis berdasarkan posisi folder project
$projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$docRoot     = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
$base_url    = rtrim(str_replace($docRoot, '', $projectRoot), '/');
if ($base_url === false) {
    $base_url = '';
}

function base_url(string $path = ''): string {
    global $base_url;
    return $base_url . $path;
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

function cek_login(): void {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . base_url('/auth/login.php'));
        exit;
    }
}

function cek_role(array $roles = []): void {
    if (!in_array($_SESSION['user_role'] ?? '', $roles, true)) {
        header("Location: " . base_url('/index.php?error=akses'));
        exit;
    }
}
