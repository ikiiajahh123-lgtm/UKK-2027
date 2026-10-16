
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catat_pelanggaran.php');
    exit;
}

/* Validasi input */
$siswa_id = filter_input(INPUT_POST, 'siswa_id', FILTER_VALIDATE_INT);
$tahun_id = filter_input(INPUT_POST, 'tahun_ajaran_id', FILTER_VALIDATE_INT);
$kelas_id = filter_input(INPUT_POST, 'kelas_id', FILTER_VALIDATE_INT);
$pelanggaran_id = filter_input(INPUT_POST, 'pelanggaran_id', FILTER_VALIDATE_INT);
$kategori_id = filter_input(INPUT_POST, 'pelanggaran_kategori_id', FILTER_VALIDATE_INT);
$guru_id = filter_input(INPUT_POST, 'guru_id', FILTER_VALIDATE_INT);
$poin = filter_input(INPUT_POST, 'poin', FILTER_VALIDATE_INT);

$tanggal = trim($_POST['tanggal'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');
$tindakan = trim($_POST['tindakan'] ?? '');
$status = trim($_POST['status'] ?? '');

$status_diizinkan = ['Ditindaklanjuti', 'Proses', 'Selesai'];

if (
    !$siswa_id ||
    !$tahun_id ||
    !$kelas_id ||
    !$pelanggaran_id ||
    !$kategori_id ||
    !$guru_id ||
    $poin === false ||
    $poin === null ||
    $poin < 0 ||
    $keterangan === '' ||
    $tindakan === '' ||
    !in_array($status, $status_diizinkan, true)
) {
    $_SESSION['pesan_error'] = 'Data pelanggaran tidak valid.';
    header('Location: catat_pelanggaran.php');
    exit;
}

/* Validasi tanggal */
$format_tanggal = DateTime::createFromFormat('!Y-m-d', $tanggal);

if (
    !$format_tanggal ||
    $format_tanggal->format('Y-m-d') !== $tanggal
) {
    $_SESSION['pesan_error'] = 'Tanggal pelanggaran tidak valid.';
    header('Location: catat_pelanggaran.php');
    exit;
}

/* Ambil data siswa */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama FROM t_siswa
     WHERE id = ? AND status_aktif = 1"
);

if (!$stmt) {
    die('Query siswa gagal: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 'i', $siswa_id);
mysqli_stmt_execute($stmt);
$data_siswa = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* Ambil data tahun ajaran */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama FROM t_tahun_ajaran
     WHERE id = ? AND status_aktif = 1"
);

if (!$stmt) {
    die('Query tahun ajaran gagal: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 'i', $tahun_id);
mysqli_stmt_execute($stmt);
$data_tahun = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* Ambil data kelas */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama FROM t_kelas
     WHERE id = ? AND status_aktif = 1"
);

if (!$stmt) {
    die('Query kelas gagal: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 'i', $kelas_id);
mysqli_stmt_execute($stmt);
$data_kelas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* Ambil data jenis pelanggaran */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama, poin, pelanggaran_kategori_id
     FROM t_pelanggaran
     WHERE id = ? AND status_aktif = 1"
);

if (!$stmt) {
    die('Query jenis pelanggaran gagal: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 'i', $pelanggaran_id);
mysqli_stmt_execute($stmt);
$data_pelanggaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* Ambil data guru */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama FROM t_guru
     WHERE id = ? AND status_aktif = 1"
);

if (!$stmt) {
    die('Query guru gagal: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 'i', $guru_id);
mysqli_stmt_execute($stmt);
$data_guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

/* Pastikan semua data ditemukan */
if (
    !$data_siswa ||
    !$data_tahun ||
    !$data_kelas ||
    !$data_pelanggaran ||
    !$data_guru ||
    (int) $data_pelanggaran['pelanggaran_kategori_id'] !== $kategori_id ||
    (int) $data_pelanggaran['poin'] !== $poin
) {
    $_SESSION['pesan_error'] =
        'Data pilihan tidak ditemukan atau sudah berubah. Silakan pilih ulang.';

    header('Location: catat_pelanggaran.php');
    exit;
}

/* Siapkan data untuk disimpan */
$nama_siswa = $data_siswa['nama'];
$nama_kelas = $data_kelas['nama'];
$nama_pelanggaran = $data_pelanggaran['nama'];
$nama_guru = $data_guru['nama'];

/* Simpan pelanggaran */
$sql = "INSERT INTO t_pelanggaran_siswa (
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
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die('Query penyimpanan gagal: ' . mysqli_error($koneksi));
}

/*
15 parameter:
i = integer
s = string

Urutan tipe:
tahun_id, siswa_id, nama_siswa, kelas_id, nama_kelas,
pelanggaran_id, nama_pelanggaran, kategori_id, guru_id,
nama_guru, tanggal, keterangan, poin, tindakan, status
*/
mysqli_stmt_bind_param(
    $stmt,
    'iisisisiisssiss',
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

/* Jalankan query */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    $_SESSION['pesan_sukses'] = 'Pelanggaran berhasil dicatat.';

    header('Location: catat_pelanggaran.php?sukses=1');
    exit;
}

$error_sql = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

die('Gagal menyimpan pelanggaran: ' . $error_sql);
?>
