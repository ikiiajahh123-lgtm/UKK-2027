
<?php
// kelola_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ======================================
// AMBIL DATA TAHUN AJARAN
// ======================================

$sql = "SELECT * FROM t_tahun_ajaran ORDER BY tanggal_mulai DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// ======================================
// KEAMANAN OUTPUT
// ======================================

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Tahun Ajaran - SIPES</title>

    <!-- BOOTSTRAP 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- DATATABLES BOOTSTRAP 5 -->
    <link
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>

<body class="bg-dark text-white">

<!-- ====================================== -->
<!-- PEMBUNGKUS HALAMAN -->
<!-- ====================================== -->

<div class="d-flex min-vh-100">

    <!-- ====================================== -->
    <!-- SIDEBAR -->
    <!-- ====================================== -->

    <aside
        class="bg-dark flex-shrink-0 min-vh-100"
        style="width:230px; min-width:230px;">

        <?php include __DIR__ . '/layout/sidebar.php'; ?>

    </aside>


    <!-- ====================================== -->
    <!-- KONTEN UTAMA -->
    <!-- ====================================== -->

    <main
        class="flex-grow-1 p-3 p-md-4"
        style="min-width:0;">

        <!-- JUDUL HALAMAN -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <h2 class="fw-bold text-white mb-0">
                Kelola Tahun Ajaran
            </h2>

            <a href="tambah_tahun_ajaran.php" class="btn btn-primary">

                <i class="fas fa-plus me-1"></i>
                Tambah Tahun Ajaran

            </a>

        </div>


        <!-- TOMBOL KEMBALI -->
        <div class="mb-4">

            <a href="dashboard.php" class="btn btn-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Dashboard

            </a>

        </div>


        <!-- ====================================== -->
        <!-- CARD DATA TAHUN AJARAN -->
        <!-- ====================================== -->

        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-body p-3 p-md-4">

                <!-- TABEL RESPONSIVE -->
                <div class="table-responsive">

                    <table
                        id="tabelTahunAjaran"
                        class="table table-bordered table-striped table-hover align-middle w-100 mb-0"
                    >

                        <!-- HEADER TABEL -->
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


                        <!-- ISI TABEL -->
                        <tbody>

                            <?php
                            $no = 1;

                            if (mysqli_num_rows($hasil) > 0):

                                while ($row = mysqli_fetch_assoc($hasil)):
                            ?>

                                <tr>

                                    <!-- NOMOR -->
                                    <td>
                                        <?= $no++; ?>
                                    </td>


                                    <!-- NAMA TAHUN AJARAN -->
                                    <td>
                                        <strong>
                                            <?= e($row['nama']); ?>
                                        </strong>
                                    </td>


                                    <!-- TANGGAL MULAI -->
                                    <td class="text-nowrap">
                                        <?= e($row['tanggal_mulai']); ?>
                                    </td>


                                    <!-- TANGGAL SELESAI -->
                                    <td class="text-nowrap">
                                        <?= e($row['tanggal_selesai']); ?>
                                    </td>


                                    <!-- STATUS AKTIF -->
                                    <td>

                                        <?php if ((int)$row['status_aktif'] === 1): ?>

                                            <span class="badge bg-success">
                                                Aktif
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">
                                                Tidak Aktif
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- TANGGAL DIBUAT -->
                                    <td class="text-nowrap">
                                        <?= e($row['created_at'] ?? '-'); ?>
                                    </td>


                                    <!-- TANGGAL DIUBAH -->
                                    <td class="text-nowrap">
                                        <?= e($row['updated_at'] ?? '-'); ?>
                                    </td>


                                    <!-- AKSI -->
                                    <td class="text-nowrap">

                                        <a
                                            href="edit_tahun_ajaran.php?id=<?= urlencode((string)$row['id']); ?>"
                                            class="btn btn-warning btn-sm"
                                        >

                                            <i class="fas fa-edit me-1"></i>
                                            Edit

                                        </a>


                                        <a
                                            href="hapus_tahun_ajaran.php?id=<?= urlencode((string)$row['id']); ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?');"
                                        >

                                            <i class="fas fa-trash me-1"></i>
                                            Hapus

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


<!-- ====================================== -->
<!-- JAVASCRIPT -->
<!-- ====================================== -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


<!-- ====================================== -->
<!-- DATATABLES -->
<!-- ====================================== -->

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
