
<?php
// kelola_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Mengambil data tahun ajaran
$sql = "SELECT * FROM t_tahun_ajaran ORDER BY tanggal_mulai DESC";
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

    <title>Kelola Tahun Ajaran</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #101827;
            color: #ffffff;
            font-family: Arial, sans-serif;
            font-size: 15px;
        }

        a {
            text-decoration: none;
        }

        /* ================= SIDEBAR ================= */

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 88px;
            flex: 0 0 88px;
            min-height: 100vh;
            background: #1c2939;
            border-right: 1px solid #344256;

            display: flex;
            flex-direction: column;
            align-items: center;

            padding: 16px 8px;
        }

        .sidebar-logo {
            width: 54px;
            height: 54px;
            display: grid;
            place-items: center;
            border-radius: 15px;
            color: white;
            font-size: 27px;
            background: linear-gradient(135deg, #397cf6, #9853e8);
            margin-bottom: 25px;
        }

        .sidebar-caption {
            color: #718198;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .sidebar-nav {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-link {
            min-height: 65px;
            width: 100%;
            border-radius: 13px;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 6px;

            color: #aab8ca;
            font-size: 11px;
            text-align: center;
            padding: 8px 3px;
        }

        .sidebar-link i {
            font-size: 19px;
        }

        .sidebar-link:hover {
            color: white;
            background: rgba(255,255,255,.06);
        }

        .sidebar-link.active {
            color: white;
            background: linear-gradient(135deg, #397cf6, #9853e8);
            box-shadow: 0 8px 20px rgba(57,124,246,.2);
        }

        .sidebar-bottom {
            width: 100%;
            margin-top: auto;
            border-top: 1px solid #344256;
            padding-top: 14px;
        }

        .logout-link {
            color: #ff777f;
        }

        /* ================= KONTEN ================= */

        .main-area {
            flex: 1;
            min-width: 0;
        }

        .content-area {
            width: 100%;
            padding: 20px 22px;
            max-width: 1800px;
            margin: 0 auto;
        }

        /* JUDUL */

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 38px;
            font-weight: 500;
            color: #ffffff;
            margin: 0;
        }

        /* TOMBOL */

        .btn {
            font-size: 14px;
            border-radius: 5px;
            padding: 8px 12px;
        }

        .btn-primary {
            background: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background: #0b5ed7;
        }

        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-back {
            margin-bottom: 15px;
            padding: 9px 13px;
        }

        .btn-warning {
            background: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        .btn-danger {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* ================= CARD TABEL ================= */

        .table-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 24px 26px 18px;
            box-shadow: 0 2px 5px rgba(0,0,0,.15);
        }

        /* ================= TABEL ================= */

        #tabelTahunAjaran {
            width: 100% !important;
            font-size: 14px;
            color: #000000;
            border-collapse: collapse;
            vertical-align: middle;
        }

        #tabelTahunAjaran thead th {
            background: #212529 !important;
            color: #ffffff !important;
            border: 1px solid #495057 !important;
            padding: 11px 8px;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelTahunAjaran tbody td {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #dee2e6;
            padding: 10px 8px;
            vertical-align: middle;
        }

        #tabelTahunAjaran tbody tr:nth-child(odd) td {
            background-color: #f1f1f1;
        }

        #tabelTahunAjaran tbody tr:hover td {
            background-color: #e9ecef;
        }

        /* STATUS */

        .badge {
            font-size: 11px;
            padding: 5px 8px;
            border-radius: 5px;
        }

        /* ================= DATATABLES ================= */

        .dt-container {
            color: #000000;
            font-size: 14px;
        }

        .dt-container .dt-layout-row {
            margin: 0 0 10px;
        }

        .dt-search input,
        .dt-length select {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #ced4da !important;
            border-radius: 5px !important;
            padding: 5px 8px !important;
            outline: none;
        }

        .dt-search input {
            margin-left: 5px;
        }

        .dt-length select {
            margin: 0 5px;
        }

        .dt-info {
            color: #000000 !important;
            padding-top: 10px !important;
        }

        .dt-paging .dt-paging-button {
            background: #ffffff !important;
            color: #0d6efd !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0 !important;
            padding: 7px 12px !important;
        }

        .dt-paging .dt-paging-button.current {
            background: #0d6efd !important;
            color: #ffffff !important;
            border-color: #0d6efd !important;
        }

        .dt-paging .dt-paging-button:hover {
            background: #e9ecef !important;
            color: #0d6efd !important;
        }

        .dt-empty {
            color: #000000 !important;
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 768px) {

            .sidebar {
                width: 70px;
                flex-basis: 70px;
                padding: 12px 5px;
            }

            .sidebar-logo {
                width: 45px;
                height: 45px;
            }

            .sidebar-caption {
                display: none;
            }

            .sidebar-link {
                min-height: 58px;
                font-size: 9px;
            }

            .sidebar-link i {
                font-size: 17px;
            }

            .content-area {
                padding: 18px 12px;
            }

            .page-heading h1 {
                font-size: 26px;
            }

            .page-heading {
                align-items: flex-start;
                flex-wrap: wrap;
            }

            .table-card {
                padding: 12px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<div class="app-shell">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <div class="main-area">

        <main class="content-area">

            <!-- JUDUL DAN TOMBOL TAMBAH -->
            <div class="page-heading">

                <h1>Kelola Tahun Ajaran</h1>

                <a href="tambah_tahun_ajaran.php" class="btn btn-primary">
                    + Tambah Tahun Ajaran
                </a>

            </div>

            <!-- KEMBALI -->
            <a href="dashboard.php" class="btn btn-secondary btn-back">
                Kembali ke Dashboard
            </a>

            <!-- CARD TABEL -->
            <div class="table-card">

                <div class="table-responsive">

                    <table id="tabelTahunAjaran"
                        class="table table-bordered table-striped table-hover align-middle">

                        <thead>
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

                            while ($row = mysqli_fetch_assoc($hasil)) {
                            ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= htmlspecialchars($row['nama']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tanggal_mulai']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tanggal_selesai']); ?>
                                </td>

                                <td>
                                    <?php if ((int)$row['status_aktif'] === 1) { ?>

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-secondary">
                                            Tidak Aktif
                                        </span>

                                    <?php } ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['created_at'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['updated_at'] ?? '-'); ?>
                                </td>

                                <td class="text-nowrap">

                                    <a href="edit_tahun_ajaran.php?id=<?= urlencode((string)$row['id']); ?>"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <a href="hapus_tahun_ajaran.php?id=<?= urlencode((string)$row['id']); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?');">
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

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
