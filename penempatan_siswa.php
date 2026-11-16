
<?php
// ==========================================
// PENEMPATAN SISWA
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ==========================================
// PROSES EDIT, PINDAH KELAS, DAN HAPUS
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // PROSES EDIT
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'edit') {

        $id = (int)($_POST['id'] ?? 0);
        $siswa_id = (int)($_POST['siswa_id'] ?? 0);
        $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
        $kelas_id = (int)($_POST['kelas_id'] ?? 0);
        $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
        $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
        $status_aktif = (int)($_POST['status_aktif'] ?? 0);

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas_siswa
             SET siswa_id = ?,
                 tahun_ajaran_id = ?,
                 kelas_id = ?,
                 tanggal_mulai = ?,
                 tanggal_selesai = ?,
                 status_aktif = ?
             WHERE id = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "iiissii",
                $siswa_id,
                $tahun_ajaran_id,
                $kelas_id,
                $tanggal_mulai,
                $tanggal_selesai,
                $status_aktif,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['pesan_sukses'] = 'Data penempatan siswa berhasil diperbarui.';
            } else {
                $_SESSION['pesan_error'] = 'Gagal memperbarui data: ' . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['pesan_error'] = 'Query edit gagal: ' . mysqli_error($koneksi);
        }

        header('Location: penempatan_siswa.php');
        exit;
    }

    // PROSES PINDAH KELAS
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'pindah_kelas') {

        $id = (int)($_POST['id'] ?? 0);
        $kelas_id = (int)($_POST['kelas_id'] ?? 0);

        if ($id <= 0 || $kelas_id <= 0) {
            $_SESSION['pesan_error'] = 'Silakan pilih kelas tujuan terlebih dahulu.';
        } else {

            $stmt = mysqli_prepare(
                $koneksi,
                "UPDATE t_kelas_siswa
                 SET kelas_id = ?
                 WHERE id = ?"
            );

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ii", $kelas_id, $id);

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['pesan_sukses'] = 'Siswa berhasil dipindahkan ke kelas baru.';
                } else {
                    $_SESSION['pesan_error'] = 'Gagal memindahkan kelas: ' . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['pesan_error'] = 'Query pindah kelas gagal: ' . mysqli_error($koneksi);
            }
        }

        header('Location: penempatan_siswa.php');
        exit;
    }

    // PROSES HAPUS
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {

        $id = (int)($_POST['id'] ?? 0);

        $stmt = mysqli_prepare(
            $koneksi,
            "DELETE FROM t_kelas_siswa WHERE id = ?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['pesan_sukses'] = 'Data penempatan siswa berhasil dihapus.';
            } else {
                $_SESSION['pesan_error'] = 'Gagal menghapus data: ' . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['pesan_error'] = 'Query hapus gagal: ' . mysqli_error($koneksi);
        }

        header('Location: penempatan_siswa.php');
        exit;
    }
}

// ==========================================
// MODE EDIT ATAU PINDAH KELAS
// ==========================================

$data_edit = null;

$mode_pindah = (
    isset($_GET['pindah']) &&
    $_GET['pindah'] === '1'
);

if (isset($_GET['edit'])) {

    $id_edit = (int)$_GET['edit'];

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT * FROM t_kelas_siswa WHERE id = ?"
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_edit);
        mysqli_stmt_execute($stmt);

        $result_edit = mysqli_stmt_get_result($stmt);
        $data_edit = mysqli_fetch_assoc($result_edit);

        mysqli_stmt_close($stmt);
    }

    if (!$data_edit) {
        $_SESSION['pesan_error'] = 'Data penempatan tidak ditemukan.';
        header('Location: penempatan_siswa.php');
        exit;
    }
}

// ==========================================
// AMBIL DATA SISWA, TAHUN, DAN KELAS
// ==========================================

$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// ==========================================
// AMBIL DATA PENEMPATAN SISWA
// ==========================================

$sql = "SELECT
            ks.id,
            s.nis,
            s.nama AS nama_siswa,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            ks.tanggal_mulai,
            ks.tanggal_selesai,
            ks.status_aktif
        FROM t_kelas_siswa ks
        LEFT JOIN t_siswa s ON ks.siswa_id = s.id
        LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON ks.kelas_id = k.id
        ORDER BY ks.id DESC";

$hasil = mysqli_query($koneksi, $sql);

