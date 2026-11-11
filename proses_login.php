<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: login.php?pesan=gagal');
    exit;
}

$sql = "SELECT id, email, password, role FROM t_users WHERE email = ? LIMIT 1";
$stmt = $koneksi->prepare($sql);
if (!$stmt) {
    die('Gagal menyiapkan query: ' . htmlspecialchars($koneksi->error));
}
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: login.php?pesan=gagal');
    exit;
}

if (!in_array($user['role'], ['admin', 'guru'], true)) {
    header('Location: login.php?pesan=gagal');
    exit;
}

session_regenerate_id(true);
$_SESSION['login'] = true;
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'admin') {
    header('Location: dashboard.php');
    exit;
}

header('Location: dashboard_guru.php');
exit;
