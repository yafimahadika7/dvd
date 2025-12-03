<?php
require_once '../config/config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $password === $user['password']) {
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['nama_lengkap'];
    $_SESSION['user_role'] = $user['role'];
    header("Location: " . base_url('/index.php'));
    exit;
}

header("Location: " . base_url('/auth/login.php?error=Username atau password salah'));
exit;
