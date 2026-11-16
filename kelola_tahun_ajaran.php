
<?php
// kelola_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data tahun ajaran
$sql = "SELECT * FROM t_tahun_ajaran ORDER BY tanggal_mulai DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Keamanan output
function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
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
<<<<<<< HEAD

<body style="background-color: #0f172a; color: white;">
=======
<body>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
<<<<<<< HEAD
    <aside
        class="bg-dark flex-shrink-0 min-vh-100"
        style="width:230px; min-width:230px;"
    >

        <?php include __DIR__ . '/layout/sidebar.php'; ?>

    </aside>


    <!-- KONTEN UTAMA -->
    <main class="flex-grow-1 p-3 p-md-4" style="min-width:0;">

        <!-- JUDUL HALAMAN -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <h2 class="text-white mb-0 fw-normal">
=======
    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="flex-grow-1 p-3 p-md-4" style="min-width:0;">

        <!-- HEADER PUTIH -->
        <div class="header-halaman d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <h2 class="fw-bold mb-0">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                Kelola Tahun Ajaran
            </h2>

            <a href="tambah_tahun_ajaran.php" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                 Tambah Tahun Ajaran
            </a>

        </div>

        <!-- TOMBOL KEMBALI -->
<<<<<<< HEAD
        <div class="mb-3">

=======
        <div class="mb-4">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>
        </div>

<<<<<<< HEAD

        <!-- CARD DATA TAHUN AJARAN -->
        <div class="card border rounded-2 shadow-sm">
=======
        <!-- CARD DATA TAHUN AJARAN -->
        <div class="card border-0 shadow-sm rounded-3">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

            <div class="card-body p-3 p-md-4">

                <div class="table-responsive">

                    <table
                        id="tabelTahunAjaran"
                        class="table table-bordered table-striped table-hover align-middle w-100 mb-0"
                    >

<<<<<<< HEAD
                        <thead class="table-light">

=======
                        <thead class="table-dark">
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                            <tr>
                                <th>No.</th>
                                <th>Nama Tahun Ajaran</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status Aktif</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;

                            if (mysqli_num_rows($hasil) > 0):
                                while ($row = mysqli_fetch_assoc($hasil)):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <strong><?= e($row['nama']); ?></strong>
                                    </td>

<<<<<<< HEAD
                                    <td>
                                        <strong>
                                            <?= e($row['nama']); ?>
                                        </strong>
                                    </td>

=======
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                                    <td class="text-nowrap">
                                        <?= e($row['tanggal_mulai']); ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= e($row['tanggal_selesai']); ?>
                                    </td>

                                    <td>
                                        <?php if ((int)$row['status_aktif'] === 1): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= e($row['created_at'] ?? '-'); ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <?= e($row['updated_at'] ?? '-'); ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <a
                                            href="edit_tahun_ajaran.php?id=<?= urlencode((string)$row['id']); ?>"
                                            class="btn btn-warning btn-sm"
                                        >
<<<<<<< HEAD
                                            <i class="fas fa-edit me-1"></i>
                                            Edit
=======
                                            <i class="fas fa-edit me-1"></i> Edit
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                                        </a>

                                        <a
                                            href="hapus_tahun_ajaran.php?id=<?= urlencode((string)$row['id']); ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?');"
                                        >
<<<<<<< HEAD
                                            <i class="fas fa-trash me-1"></i>
                                            Hapus
=======
                                            <i class="fas fa-trash me-1"></i> Hapus
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Belum ada data tahun ajaran.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

    </main>
</div>

<<<<<<< HEAD

<!-- JAVASCRIPT -->

=======
<!-- JAVASCRIPT -->
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<<<<<<< HEAD

<!-- DATATABLES -->

=======
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
<script>
$(document).ready(function () {

    $('#tabelTahunAjaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[2, 'desc']],
        scrollX: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data tahun ajaran",
            infoEmpty: "Tidak ada data tahun ajaran",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tahun ajaran tidak ditemukan",
            emptyTable: "Belum ada data tahun ajaran",

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