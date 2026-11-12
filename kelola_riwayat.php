
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Keamanan output
function aman($nilai) {
    return htmlspecialchars((string)($nilai ?? ''), ENT_QUOTES, 'UTF-8');
}

// Format tanggal Indonesia
function tanggalIndonesia($tanggal) {
    if (empty($tanggal) || $tanggal === '0000-00-00') {
        return '-';
    }

    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];

    $pecah = explode('-', $tanggal);

    if (count($pecah) !== 3) {
        return '-';
    }

    $nomorBulan = (int)$pecah[1];

    if ($nomorBulan < 1 || $nomorBulan > 12) {
        return '-';
    }

    return (int)$pecah[2] . ' ' .
           $bulan[$nomorBulan] . ' ' .
           $pecah[0];
}

// Ambil data riwayat
$query = "
    SELECT
        id,
        nama_siswa,
        nama_kelas,
        nama_pelanggaran,
        nama_guru,
        tanggal,
        keterangan,
        poin,
        tindakan,
        status,
        created_at,
        updated_at
    FROM t_pelanggaran_siswa
    ORDER BY tanggal DESC, id DESC
";

$result = mysqli_query($koneksi, $query);

$error = '';

if (!$result) {
    $error = "Gagal mengambil data riwayat: " . mysqli_error($koneksi);
}

$totalRiwayat = $result ? mysqli_num_rows($result) : 0;

