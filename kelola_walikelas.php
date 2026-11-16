
<?php
// kelola_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data wali kelas
$sql = "SELECT
            w.*,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
        FROM t_wali_kelas w
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY w.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
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
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="p-4 flex-grow-1" style="min-width: 0; flex-basis: 0;">

            <!-- JUDUL HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Kelola Wali Kelas</h1>

                <a href="tambah_walikelas.php" class="btn btn-primary">
                    + Tambah Wali Kelas
                </a>
            </div>

            <!-- KEMBALI KE DASHBOARD -->
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <!-- TABEL DATA WALI KELAS -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelWaliKelas" class="table table-striped table-bordered table-hover align-middle w-100">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tahun Ajaran ID</th>
                                    <th>Kelas</th>
                                    <th>Guru</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                                    <tr>

                                        <td>
                                            <?= htmlspecialchars((string)$row['id']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)$row['tahun_ajaran_id']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['nama_kelas'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['nama_guru'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['tanggal_mulai'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['tanggal_selesai'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?php if ((int)$row['status_aktif'] === 1) { ?>

                                                <span class="badge bg-success">
                                                    Aktif
                                                </span>

                                            <?php } else { ?>

                                                <span class="badge bg-danger">
                                                    Tidak Aktif
                                                </span>

                                            <?php } ?>
                                        </td>

                                        <td class="text-nowrap">

                                            <a
                                                href="edit_walikelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>

                                            <a
                                                href="hapus_walikelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');"
                                            >
                                                Hapus
                                            </a>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelWaliKelas').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [],

        scrollX: true,

        language: {
            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data wali kelas",

            infoEmpty: "Tidak ada data wali kelas",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data wali kelas tidak ditemukan",

            emptyTable: "Belum ada data wali kelas",

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
