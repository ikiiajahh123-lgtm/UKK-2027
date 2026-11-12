
<?php
// dashboard.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan hanya admin yang membuka halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Menghitung jumlah data
function jumlahData($koneksi, $tabel)
{
    $query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM `$tabel`");

    if ($query) {
        $data = mysqli_fetch_assoc($query);
        return (int) $data['total'];
    }

    return 0;
}

$totalSiswa = jumlahData($koneksi, 't_siswa');
$totalGuru = jumlahData($koneksi, 't_guru');
$totalKelas = jumlahData($koneksi, 't_kelas');
$totalPelanggaran = jumlahData($koneksi, 't_pelanggaran');

$namaAdmin = $_SESSION['nama'] ?? 'Admin';
$emailAdmin = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-dark">

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="bg-dark text-white flex-grow-1" style="min-width: 0; flex-basis: 0;">

            <!-- HEADER -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-secondary bg-opacity-25 border-bottom border-secondary px-3 px-md-4 py-3">
                <div class="container-fluid">

                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-25 rounded-3 p-2">
                            <i class="bi bi-buildings fs-4 text-info"></i>
                        </div>

                        <div>
                            <div class="fw-bold">
                                Sistem Informasi Pelanggaran Siswa
                            </div>
                            <small class="text-secondary">Panel Admin</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 ms-auto">
                        <div class="d-none d-lg-block">
                            <div class="fw-semibold small">
                                <?= htmlspecialchars($namaAdmin, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <small class="text-secondary">
                                <?= htmlspecialchars($emailAdmin, ENT_QUOTES, 'UTF-8'); ?>
                            </small>
                        </div>

                        <div class="rounded-circle bg-secondary p-2">
                            <i class="bi bi-person-fill fs-5"></i>
                        </div>
                    </div>

                </div>
            </nav>

            <!-- ISI DASHBOARD -->
            <div class="container-fluid px-3 px-md-4 py-4">

                <!-- JUDUL -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h3 class="fw-bold mb-1">Dashboard Admin</h3>
                        <p class="text-info small mb-0">
                            Selamat datang, <?= htmlspecialchars($namaAdmin, ENT_QUOTES, 'UTF-8'); ?>.
                            Kelola data sistem informasi pelanggaran siswa di sini.
                        </p>
                    </div>

                    <span class="badge rounded-pill text-primary border border-primary px-3 py-2">
                        <i class="bi bi-shield-lock me-1"></i> Admin
                    </span>
                </div>

                <!-- KARTU STATISTIK -->
                <div class="row g-3 mb-4">

                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="kelola_siswa.php" class="text-decoration-none">
                            <div class="card bg-secondary bg-opacity-25 border border-secondary rounded-4 text-white h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-info">Data Siswa</small>
                                        <span class="rounded-circle bg-primary bg-opacity-25 p-3">
                                            <i class="bi bi-people fs-4 text-primary"></i>
                                        </span>
                                    </div>
                                    <h3 class="fw-bold mb-1"><?= number_format($totalSiswa); ?></h3>
                                    <small class="text-secondary">Kelola data siswa</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="kelola_guru.php" class="text-decoration-none">
                            <div class="card bg-secondary bg-opacity-25 border border-secondary rounded-4 text-white h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-info">Data Guru</small>
                                        <span class="rounded-circle bg-primary bg-opacity-25 p-3">
                                            <i class="bi bi-person-badge fs-4 text-info"></i>
                                        </span>
                                    </div>
                                    <h3 class="fw-bold mb-1"><?= number_format($totalGuru); ?></h3>
                                    <small class="text-secondary">Kelola data guru</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="kelola_kelas.php" class="text-decoration-none">
                            <div class="card bg-secondary bg-opacity-25 border border-secondary rounded-4 text-white h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-info">Data Kelas</small>
                                        <span class="rounded-circle bg-warning bg-opacity-25 p-3">
                                            <i class="bi bi-building fs-4 text-warning"></i>
                                        </span>
                                    </div>
                                    <h3 class="fw-bold mb-1"><?= number_format($totalKelas); ?></h3>
                                    <small class="text-secondary">Kelola data kelas</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="kelola_pelanggaran_kategori.php" class="text-decoration-none">
                            <div class="card bg-secondary bg-opacity-25 border border-secondary rounded-4 text-white h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-info">Pelanggaran</small>
                                        <span class="rounded-circle bg-success bg-opacity-25 p-3">
                                            <i class="bi bi-clipboard-check fs-4 text-success"></i>
                                        </span>
                                    </div>
                                    <h3 class="fw-bold mb-1"><?= number_format($totalPelanggaran); ?></h3>
                                    <small class="text-secondary">Data pelanggaran siswa</small>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <!-- MENU CEPAT -->
                <div class="card bg-secondary bg-opacity-25 border border-secondary rounded-4 text-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center flex-wrap gap-3">

                            <div class="rounded-3 bg-primary bg-opacity-25 p-3">
                                <i class="bi bi-mortarboard-fill fs-3 text-info"></i>
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Selamat bekerja!</h6>
                                <small class="text-info">
                                    Gunakan menu di samping untuk mengelola data siswa,
                                    guru, kelas, dan pelanggaran.
                                </small>
                            </div>

                            <a href="kelola_siswa.php" class="btn btn-primary btn-sm rounded-3 px-3 py-2">
                                Kelola Data Siswa
                                <i class="bi bi-arrow-right ms-1"></i>
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
