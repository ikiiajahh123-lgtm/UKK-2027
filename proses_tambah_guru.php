<?php
// proses_tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data dari form
$user_id = $_POST['user_id'] ?? '';
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$status_aktif = $_POST['status_aktif'] ?? '';

// Validasi
if (
    $user_id === '' ||
    $nip === '' ||
    $nama === '' ||
    $status_aktif === ''
) {
    die("Data belum lengkap.");
}

// Pastikan user_id benar-benar ada di tabel users
$cek_user = mysqli_prepare(
    $koneksi,
    "SELECT id FROM users WHERE id = ?"
);

mysqli_stmt_bind_param(
    $cek_user,
    "i",
    $user_id
);

mysqli_stmt_execute($cek_user);

$hasil_user = mysqli_stmt_get_result($cek_user);

if (mysqli_num_rows($hasil_user) == 0) {
    die("User yang dipilih tidak ditemukan di tabel users.");
}

mysqli_stmt_close($cek_user);

// Cek apakah NIP sudah digunakan
$cek_nip = mysqli_prepare(
    $koneksi,
    "SELECT nip FROM t_guru WHERE nip = ?"
);

mysqli_stmt_bind_param(
    $cek_nip,
    "s",
    $nip
);

mysqli_stmt_execute($cek_nip);

$hasil_nip = mysqli_stmt_get_result($cek_nip);

if (mysqli_num_rows($hasil_nip) > 0) {
    die("NIP sudah terdaftar. Silakan gunakan NIP yang lain.");
}

mysqli_stmt_close($cek_nip);

// Simpan data guru
$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO t_guru
    (user_id, nip, nama, email, status_aktif)
    VALUES (?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "isssi",
    $user_id,
    $nip,
    $nama,
    $email,
    $status_aktif
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: kelola_guru.php?pesan=tambah_berhasil");
    exit();

} else {

    echo "Gagal menyimpan data guru.<br>";
    echo "Error: " . mysqli_error($koneksi);

    mysqli_stmt_close($stmt);
}
?>