
<?php
// kelola_laporan.php

// Session sudah dijalankan oleh cek_session.php
require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

// Fungsi keamanan
function e($value) {
    return htmlspecialchars((string)($value ?? '-'), ENT_QUOTES, 'UTF-8');
}

// ======================================
// CEK KOLOM DATABASE
// ======================================

$kolom = [];

$cekKolom = mysqli_query($koneksi, "SHOW COLUMNS FROM t_pelanggaran_siswa");

if (!$cekKolom) {
    die("Tabel t_pelanggaran_siswa tidak ditemukan: " . e(mysqli_error($koneksi)));
}

while ($c = mysqli_fetch_assoc($cekKolom)) {
    $kolom[] = $c['Field'];
}

// Memilih kolom yang tersedia
function pilihKolom($daftar, $tersedia, $alias) {
    foreach ($daftar as $nama) {
        if (in_array($nama, $tersedia, true)) {
            return "`$nama` AS `$alias`";
        }
    }

    return "'' AS `$alias`";
}

// Kolom utama berdasarkan struktur rekap poin
$namaSiswa = pilihKolom(['nama_siswa'], $kolom, 'nama_siswa');
$namaKelas = pilihKolom(['nama_kelas'], $kolom, 'nama_kelas');
$tanggal = pilihKolom(['tanggal', 'tanggal_pelanggaran'], $kolom, 'tanggal');
$poin = pilihKolom(['poin'], $kolom, 'poin');

// Kolom tambahan jika tersedia
$pelanggaran = pilihKolom(
    ['nama_pelanggaran', 'pelanggaran', 'jenis_pelanggaran'],
    $kolom,
    'pelanggaran'
);

$keterangan = pilihKolom(
    ['keterangan', 'deskripsi'],
    $kolom,
    'keterangan'
);

$guru = pilihKolom(
    ['nama_guru', 'guru'],
    $kolom,
    'nama_guru'
);

$tindakan = pilihKolom(
    ['tindakan'],
    $kolom,
    'tindakan'
);

$status = pilihKolom(
    ['status'],
    $kolom,
    'status'
);

// ======================================
// AMBIL DATA LAPORAN
// ======================================

$sql = "
    SELECT
        id,
        $namaSiswa,
        $namaKelas,
        $pelanggaran,
        $keterangan,
        $guru,
        $tanggal,
        $poin,
        $tindakan,
        $status
    FROM t_pelanggaran_siswa
    ORDER BY tanggal DESC, id DESC
";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Gagal mengambil data: " . e(mysqli_error($koneksi)));
}

$totalLaporan = mysqli_num_rows($hasil);

$laporan = [];

while ($row = mysqli_fetch_assoc($hasil)) {
    $laporan[] = $row;
}

