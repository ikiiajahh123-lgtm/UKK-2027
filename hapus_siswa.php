<?php
// hapus_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID siswa dari URL
$id = $_GET['id'] ?? '';

// Validasi ID
if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kelola_siswa.php");
    exit;
}

// Cek apakah data siswa ada
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama FROM t_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Jika siswa tidak ditemukan
if (!$siswa) {
    header("Location: kelola_siswa.php");
    exit;
}

// Hapus data siswa
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_siswa WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header("Location: kelola_siswa.php?pesan=hapus_berhasil");
    exit;

} else {

    $error = mysqli_error($koneksi);

    mysqli_stmt_close($stmt);

    die(
        "Gagal menghapus data siswa.<br><br>" .
        "Error database: " . htmlspecialchars($error)
    );
}
?>