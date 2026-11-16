
<?php
// proses_simpan_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catat_pelanggaran.php');
    exit;
}

// Fungsi kembali dengan pesan error
function gagal($pesan) {
    $_SESSION['pesan_error'] = $pesan;
    header('Location: catat_pelanggaran.php');
    exit;
}

// Ambil data dari form
$siswa_id = filter_input(INPUT_POST, 'siswa_id', FILTER_VALIDATE_INT);
$tahun_id = filter_input(INPUT_POST, 'tahun_ajaran_id', FILTER_VALIDATE_INT);
$kelas_id = filter_input(INPUT_POST, 'kelas_id', FILTER_VALIDATE_INT);
$pelanggaran_id = filter_input(INPUT_POST, 'pelanggaran_id', FILTER_VALIDATE_INT);
$guru_id = filter_input(INPUT_POST, 'guru_id', FILTER_VALIDATE_INT);

$tanggal = $_POST['tanggal'] ?? '';
$keterangan = trim($_POST['keterangan'] ?? '');
$tindakan = trim($_POST['tindakan'] ?? '');
$status = $_POST['status'] ?? '';

$status_diizinkan = [
    'Tercatat',
    'Ditindaklanjuti',
    'Proses',
    'Selesai'
];

// Validasi data wajib
if (
    !$siswa_id ||
    !$tahun_id ||
    !$kelas_id ||
    !$pelanggaran_id ||
    !$guru_id ||
    !in_array($status, $status_diizinkan, true)
) {
    gagal('Mohon lengkapi data pelanggaran dengan benar.');
}

// Validasi tanggal
$format_tanggal = DateTime::createFromFormat('!Y-m-d', $tanggal);

if (
    !$format_tanggal ||
    $format_tanggal->format('Y-m-d') !== $tanggal
) {
    gagal('Tanggal pelanggaran tidak valid.');
}

// Ambil data siswa aktif
$stmt = mysqli_prepare($koneksi, "
    SELECT nama FROM t_siswa
    WHERE id = ? AND status_aktif = 1
");

mysqli_stmt_bind_param($stmt, "i", $siswa_id);
mysqli_stmt_execute($stmt);
$data_siswa = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data_siswa) {
    gagal('Data siswa tidak ditemukan atau tidak aktif.');
}

// Ambil tahun ajaran aktif
$stmt = mysqli_prepare($koneksi, "
    SELECT nama FROM t_tahun_ajaran
    WHERE id = ? AND status_aktif = 1
");

mysqli_stmt_bind_param($stmt, "i", $tahun_id);
mysqli_stmt_execute($stmt);
$data_tahun = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data_tahun) {
    gagal('Tahun ajaran tidak ditemukan atau tidak aktif.');
}

// Ambil kelas aktif
$stmt = mysqli_prepare($koneksi, "
    SELECT nama FROM t_kelas
    WHERE id = ? AND status_aktif = 1
");

mysqli_stmt_bind_param($stmt, "i", $kelas_id);
mysqli_stmt_execute($stmt);
$data_kelas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data_kelas) {
    gagal('Kelas tidak ditemukan atau tidak aktif.');
}

// AMBIL POIN LANGSUNG DARI DATABASE
// Tidak mengambil poin dari POST/form.
$stmt = mysqli_prepare($koneksi, "
    SELECT id, nama, poin, pelanggaran_kategori_id
    FROM t_pelanggaran
    WHERE id = ? AND status_aktif = 1
");

mysqli_stmt_bind_param($stmt, "i", $pelanggaran_id);
mysqli_stmt_execute($stmt);
$data_pelanggaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data_pelanggaran) {
    gagal('Jenis pelanggaran tidak ditemukan atau tidak aktif.');
}

// Ambil guru aktif
$stmt = mysqli_prepare($koneksi, "
    SELECT nama FROM t_guru
    WHERE id = ? AND status_aktif = 1
");

mysqli_stmt_bind_param($stmt, "i", $guru_id);
mysqli_stmt_execute($stmt);
$data_guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$data_guru) {
    gagal('Data guru tidak ditemukan atau tidak aktif.');
}

// POIN DAN KATEGORI DIAMBIL DARI DATABASE
$poin = (int)$data_pelanggaran['poin'];
$kategori_id = (int)$data_pelanggaran['pelanggaran_kategori_id'];

if ($poin <= 0) {
    gagal('Poin jenis pelanggaran tidak valid. Periksa data jenis pelanggaran.');
}

// Simpan data nama
$nama_siswa = $data_siswa['nama'];
$nama_kelas = $data_kelas['nama'];
$nama_pelanggaran = $data_pelanggaran['nama'];
$nama_guru = $data_guru['nama'];

// Simpan ke tabel t_pelanggaran_siswa
$sql = "
    INSERT INTO t_pelanggaran_siswa (
        tahun_ajaran_id,
        siswa_id,
        nama_siswa,
        kelas_id,
        nama_kelas,
        pelanggaran_id,
        nama_pelanggaran,
        pelanggaran_kategori_id,
        guru_id,
        nama_guru,
        tanggal,
        keterangan,
        poin,
        tindakan,
        status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die('Gagal menyiapkan penyimpanan: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iisisisiisssiss",
    $tahun_id,
    $siswa_id,
    $nama_siswa,
    $kelas_id,
    $nama_kelas,
    $pelanggaran_id,
    $nama_pelanggaran,
    $kategori_id,
    $guru_id,
    $nama_guru,
    $tanggal,
    $keterangan,
    $poin,
    $tindakan,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    $_SESSION['pesan_sukses'] = 'Data pelanggaran berhasil disimpan dengan poin sesuai jenis pelanggaran.';

    header('Location: catat_pelanggaran.php');
    exit;
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

die('Gagal menyimpan data: ' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8'));
?>
