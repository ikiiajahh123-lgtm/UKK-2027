
<?php
session_start();
include "config/koneksi.php";

$nama = "Tazkia Zahra";
$kelas = "XI RPL";
$sekolah = "SMK Muhammadiyah Tasikmalaya";
$jurusan = "Rekayasa Perangkat Lunak";
$hobi = "Mendengarkan Musik";
$cita_cita = "Pramugari";

$foto = "assets/foto-profil.jpg";
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
            background: #f5f6fa;
            color: #333;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            background: #343a40;
            position: fixed;
            padding: 15px;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .sidebar h2 {
            color: white;
            font-size: 22px;
            margin-bottom: 25px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 11px;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #087df1;
        }

        .sidebar .logout {
            color: #ff6b6b;
            margin-top: 20px;
        }

        .main {
            margin-left: 230px;
            padding: 25px;
        }

        .main h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .breadcrumb {
            margin-bottom: 25px;
            color: #777;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #087df1;
            text-decoration: none;
        }

        .card {
            max-width: 850px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #e6efff;
            color: #087df1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            font-weight: bold;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile h2 {
            margin: 0 0 8px;
            font-size: 23px;
        }

        .profile p {
            margin: 0;
            color: #777;
            line-height: 1.6;
            font-size: 14px;
        }

        .card h3 {
            font-size: 18px;
            margin: 0 0 15px;
        }

        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .item {
            padding: 14px;
            background: #f8f9fc;
            border-radius: 6px;
        }

        .item small {
            display: block;
            color: #777;
            margin-bottom: 7px;
        }

        .item strong {
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .deskripsi {
            margin-top: 20px;
            padding: 15px;
            background: #eef5ff;
            border-radius: 6px;
            line-height: 1.7;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #087df1;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn:hover {
            background: #0069d9;
        }

        @media (max-width: 700px) {
            .sidebar {
                width: 160px;
                padding: 10px;
            }

            .sidebar h2 {
                font-size: 17px;
            }

            .sidebar a {
                font-size: 12px;
                padding: 9px 6px;
            }

            .main {
                margin-left: 160px;
                padding: 15px 10px;
            }

            .main h1 {
                font-size: 24px;
            }

            .card {
                padding: 15px;
            }

            .profile {
                align-items: flex-start;
                flex-direction: column;
            }

            .info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>My Website</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="data_siswa.php">Data Siswa</a>
    <a href="data_guru.php">Data Guru</a>
    <a href="kelas.php">Kelas</a>
    <a href="tahun_ajaran.php">Tahun Ajaran</a>
    <a href="penempatan_siswa.php">Penempatan Siswa</a>
    <a href="wali_kelas.php">Wali Kelas</a>
    <a href="laporan.php">Laporan</a>
    <a href="about_me.php" class="active">About Me</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="main">

    <h1>About Me</h1>

    <div class="breadcrumb">
        <a href="dashboard.php">Dashboard</a> / About Me
    </div>

    <div class="card">

        <div class="profile">

            <div class="avatar">
                <?php if (file_exists($foto)) { ?>
                    <img src="<?php echo htmlspecialchars($foto); ?>"
                         alt="Foto Profil">
                <?php } else { ?>
                    TZ
                <?php } ?>
            </div>

            <div>
                <h2><?php echo htmlspecialchars($nama); ?></h2>
                <p><?php echo htmlspecialchars($kelas); ?></p>
                <p><?php echo htmlspecialchars($jurusan); ?></p>
            </div>

        </div>

        <h3>Informasi Pribadi</h3>

        <div class="info">

            <div class="item">
                <small>Nama Lengkap</small>
                <strong><?php echo htmlspecialchars($nama); ?></strong>
            </div>

            <div class="item">
                <small>Kelas</small>
                <strong><?php echo htmlspecialchars($kelas); ?></strong>
            </div>

            <div class="item">
                <small>Sekolah</small>
                <strong><?php echo htmlspecialchars($sekolah); ?></strong>
            </div>

            <div class="item">
                <small>Jurusan</small>
                <strong><?php echo htmlspecialchars($jurusan); ?></strong>
            </div>

            <div class="item">
                <small>Hobi</small>
                <strong><?php echo htmlspecialchars($hobi); ?></strong>
            </div>

            <div class="item">
                <small>Cita-cita</small>
                <strong><?php echo htmlspecialchars($cita_cita); ?></strong>
            </div>

        </div>

        <div class="deskripsi">
            Saya adalah pelajar SMK Muhammadiyah Tasikmalaya
            jurusan Rekayasa Perangkat Lunak. Saya senang
            mendengarkan musik, belajar hal baru, dan ingin
            meraih cita-cita menjadi seorang pramugari.
        </div>

        <a href="dashboard.php" class="btn">
            Kembali ke Dashboard
        </a>

    </div>

</div>

</body>
</html>