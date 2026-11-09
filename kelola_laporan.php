<?php
// laporan.php
// Asumsi struktur database:
// t_pelanggaran: id, id_siswa, id_kategori, tanggal_pelanggaran, deskripsi, status
// t_siswa: id, nama
// t_kategori_pelanggaran: id, nama_kategori

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "
    SELECT
        p.id,
        s.nama AS nama_siswa,
        k.nama_kategori,
        p.deskripsi,
        p.tanggal_pelanggaran,
        p.status
    FROM t_pelanggaran AS p
    INNER JOIN t_siswa AS s ON s.id = p.id_siswa
    LEFT JOIN t_kategori_pelanggaran AS k ON k.id = p.id_kategori
    WHERE s.nama LIKE '%Rizki%'
       OR s.nama LIKE '%Aradea%'
    ORDER BY p.tanggal_pelanggaran DESC, p.id DESC
";

$hasil = mysqli_query($koneksi, $sql);
$error_query = !$hasil ? mysqli_error($koneksi) : '';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">

    <style>
        body { background: #f4f6fb; }
        main { min-height: 100vh; }
        .page-title { color: #172b4d; font-weight: 400; }
        .card { border: 1px solid #d5d9df; border-radius: 7px; }
        .table thead th { white-space: nowrap; vertical-align: middle; }
        .table tbody td { vertical-align: middle; }
        .badge-status { font-size: 12px; }
        .dataTables_wrapper { width: 100%; }
        @media (max-width: 767.98px) {
            main { padding: 18px !important; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'layout/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <h1 class="page-title mb-0">Laporan Pelanggaran Siswa</h1>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    Cetak Laporan
                </button>
            </div>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <div class="card shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="mb-1">Data Laporan Pelanggaran</h5>
                            <small class="text-muted">Laporan untuk siswa Rizki dan Aradea</small>
                        </div>
                    </div>

                    <?php if ($error_query !== ''): ?>
                        <div class="alert alert-danger">
                            <strong>Query laporan belum cocok dengan struktur database.</strong><br>
                            <?= e($error_query) ?><br>
                            Periksa nama tabel dan kolom pada bagian query di awal file laporan.php.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="tabelLaporan" class="table table-striped table-bordered table-hover align-middle w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Siswsssa</th>
                                        <th>Kategori Pelanggaran</th>
                                        <th>Deskripsi Pelanggaran</th>
                                        <th>Tanggal Pelanggaran</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil)):
                                    $status = strtolower(trim((string)($row['status'] ?? '')));
                                    $statusAktif = in_array($status, ['selesai', 'ditindaklanjuti', 'aktif'], true);
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= e($row['nama_siswa']) ?></td>
                                        <td><?= e($row['nama_kategori'] ?: 'Belum dikategorikan') ?></td>
                                        <td><?= e($row['deskripsi'] ?: '-') ?></td>
                                        <td><?= e($row['tanggal_pelanggaran'] ?: '-') ?></td>
                                        <td>
                                            <?php if ($statusAktif): ?>
                                                <span class="badge bg-success badge-status"><?= e($row['status']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark badge-status"><?= e($row['status'] ?: 'Belum diproses') ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function () {
    $('#tabelLaporan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[4, 'desc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data laporan',
            infoEmpty: 'Tidak ada data laporan',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data laporan tidak ditemukan',
            emptyTable: 'Belum ada laporan pelanggaran untuk Rizki dan Aradea',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    });
});
</script>
</body>
</html>