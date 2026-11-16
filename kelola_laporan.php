
<?php
// kelola_laporan.php

require_once __DIR__ . '/includes/cek_session.php';
require_once __DIR__ . '/config/koneksi.php';

// ======================================
// FUNGSI KEAMANAN OUTPUT
// ======================================

function e($value) {
    return htmlspecialchars(
        (string)($value ?? '-'),
        ENT_QUOTES,
        'UTF-8'
    );
}

// ======================================
// CEK KOLOM DATABASE
// ======================================

$kolom = [];

$cekKolom = mysqli_query(
    $koneksi,
    "SHOW COLUMNS FROM t_pelanggaran_siswa"
);

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

// Kolom utama
$namaSiswa = pilihKolom(
    ['nama_siswa'],
    $kolom,
    'nama_siswa'
);

$namaKelas = pilihKolom(
    ['nama_kelas'],
    $kolom,
    'nama_kelas'
);

$tanggal = pilihKolom(
    ['tanggal', 'tanggal_pelanggaran'],
    $kolom,
    'tanggal'
);

$poin = pilihKolom(
    ['poin'],
    $kolom,
    'poin'
);

// Kolom tambahan
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

// ======================================
// DATA GURU
// ======================================

$namaGuru = e($_SESSION['nama'] ?? 'Guru');
$emailGuru = e($_SESSION['email'] ?? '');

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laporan Pelanggaran Siswa | SIPES</title>

    <!-- BOOTSTRAP 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- BOOTSTRAP ICONS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

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

        /* KONTEN UTAMA */
        .main-content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            background-color: #f4f6fc !important;
            color: #212529 !important;
        }

        /* JUDUL */
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
            background-color: #ffffff !important;
            color: #212529 !important;
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
        .ikon-laporan {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        /* INPUT */
        .form-control,
        .form-select {
            border-color: #ced4da;
        }

        /* PAGINATION */
        .pagination .page-link {
            color: #0d6efd;
        }

        .pagination .active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
        }

        /* CETAK */
        @media print {

            body {
                background: #ffffff !important;
            }

            aside,
            .no-print,
            #pagination,
            #infoData {
                display: none !important;
            }

            .main-content {
                width: 100% !important;
                background: #ffffff !important;
                padding: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table thead th {
                background: #212529 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

        }

        @media (max-width: 768px) {

            .judul-halaman {
                font-size: 28px;
            }

            main.main-content {
                padding: 15px !important;
            }

            .pencarian {
                width: 100% !important;
            }

        }

    </style>

</head>

<body>

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="main-content p-3 p-lg-4">

        <!-- JUDUL HALAMAN -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

            <div>

                <h2 class="judul-halaman mb-2">
                    Laporan Pelanggaran Siswa
                </h2>

                <p class="deskripsi mb-0">
                    Informasi dan laporan seluruh pelanggaran siswa.
                </p>

            </div>

            <button
                onclick="window.print()"
                class="btn btn-primary no-print"
            >
                <i class="bi bi-printer me-1"></i>
                Cetak Laporan
            </button>

        </div>

        <!-- TOMBOL KEMBALI -->
        <div class="mb-4 no-print">

            <a href="dashboard_guru.php" class="btn btn-secondary">

                <i class="bi bi-arrow-left me-1"></i>

                Kembali ke Dashboard

            </a>

        </div>

        <!-- TOTAL LAPORAN -->
        <div class="card shadow-sm mb-4">

            <div class="card-body p-3 p-md-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h6 class="text-secondary mb-2">
                            Total Laporan Pelanggaran
                        </h6>

                        <h2 class="text-success fw-bold mb-1">
                            <?= number_format($totalLaporan, 0, ',', '.'); ?>
                        </h2>

                        <small class="text-secondary">
                            Data tercatat dalam sistem
                        </small>

                    </div>

                    <div class="ikon-laporan">
                        <i class="bi bi-file-earmark-text fs-2"></i>
                    </div>

                </div>

            </div>

        </div>

        <!-- TABEL LAPORAN -->
        <div class="card shadow-sm">

            <div class="card-body p-3 p-md-4">

                <!-- JUDUL TABEL -->
                <h5 class="fw-bold mb-4 text-dark">

                    <i class="bi bi-table me-2"></i>

                    Data Laporan Pelanggaran

                </h5>

                <!-- PENCARIAN DAN JUMLAH DATA -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3 no-print">

                    <div>

                        <label for="jumlahData" class="me-2 text-dark">
                            Tampilkan
                        </label>

                        <select
                            id="jumlahData"
                            class="form-select form-select-sm d-inline-block w-auto"
                        >

                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>

                        </select>

                        <span class="ms-2 text-dark">
                            data
                        </span>

                    </div>

                    <div class="d-flex align-items-center gap-2">

                        <label for="cari" class="text-dark">
                            Cari:
                        </label>

                        <input
                            type="text"
                            id="cari"
                            class="form-control form-control-sm pencarian"
                            style="width: 220px;"
                            placeholder="Cari nama siswa..."
                        >

                    </div>

                </div>

                <!-- TABEL RESPONSIVE -->
                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped table-hover align-middle mb-0"
                        id="tabelLaporan"
                    >

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
                                    <td colspan="10" class="text-center py-5">

                                        <i class="bi bi-inbox fs-1 text-secondary"></i>

                                        <p class="text-secondary mb-0 mt-2">
                                            Belum ada laporan pelanggaran.
                                        </p>

                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php $no = 1; ?>

                                <?php foreach ($laporan as $row): ?>

                                    <?php

                                    $st = strtolower(
                                        trim((string)($row['status'] ?? ''))
                                    );

                                    $badge = 'secondary';

                                    if (
                                        in_array(
                                            $st,
                                            ['selesai', 'ditindaklanjuti', 'aktif'],
                                            true
                                        )
                                    ) {

                                        $badge = 'success';

                                    } elseif (
                                        in_array(
                                            $st,
                                            ['tercatat', 'menunggu', 'pending'],
                                            true
                                        )
                                    ) {

                                        $badge = 'warning';

                                    }

                                    ?>

                                    <tr>

                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td class="fw-semibold">
                                            <?= e($row['nama_siswa']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_kelas']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['pelanggaran']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['keterangan']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_guru']); ?>
                                        </td>

                                        <td class="text-nowrap">

                                            <?php

                                            if (!empty($row['tanggal'])) {

                                                $waktu = strtotime($row['tanggal']);

                                                echo $waktu
                                                    ? date('d-m-Y', $waktu)
                                                    : e($row['tanggal']);

                                            } else {

                                                echo '-';

                                            }

                                            ?>

                                        </td>

                                        <td class="fw-bold text-center">
                                            <?= number_format((float)$row['poin'], 0, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <?= e($row['tindakan']); ?>
                                        </td>

                                        <td>

                                            <span class="badge bg-<?= $badge; ?>">

                                                <?= e(
                                                    !empty($row['status'])
                                                        ? $row['status']
                                                        : 'Belum diproses'
                                                ); ?>

                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <!-- PESAN DATA TIDAK DITEMUKAN -->
                <div
                    id="pesanKosong"
                    class="alert alert-warning mt-3 d-none"
                >
                    Data laporan tidak ditemukan.
                </div>

                <!-- INFORMASI DAN PAGINATION -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 no-print">

                    <small id="infoData" class="text-secondary"></small>

                    <nav>

                        <ul
                            class="pagination pagination-sm mb-0"
                            id="pagination"
                        ></ul>

                    </nav>

                </div>

            </div>

        </div>

    </main>

</div>

<!-- BOOTSTRAP JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- PENCARIAN DAN PAGINATION -->
<script>

document.addEventListener('DOMContentLoaded', function () {

    const tabel = document.querySelector('#isiTabel');

    const pencarian = document.querySelector('#cari');

    const jumlah = document.querySelector('#jumlahData');

    const pagination = document.querySelector('#pagination');

    const info = document.querySelector('#infoData');

    const pesanKosong = document.querySelector('#pesanKosong');

    const semuaBaris = Array.from(
        tabel.querySelectorAll('tr')
    ).filter(row => row.querySelectorAll('td').length > 1);

    let halaman = 1;

    function tampilkanData() {

        const keyword = pencarian.value.toLowerCase().trim();

        const hasil = semuaBaris.filter(row =>
            row.textContent.toLowerCase().includes(keyword)
        );

        const perHalaman = parseInt(jumlah.value);

        const total = hasil.length;

        const totalHalaman = Math.ceil(total / perHalaman);

        if (halaman > totalHalaman) {
            halaman = Math.max(1, totalHalaman);
        }

        semuaBaris.forEach(row => {
            row.style.display = 'none';
        });

        const awal = (halaman - 1) * perHalaman;

        const akhir = awal + perHalaman;

        hasil.slice(awal, akhir).forEach((row, index) => {

            row.style.display = '';

            row.cells[0].textContent = awal + index + 1;

        });

        // Informasi jumlah data
        if (total > 0) {

            info.textContent =
                `Menampilkan ${awal + 1} sampai ${Math.min(akhir, total)} dari ${total} data`;

        } else {

            info.textContent = 'Tidak ada data';

        }

        // Pesan kosong
        pesanKosong.classList.toggle(
            'd-none',
            total > 0
        );

        // Pagination
        pagination.innerHTML = '';

        for (let i = 1; i <= totalHalaman; i++) {

            const li = document.createElement('li');

            li.className =
                'page-item' + (i === halaman ? ' active' : '');

            const button = document.createElement('button');

            button.type = 'button';

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

    // Pencarian
    pencarian.addEventListener('input', function () {

        halaman = 1;

        tampilkanData();

    });

    // Jumlah data
    jumlah.addEventListener('change', function () {

        halaman = 1;

        tampilkanData();

    });

    // Tampilkan data pertama kali
    tampilkanData();

});

</script>

</body>
</html>
