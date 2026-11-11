
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

        $row['jumlah_pelanggaran'] =
            (int)$row['jumlah_pelanggaran'];

        $row['total_poin'] =
            (int)$row['total_poin'];

        $dataRekap[] = $row;

        $totalSiswa++;

        $totalPelanggaran +=
            $row['jumlah_pelanggaran'];

        $totalSeluruhPoin +=
            $row['total_poin'];
    }

    mysqli_free_result($result);

} else {

    // Simpan error untuk ditampilkan secara aman
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

</head>

<body class="bg-dark text-white">

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/layout/sidebar.php'; ?>


    <!-- KONTEN UTAMA -->
    <div class="flex-grow-1 min-vh-100">

        <!-- TOPBAR -->
        <nav class="navbar navbar-dark bg-dark border-bottom border-secondary px-4 py-3">

            <div class="d-flex align-items-center gap-3">

                <div class="bg-primary bg-opacity-25 rounded-3 p-2">
                    <i class="bi bi-buildings text-primary fs-4"></i>
                </div>

                <div>
                    <div class="fw-bold">
                        Sistem Informasi Pelanggaran Siswa
                    </div>

                    <small class="text-secondary">
                        Panel Guru
                    </small>
                </div>

            </div>

            <div class="d-flex align-items-center gap-2">

                <div class="bg-secondary bg-opacity-25 rounded-circle p-2">
                    <i class="bi bi-person-fill text-white fs-5"></i>
                </div>

                <div>
                    <div class="fw-bold small">
                        <?= $namaGuru; ?>
                    </div>

                    <small class="text-secondary">
                        <?= $emailGuru; ?>
                    </small>
                </div>

            </div>

        </nav>


        <!-- ISI HALAMAN -->
        <main class="container-fluid p-4">

            <!-- JUDUL HALAMAN -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                <div>
                    <h2 class="fw-bold mb-2">
                        Rekap Poin Siswa
                    </h2>

                    <p class="text-secondary mb-0">
                        Pantau jumlah pelanggaran dan total poin setiap siswa.
                    </p>
                </div>

                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Dashboard
                </a>

            </div>


            <!-- PESAN ERROR -->
            <?php if ($error !== ''): ?>

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?= aman($error); ?>
                </div>

            <?php endif; ?>


            <!-- STATISTIK -->
            <div class="row g-3 mb-4">

                <!-- TOTAL SISWA -->
                <div class="col-md-4">

                    <div class="card bg-light text-dark shadow-sm h-100">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-secondary mb-2">
                                    Total Siswa
                                </p>

                                <h2 class="fw-bold mb-0">
                                    <?= number_format($totalSiswa, 0, ',', '.'); ?>
                                </h2>
                            </div>

                            <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- TOTAL PELANGGARAN -->
                <div class="col-md-4">

                    <div class="card bg-light text-dark shadow-sm h-100">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-secondary mb-2">
                                    Total Pelanggaran
                                </p>

                                <h2 class="fw-bold mb-0">
                                    <?= number_format($totalPelanggaran, 0, ',', '.'); ?>
                                </h2>
                            </div>

                            <div class="bg-purple bg-opacity-10 text-primary rounded-3 p-3">
                                <i class="bi bi-clipboard-check fs-3"></i>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- TOTAL POIN -->
                <div class="col-md-4">

                    <div class="card bg-light text-dark shadow-sm h-100">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <p class="text-secondary mb-2">
                                    Total Seluruh Poin
                                </p>

                                <h2 class="fw-bold mb-0">
                                    <?= number_format($totalSeluruhPoin, 0, ',', '.'); ?>
                                </h2>
                            </div>

                            <div class="bg-warning bg-opacity-25 text-warning rounded-3 p-3">
                                <i class="bi bi-bar-chart-fill fs-3"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- TABEL REKAP -->
            <div class="card bg-light text-dark shadow-sm">

                <div class="card-body p-3 p-md-4">

                    <!-- JUDUL TABEL -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

                        <h5 class="fw-bold mb-0">

                            <i class="bi bi-list-ol"></i>
                            Daftar Rekap Poin Siswa

                        </h5>

                        <input
                            type="text"
                            id="cariSiswa"
                            class="form-control"
                            style="max-width: 300px;"
                            placeholder="Cari nama siswa atau kelas..."
                            autocomplete="off"
                        >

                    </div>


                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover align-middle mb-0"
                               id="tabelRekap">

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

                                    <tr>
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

                    </div>


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

    if (!input || !tabel) {
        return;
    }

    input.addEventListener('input', function () {

        const keyword = this.value.toLowerCase();

        const baris = tabel.querySelectorAll('tbody tr');

        baris.forEach(function (row) {

            if (row.querySelector('.text-secondary')) {
                return;
            }

            const isi = row.textContent.toLowerCase();

            row.style.display = isi.includes(keyword) ? '' : 'none';

        });

    });

});

</script>

</body>
</html>
