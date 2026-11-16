
<?php
// catat_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Ambil pesan dari proses penyimpanan
$pesan = $_SESSION['pesan_sukses'] ?? '';
$error = $_SESSION['pesan_error'] ?? '';

unset($_SESSION['pesan_sukses'], $_SESSION['pesan_error']);

// Dropdown siswa
$siswa = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_siswa
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Dropdown tahun ajaran
$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY tanggal_mulai DESC
");

// Dropdown kelas
$kelas = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_kelas
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Dropdown guru
$guru = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Jenis pelanggaran beserta poin dari database
$kategori = mysqli_query($koneksi, "
    SELECT id, nama, poin, pelanggaran_kategori_id
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Data catatan pelanggaran
$data = mysqli_query($koneksi, "
    SELECT
        ps.*,
        COALESCE(s.nama, ps.nama_siswa) AS nama_siswa_tampil,
        COALESCE(k.nama, ps.nama_kelas) AS nama_kelas_tampil,
        ta.nama AS nama_tahun
    FROM t_pelanggaran_siswa ps
    LEFT JOIN t_siswa s ON ps.siswa_id = s.id
    LEFT JOIN t_kelas k ON ps.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON ps.tahun_ajaran_id = ta.id
    ORDER BY ps.tanggal DESC, ps.id DESC
");

if (!$siswa || !$tahun || !$kelas || !$guru || !$kategori || !$data) {
    die("Terjadi kesalahan database: " . mysqli_error($koneksi));
}

// Link dashboard sesuai role
$dashboardLink = (($_SESSION['role'] ?? '') === 'guru')
    ? 'dashboard_guru.php'
    : 'dashboard.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catat Pelanggaran Siswa - SIPES</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="bg-dark text-white">

<div class="d-flex min-vh-100">

    <?php include 'layout/sidebar.php'; ?>

    <main class="flex-grow-1 min-vw-0 bg-dark">
        <div class="container-fluid p-3 p-lg-4">

            <h2 class="fw-bold mb-3 text-white">
                Catat Pelanggaran Siswa
            </h2>

            <a href="<?= e($dashboardLink); ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>

            <?php if ($pesan): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= e($pesan); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= e($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- FORM CATAT PELANGGARAN -->
            <div class="card bg-white text-dark border-0 shadow-sm rounded-3 mb-4">

                <div class="card-header bg-light text-dark border-bottom p-3">
                    Form Catat Pelanggaran
                </div>

                <div class="card-body p-3 p-lg-4">

                    <form method="POST" action="proses_simpan_pelanggaran.php">

                        <div class="row g-3">

                            <!-- SISWA -->
                            <div class="col-md-6">
                                <label class="form-label">Siswa</label>
                                <select name="siswa_id" class="form-select" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    <?php while ($row = mysqli_fetch_assoc($siswa)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- TAHUN AJARAN -->
                            <div class="col-md-6">
                                <label class="form-label">Tahun Ajaran</label>
                                <select name="tahun_ajaran_id" class="form-select" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    <?php while ($row = mysqli_fetch_assoc($tahun)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- KELAS -->
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <select name="kelas_id" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php while ($row = mysqli_fetch_assoc($kelas)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- JENIS PELANGGARAN -->
                            <div class="col-md-6">
                                <label class="form-label">Jenis Pelanggaran</label>

                                <select name="pelanggaran_id"
                                        id="pelanggaran_id"
                                        class="form-select"
                                        required>

                                    <option value="">-- Pilih Pelanggaran --</option>

                                    <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
                                        <option
                                            value="<?= (int)$row['id']; ?>"
                                            data-poin="<?= (int)$row['poin']; ?>"
                                            data-kategori="<?= (int)$row['pelanggaran_kategori_id']; ?>">
                                            <?= e($row['nama']); ?>
                                            (<?= (int)$row['poin']; ?> poin)
                                        </option>
                                    <?php endwhile; ?>

                                </select>

                                <input type="hidden"
                                       name="pelanggaran_kategori_id"
                                       id="pelanggaran_kategori_id">
                            </div>

                            <!-- GURU -->
                            <div class="col-md-6">
                                <label class="form-label">Guru Pelapor</label>
                                <select name="guru_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    <?php while ($row = mysqli_fetch_assoc($guru)): ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- TANGGAL -->
                            <div class="col-md-6">
                                <label class="form-label">Tanggal</label>
                                <input type="date"
                                       name="tanggal"
                                       class="form-control"
                                       value="<?= date('Y-m-d'); ?>"
                                       required>
                            </div>

                            <!-- POIN OTOMATIS DAN TERKUNCI -->
                            <div class="col-md-6">
                                <label class="form-label">Poin Pelanggaran</label>

                                <input type="text"
                                       name="poin"
                                       id="poin"
                                       class="form-control bg-light"
                                       placeholder="Poin otomatis dari jenis pelanggaran"
                                       readonly
                                       required
                                       autocomplete="off"
                                       style="cursor: not-allowed;">

                                <small class="text-muted">
                                    Poin mengikuti jenis pelanggaran dan tidak dapat diubah.
                                </small>
                            </div>

                            <!-- STATUS -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Tercatat">Tercatat</option>
                                    <option value="Ditindaklanjuti">Ditindaklanjuti</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>

                            <!-- KETERANGAN -->
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan keterangan pelanggaran..."></textarea>
                            </div>

                            <!-- TINDAKAN -->
                            <div class="col-md-6">
                                <label class="form-label">Tindakan</label>
                                <textarea name="tindakan"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan tindakan yang diberikan..."></textarea>
                            </div>

                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Simpan Pelanggaran
                            </button>

                            <a href="<?= e($dashboardLink); ?>" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <!-- TABEL DATA PELANGGARAN -->
            <div class="card bg-white text-dark border-0 shadow-sm rounded-3">

                <div class="card-header bg-light text-dark border-bottom p-3">
                    Data Catatan Pelanggaran
                </div>

                <div class="card-body p-3 p-lg-4">

                    <div class="table-responsive">
                        <table id="tabelPelanggaran"
                               class="table table-bordered table-striped table-hover align-middle mb-0 w-100">

                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Pelanggaran</th>
                                    <th>Guru</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($data)):
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= e($row['nama_siswa_tampil']); ?></td>
                                        <td><?= e($row['nama_kelas_tampil']); ?></td>
                                        <td><?= e($row['nama_tahun'] ?? '-'); ?></td>
                                        <td><?= e($row['nama_pelanggaran']); ?></td>
                                        <td><?= e($row['nama_guru']); ?></td>
                                        <td class="text-nowrap"><?= e($row['tanggal']); ?></td>
                                        <td class="text-center">
                                            <strong><?= (int)$row['poin']; ?></strong>
                                        </td>
                                        <td><?= e($row['tindakan']); ?></td>
                                        <td>
                                            <?php
                                            $statusData = strtolower($row['status'] ?? '');

                                            if ($statusData === 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } elseif ($statusData === 'ditindaklanjuti') {
                                                echo '<span class="badge bg-info text-dark">Ditindaklanjuti</span>';
                                            } elseif ($statusData === 'proses') {
                                                echo '<span class="badge bg-primary">Proses</span>';
                                            } else {
                                                echo '<span class="badge bg-warning text-dark">Tercatat</span>';
                                            }
                                            ?>
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

    // Mengisi poin dan kategori secara otomatis
    $('#pelanggaran_id').on('change', function () {
        const pilihan = $(this).find(':selected');

        const poin = pilihan.attr('data-poin') || '';
        const kategori = pilihan.attr('data-kategori') || '';

        $('#poin').val(poin);
        $('#pelanggaran_kategori_id').val(kategori);
    });

    $('#tabelPelanggaran').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[6, 'desc']],
        scrollX: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data pelanggaran",
            infoEmpty: "Tidak ada data pelanggaran",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data pelanggaran tidak ditemukan",
            emptyTable: "Belum ada catatan pelanggaran",
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
