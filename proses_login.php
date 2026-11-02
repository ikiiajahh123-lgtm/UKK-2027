<?php
session_start();
include "config/koneksi.php";

$email = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM t_users WHERE email='$email'");
$cek = mysqli_num_rows($query);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($query);

    // Mendukung password hash atau teks biasa
    if (password_verify($password, $data['password']) || $password === $data['password']) {
        
        $_SESSION['login'] = true;
        $_SESSION['name'] = $data['name'];
        $_SESSION['email'] = $data['email'];
        
        // Membersihkan spasi dan mengubah role menjadi huruf kecil semua
        $role = strtolower(trim($data['role']));
        $_SESSION['role'] = $role;

        // PENGONDISIAN REDIRECTION BERDASARKAN ROLE
        if ($role === 'admin') {
            header("Location: dashboard.php");
            exit;
        } elseif ($role === 'guru') {
            header("Location: dashboard_guru.php");
            exit;
        } else {
            header("Location: login.php?pesan=role_salah");
            exit;
        }

    } else {
        header("Location: login.php?pesan=gagal");
        exit;
    }
} else {
    header("Location: login.php?pesan=gagal");
    exit;
}
?>