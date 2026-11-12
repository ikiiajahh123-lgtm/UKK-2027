
<?php
// penempatan_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil daftar siswa aktif
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Ambil daftar tahun ajaran aktif
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Ambil daftar kelas aktif
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

// Periksa query
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
                <h1>Penempatan Siswa</h1>
            </div>

            <!-- TOMBOL KEMBALI -->
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <!-- FORM PENEMPATAN SISWA -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <h4 class="mb-4">Form Penempatan Siswa</h4>

                    <form action="proses_simpan_penempatan.php" method="POST">

                        <div class="row g-3">

                            <!-- SISWA -->
                            <div class="col-md-6">
                                <label for="siswa_id" class="form-label">
                                    Siswa
                                </label>

                                <select name="siswa_id" id="siswa_id" class="form-select" required>
                                    <option value="">-- Pilih Siswa --</option>

                                    <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                        <option value="<?= htmlspecialchars((string)$s['id']) ?>">
                                            <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- TAHUN AJARAN -->
                            <div class="col-md-6">
                                <label for="tahun_ajaran_id" class="form-label">
                                    Tahun Ajaran
                                </label>

                                <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>

                                    <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                        <option value="<?= htmlspecialchars((string)$ta['id']) ?>">
                                            <?= htmlspecialchars($ta['nama']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- KELAS -->
                            <div class="col-md-6">
                                <label for="kelas_id" class="form-label">
                                    Kelas
                                </label>

                                <select name="kelas_id" id="kelas_id" class="form-select" required>
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

                            <!-- STATUS -->
                            <div class="col-md-6">
                                <label for="status_aktif" class="form-label">
                                    Status
                                </label>

                                <select name="status_aktif" id="status_aktif" class="form-select" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>

                            <!-- TANGGAL MULAI -->
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

                            <!-- TANGGAL SELESAI -->
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

                            <!-- TOMBOL SIMPAN -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Penempatan
                                </button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>

            <!-- TABEL DATA PENEMPATAN -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-4">Data Penempatan Siswa</h4>

                    <div class="table-responsive">

                        <table id="tabelPenempatan" class="table table-striped table-bordered table-hover align-middle w-100">

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
                                            <?= htmlspecialchars((string)($row['tanggal_mulai'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars((string)($row['tanggal_selesai'] ?? '-')) ?>
                                        </td>

                                        <td>
                                            <?php if ((int)$row['status_aktif'] === 1) { ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            <?php } ?>
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
    $('#tabelPenempatan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [],
        scrollX: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data penempatan siswa",
            infoEmpty: "Tidak ada data penempatan siswa",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data penempatan siswa tidak ditemukan",
            emptyTable: "Belum ada data penempatan siswa",

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
