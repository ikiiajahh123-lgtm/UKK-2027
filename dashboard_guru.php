
<?php
session_start();

require_once __DIR__ . '/config/koneksi.php';

// ==========================================
// CEK LOGIN
// ==========================================

if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true
) {
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

$nama = htmlspecialchars(
    $_SESSION['nama'] ?? 'Guru',
    ENT_QUOTES,
    'UTF-8'
);

$email = htmlspecialchars(
    $_SESSION['email'] ?? '',
    ENT_QUOTES,
    'UTF-8'
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Guru | Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
       href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- CSS Utama -->
    <link
        rel="stylesheet"
        href="layout/style.css?v=2"
    >

</head>

<body>

<div class="app-shell">

    <!-- ======================================
         SIDEBAR
    ======================================= -->

    <?php include __DIR__ . '/layout/sidebar.php'; ?>


    <!-- ======================================
         KONTEN UTAMA
    ======================================= -->

    <div class="main-area">


        <!-- ==================================
             TOPBAR
        =================================== -->

        <header class="topbar">

            <div class="school-brand">

                <div class="brand-icon">
                    <i class="bi bi-buildings"></i>
                </div>

                <div>
                    <strong>
                        Sistem Informasi Pelanggaran Siswa
                    </strong>

                    <small>Panel Guru</small>
                </div>

            </div>


            <div class="topbar-right">

                <div class="top-search">
                    <i class="bi bi-search"></i>
                    <span>Cari menu...</span>
                </div>

                <button
                    class="top-icon"
                    type="button"
                    aria-label="Notifikasi"
                >
                    <i class="bi bi-bell"></i>
                </button>

                <div class="user-info">

                    <div class="avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div>
                        <strong><?= $nama; ?></strong>
                        <small><?= $email; ?></small>
                    </div>

                </div>

            </div>

        </header>


        <!-- ==================================
             DASHBOARD CONTENT
        =================================== -->

        <main class="content-area">


            <!-- JUDUL DASHBOARD -->

            <div class="page-heading">

                <div>

                    <h1>Dashboard Guru</h1>

                    <p>
                        Selamat datang, <?= $nama; ?>.
                        Kelola pencatatan pelanggaran siswa di sini.
                    </p>

                </div>

                <span class="role-chip">
                    <i class="bi bi-person-check"></i>
                    Guru
                </span>

            </div>


            <!-- ==================================
                 MENU STATISTIK
            =================================== -->

            <section class="stats-grid guru-stats">


                <!-- CATAT PELANGGARAN -->

                <article class="stat-card">

                    <div class="stat-copy">

                        <span>Catat Pelanggaran</span>

                        <strong class="stat-word">
                            Mulai
                        </strong>

                        <small>
                            Input pelanggaran siswa
                        </small>

                    </div>

                    <div class="stat-icon blue">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <a
                        href="catat_pelanggaran.php"
                        class="card-hit"
                        aria-label="Catat Pelanggaran"
                    ></a>

                </article>


                <!-- TINDAKAN -->

                <article class="stat-card">

                    <div class="stat-copy">

                        <span>Tindakan</span>

                        <strong class="stat-word">
                            Kelola
                        </strong>

                        <small>
                            Proses tindak lanjut
                        </small>

                    </div>

                    <div class="stat-icon purple">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>

                    <a
                        href="kelola_tindakan.php"
                        class="card-hit"
                        aria-label="Kelola Tindakan"
                    ></a>

                </article>


                <!-- RIWAYAT -->

                <article class="stat-card">

                    <div class="stat-copy">

                        <span>Riwayat</span>

                        <strong class="stat-word">
                            Lihat
                        </strong>

                        <small>
                            Riwayat pelanggaran siswa
                        </small>

                    </div>

                    <div class="stat-icon orange">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <a
                        href="kelola_riwayat.php"
                        class="card-hit"
                        aria-label="Lihat Riwayat"
                    ></a>

                </article>


                <!-- REKAP POIN -->

                <article class="stat-card">

                    <div class="stat-copy">

                        <span>Rekap Poin</span>

                        <strong class="stat-word">
                            Buka
                        </strong>

                        <small>
                            Rekap poin siswa
                        </small>

                    </div>

                    <div class="stat-icon green">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>

                    <a
                        href="rekap_poin.php"
                        class="card-hit"
                        aria-label="Rekap Poin"
                    ></a>

                </article>


            </section>


            <!-- ==================================
                 WELCOME PANEL
            =================================== -->

            <section class="panel welcome-panel">

                <div class="welcome-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <div>

                    <h2>Selamat bekerja!</h2>

                    <p>
                        Gunakan menu di samping untuk mencatat
                        pelanggaran, melihat riwayat, dan
                        memantau rekap poin siswa.
                    </p>

                </div>

                <a
                    href="catat_pelanggaran.php"
                    class="primary-link"
                >
                    Catat Pelanggaran
                    <i class="bi bi-arrow-right"></i>
                </a>

            </section>


        </main>

    </div>

</div>

</body>
</html>