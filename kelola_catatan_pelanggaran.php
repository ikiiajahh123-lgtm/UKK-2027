
<?php
// catatan_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = '';
$error = '';

// SIMPAN DATA PELANGGARAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $guru_id = (int)($_POST['guru_id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $poin = (int)($_POST['poin'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = $_POST['status'] ?? 'tercatat';

    $status_valid = ['tercatat', 'Ditindaklanjuti', 'selesai'];

    if (
        !$siswa_id || !$kelas_id || !$tahun_ajaran_id ||
        !$kategori_id || !$guru_id || !$tanggal || $poin <= 0
    ) {
        $error = "Mohon lengkapi semua data yang wajib diisi.";
    } elseif (!in_array($status, $status_valid, true)) {
        $error = "Status pelanggaran tidak valid.";
    } else {

        $stmt = mysqli_prepare($koneksi, "
            INSERT INTO t_pelanggaran
            (siswa_id, kelas_id, tahun_ajaran_id, kategori_id,
             guru_id, tanggal, poin, keterangan, tindakan, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "iiiiisisss",
                $siswa_id,
                $kelas_id,
                $tahun_ajaran_id,
                $kategori_id,
                $guru_id,
                $tanggal,
                $poin,
                $keterangan,
                $tindakan,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: catatan_pelanggaran.php?berhasil=1");
                exit;
            } else {
                $error = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);

        } else {
            $error = "Query gagal: " . mysqli_error($koneksi);
        }
    }
}

if (isset($_GET['berhasil'])) {
    $pesan = "Data pelanggaran berhasil disimpan.";
}

