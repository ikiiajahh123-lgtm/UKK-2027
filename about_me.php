
<?php
session_start();
include "config/koneksi.php";

$nama = "Tazkia Zahra";
$kelas = "XI RPL";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Mendengarkan Musik";
$cita_cita = "PRAMUGARI";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: white;
            color: #212529;
        }

        /* Sidebar mengikuti dashboard */
        .sidebar {
            width: 285px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            padding: 14px;
            overflow-y: auto;
        }

        .sidebar h2 {
            color: white;
            font-size: 22px;
            margin: 3px 0 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 11px 14px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar a.active {
            background: #087df1;
        }

        .sidebar .logout {
            color: #dc3545;
            margin-top: 35px;
        }

        /* Konten utama */
        .main {
            margin-left: 285px;
            padding: 25px 21px;
        }

        .main h1 {
            font-size: 36px;
            font-weight: 500;
            margin: 0 0 8px;
        }

        .navigation {
            margin-bottom: 22px;
            font-size: 14px;
        }

        .navigation a {
            color: #007bff;
            text-decoration: none;
        }

        .navigation a:hover {
            text-decoration: underline;
        }

        /* Kartu About Me */
        .about-card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 25px;
            max-width: 1000px;
            background: white;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 22px;
            padding-bottom: 22px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 22px;
        }

        .avatar {
            width: 105px;
            height: 105px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 48px;
            flex-shrink: 0;
        }

        .profile-header h2 {
            margin: 0 0 10px;
            font-size: 27px;
        }

        .profile-header p {
            color: #6c757d;
            margin: 0;
            line-height: 1.6;
        }

        .section-title {
            font-size: 21px;
            margin: 0 0 16px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .info-item {
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 4px;
            background: #f8f9fa;
        }

        .info-item .label {
            display: block;
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 9px;
        }

        .info-item .value {
            display: block;
            font-size: 15px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .description {
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #087df1;
            line-height: 1.8;
        }

        .description h3 {
            margin: 0 0 10px;
            font-size: 20px;
        }

        .description p {
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 22px;
            background: #087df1;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn:hover {
            background: #0069d9;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main {
                margin-left: 200px;
                padding: 20px 15px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .profile-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 500px) {
            .sidebar {
                width: 150px;
                padding: 8px;
            }

            .sidebar h2 {
                font-size: 17px;
            }

            .sidebar a {
                padding: 10px 7px;
                font-size: 12px;
            }

            .main {
                margin-left: 150px;
                padding: 18px 10px;
            }

            .main h1 {
                font-size: 27px;
            }

            .about-card {
                padding: 14px;
            }

            .avatar {
                width: 75px;
                height: 75px;
                font-size: 35px;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h2>My Website</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="data_siswa.php">Data Siswa</a>
    <a href="data_guru.php">Data Guru</a>
    <a href="kelas.php">Kelas</a>
    <a href="tahun_ajaran.php">Tahun ajaran</a>
    <a href="penempatan_siswa.php">Penempatan Siswa</a>
    <a href="wali_kelas.php">Wali kelas</a>
    <a href="laporan.php">Laporan</a>
    <a href="about_me.php" class="active">About Me</a>

    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- Konten -->
<div class="main">

    <h1>About Me</h1>

    <div class="navigation">
        <a href="dashboard.php">Kembali ke Dashboard</a>
        | Tentang Saya
    </div>

    <div class="about-card">

        <div class="profile-header">
            <div class="avatar">👩🏻</div>

            <div>
                <h2>
                    <?php echo htmlspecialchars($nama); ?>
                </h2>

                <p>
                    Pelajar <?php echo htmlspecialchars($kelas); ?>
                    <br>
                    <?php echo htmlspecialchars($jurusan); ?>
                </p>
            </div>
        </div>

        <h3 class="section-title">Informasi Pribadi</h3>

        <div class="info-grid">

            <div class="info-item">
                <span class="label">Nama Lengkap</span>
                <span class="value">
                    <?php echo htmlspecialchars($nama); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Kelas</span>
                <span class="value">
                    <?php echo htmlspecialchars($kelas); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Sekolah</span>
                <span class="value">
                    <?php echo htmlspecialchars($sekolah); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Jurusan</span>
                <span class="value">
                    <?php echo htmlspecialchars($jurusan); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Hobi</span>
                <span class="value">
                    <?php echo htmlspecialchars($hobi); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="label">Cita-cita</span>
                <span class="value">
                    <?php echo htmlspecialchars($cita_cita); ?>
                </span>
            </div>

        </div>

        <div class="description">
            <h3>Deskripsi Diri</h3>

            <p>
                Saya adalah seorang pelajar yang sedang belajar
                Rekayasa Perangkat Lunak di SMK Muhammadiyah
                Tasikmalaya. Saya senang mendengarkan musik dan
                mempelajari hal-hal baru. Saya ingin terus
                mengembangkan kemampuan diri, menambah pengalaman,
                dan berusaha meraih cita-cita menjadi seorang
                pramugari.
            </p>
        </div>

        <a href="dashboard.php" class="btn">
            Kembali ke Dashboard
        </a>

    </div>

</div>

</body>
</html>