// Periksa query
if (!$siswa || !$tahun || !$kelas || !$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Fungsi keamanan output
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Tindakan - SIPES</title>

<<<<<<< HEAD
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">
=======
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6fc;
            color: #212529;
            font-family: Arial, sans-serif;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            background: #f4f6fc;
        }

        .judul-halaman {
            font-size: 36px;
            font-weight: 400;
            color: #152238;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            margin-bottom: 0;
            font-size: 14px;
        }

        .table th {
            background: #212529 !important;
            color: white !important;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) > * {
            background-color: #f1f1f1;
        }

        .table-hover tbody tr:hover > * {
            background-color: #e8f0ff;
        }

        .form-tindakan {
            min-width: 230px;
        }

        .form-tindakan textarea {
            min-width: 210px;
            resize: vertical;
        }

        .form-status {
            min-width: 160px;
        }

        .jumlah-data {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
        }

        .dataTables_wrapper {
            width: 100%;
        }

        .dataTables_filter input,
        .dataTables_length select {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            padding: 5px 10px !important;
        }

        .dataTables_paginate .pagination {
            margin-top: 12px;
        }

        .btn-primary {
            background-color: #0d6efd;
        }

        @media(max-width: 768px) {
            .judul-halaman {
                font-size: 28px;
            }

            .card-body {
                padding: 12px;
            }
        }
    </style>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="p-4 flex-grow-1" style="min-width: 0; flex-basis: 0;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Penempatan Siswa</h1>
            </div>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= e($_SESSION['pesan_sukses']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= e($_SESSION['pesan_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <!-- FORM PENEMPATAN SISWA -->
            <div class="card shadow-sm mb-4" id="formPenempatan">
                <div class="card-body">

                    <h4 class="mb-4">
                        <?php
                        if ($mode_pindah && $data_edit) {
                            echo 'Pindah Kelas Siswa';
                        } elseif ($data_edit) {
                            echo 'Edit Penempatan Siswa';
                        } else {
                            echo 'Form Penempatan Siswa';
                        }
                        ?>
                    </h4>

                    <form
                        action="<?= $data_edit ? 'penempatan_siswa.php' : 'proses_simpan_penempatan.php' ?>"
                        method="POST"
                    >

                        <?php if ($data_edit) { ?>
                            <input
                                type="hidden"
                                name="aksi"
                                value="<?= $mode_pindah ? 'pindah_kelas' : 'edit' ?>"
                            >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int)$data_edit['id'] ?>"
                            >
                        <?php } ?>

                        <div class="row g-3">

                            <?php if ($mode_pindah && $data_edit) { ?>

                                <!-- DATA SISWA SAAT PINDAH KELAS -->
                                <div class="col-md-6">
                                    <label class="form-label">Siswa</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?= e($data_edit['siswa_id']) ?>"
                                        readonly
                                    >
                                    <small class="text-muted">
                                        Data siswa tidak akan diubah.
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tahun Ajaran</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?= e($data_edit['tahun_ajaran_id']) ?>"
                                        readonly
                                    >
                                </div>

                                <!-- PILIH KELAS TUJUAN -->
                                <div class="col-md-6">
                                    <label for="kelas_id" class="form-label">
                                        Kelas Tujuan
                                    </label>

                                    <select
                                        name="kelas_id"
                                        id="kelas_id"
                                        class="form-select"
                                        required
                                    >
                                        <option value="">-- Pilih Kelas Tujuan --</option>

                                        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                            <option value="<?= (int)$k['id'] ?>">
                                                <?= e($k['nama'] . ' - Tingkat ' . $k['tingkat'] . ' - ' . $k['jurusan']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                            <?php } else { ?>

                                <!-- SISWA -->
                                <div class="col-md-6">
                                    <label for="siswa_id" class="form-label">Siswa</label>

                                    <select name="siswa_id" id="siswa_id" class="form-select" required>
                                        <option value="">-- Pilih Siswa --</option>

                                        <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                            <option
                                                value="<?= (int)$s['id'] ?>"
                                                <?= ($data_edit && (int)$data_edit['siswa_id'] === (int)$s['id']) ? 'selected' : '' ?>
                                            >
                                                <?= e($s['nis'] . ' - ' . $s['nama']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- TAHUN AJARAN -->
                                <div class="col-md-6">
                                    <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>

                                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select" required>
                                        <option value="">-- Pilih Tahun Ajaran --</option>

                                        <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                            <option
                                                value="<?= (int)$ta['id'] ?>"
                                                <?= ($data_edit && (int)$data_edit['tahun_ajaran_id'] === (int)$ta['id']) ? 'selected' : '' ?>
                                            >
                                                <?= e($ta['nama']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- KELAS -->
                                <div class="col-md-6">
                                    <label for="kelas_id" class="form-label">Kelas</label>

                                    <select name="kelas_id" id="kelas_id" class="form-select" required>
                                        <option value="">-- Pilih Kelas --</option>

                                        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                            <option
                                                value="<?= (int)$k['id'] ?>"
                                                <?= ($data_edit && (int)$data_edit['kelas_id'] === (int)$k['id']) ? 'selected' : '' ?>
                                            >
                                                <?= e($k['nama'] . ' - Tingkat ' . $k['tingkat'] . ' - ' . $k['jurusan']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- STATUS -->
                                <div class="col-md-6">
                                    <label for="status_aktif" class="form-label">Status</label>

                                    <select name="status_aktif" id="status_aktif" class="form-select" required>
                                        <option value="1" <?= ($data_edit && (int)$data_edit['status_aktif'] === 1) ? 'selected' : '' ?>>
                                            Aktif
                                        </option>

                                        <option value="0" <?= ($data_edit && (int)$data_edit['status_aktif'] === 0) ? 'selected' : '' ?>>
                                            Tidak Aktif
                                        </option>
                                    </select>
                                </div>

                                <!-- TANGGAL MULAI -->
                                <div class="col-md-6">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>

                                    <input
                                        type="date"
                                        name="tanggal_mulai"
                                        id="tanggal_mulai"
                                        class="form-control"
                                        value="<?= $data_edit ? e($data_edit['tanggal_mulai']) : '' ?>"
                                        required
                                    >
                                </div>

                                <!-- TANGGAL SELESAI -->
                                <div class="col-md-6">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>

                                    <input
                                        type="date"
                                        name="tanggal_selesai"
                                        id="tanggal_selesai"
                                        class="form-control"
                                        value="<?= $data_edit ? e($data_edit['tanggal_selesai']) : '' ?>"
                                        required
                                    >
                                </div>

                            <?php } ?>

                            <!-- TOMBOL -->
                            <div class="col-12 mt-4">

                                <?php if ($mode_pindah && $data_edit) { ?>

                                    <button type="submit" class="btn btn-primary">
                                        Simpan Pindah Kelas
                                    </button>

                                    <a href="penempatan_siswa.php" class="btn btn-secondary">
                                        Batal
                                    </a>

                                <?php } elseif ($data_edit) { ?>

                                    <button type="submit" class="btn btn-success">
                                        Simpan Perubahan
                                    </button>

                                    <a href="penempatan_siswa.php" class="btn btn-secondary">
                                        Batal
                                    </a>

                                <?php } else { ?>

                                    <button type="submit" class="btn btn-primary">
                                        Simpan Penempatan
                                    </button>

                                <?php } ?>

                            </div>

                        </div>

                    </form>
                </div>
            </div>

            <!-- TABEL DATA PENEMPATAN -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-4">Data Penempatan Siswa</h4>

                    <div class="table-responsive">

                        <table id="tabelPenempatan" class="table table-striped table-bordered table-hover align-middle w-100">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;

                                while ($row = mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= e($row['nis'] ?? '-') ?></td>
                                        <td><?= e($row['nama_siswa'] ?? '-') ?></td>
                                        <td><?= e($row['tahun_ajaran'] ?? '-') ?></td>
                                        <td><?= e($row['nama_kelas'] ?? '-') ?></td>
                                        <td><?= e($row['tingkat'] ?? '-') ?></td>
                                        <td><?= e($row['jurusan'] ?? '-') ?></td>
                                        <td><?= e($row['tanggal_mulai'] ?? '-') ?></td>
                                        <td><?= e($row['tanggal_selesai'] ?? '-') ?></td>

                                        <td>
                                            <?php if ((int)$row['status_aktif'] === 1) { ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            <?php } ?>
                                        </td>

                                        
                                        <!-- TOMBOL EDIT, HAPUS, DAN PINDAH KELAS -->
                                        <td class="text-nowrap">

                                            <!-- EDIT -->
                                            <a
                                                href="penempatan_siswa.php?edit=<?= (int)$row['id'] ?>#formPenempatan"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>

                                            <!-- HAPUS -->
                                            <form
                                                action="penempatan_siswa.php"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus data penempatan siswa ini?')"
                                            >
                                                <input type="hidden" name="aksi" value="hapus">
                                                <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>

                                            <!-- PINDAH KELAS (PALING UJUNG) -->
                                            <a
                                                href="penempatan_siswa.php?edit=<?= (int)$row['id'] ?>&pindah=1#formPenempatan"
                                                class="btn btn-primary btn-sm"
                                            >
                                                Pindah Kelas
                                            </a>

                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelPenempatan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        scrollX: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data penempatan siswa",
            infoEmpty: "Tidak ada data penempatan siswa",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data penempatan siswa tidak ditemukan",
            emptyTable: "Belum ada data penempatan siswa",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

</body>
</html>
