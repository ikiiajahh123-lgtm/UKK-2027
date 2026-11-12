
<?php
// kelola_tindakan.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = '';
$error = '';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// PROSES SIMPAN TINDAKAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_tindakan'])) {

    $id = (int)($_POST['id'] ?? 0);
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = trim($_POST['status'] ?? '');

    $status_valid = ['tercatat', 'Ditindaklanjuti', 'Selesai'];

    if ($id <= 0) {
        $error = 'ID pelanggaran tidak valid.';
    } elseif (!in_array($status, $status_valid, true)) {
        $error = 'Status tindakan tidak valid.';
    } elseif ($tindakan === '') {
        $error = 'Tindakan tidak boleh kosong.';
    } else {

        $stmt = mysqli_prepare($koneksi, "
            UPDATE t_pelanggaran_siswa
            SET tindakan = ?, status = ?
            WHERE id = ?
        ");

        if (!$stmt) {
            $error = 'Query gagal: ' . mysqli_error($koneksi);
        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $tindakan,
                $status,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                header('Location: kelola_tindakan.php?berhasil=1');
                exit;
            } else {
                $error = 'Gagal menyimpan: ' . mysqli_stmt_error($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }
}

if (isset($_GET['berhasil'])) {
    $pesan = 'Tindakan berhasil disimpan!';
}

// AMBIL DATA PELANGGARAN SISWA
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
        status
    FROM t_pelanggaran_siswa
    ORDER BY tanggal DESC, id DESC
";

$data = mysqli_query($koneksi, $query);

if (!$data) {
    die('Gagal mengambil data: ' . e(mysqli_error($koneksi)));
}

$total = mysqli_num_rows($data);

// LINK DASHBOARD
$dashboardLink = (
    isset($_SESSION['role']) && $_SESSION['role'] === 'guru'
) ? 'dashboard_guru.php' : 'dashboard.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Tindakan - SIPES</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6fc;
            color: #212529;
            font-family: Arial, sans-serif;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            min-height: 100vh;
            background: #f4f6fc;
        }

        .judul-halaman {
            font-size: 36px;
            font-weight: 400;
            color: #152238;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            margin-bottom: 0;
            font-size: 14px;
        }

        .table th {
            background: #212529 !important;
            color: white !important;
            text-align: center;
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

        .form-tindakan {
            min-width: 230px;
        }

        .form-tindakan textarea {
            min-width: 210px;
            resize: vertical;
        }

        .form-status {
            min-width: 160px;
        }

        .jumlah-data {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
        }

        .dataTables_wrapper {
            width: 100%;
        }

        .dataTables_filter input,
        .dataTables_length select {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            padding: 5px 10px !important;
        }

        .dataTables_paginate .pagination {
            margin-top: 12px;
        }

        .btn-primary {
            background-color: #0d6efd;
        }

        @media(max-width: 768px) {
            .judul-halaman {
                font-size: 28px;
            }

            .card-body {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <div class="container-fluid p-3 p-lg-4">

            <h2 class="judul-halaman">
                Kelola Tindakan
            </h2>

            <a href="<?= e($dashboardLink); ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>

            <!-- NOTIFIKASI -->
            <?php if ($pesan !== ''): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= e($pesan); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= e($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- TOTAL DATA -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-secondary">
                                Total Catatan Pelanggaran Siswa
                            </div>

                            <div class="jumlah-data">
                                <?= $total; ?>
                            </div>
                        </div>

                        <div class="bg-primary text-white rounded-3 p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TABEL TINDAKAN -->
            <div class="card shadow-sm">

                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Daftar Tindakan Siswa
                    </h5>
                </div>

                <div class="card-body bg-white">

                    <div class="table-responsive">

                        <table id="tabelTindakan"
                               class="table table-bordered table-striped table-hover align-middle w-100">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Guru Pelapor</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Keterangan</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($data)):

                                $status = strtolower(trim($row['status'] ?? ''));

                                if ($status === 'selesai') {
                                    $badge = 'bg-success';
                                    $statusLabel = 'Selesai';
                                } elseif ($status === 'ditindaklanjuti') {
                                    $badge = 'bg-primary';
                                    $statusLabel = 'Ditindaklanjuti';
                                } else {
                                    $badge = 'bg-warning text-dark';
                                    $statusLabel = 'Tercatat';
                                }
                            ?>

                                <tr>

                                    <td class="text-center">
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        <strong><?= e($row['nama_siswa']); ?></strong>
                                    </td>

                                    <td><?= e($row['nama_kelas']); ?></td>

                                    <td><?= e($row['nama_pelanggaran']); ?></td>

                                    <td><?= e($row['nama_guru']); ?></td>

                                    <td class="text-nowrap">
                                        <?= e($row['tanggal']); ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            <?= (int)$row['poin']; ?> Poin
                                        </span>
                                    </td>

                                    <td>
                                        <?= e($row['keterangan']); ?>
                                    </td>

                                    <!-- FORM TINDAKAN -->
                                    <td class="form-tindakan">

                                        <form method="POST" action="kelola_tindakan.php">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= (int)$row['id']; ?>">

                                            <textarea
                                                name="tindakan"
                                                class="form-control mb-2"
                                                rows="3"
                                                placeholder="Masukkan tindakan siswa..."
                                                required><?= e($row['tindakan']); ?></textarea>

                                            <select name="status" class="form-select form-status">

                                                <option value="tercatat"
                                                    <?= $status === 'tercatat' ? 'selected' : ''; ?>>
                                                    Tercatat
                                                </option>

                                                <option value="Ditindaklanjuti"
                                                    <?= $status === 'ditindaklanjuti' ? 'selected' : ''; ?>>
                                                    Ditindaklanjuti
                                                </option>

                                                <option value="Selesai"
                                                    <?= $status === 'selesai' ? 'selected' : ''; ?>>
                                                    Selesai
                                                </option>

                                            </select>

                                            <button type="submit"
                                                    name="simpan_tindakan"
                                                    value="1"
                                                    class="btn btn-primary btn-sm w-100 mt-2">

                                                <i class="fas fa-save me-1"></i>
                                                Simpan Tindakan

                                            </button>

                                        </form>

                                    </td>

                                    <!-- STATUS -->
                                    <td class="text-center">
                                        <span class="badge <?= $badge; ?>">
                                            <?= e($statusLabel); ?>
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="text-center">
                                        <span class="text-muted">
                                            #<?= (int)$row['id']; ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>

        </div>

    </main>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelTindakan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[5, 'desc']],
        scrollX: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada catatan pelanggaran siswa",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

});
</script>

</body>
</html>
