
<?php
// about.php

session_start();
include "config/koneksi.php";

$nama = "rizki";
$kelas = "XII RPL";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Baca Buku";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tentang Saya</title>

    <!-- Bootstrap sama dengan Kelola Siswa -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS utama -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        /* KONTEN PROFIL */
        .profile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .06);
            margin-bottom: 25px;
        }

        .profile-photo-wrapper {
            margin-bottom: 18px;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #e9ecef;
        }

        .profile-name {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #212529;
        }

        .profile-role {
            color: #0d6efd;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .profile-description {
            max-width: 750px;
            margin: 0 auto;
            line-height: 1.8;
            color: #6c757d;
        }

        .profile-divider {
            margin: 25px auto;
            max-width: 750px;
            border-color: #dee2e6;
        }

        .back-button {
            display: inline-block;
            background: #6c757d;
            color: #fff;
            padding: 9px 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-button:hover {
            background: #5c636a;
            color: #fff;
        }

        /* INFORMASI PERSONAL */
        .section-title {
            font-size: 21px;
            font-weight: 600;
            margin-bottom: 18px;
            color: #212529;
        }

        .info-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .04);
        }

        .info-label {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 500;
            color: #212529;
            overflow-wrap: anywhere;
        }

        /* DESKRIPSI DAN KEAHLIAN */
        .description-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 22px;
            height: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .04);
        }

        .description-card p {
            line-height: 1.8;
            color: #555;
            margin-bottom: 0;
        }

        .skill-item {
            display: inline-block;
            background: #e7f1ff;
            color: #0d6efd;
            padding: 7px 12px;
            border-radius: 5px;
            margin: 4px 3px 4px 0;
            font-size: 14px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .profile-card {
                padding: 20px 15px;
            }

            .profile-photo {
                width: 120px;
                height: 120px;
            }

            .profile-name {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR SAMA DENGAN HALAMAN LAIN -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="p-4 flex-grow-1" style="min-width: 0; flex-basis: 0;">

            <!-- HEADER -->
            <div class="mb-4">
                <h1>Tentang Saya</h1>
                <p class="text-secondary mb-0">
                    Mengenal lebih dekat dengan saya.
                </p>
            </div>

            <!-- PROFIL -->
            <section class="profile-card">

                <div class="profile-photo-wrapper">
                    <img
                        src="iki.jpg"
                        alt="Foto Profil Rizki"
                        class="profile-photo"
                        onerror="this.onerror=null;this.src='foto-profil.png';"
                    >
                </div>

                <h2 class="profile-name">
                    <?= htmlspecialchars($nama) ?>
                </h2>

                <div class="profile-role">
                    <?= htmlspecialchars($jurusan) ?>
                </div>

                <p class="profile-description">
                    Halo, saya <?= htmlspecialchars($nama) ?>,
                    seorang pelajar kelas
                    <?= htmlspecialchars($kelas) ?> di
                    <?= htmlspecialchars($sekolah) ?>.
                    Saya memiliki ketertarikan pada teknologi,
                    pengembangan website, dan desain antarmuka.
                    Saya terus belajar untuk meningkatkan kemampuan
                    serta menciptakan website yang bermanfaat,
                    responsif, dan mudah digunakan.
                </p>

                <hr class="profile-divider">

                <a href="dashboard.php" class="back-button">
                    Kembali ke Dashboard
                </a>

            </section>

            <!-- INFORMASI PERSONAL -->
            <section class="mb-4">

                <h3 class="section-title">Informasi Personal</h3>

                <div class="row g-3">

                    <div class="col-sm-6 col-xl-4">
                        <div class="info-card">
                            <div class="info-label">NAMA LENGKAP</div>
                            <div class="info-value">
                                <?= htmlspecialchars($nama) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4">
                        <div class="info-card">
                            <div class="info-label">KELAS</div>
                            <div class="info-value">
                                <?= htmlspecialchars($kelas) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-4">
                        <div class="info-card">
                            <div class="info-label">HOBI</div>
                            <div class="info-value">
                                <?= htmlspecialchars($hobi) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-6">
                        <div class="info-card">
                            <div class="info-label">ASAL SEKOLAH</div>
                            <div class="info-value">
                                <?= htmlspecialchars($sekolah) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-6">
                        <div class="info-card">
                            <div class="info-label">JURUSAN</div>
                            <div class="info-value">
                                <?= htmlspecialchars($jurusan) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- DESKRIPSI DAN KEAHLIAN -->
            <section class="row g-3">

                <div class="col-lg-7">
                    <div class="description-card">

                        <h3 class="section-title">Deskripsi Diri</h3>

                        <p>
                            Saya adalah seorang pelajar yang sedang
                            mendalami Rekayasa Perangkat Lunak. Saya
                            senang mempelajari teknologi, memahami
                            cara kerja website, dan mencoba hal-hal
                            baru dalam pemrograman.
                        </p>

                        <p class="mt-3">
                            Saya ingin terus mengembangkan kemampuan
                            di bidang web development dan bercita-cita
                            menjadi seorang Web Developer yang mampu
                            menghasilkan karya digital berkualitas.
                        </p>

                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="description-card">

                        <h3 class="section-title">Bidang yang Dipelajari</h3>

                        <span class="skill-item">HTML</span>
                        <span class="skill-item">CSS</span>
                        <span class="skill-item">PHP</span>
                        <span class="skill-item">Bootstrap</span>
                        <span class="skill-item">MySQL</span>
                        <span class="skill-item">Web Development</span>

                        <hr style="border-color: #e8ecf4; margin: 16px 0;">

                        <p>
                            Berfokus pada pengembangan website dengan
                            struktur yang rapi, desain yang konsisten,
                            dan pengalaman pengguna yang baik.
                        </p>

                    </div>
                </div>

            </section>

        </main>
    </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
