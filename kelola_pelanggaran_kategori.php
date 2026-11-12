
<?php
// kelola_pelanggaran_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data kategori pelanggaran
$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id DESC";
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

    <title>Kelola Kategori Pelanggaran</title>

    <!-- Bootstrap sama dengan Kelola Siswa -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables sama dengan Kelola Siswa -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- CSS utama -->
    <link rel="stylesheet" href="layout/style.css">
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
                <h1>Kelola Kategori Pelanggaran</h1>

                <a href="tambah_pelanggaran_kategori.php" class="btn btn-primary">
                    + Tambah Kategori
                </a>
            </div>

            <!-- KEMBALI KE DASHBOARD -->
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <!-- TABEL KATEGORI -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelKategori" class="table table-striped table-bordered table-hover align-middle w-100">

                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Status Aktif</th>
                                    <th>Dibuat</th>
                                    <th>Diubah</th>
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
                                            <?= htmlspecialchars((string)$row['nama']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['deskripsi'] ?? '-')) ?>
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

                                        <td>
                                            <?= htmlspecialchars((string)($row['created_at'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['updated_at'] ?? '-')) ?>
                                        </td>

                                        <td class="text-nowrap">

                                            <a
                                                href="edit_pelanggaran_kategori.php?id=<?= urlencode((string)$row['id']) ?>"
                                                class="btn btn-warning btn-sm"
                                            >
                                                Edit
                                            </a>

                                            <a
                                                href="hapus_pelanggaran_kategori.php?id=<?= urlencode((string)$row['id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus kategori pelanggaran ini?');"
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

    $('#tabelKategori').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [],

        scrollX: true,

        language: {
            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data kategori",

            infoEmpty: "Tidak ada data kategori",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data kategori tidak ditemukan",

            emptyTable: "Belum ada data kategori pelanggaran",

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
