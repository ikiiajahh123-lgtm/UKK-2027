
<?php
// kelola_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

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

    <title>Kelola Wali Kelas</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- DataTables -->
    <link
        href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="layout/style.css">

    <style>
        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        main.content-area {
            min-width: 0;
            padding: 20px 18px !important;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px;
        }

        .page-header h1 {
            font-size: 30px;
            font-weight: 400;
            margin: 0;
            color: #182338;
        }

        .btn-add {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-add:hover {
            background: #0b5ed7;
            color: white;
        }

        .btn-back {
            display: inline-block;
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 7px 11px;
            border-radius: 5px;
            margin-bottom: 12px;
        }

        .btn-back:hover {
            background: #5c636a;
            color: white;
        }

        .table-card {
            background: white;
            padding: 18px 22px 14px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .06);
            width: 100%;
        }

        #tabelWaliKelas {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        #tabelWaliKelas thead th {
            background: #212529 !important;
            color: white !important;
            font-weight: bold;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelWaliKelas th,
        #tabelWaliKelas td {
            border: 1px solid #dee2e6;
            padding: 7px 8px;
            vertical-align: middle;
        }

        #tabelWaliKelas tbody tr:hover {
            background: #e8f1ff;
        }

        .badge-status {
            display: inline-block;
            font-size: 10px;
            font-weight: bold;
            color: white;
            padding: 3px 6px;
            border-radius: 4px;
        }

        .status-aktif {
            background: #198754;
        }

        .status-nonaktif {
            background: #dc3545;
        }

        .btn-edit,
        .btn-delete {
            border: none;
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-edit:hover {
            background: #e0a800;
            color: #212529;
        }

        .btn-delete:hover {
            background: #bb2d3b;
            color: white;
        }

        .dataTables_wrapper {
            font-size: 12px;
        }

        .dt-search input,
        .dt-length select {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            padding: 5px 8px !important;
            margin-bottom: 8px;
        }

        .dt-paging .dt-paging-button {
            padding: 4px 10px !important;
        }

        .dt-info {
            margin-top: 8px;
        }

        @media (max-width: 768px) {
            main.content-area {
                padding: 15px 10px !important;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .table-card {
                padding: 12px 8px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 content-area">

            <div class="page-header">
                <h1>Kelola Wali Kelas</h1>

                <a href="tambah_walikelas.php" class="btn-add">
                    + Tambah Wali Kelas
                </a>
            </div>

            <a href="dashboard.php" class="btn-back">
                Kembali ke Dashboard
            </a>

            <div class="table-card">
                <div class="table-responsive">

                    <table
                        id="tabelWaliKelas"
                        class="table table-striped table-bordered"
                    >
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
                                    <?= htmlspecialchars((string)$row['tanggal_mulai']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)$row['tanggal_selesai']) ?>
                                </td>

                                <td>
                                    <?php if ((int)$row['status_aktif'] === 1) { ?>
                                        <span class="badge-status status-aktif">
                                            Aktif
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge-status status-nonaktif">
                                            Tidak Aktif
                                        </span>
                                    <?php } ?>
                                </td>

                                <td class="text-nowrap">
                                    <a
                                        href="edit_walikelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                        class="btn-edit"
                                    >Edit</a>

                                    <a
                                        href="hapus_walikelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');"
                                    >Hapus</a>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelWaliKelas').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [],
        language: {
            lengthMenu: 'Tampilkan _MENU_ data',
            search: 'Cari:',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data wali kelas',
            infoEmpty: 'Tidak ada data wali kelas',
            zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Belum ada data wali kelas',
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
