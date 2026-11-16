
<?php
// rekap_poin.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/koneksi.php';

// =====================================
// CEK LOGIN GURU
// =====================================

if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    ($_SESSION['role'] ?? '') !== 'guru'
) {
    header('Location: login.php');
    exit;
}

// =====================================
// FUNGSI AMAN
// =====================================

function aman($data)
{
    return htmlspecialchars(
        (string)($data ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

// =====================================
// AMBIL DATA REKAP
// =====================================

$dataRekap = [];

$totalSiswa = 0;
$totalPelanggaran = 0;
$totalSeluruhPoin = 0;

$error = '';

$query = "
    SELECT
        nama_siswa,
        nama_kelas,
        COUNT(id) AS jumlah_pelanggaran,
        SUM(COALESCE(poin, 0)) AS total_poin,
        MAX(tanggal) AS pelanggaran_terakhir
    FROM t_pelanggaran_siswa
    GROUP BY nama_siswa, nama_kelas
    ORDER BY total_poin DESC, nama_siswa ASC
";

$result = mysqli_query($koneksi, $query);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {

        $row['jumlah_pelanggaran'] = (int)$row['jumlah_pelanggaran'];
        $row['total_poin'] = (int)$row['total_poin'];

        $dataRekap[] = $row;

        $totalSiswa++;
        $totalPelanggaran += $row['jumlah_pelanggaran'];
        $totalSeluruhPoin += $row['total_poin'];
    }

    mysqli_free_result($result);

} else {

    error_log(mysqli_error($koneksi));

    $error = 'Data rekap poin gagal dimuat. Periksa struktur tabel database.';
}

// =====================================
// DATA GURU
// =====================================

$namaGuru = aman($_SESSION['nama'] ?? 'Guru');
$emailGuru = aman($_SESSION['email'] ?? '');

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Rekap Poin Siswa | SIPES</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- BOOTSTRAP ICONS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

<<<<<<< HEAD
    <!-- CSS CETAK -->
    <style>
        @media print {

            @page {
                size: landscape;
                margin: 15mm;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
            }

            .no-print,
            nav,
            aside,
            .sidebar {
                display: none !important;
            }

            body,
            .d-flex,
            .flex-grow-1,
            main {
                display: block !important;
                width: 100% !important;
                min-height: auto !important;
                background: #ffffff !important;
                color: #000000 !important;
            }

            main {
                padding: 0 !important;
            }

            .card {
                box-shadow: none !important;
                break-inside: avoid;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 11px !important;
            }

            table th,
            table td {
                border: 1px solid #777777 !important;
                padding: 7px !important;
                color: #000000 !important;
            }

            table thead th {
                background: #dddddd !important;
                color: #000000 !important;
            }

            table tbody tr {
                break-inside: avoid;
            }

            .badge {
                border: 1px solid #777777 !important;
            }

            .text-secondary {
                color: #333333 !important;
            }
        }
=======
    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: #f4f6fc !important;
            color: #212529;
            font-family: Arial, sans-serif;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            background-color: #f4f6fc !important;
            color: #212529 !important;
        }

        /* HEADER ATAS */
        .topbar {
            background-color: #ffffff !important;
            color: #212529 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .topbar .fw-bold {
            color: #212529 !important;
        }

        .topbar small {
            color: #6c757d !important;
        }

        /* JUDUL HALAMAN */
        .judul-halaman {
            color: #152238 !important;
            font-size: 36px;
            font-weight: 400;
        }

        .deskripsi {
            color: #6c757d !important;
        }

        /* CARD */
        .card {
            background-color: #ffffff !important;
            color: #212529 !important;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .card-body {
            background-color: #ffffff;
            color: #212529;
        }

        /* TABEL */
        .table {
            color: #212529;
            font-size: 14px;
        }

        .table thead th {
            background-color: #212529 !important;
            color: #ffffff !important;
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

        /* STATISTIK */
        .statistik-card {
            min-height: 125px;
        }

        .ikon-statistik {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        /* PENCARIAN */
        #cariSiswa {
            max-width: 300px;
        }

        @media (max-width: 768px) {

            .judul-halaman {
                font-size: 28px;
            }

            .topbar {
                padding: 12px !important;
            }

            .topbar .email-guru {
                display: none;
            }

            .main-content main {
                padding: 15px !important;
            }

            #cariSiswa {
                max-width: 100%;
                width: 100%;
            }

        }

>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
    </style>

</head>

<<<<<<< HEAD
<body class="bg-light text-dark">
=======
<body>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
<<<<<<< HEAD
    <div
        class="no-print sticky-top vh-100 flex-shrink-0 bg-dark"
        style="align-self: flex-start;"
    >
        <?php include __DIR__ . '/layout/sidebar.php'; ?>
    </div>
=======
    <?php include __DIR__ . '/layout/sidebar.php'; ?>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

    <!-- KONTEN UTAMA -->
    <div class="main-content">

<<<<<<< HEAD
        <!-- TOPBAR -->
        <nav class="navbar navbar-light bg-white border-bottom px-4 py-3 no-print">
=======
        <!-- HEADER ATAS -->
        <nav class="navbar topbar px-4 py-3">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

            <div class="d-flex align-items-center gap-3">

                <div class="bg-primary bg-opacity-10 rounded-3 p-2">
                    <i class="bi bi-buildings text-primary fs-4"></i>
                </div>

                <div>
                    <div class="fw-bold">
                        Sistem Informasi Pelanggaran Siswa
                    </div>

                    <small>
                        Panel Guru
                    </small>
                </div>

            </div>

            <div class="d-flex align-items-center gap-2">

                <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                    <i class="bi bi-person-fill text-primary fs-5"></i>
                </div>

                <div>
                    <div class="fw-bold small">
                        <?= $namaGuru; ?>
                    </div>

                    <small class="email-guru">
                        <?= $emailGuru; ?>
                    </small>
                </div>

            </div>

        </nav>

        <!-- ISI HALAMAN -->
        <main class="container-fluid p-3 p-lg-4">

            <!-- JUDUL HALAMAN -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                <div>

                    <h2 class="judul-halaman mb-2">
                        Rekap Poin Siswa
                    </h2>

                    <p class="deskripsi mb-0">
                        Pantau jumlah pelanggaran dan total poin setiap siswa.
                    </p>

                </div>

<<<<<<< HEAD
                <!-- TOMBOL CETAK DAN KEMBALI -->
                <div class="d-flex gap-2 flex-wrap no-print">

                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-printer-fill"></i>
                        Cetak Rekap Poin
                    </button>

                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Dashboard
                    </a>

                </div>
=======
                <a href="dashboard_guru.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

            </div>

            <!-- PESAN ERROR -->
            <?php if ($error !== ''): ?>

                <div class="alert alert-danger no-print">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= aman($error); ?>
                </div>

            <?php endif; ?>

            <!-- STATISTIK -->
            <div class="row g-3 mb-4">

                <!-- TOTAL SISWA -->
                <div class="col-md-4">

<<<<<<< HEAD
                    <div class="card bg-white text-dark shadow-sm h-100">
=======
                    <div class="card shadow-sm statistik-card h-100">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-secondary mb-2">
                                    Total Siswa
                                </p>

                                <h2 class="fw-bold mb-0 text-dark">
                                    <?= number_format($totalSiswa, 0, ',', '.'); ?>
                                </h2>

                            </div>

                            <div class="ikon-statistik bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- TOTAL PELANGGARAN -->
                <div class="col-md-4">

<<<<<<< HEAD
                    <div class="card bg-white text-dark shadow-sm h-100">
=======
                    <div class="card shadow-sm statistik-card h-100">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-secondary mb-2">
                                    Total Pelanggaran
                                </p>

                                <h2 class="fw-bold mb-0 text-dark">
                                    <?= number_format($totalPelanggaran, 0, ',', '.'); ?>
                                </h2>

                            </div>

<<<<<<< HEAD
                            <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
=======
                            <div class="ikon-statistik bg-success bg-opacity-10 text-success">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                                <i class="bi bi-clipboard-check fs-3"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- TOTAL POIN -->
                <div class="col-md-4">

<<<<<<< HEAD
                    <div class="card bg-white text-dark shadow-sm h-100">
=======
                    <div class="card shadow-sm statistik-card h-100">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <p class="text-secondary mb-2">
                                    Total Seluruh Poin
                                </p>

                                <h2 class="fw-bold mb-0 text-dark">
                                    <?= number_format($totalSeluruhPoin, 0, ',', '.'); ?>
                                </h2>

                            </div>

                            <div class="ikon-statistik bg-warning bg-opacity-25 text-warning">
                                <i class="bi bi-bar-chart-fill fs-3"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- TABEL REKAP -->
<<<<<<< HEAD
            <div class="card bg-white text-dark shadow-sm">
=======
            <div class="card shadow-sm">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

                <div class="card-body p-3 p-md-4">

                    <!-- JUDUL TABEL -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

<<<<<<< HEAD
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-list-ol"></i>
=======
                        <h5 class="fw-bold mb-0 text-dark">

                            <i class="bi bi-list-ol me-1"></i>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                            Daftar Rekap Poin Siswa
                        </h5>

                        <input
                            type="text"
                            id="cariSiswa"
<<<<<<< HEAD
                            class="form-control no-print"
                            style="max-width: 300px;"
=======
                            class="form-control"
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                            placeholder="Cari nama siswa atau kelas..."
                            autocomplete="off"
                        >

                    </div>

                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table
                            class="table table-bordered table-striped table-hover align-middle mb-0"
                            id="tabelRekap"
                        >

                            <thead class="table-dark">

                                <tr>
                                    <th style="width:65px;">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jumlah Pelanggaran</th>
                                    <th>Total Poin</th>
                                    <th>Pelanggaran Terakhir</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (empty($dataRekap)): ?>

                                    <tr id="barisKosong">
                                        <td colspan="6" class="text-center py-5">

                                            <i class="bi bi-inbox fs-1 text-secondary"></i>

                                            <p class="text-secondary mb-0 mt-2">
                                                Belum ada data rekap poin siswa.
                                            </p>

                                        </td>
                                    </tr>

                                <?php else: ?>

                                    <?php $no = 1; ?>

                                    <?php foreach ($dataRekap as $row): ?>

                                        <tr>

                                            <td>
                                                <span class="badge bg-secondary p-2">
                                                    <?= $no++; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <strong>
                                                    <?= aman($row['nama_siswa']); ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?= aman($row['nama_kelas']); ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-success-subtle text-success p-2">
                                                    <?= number_format($row['jumlah_pelanggaran'], 0, ',', '.'); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-primary-subtle text-primary p-2">
                                                    <?= number_format($row['total_poin'], 0, ',', '.'); ?>
                                                    Poin
                                                </span>
                                            </td>

                                            <td>
                                                <?php
                                                if (!empty($row['pelanggaran_terakhir'])) {

                                                    $timestamp = strtotime($row['pelanggaran_terakhir']);

                                                    echo $timestamp
                                                        ? date('d-m-Y', $timestamp)
                                                        : '-';

                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>

                        </table>

                        <div id="hasilKosong" class="alert alert-warning mt-3 d-none">
                            Data siswa tidak ditemukan.
                        </div>

<<<<<<< HEAD
=======
                    </div>

>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                    <!-- INFORMASI -->
                    <div class="mt-3">

                        <small class="text-secondary">

                            <i class="bi bi-info-circle"></i>

                            Total poin dihitung dari seluruh catatan pelanggaran
                            yang tersimpan dalam database.

                        </small>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- PENCARIAN DATA -->
<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('cariSiswa');
    const tabel = document.getElementById('tabelRekap');
    const hasilKosong = document.getElementById('hasilKosong');

    if (!input || !tabel) {
        return;
    }

    input.addEventListener('input', function () {

<<<<<<< HEAD
        const keyword = this.value.toLowerCase();
=======
        const keyword = this.value.toLowerCase().trim();

>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
        const baris = tabel.querySelectorAll('tbody tr');

        let jumlahTampil = 0;

        baris.forEach(function (row) {

            if (row.id === 'barisKosong') {
                return;
            }

            const isi = row.textContent.toLowerCase();

            if (isi.includes(keyword)) {

                row.style.display = '';
                jumlahTampil++;

            } else {

                row.style.display = 'none';

            }

        });

        if (hasilKosong) {
            hasilKosong.classList.toggle('d-none', jumlahTampil > 0);
        }

    });

});

</script>

</body>
</html>
