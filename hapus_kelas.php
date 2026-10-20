<?php
// hapus_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID kelas dari URL
$id = $_GET['id'] ?? '';

// Validasi ID
if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kelola_kelas.php");
    exit;
}

// Cek apakah kelas ada
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama FROM t_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$kelas = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Jika kelas tidak ditemukan
if (!$kelas) {
    header("Location: kelola_kelas.php");
    exit;
}

// Hapus kelas
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: kelola_kelas.php?pesan=hapus_berhasil");
    exit;

} else {

    $error = mysqli_error($koneksi);

    mysqli_stmt_close($stmt);

    die(
        "Gagal menghapus data kelas.<br><br>" .
        "Error database: " .
        htmlspecialchars($error)
    );
}
?>