$namaGuru = e($_SESSION['nama'] ?? 'Guru');
$emailGuru = e($_SESSION['email'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
</head>

<body class="bg-light">

<div class="app-shell">

    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <div class="main-area">

        <!-- HEADER -->
        <header class="topbar">
            <div class="school-brand">
                <div class="brand-icon">
                    <i class="bi bi-buildings"></i>
                </div>

                <div>
                    <strong>Sistem Informasi Pelanggaran Siswa</strong>
                    <small>Panel Guru</small>
                </div>
            </div>

            <div class="topbar-right">
                <div class="user-info">
                    <div class="avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div>
                        <strong><?= $namaGuru ?></strong>
                        <small><?= $emailGuru ?></small>
                    </div>
                </div>
            </div>
        </header>

        <main class="container-fluid p-4">

            <!-- JUDUL -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                <div>
                    <h2 class="fw-bold mb-1">Laporan Pelanggaran Siswa</h2>
                    <p class="text-secondary mb-0">
                        Data seluruh laporan pelanggaran siswa.
                    </p>
                </div>

                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>

            </div>

            <a href="dashboard_guru.php" class="btn btn-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <!-- TOTAL LAPORAN -->
            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="text-secondary">Total Laporan Pelanggaran</h6>

                        <h2 class="text-success fw-bold mb-0">
                            <?= number_format($totalLaporan, 0, ',', '.') ?>
                        </h2>

                        <small class="text-secondary">
                            Data tercatat dalam sistem
                        </small>
                    </div>

                    <i class="bi bi-file-earmark-text text-primary fs-1"></i>

                </div>
            </div>

            <!-- TABEL LAPORAN -->
            <div class="card shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-table"></i>
                        Data Laporan Pelanggaran
                    </h5>

                    <div class="d-flex justify-content-between flex-wrap gap-3 mb-3">

                        <div>
                            <label class="me-2">Tampilkan</label>

                            <select id="jumlahData" class="form-select d-inline-block w-auto">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>

                            <span class="ms-2">data</span>
                        </div>

                        <div>
                            <label for="cari" class="me-2">Cari:</label>
                            <input type="text" id="cari"
                                class="form-control d-inline-block w-auto"
                                placeholder="Cari nama siswa...">
                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Pelanggaran</th>
                                    <th>Keterangan</th>
                                    <th>Nama Guru</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody id="isiTabel">

                            <?php if (empty($laporan)): ?>

                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        Belum ada laporan pelanggaran.
                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php $no = 1; ?>

                                <?php foreach ($laporan as $row): ?>

                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td class="fw-semibold">
                                            <?= e($row['nama_siswa']) ?>
                                        </td>

                                        <td><?= e($row['nama_kelas']) ?></td>

                                        <td><?= e($row['pelanggaran']) ?></td>

                                        <td><?= e($row['keterangan']) ?></td>

                                        <td><?= e($row['nama_guru']) ?></td>

                                        <td>
                                            <?php
                                            if (!empty($row['tanggal'])) {
                                                $waktu = strtotime($row['tanggal']);
                                                echo $waktu ? date('d-m-Y', $waktu) : e($row['tanggal']);
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>

                                        <td class="fw-bold text-center">
                                            <?= number_format((float)$row['poin'], 0, ',', '.') ?>
                                        </td>

                                        <td><?= e($row['tindakan']) ?></td>

                                        <td>
                                            <?php
                                            $st = strtolower(trim((string)$row['status']));
                                            $badge = 'secondary';

                                            if (in_array($st, ['selesai', 'ditindaklanjuti', 'aktif'], true)) {
                                                $badge = 'success';
                                            } elseif (in_array($st, ['tercatat', 'menunggu', 'pending'], true)) {
                                                $badge = 'warning';
                                            }
                                            ?>

                                            <span class="badge bg-<?= $badge ?>">
                                                <?= e($row['status'] ?: 'Belum diproses') ?>
                                            </span>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                            </tbody>
                        </table>

                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <small id="infoData" class="text-secondary"></small>

                        <nav>
                            <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                        </nav>

                    </div>

                </div>
            </div>

        </main>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.querySelector('#isiTabel');
    const pencarian = document.querySelector('#cari');
    const jumlah = document.querySelector('#jumlahData');
    const pagination = document.querySelector('#pagination');
    const info = document.querySelector('#infoData');

    const semuaBaris = Array.from(tabel.querySelectorAll('tr'))
        .filter(row => row.querySelectorAll('td').length > 1);

    let halaman = 1;

    function tampilkanData() {

        const keyword = pencarian.value.toLowerCase();

        const hasil = semuaBaris.filter(row =>
            row.textContent.toLowerCase().includes(keyword)
        );

        const perHalaman = parseInt(jumlah.value);
        const total = hasil.length;
        const totalHalaman = Math.ceil(total / perHalaman);

        if (halaman > totalHalaman) {
            halaman = Math.max(1, totalHalaman);
        }

        semuaBaris.forEach(row => row.style.display = 'none');

        const awal = (halaman - 1) * perHalaman;
        const akhir = awal + perHalaman;

        hasil.slice(awal, akhir).forEach(row => {
            row.style.display = '';
        });

        info.textContent = total > 0
            ? `Menampilkan ${awal + 1} sampai ${Math.min(akhir, total)} dari ${total} data`
            : 'Tidak ada data';

        pagination.innerHTML = '';

        for (let i = 1; i <= totalHalaman; i++) {

            const li = document.createElement('li');
            li.className = 'page-item' + (i === halaman ? ' active' : '');

            const button = document.createElement('button');
            button.className = 'page-link';
            button.textContent = i;

            button.addEventListener('click', function () {
                halaman = i;
                tampilkanData();
            });

            li.appendChild(button);
            pagination.appendChild(li);
        }
    }

    pencarian.addEventListener('input', function () {
        halaman = 1;
        tampilkanData();
    });

    jumlah.addEventListener('change', function () {
        halaman = 1;
        tampilkanData();
    });

    tampilkanData();
});
</script>

</body>
</html>
