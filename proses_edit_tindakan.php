<?php
// proses_edit_tindakan.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_guru.php");
    exit;
}

// Ambil data dari form
$id           = (int) ($_POST['id'] ?? 0);
$nip          = trim($_POST['nip'] ?? '');
$nama         = trim($_POST['nama'] ?? '');
$email        = trim($_POST['email'] ?? '');
$status_aktif = $_POST['status_aktif'] ?? '';

// Validasi ID
if ($id <= 0) {
    die("ID guru tidak valid.");
}

// Validasi data wajib
if ($nip === '' || $nama === '' || $email === '') {
    die("NIP, nama, dan email wajib diisi.");
}

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid.");
}

// Validasi status
if (!in_array((string) $status_aktif, ['0', '1'], true)) {
    die("Status aktif tidak valid.");
}

$status_aktif = (int) $status_aktif;

// Pastikan data guru tersedia
$cek = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_guru WHERE id = ?"
);

if (!$cek) {
    die("Gagal memeriksa data: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

$ada = mysqli_stmt_num_rows($cek) > 0;
mysqli_stmt_close($cek);

if (!$ada) {
    die("Data guru tidak ditemukan.");
}

// Update data guru
$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE t_guru
     SET nip = ?, nama = ?, email = ?, status_aktif = ?
     WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "sssii",
    $nip,
    $nama,
    $email,
    $status_aktif,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: kelola_guru.php?pesan=berhasil_edit");
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

die("Gagal mengubah data guru: " . htmlspecialchars($error));
?>