
<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

// ==========================================
// CEK LOGIN
// ==========================================

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}

// ==========================================
// CEK ROLE GURU
// ==========================================

if (($_SESSION['role'] ?? '') !== 'guru') {
    header('Location: login.php');
    exit;
}

// ==========================================
// DATA PENGGUNA
// ==========================================

$nama = htmlspecialchars($_SESSION['nama'] ?? 'Guru', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Guru | Pelanggaran Siswa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body style="margin:0; background:#212529; color:#fff; font-family:Arial,sans-serif;">

<div class="d-flex min-vh-100">

    <!-- ======================================
         SIDEBAR
    ======================================= -->

    <?php include __DIR__ . '/layout/sidebar.php'; ?>


    <!-- ======================================
         KONTEN UTAMA
    ======================================= -->

    <div class="flex-grow-1 min-vh-100" style="min-width:0; background:#212529;">

        <!-- TOPBAR -->
        <header class="d-flex align-items-center justify-content-between px-4"
                style="height:72px; background:#343a40; border-bottom:1px solid #6c757d;">

            <div class="d-flex align-items-center gap-3">

                <div class="d-flex align-items-center justify-content-center rounded-3"
                     style="width:48px; height:48px; background:#29486d; color:#0dcaf0; font-size:23px;">
                    <i class="bi bi-buildings"></i>
                </div>

                <div>
                    <strong class="d-block" style="font-size:15px;">
                        Sistem Informasi Pelanggaran Siswa
                    </strong>

                    <small style="color:#89939d;">
                        Panel Guru
                    </small>
                </div>

            </div>

            <div class="d-flex align-items-center gap-3">

                <div class="text-end">
                    <strong class="d-block" style="font-size:13px;">
                        <?= $nama; ?>
                    </strong>

                    <small style="color:#89939d; font-size:12px;">
                        <?= $email; ?>
                    </small>
                </div>

                <div class="d-flex align-items-center justify-content-center rounded-circle"
                     style="width:42px; height:42px; background:#6c757d; color:white; font-size:19px;">
                    <i class="bi bi-person-fill"></i>
                </div>

            </div>

        </header>


        <!-- ======================================
             DASHBOARD CONTENT
        ======================================= -->

        <main class="container-fluid px-4 py-4">

            <!-- JUDUL -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h1 class="fw-bold mb-1" style="font-size:28px;">
                        Dashboard Guru
                    </h1>

                    <p class="mb-0" style="color:#0dcaf0; font-size:13px;">
                        Selamat datang, <?= $nama; ?>.
                        Kelola pencatatan pelanggaran siswa di sini.
                    </p>
                </div>

                <span class="rounded-pill px-3 py-2"
                      style="border:1px solid #0d6efd; color:#0d6efd; font-size:12px;">
                    <i class="bi bi-person-check"></i>
                    Guru
                </span>

            </div>


            <!-- ======================================
                 KARTU MENU GURU
            ======================================= -->

            <div class="row g-3 mb-4">

                <!-- CATAT PELANGGARAN -->
                <div class="col-12 col-md-6 col-xl-3">

                    <a href="catat_pelanggaran.php"
                       class="text-decoration-none text-white">

                        <div class="rounded-4 p-4 h-100"
                             style="background:#343a40; border:1px solid #6c757d; min-height:181px;">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span style="color:#0dcaf0; font-size:13px;">
                                    Catat Pelanggaran
                                </span>

                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:64px; height:64px; background:#29486d; color:#0d6efd; font-size:25px;">
                                    <i class="bi bi-pencil-square"></i>
                                </div>

                            </div>

                            <h3 class="fw-bold mb-2" style="font-size:27px;">
                                Mulai
                            </h3>

                            <small style="color:#89939d;">
                                Input pelanggaran siswa
                            </small>

                        </div>

                    </a>

                </div>


                <!-- TINDAKAN -->
                <div class="col-12 col-md-6 col-xl-3">

                    <a href="kelola_tindakan.php"
                       class="text-decoration-none text-white">

                        <div class="rounded-4 p-4 h-100"
                             style="background:#343a40; border:1px solid #6c757d; min-height:181px;">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span style="color:#0dcaf0; font-size:13px;">
                                    Tindakan
                                </span>

                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:64px; height:64px; background:#49346b; color:#bd79ff; font-size:25px;">
                                    <i class="bi bi-clipboard2-check"></i>
                                </div>

                            </div>

                            <h3 class="fw-bold mb-2" style="font-size:27px;">
                                Kelola
                            </h3>

                            <small style="color:#89939d;">
                                Proses tindak lanjut
                            </small>

                        </div>

                    </a>

                </div>


                <!-- RIWAYAT -->
                <div class="col-12 col-md-6 col-xl-3">

                    <a href="kelola_riwayat.php"
                       class="text-decoration-none text-white">

                        <div class="rounded-4 p-4 h-100"
                             style="background:#343a40; border:1px solid #6c757d; min-height:181px;">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span style="color:#0dcaf0; font-size:13px;">
                                    Riwayat
                                </span>

                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:64px; height:64px; background:#665323; color:#ffc107; font-size:25px;">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                            </div>

                            <h3 class="fw-bold mb-2" style="font-size:27px;">
                                Lihat
                            </h3>

                            <small style="color:#89939d;">
                                Riwayat pelanggaran siswa
                            </small>

                        </div>

                    </a>

                </div>


                <!-- REKAP POIN -->
                <div class="col-12 col-md-6 col-xl-3">

                    <a href="rekap_poin.php"
                       class="text-decoration-none text-white">

                        <div class="rounded-4 p-4 h-100"
                             style="background:#343a40; border:1px solid #6c757d; min-height:181px;">

                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <span style="color:#0dcaf0; font-size:13px;">
                                    Rekap Poin
                                </span>

                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:64px; height:64px; background:#205b48; color:#20c997; font-size:25px;">
                                    <i class="bi bi-bar-chart-line"></i>
                                </div>

                            </div>

                            <h3 class="fw-bold mb-2" style="font-size:27px;">
                                Buka
                            </h3>

                            <small style="color:#89939d;">
                                Rekap poin siswa
                            </small>

                        </div>

                    </a>

                </div>

            </div>


            <!-- ======================================
                 PANEL SELAMAT BEKERJA
            ======================================= -->

            <section class="rounded-4 p-4"
                     style="background:#343a40; border:1px solid #6c757d;">

                <div class="d-flex align-items-center gap-3 flex-wrap">

                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width:56px; height:68px; background:#29486d; color:#0dcaf0; font-size:27px;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div class="flex-grow-1">

                        <h5 class="fw-bold mb-1" style="font-size:15px;">
                            Selamat bekerja!
                        </h5>

                        <p class="mb-0" style="color:#0dcaf0; font-size:13px;">
                            Gunakan menu di samping untuk mencatat pelanggaran,
                            melihat riwayat, dan memantau rekap poin siswa.
                        </p>

                    </div>

                    <a href="catat_pelanggaran.php"
                       class="btn btn-primary px-3 py-2">

                        <small>
                            Catat Pelanggaran
                            <i class="bi bi-arrow-right"></i>
                        </small>

                    </a>

                </div>

            </section>

        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
