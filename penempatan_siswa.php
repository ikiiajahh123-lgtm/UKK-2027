
<?php
// penempatan_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil daftar siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil daftar tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Ambil daftar kelas
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil data penempatan siswa
$sql = "SELECT
            ks.id,
            s.nis,
            s.nama AS nama_siswa,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            ks.tanggal_mulai,
            ks.tanggal_selesai,
            ks.status_aktif
        FROM t_kelas_siswa ks
        LEFT JOIN t_siswa s ON ks.siswa_id = s.id
        LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON ks.kelas_id = k.id
        ORDER BY ks.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$siswa || !$tahun || !$kelas || !$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penempatan Siswa</title>

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

        .btn-back {
            display: inline-block;
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 7px 11px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .btn-back:hover {
            background: #5c636a;
            color: white;
        }

        .content-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .06);
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 6px;
        }

        .btn-save {
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 18px;
        }

        .btn-save:hover {
            background: #0b5ed7;
            color: white;
        }

        #tabelPenempatan {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        #tabelPenempatan thead th {
            background: #212529 !important;
            color: white !important;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelPenempatan th,
        #tabelPenempatan td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: middle;
        }

        #tabelPenempatan tbody tr:hover {
            background: #e8f1ff;
        }

        .badge-status {
            display: inline-block;
            font-size: 10px;
            font-weight: bold;
            color: white;
            padding: 4px 7px;
            border-radius: 4px;
        }

        .status-aktif {
            background: #198754;
        }

        .status-nonaktif {
            background: #dc3545;
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
                font-size: 24px;
            }

            .content-card {
                padding: 14px 10px;
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
                <h1>Penempatan Siswa</h1>
            </div>

            <a href="dashboard.php" class="btn-back">
                Kembali ke Dashboard
            </a>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <!-- FORM PENEMPATAN -->
            <div class="content-card">

                <h3 class="section-title">Form Penempatan Siswa</h3>

                <form action="proses_simpan_penempatan.php" method="POST">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="siswa_id" class="form-label">
                                Siswa
                            </label>

                            <select
                                name="siswa_id"
                                id="siswa_id"
                                class="form-select"
                                required
                            >
                                <option value="">-- Pilih Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                    <option value="<?= htmlspecialchars((string)$s['id']) ?>">
                                        <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="tahun_ajaran_id" class="form-label">
                                Tahun Ajaran
                            </label>

                            <select
                                name="tahun_ajaran_id"
                                id="tahun_ajaran_id"
                                class="form-select"
                                required
                            >
                                <option value="">-- Pilih Tahun Ajaran --</option>

                                <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                    <option value="<?= htmlspecialchars((string)$ta['id']) ?>">
                                        <?= htmlspecialchars($ta['nama']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="kelas_id" class="form-label">
                                Kelas
                            </label>

                            <select
                                name="kelas_id"
                                id="kelas_id"
                                class="form-select"
                                required
                            >
                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?= htmlspecialchars((string)$k['id']) ?>">
                                        <?= htmlspecialchars(
                                            $k['nama'] . ' - Tingkat ' .
                                            $k['tingkat'] . ' - ' . $k['jurusan']
                                        ) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="status_aktif" class="form-label">
                                Status
                            </label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required
                            >
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_mulai" class="form-label">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="tanggal_mulai"
                                id="tanggal_mulai"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_selesai" class="form-label">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="tanggal_selesai"
                                id="tanggal_selesai"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn-save">
                                Simpan Penempatan
                            </button>
                        </div>

                    </div>

                </form>
            </div>

            <!-- TABEL DATA PENEMPATAN -->
            <div class="content-card">

                <h3 class="section-title">Data Penempatan Siswa</h3>

                <div class="table-responsive">
                    <table
                        id="tabelPenempatan"
                        class="table table-striped table-bordered"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Tahun Ajaran</th>
                                <th>Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                        $no = 1;

                        while ($row = mysqli_fetch_assoc($hasil)) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars((string)($row['nis'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)($row['nama_siswa'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)($row['tahun_ajaran'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)($row['nama_kelas'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)($row['tingkat'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars((string)($row['jurusan'] ?? '-')) ?>
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
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </main>
    </div>
</div>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelPenempatan').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [],
        language: {
            lengthMenu: 'Tampilkan _MENU_ data',
            search: 'Cari:',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data penempatan siswa',
            infoEmpty: 'Tidak ada data penempatan siswa',
            zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Belum ada data penempatan siswa',
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