// Dashboard sesuai role
$dashboardLink = (
    isset($_SESSION['role']) && $_SESSION['role'] === 'guru'
) ? 'dashboard_guru.php' : 'dashboard.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pelanggaran Siswa - SIPES</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            min-width: 0;
            min-height: 100vh;
            background-color: #f4f6fc !important;
            color: #212529 !important;
        }

        .judul-halaman {
            color: #152238 !important;
            font-size: 36px;
            font-weight: 400;
        }

        .card {
            background: #ffffff !important;
            color: #212529;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .card-body {
            background-color: #ffffff;
            color: #212529;
        }

        .table {
            color: #212529;
            font-size: 14px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table thead th {
            background-color: #212529 !important;
            color: #ffffff !important;
            white-space: nowrap;
        }

        .table-striped tbody tr:nth-of-type(odd) > * {
            background-color: #f1f1f1;
        }

        .table-hover tbody tr:hover > * {
            background-color: #e8f0ff;
        }

        .table-responsive {
            width: 100%;
        }

        .alert-light {
            background-color: #ffffff !important;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        @media (max-width: 768px) {
            .judul-halaman {
                font-size: 28px;
            }

            main.main-content {
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid p-0">

    <div class="d-flex min-vh-100">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="flex-grow-1 main-content p-3 p-lg-4">

            <!-- JUDUL HALAMAN -->
            <h2 class="judul-halaman mb-3">
                Riwayat Pelanggaran Siswa
            </h2>

            <!-- TOMBOL KEMBALI -->
            <a href="<?= aman($dashboardLink); ?>" class="btn btn-secondary mb-3">
                ← Kembali ke Dashboard
            </a>

            <?php if ($error !== ''): ?>

                <div class="alert alert-danger">
                    <?= aman($error); ?>
                </div>

            <?php else: ?>

                <!-- CARD RIWAYAT -->
                <div class="card shadow-sm">

                    <div class="card-body p-3 p-lg-4">

                        <!-- INFORMASI TOTAL -->
                        <div class="alert alert-light border mb-4">

                            <div class="text-dark">
                                Total Riwayat Pelanggaran
                            </div>

                            <h3 class="text-success fw-bold mb-0">
                                <?= $totalRiwayat; ?>
                            </h3>

                            <span class="text-dark">
                                Data tercatat dalam sistem
                            </span>

                        </div>

                        <!-- FILTER DAN PENCARIAN -->
                        <div class="row align-items-center mb-4">

                            <div class="col-md-6 mb-2 mb-md-0">

                                <div class="d-flex align-items-center gap-2">

                                    <label for="filterStatus" class="text-dark text-nowrap">
                                        Filter Status:
                                    </label>

                                    <select id="filterStatus" class="form-select w-auto">

                                        <option value="">Semua Status</option>

                                        <option value="tercatat">
                                            Tercatat
                                        </option>

                                        <option value="ditindaklanjuti">
                                            Ditindaklanjuti
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="d-flex align-items-center justify-content-md-end gap-2">

                                    <label for="cariRiwayat" class="text-dark">
                                        Cari:
                                    </label>

                                    <input
                                        type="text"
                                        id="cariRiwayat"
                                        class="form-control"
                                        style="max-width: 240px;"
                                        placeholder="Cari nama siswa..."
                                    >

                                </div>

                            </div>

                        </div>

                        <!-- TABEL RIWAYAT -->
                        <div class="table-responsive">

                            <table
                                class="table table-bordered table-striped table-hover align-middle mb-0"
                                id="tabelRiwayat"
                            >

                                <thead class="table-dark">

                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Pelanggaran</th>
                                        <th>Nama Guru</th>
                                        <th>Tanggal</th>
                                        <th>Poin</th>
                                        <th>Tindakan</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                <?php if ($totalRiwayat > 0): ?>

                                    <?php $no = 1; ?>

                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                        <?php
                                        $statusAsli = trim($row['status'] ?? '');
                                        $statusLower = strtolower($statusAsli);

                                        if ($statusLower === 'tercatat') {
                                            $statusClass = 'bg-warning text-dark';
                                        } elseif ($statusLower === 'ditindaklanjuti') {
                                            $statusClass = 'bg-success text-white';
                                        } else {
                                            $statusClass = 'bg-secondary text-white';
                                        }
                                        ?>

                                        <tr data-status="<?= aman($statusLower); ?>">

                                            <td>
                                                <?= $no++; ?>
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
                                                <?= aman($row['nama_pelanggaran']); ?>

                                                <?php if (!empty($row['keterangan'])): ?>

                                                    <br>

                                                    <small class="text-muted">
                                                        <?= aman($row['keterangan']); ?>
                                                    </small>

                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?= aman($row['nama_guru']); ?>
                                            </td>

                                            <td class="text-nowrap">
                                                <?= aman(tanggalIndonesia($row['tanggal'])); ?>
                                            </td>

                                            <td class="text-center">
                                                <strong>
                                                    <?= (int)$row['poin']; ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?= aman($row['tindakan']); ?>
                                            </td>

                                            <td>
                                                <span class="badge <?= $statusClass; ?>">
                                                    <?= aman($statusAsli); ?>
                                                </span>
                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            Belum ada riwayat pelanggaran siswa.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                                </tbody>

                            </table>

                            <div id="hasilKosong" class="alert alert-warning mt-3 d-none">
                                Data riwayat tidak ditemukan.
                            </div>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </main>

    </div>

</div>

<!-- JAVASCRIPT BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- FILTER DAN PENCARIAN -->
<script>

const inputCari = document.getElementById('cariRiwayat');
const filterStatus = document.getElementById('filterStatus');
const tabel = document.getElementById('tabelRiwayat');
const hasilKosong = document.getElementById('hasilKosong');

function filterRiwayat() {

    if (!tabel) return;

    const kataKunci = inputCari.value.toLowerCase().trim();
    const statusDipilih = filterStatus.value.toLowerCase();

    const baris = tabel.querySelectorAll('tbody tr');

    let jumlahTampil = 0;

    baris.forEach(function(row) {

        const teks = row.textContent.toLowerCase();
        const status = row.dataset.status || '';

        const cocokCari = teks.includes(kataKunci);

        const cocokStatus =
            statusDipilih === '' || status === statusDipilih;

        if (cocokCari && cocokStatus) {

            row.classList.remove('d-none');
            jumlahTampil++;

        } else {

            row.classList.add('d-none');

        }

    });

    if (hasilKosong) {
        hasilKosong.classList.toggle('d-none', jumlahTampil !== 0);
    }

}

if (inputCari && filterStatus) {

    inputCari.addEventListener('input', filterRiwayat);

    filterStatus.addEventListener('change', filterRiwayat);

}

</script>

</body>
</html>