// AMBIL DATA DROPDOWN
$siswa = mysqli_query($koneksi, "
    SELECT id, nama FROM t_siswa ORDER BY nama ASC
");

$kelas = mysqli_query($koneksi, "
    SELECT id, nama FROM t_kelas ORDER BY nama ASC
");

$tahun = mysqli_query($koneksi, "
    SELECT id, nama FROM t_tahun_ajaran
    ORDER BY tanggal_mulai DESC
");

$guru = mysqli_query($koneksi, "
    SELECT id, nama FROM t_guru ORDER BY nama ASC
");

$kategori = mysqli_query($koneksi, "
    SELECT id, nama FROM t_kategori_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// AMBIL DATA CATATAN PELANGGARAN
$data = mysqli_query($koneksi, "
    SELECT
        p.*,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        ta.nama AS nama_tahun,
        kp.nama AS nama_pelanggaran,
        g.nama AS nama_guru
    FROM t_pelanggaran p
    LEFT JOIN t_siswa s ON p.siswa_id = s.id
    LEFT JOIN t_kelas k ON p.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON p.tahun_ajaran_id = ta.id
    LEFT JOIN t_kategori_pelanggaran kp ON p.kategori_id = kp.id
    LEFT JOIN t_guru g ON p.guru_id = g.id
    ORDER BY p.tanggal DESC, p.id DESC
");

if (!$siswa || !$kelas || !$tahun || !$guru || !$kategori || !$data) {
    die("Terjadi kesalahan database: " . mysqli_error($koneksi));
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// LINK DASHBOARD SESUAI ROLE
$dashboardLink = ($_SESSION['role'] === 'guru')
    ? 'dashboard_guru.php'
    : 'dashboard.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catat Pelanggaran Siswa - SIPES</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DATATABLES BOOTSTRAP 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-dark text-white">

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="flex-grow-1 min-vw-0 bg-dark">

        <div class="container-fluid p-3 p-lg-4">

            <!-- JUDUL -->
            <h2 class="fw-bold mb-3 text-white">
                Catat Pelanggaran Siswa
            </h2>

            <!-- TOMBOL KEMBALI -->
            <a href="<?= e($dashboardLink); ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>

            <!-- PESAN BERHASIL -->
            <?php if ($pesan): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= e($pesan); ?>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- PESAN ERROR -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= e($error); ?>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- FORM CATAT PELANGGARAN -->
            <div class="card bg-white text-dark border-0 shadow-sm rounded-3 mb-4">

                <div class="card-header bg-light text-dark border-bottom p-3">
                    Form Catat Pelanggaran
                </div>

                <div class="card-body p-3 p-lg-4">

                    <form method="POST" action="">

                        <div class="row g-3">

                            <!-- SISWA -->
                            <div class="col-md-6">
                                <label class="form-label">Siswa</label>

                                <select name="siswa_id" class="form-select" required>
                                    <option value="">-- Pilih Siswa --</option>

                                    <?php while ($row = mysqli_fetch_assoc($siswa)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- TAHUN AJARAN -->
                            <div class="col-md-6">
                                <label class="form-label">Tahun Ajaran</label>

                                <select name="tahun_ajaran_id" class="form-select" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>

                                    <?php while ($row = mysqli_fetch_assoc($tahun)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- KELAS -->
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>

                                <select name="kelas_id" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>

                                    <?php while ($row = mysqli_fetch_assoc($kelas)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- JENIS PELANGGARAN -->
                            <div class="col-md-6">
                                <label class="form-label">Jenis Pelanggaran</label>

                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Pelanggaran --</option>

                                    <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- GURU -->
                            <div class="col-md-6">
                                <label class="form-label">Guru Pelapor</label>

                                <select name="guru_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>

                                    <?php while ($row = mysqli_fetch_assoc($guru)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- TANGGAL -->
                            <div class="col-md-6">
                                <label class="form-label">Tanggal</label>

                                <input type="date"
                                       name="tanggal"
                                       class="form-control"
                                       value="<?= date('Y-m-d'); ?>"
                                       required>
                            </div>

                            <!-- POIN -->
                            <div class="col-md-6">
                                <label class="form-label">Poin Pelanggaran</label>

                                <input type="number"
                                       name="poin"
                                       class="form-control"
                                       min="1"
                                       placeholder="Masukkan jumlah poin"
                                       required>
                            </div>

                            <!-- STATUS -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">
                                    <option value="tercatat">Tercatat</option>
                                    <option value="Ditindaklanjuti">Ditindaklanjuti</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>

                            <!-- KETERANGAN -->
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>

                                <textarea name="keterangan"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan keterangan pelanggaran..."></textarea>
                            </div>

                            <!-- TINDAKAN -->
                            <div class="col-md-6">
                                <label class="form-label">Tindakan</label>

                                <textarea name="tindakan"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan tindakan yang diberikan..."></textarea>
                            </div>

                        </div>

                        <!-- TOMBOL -->
                        <div class="d-flex flex-wrap gap-2 mt-3">

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Simpan Pelanggaran
                            </button>

                            <a href="<?= e($dashboardLink); ?>" class="btn btn-secondary">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>
            </div>

            <!-- TABEL CATATAN PELANGGARAN -->
            <div class="card bg-white text-dark border-0 shadow-sm rounded-3">

                <div class="card-header bg-light text-dark border-bottom p-3">
                    Data Catatan Pelanggaran
                </div>

                <div class="card-body p-3 p-lg-4">

                    <div class="table-responsive">

                        <table id="tabelPelanggaran"
                               class="table table-bordered table-striped table-hover align-middle mb-0 w-100">

                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Pelanggaran</th>
                                    <th>Guru</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($row = mysqli_fetch_assoc($data)):
                                ?>

                                    <tr>
                                        <td><?= $no++; ?></td>

                                        <td>
                                            <strong><?= e($row['nama_siswa']); ?></strong>
                                        </td>

                                        <td><?= e($row['nama_kelas']); ?></td>

                                        <td><?= e($row['nama_tahun']); ?></td>

                                        <td><?= e($row['nama_pelanggaran']); ?></td>

                                        <td><?= e($row['nama_guru']); ?></td>

                                        <td class="text-nowrap">
                                            <?= e($row['tanggal']); ?>
                                        </td>

                                        <td class="text-center">
                                            <strong><?= (int)$row['poin']; ?></strong>
                                        </td>

                                        <td><?= e($row['tindakan']); ?></td>

                                        <td>
                                            <?php
                                            $status_data = strtolower($row['status'] ?? '');

                                            if ($status_data === 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } elseif ($status_data === 'ditindaklanjuti') {
                                                echo '<span class="badge bg-success">Ditindaklanjuti</span>';
                                            } else {
                                                echo '<span class="badge bg-warning text-dark">Tercatat</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>

    </main>

</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DATATABLES -->
<script>
$(document).ready(function () {

    $('#tabelPelanggaran').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [[6, 'desc']],

        scrollX: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data pelanggaran",
            infoEmpty: "Tidak ada data pelanggaran",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data pelanggaran tidak ditemukan",
            emptyTable: "Belum ada catatan pelanggaran",

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
