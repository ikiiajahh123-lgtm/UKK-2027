
<?php
// catatan_pelanggaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = '';
$error = '';

// SIMPAN DATA PELANGGARAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $guru_id = (int)($_POST['guru_id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $poin = (int)($_POST['poin'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = $_POST['status'] ?? 'tercatat';

    $status_valid = ['tercatat', 'Ditindaklanjuti', 'selesai'];

    if (
        !$siswa_id || !$kelas_id || !$tahun_ajaran_id ||
        !$kategori_id || !$guru_id || !$tanggal || $poin <= 0
    ) {
        $error = "Mohon lengkapi semua data yang wajib diisi.";
    } elseif (!in_array($status, $status_valid, true)) {
        $error = "Status pelanggaran tidak valid.";
    } else {

        $stmt = mysqli_prepare($koneksi, "
            INSERT INTO t_pelanggaran
            (siswa_id, kelas_id, tahun_ajaran_id, kategori_id,
             guru_id, tanggal, poin, keterangan, tindakan, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "iiiiisisss",
                $siswa_id,
                $kelas_id,
                $tahun_ajaran_id,
                $kategori_id,
                $guru_id,
                $tanggal,
                $poin,
                $keterangan,
                $tindakan,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: catatan_pelanggaran.php?berhasil=1");
                exit;
            } else {
                $error = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);

        } else {
            $error = "Query gagal: " . mysqli_error($koneksi);
        }
    }
}

if (isset($_GET['berhasil'])) {
    $pesan = "Data pelanggaran berhasil disimpan.";
}

