<?php
// hapus_walikelas.php

include "includes/cek_session.php";
include "config/koneksi.php";

/* Ambil ID dari URL */
$id = $_GET['id'] ?? '';

/* Cek ID */
if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kelola_walikelas.php");
    exit;
}

$id = (int)$id;

/* Cek apakah data wali kelas ada */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_wali_kelas WHERE id = ?"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/* Jika tidak ada */
if (!$data) {
    echo "<script>
            alert('Data wali kelas tidak ditemukan.');
            window.location='kelola_walikelas.php';
          </script>";
    exit;
}

/* Hapus data */
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_wali_kelas WHERE id = ?"
);

if (!$stmt) {
    die("Query hapus gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    echo "<script>
            alert('Data wali kelas berhasil dihapus.');
            window.location='kelola_walikelas.php';
          </script>";

    exit;

} else {

    $error = mysqli_stmt_error($stmt);

    mysqli_stmt_close($stmt);

    die("Data wali kelas gagal dihapus: " . htmlspecialchars($error));
}
?>