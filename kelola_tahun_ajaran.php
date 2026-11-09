
<?php
// kelola_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// AMBIL DATA
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

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet">

    <!-- CSS UTAMA -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        /* TAMPILAN SAMA DENGAN KELOLA SISWA */

        body {
            background-color: #f4f6fb;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        main.content-area {
            min-width: 0;
            padding: 24px !important;
        }

        /* JUDUL HALAMAN */
        main.content-area h1 {
            font-size: 32px;
            font-weight: 500;
            color: #212529;
            margin: 0;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 8px;
        }

        .card-body {
            padding: 20px;
        }

        /* TABEL */
        #tabelTahunAjaran {
            width: 100% !important;
            font-size: 14px;
            vertical-align: middle;
        }

        #tabelTahunAjaran thead th {
            background-color: #212529 !important;
            color: white !important;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelTahunAjaran th,
        #tabelTahunAjaran td {
            padding: 10px;
            vertical-align: middle;
        }

        /* STATUS */
        .badge {
            font-size: 12px;
            padding: 6px 9px;
        }

        /* DATATABLES */
        .dt-search input,
        .dt-length select {
            border: 1px solid #ced4da !important;
            border-radius: 5px !important;
            padding: 5px 8px !important;
        }

        .dataTables_wrapper {
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            main.content-area {
                padding: 15px !important;
            }

            main.content-area h1 {
                font-size: 24px;
            }
        }
    </style>

</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR SAMA DENGAN KELOLA SISWA -->
            <?php include 'layout/sidebar.php'; ?>

            <!-- KONTEN -->
            <main class="col-md-9 col-lg-10 p-4 content-area">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                    <h1>Kelola Tahun Ajaran</h1>

                    <a href="tambah_tahun_ajaran.php" class="btn btn-primary">
                        + Tambah Tahun Ajaran
                    </a>

                </div>

                <!-- KEMBALI -->
                <a href="dashboard.php" class="btn btn-secondary mb-3">
                    Kembali ke Dashboard
                </a>

                <!-- CARD TABEL -->
                <div class="card shadow-sm">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="tabelTahunAjaran"
                                class="table table-striped table-bordered table-hover align-middle w-100">

                                <thead class="table-dark">

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

                                            <td>
                                                <?= $no++; ?>
                                            </td>

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

                </div>

            </main>

        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <!-- DataTables Bootstrap 5 -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {

            $('#tabelTahunAjaran').DataTable({

                pageLength: 10,

                lengthMenu: [5, 10, 25, 50, 100],

                order: [[1, 'asc']],

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