// AMBIL DATA DROPDOWN
$siswa = mysqli_query($koneksi, "
    SELECT id, nama FROM t_siswa ORDER BY nama ASC
");

$kelas = mysqli_query($koneksi, "
    SELECT id, nama FROM t_kelas ORDER BY nama ASC
");

$tahun = mysqli_query($koneksi, "
    SELECT id, nama FROM t_tahun_ajaran
    ORDER BY tanggal_mulai DESC
");

$guru = mysqli_query($koneksi, "
    SELECT id, nama FROM t_guru ORDER BY nama ASC
");

$kategori = mysqli_query($koneksi, "
    SELECT id, nama FROM t_kategori_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// AMBIL DATA CATATAN PELANGGARAN
$data = mysqli_query($koneksi, "
    SELECT
        p.*,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        ta.nama AS nama_tahun,
        kp.nama AS nama_pelanggaran,
        g.nama AS nama_guru

    FROM t_pelanggaran p

    LEFT JOIN t_siswa s ON p.siswa_id = s.id
    LEFT JOIN t_kelas k ON p.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON p.tahun_ajaran_id = ta.id
    LEFT JOIN t_kategori_pelanggaran kp ON p.kategori_id = kp.id
    LEFT JOIN t_guru g ON p.guru_id = g.id

    ORDER BY p.tanggal DESC, p.id DESC
");

if (!$siswa || !$kelas || !$tahun || !$guru || !$kategori || !$data) {
    die("Terjadi kesalahan database: " . mysqli_error($koneksi));
}

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catat Pelanggaran Siswa</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- DATATABLES -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --bg: #101827;
            --panel: #1d2a3a;
            --border: rgba(161,179,205,.16);
            --muted: #9aa9bd;
            --text: #f4f7fb;
            --accent: #397cf6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* LAYOUT */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .main-area {
            min-width: 0;
            flex: 1;
        }

        .content-area {
            padding: 34px 4.2%;
            max-width: 1800px;
            margin: auto;
        }

        /* HEADER */
        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .page-heading h1 {
            font-size: 30px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 8px;
        }

        .page-heading p {
            color: var(--muted);
            font-size: 14px;
            margin: 0;
        }

        /* BUTTON */
        .btn {
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 14px;
        }

        .btn-primary {
            background: #397cf6;
            border-color: #397cf6;
        }

        .btn-primary:hover {
            background: #2868dc;
        }

        .btn-back {
            background: #66717e;
            border-color: #66717e;
            margin: 15px 0 20px;
        }

        /* CARD */
        .content-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            margin-bottom: 22px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
        }

        .card-header-custom {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            background: #202f43;
        }

        .card-header-custom h5 {
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 5px;
            color: white;
        }

        .card-header-custom p {
            color: var(--muted);
            font-size: 12px;
            margin: 0;
        }

        .card-body-custom {
            padding: 22px;
        }

        /* FORM */
        .form-label {
            color: #eaf0f8;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background-color: #29384b;
            border: 1px solid #40516a;
            color: white;
            border-radius: 7px;
            font-size: 13px;
            min-height: 40px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #29384b;
            color: white;
            border-color: #397cf6;
            box-shadow: 0 0 0 3px rgba(57,124,246,.15);
        }

        .form-control::placeholder {
            color: #91a4bf;
        }

        .form-select option {
            background: #29384b;
            color: white;
        }

        textarea.form-control {
            min-height: 90px;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        /* TABLE */
        .table-card-body {
            padding: 22px;
        }

        #tabelPelanggaran {
            width: 100% !important;
            font-size: 12px;
            color: white;
        }

        #tabelPelanggaran thead th {
            background: #192330 !important;
            color: white !important;
            border-color: #344256 !important;
            padding: 13px 10px;
            white-space: nowrap;
        }

        #tabelPelanggaran tbody td {
            background: #1d2a3a;
            color: #eaf0f8;
            border-color: #344256;
            padding: 12px 10px;
            vertical-align: middle;
        }

        #tabelPelanggaran tbody tr:hover td {
            background: #29394e;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            color: #eaf0f8;
            background-color: #202e40;
        }

        .badge {
            font-size: 10px;
            padding: 6px 9px;
        }

        /* DATATABLES */
        .dt-container {
            color: var(--text);
            font-size: 12px;
        }

        .dt-search input,
        .dt-length select {
            background: #29384b !important;
            color: white !important;
            border: 1px solid #40516a !important;
            border-radius: 7px !important;
            padding: 7px 10px !important;
        }

        .dt-search input {
            margin-left: 8px;
        }

        .dt-length select {
            margin: 0 5px;
        }

        .dt-info {
            color: var(--muted) !important;
            padding-top: 15px !important;
        }

        .dt-paging .dt-paging-button {
            color: #dce6f4 !important;
            border-radius: 6px !important;
        }

        .dt-paging .dt-paging-button.current {
            background: #397cf6 !important;
            color: white !important;
            border: 1px solid #397cf6 !important;
        }

        .dt-paging .dt-paging-button:hover {
            background: #29384b !important;
            color: white !important;
            border: 1px solid #40516a !important;
        }

        /* ALERT */
        .alert {
            border-radius: 8px;
            font-size: 13px;
        }

        /* RESPONSIVE */
        @media(max-width: 1100px) {
            .content-area {
                padding: 25px 20px;
            }

            .card-body-custom,
            .table-card-body {
                padding: 16px;
            }
        }

        @media(max-width: 700px) {
            .content-area {
                padding: 22px 12px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .page-heading h1 {
                font-size: 24px;
            }

            .card-body-custom,
            .table-card-body {
                padding: 13px;
            }
        }
    </style>
</head>

<body>

<div class="app-shell">

    <!-- SIDEBAR ADMIN -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN -->
    <div class="main-area">

        <main class="content-area">

            <!-- HEADER -->
            <div class="page-heading">
                <div>
                    <h1>Catat Pelanggaran Siswa</h1>
                    <p>Kelola dan catat data pelanggaran siswa dengan mudah.</p>
                </div>
            </div>

            <a href="dashboard.php" class="btn btn-secondary btn-back">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>

            <!-- PESAN -->
            <?php if ($pesan) { ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= e($pesan); ?>
                </div>
            <?php } ?>

            <?php if ($error) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= e($error); ?>
                </div>
            <?php } ?>

            <!-- FORM CATAT PELANGGARAN -->
            <div class="content-card">

                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-clipboard-list me-2"></i>
                        Form Catat Pelanggaran
                    </h5>
                    <p>Lengkapi informasi pelanggaran siswa di bawah ini.</p>
                </div>

                <div class="card-body-custom">

                    <form method="POST" action="">

                        <div class="row g-3">

                            <!-- SISWA -->
                            <div class="col-md-6">
                                <label class="form-label">Siswa *</label>
                                <select name="siswa_id" class="form-select" required>
                                    <option value="">-- Pilih Siswa --</option>

                                    <?php while ($row = mysqli_fetch_assoc($siswa)) { ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- TAHUN AJARAN -->
                            <div class="col-md-6">
                                <label class="form-label">Tahun Ajaran *</label>
                                <select name="tahun_ajaran_id" class="form-select" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>

                                    <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- KELAS -->
                            <div class="col-md-6">
                                <label class="form-label">Kelas *</label>
                                <select name="kelas_id" class="form-select" required>
                                    <option value="">-- Pilih Kelas --</option>

                                    <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- JENIS PELANGGARAN -->
                            <div class="col-md-6">
                                <label class="form-label">Jenis Pelanggaran *</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Pelanggaran --</option>

                                    <?php while ($row = mysqli_fetch_assoc($kategori)) { ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- GURU -->
                            <div class="col-md-6">
                                <label class="form-label">Guru Pelapor *</label>
                                <select name="guru_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>

                                    <?php while ($row = mysqli_fetch_assoc($guru)) { ?>
                                        <option value="<?= (int)$row['id']; ?>">
                                            <?= e($row['nama']); ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- TANGGAL -->
                            <div class="col-md-6">
                                <label class="form-label">Tanggal *</label>
                                <input type="date"
                                    name="tanggal"
                                    class="form-control"
                                    value="<?= date('Y-m-d'); ?>"
                                    required>
                            </div>

                            <!-- POIN -->
                            <div class="col-md-6">
                                <label class="form-label">Poin Pelanggaran *</label>
                                <input type="number"
                                    name="poin"
                                    class="form-control"
                                    min="1"
                                    placeholder="Masukkan jumlah poin"
                                    required>
                            </div>

                            <!-- STATUS -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="tercatat">Tercatat</option>
                                    <option value="Ditindaklanjuti">Ditindaklanjuti</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>

                            <!-- KETERANGAN -->
                            <div class="col-md-6">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan"
                                    class="form-control"
                                    placeholder="Masukkan keterangan pelanggaran..."></textarea>
                            </div>

                            <!-- TINDAKAN -->
                            <div class="col-md-6">
                                <label class="form-label">Tindakan</label>
                                <textarea name="tindakan"
                                    class="form-control"
                                    placeholder="Masukkan tindakan yang diberikan..."></textarea>
                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="d-flex gap-2 mt-4">

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Simpan Pelanggaran
                            </button>

                            <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>
            </div>

            <!-- TABEL CATATAN PELANGGARAN -->
            <div class="content-card">

                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-table me-2"></i>
                        Data Catatan Pelanggaran
                    </h5>
                    <p>Daftar seluruh catatan pelanggaran siswa.</p>
                </div>

                <div class="table-card-body">

                    <div class="table-responsive">

                        <table id="tabelPelanggaran"
                            class="table table-bordered table-striped table-hover align-middle w-100">

                            <thead>
                                <tr>
                                    <th>No.</th>
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

                                while ($row = mysqli_fetch_assoc($data)) {
                                ?>

                                    <tr>
                                        <td><?= $no++; ?></td>

                                        <td><?= e($row['nama_siswa']); ?></td>

                                        <td><?= e($row['nama_kelas']); ?></td>

                                        <td><?= e($row['nama_tahun']); ?></td>

                                        <td><?= e($row['nama_pelanggaran']); ?></td>

                                        <td><?= e($row['nama_guru']); ?></td>

                                        <td><?= e($row['tanggal']); ?></td>

                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?= (int)$row['poin']; ?>
                                            </span>
                                        </td>

                                        <td><?= e($row['tindakan']); ?></td>

                                        <td>
                                            <?php
                                            $status_data = strtolower($row['status']);

                                            if ($status_data === 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } elseif ($status_data === 'ditindaklanjuti') {
                                                echo '<span class="badge bg-warning text-dark">Ditindaklanjuti</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">Tercatat</span>';
                                            }
                                            ?>
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

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {

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
