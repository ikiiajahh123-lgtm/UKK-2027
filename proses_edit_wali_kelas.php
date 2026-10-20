
<?php
// proses_edit_wali_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_walikelas.php");
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
$kelas_id = (int) ($_POST['kelas_id'] ?? 0);
$guru_id = (int) ($_POST['guru_id'] ?? 0);
$tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
$tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
$status_aktif = (int) ($_POST['status_aktif'] ?? -1);

if (
    $id <= 0 ||
    $tahun_ajaran_id <= 0 ||
    $kelas_id <= 0 ||
    $guru_id <= 0 ||
    !in_array($status_aktif, [0, 1], true) ||
    $tanggal_mulai === '' ||
    $tanggal_selesai === ''
) {
    exit('Data tidak lengkap atau tidak valid.');
}

if ($tanggal_selesai < $tanggal_mulai) {
    exit('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
}

$sql = "UPDATE t_wali_kelas SET
        tahun_ajaran_id = ?,
        kelas_id = ?,
        guru_id = ?,
        tanggal_mulai = ?,
        tanggal_selesai = ?,
        status_aktif = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    exit('Gagal menyiapkan query: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iiissii",
    $tahun_ajaran_id,
    $kelas_id,
    $guru_id,
    $tanggal_mulai,
    $tanggal_selesai,
    $status_aktif,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: kelola_wali_kelas.php");
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

echo "Gagal mengubah data wali kelas: "
    . htmlspecialchars($error);
?>
