
<?php
// catat_pelanggaran.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Daftar siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT id, nis, nama
     FROM t_siswa
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Daftar tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Daftar kelas
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama, tingkat, jurusan
     FROM t_kelas
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Daftar pelanggaran
$pelanggaran = mysqli_query(
    $koneksi,
    "SELECT id, nama, poin, pelanggaran_kategori_id
     FROM t_pelanggaran
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Daftar guru
$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_guru
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Periksa query dropdown
if (!$siswa || !$tahun || !$kelas || !$pelanggaran || !$guru) {
    die("Gagal mengambil data form: " . mysqli_error($koneksi));
}

// Data catatan pelanggaran
$sql = "SELECT
            ps.*,
            ta.nama AS tahun_ajaran,
            s.nis,
            s.nama AS nama_siswa,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            p.nama AS nama_pelanggaran,
            g.nama AS nama_guru
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_tahun_ajaran ta
            ON ps.tahun_ajaran_id = ta.id
        LEFT JOIN t_siswa s
            ON ps.siswa_id = s.id
        LEFT JOIN t_kelas k
            ON ps.kelas_id = k.id
        LEFT JOIN t_pelanggaran p
            ON ps.pelanggaran_id = p.id
        LEFT JOIN t_guru g
            ON ps.guru_id = g.id
        ORDER BY ps.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Gagal mengambil data catatan pelanggaran: " .
        mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catat Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #ffffff;
            font-family: Arial, sans-serif;
            color: #212529;
        }

        .page-title {
            font-size: 36px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .navigation {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .navigation a {
            color: #0d6efd;
            text-decoration: none;
        }

        .navigation a:hover {
            text-decoration: underline;
        }

        .form-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            background: #ffffff;
        }

        .section-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
        }

        #tabelPelanggaran {
            width: 100%;
        }

        #tabelPelanggaran thead th {
            background-color: #212529;
            color: #ffffff;
            vertical-align: middle;
            white-space: nowrap;
        }

        #tabelPelanggaran tbody td {
            vertical-align: middle;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 13px;
        }

        .status-proses {
            background: #fff3cd;
            color: #664d03;
        }

        .status-selesai {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-ditindaklanjuti {
            background: #cfe2ff;
            color: #084298;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 28px;
            }

            .form-card {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="page-title">Catat Pelanggaran Siswa</h1>

            <div class="navigation">
                <a href="dashboard.php">Kembali ke Dashboard</a>
                | Catat Pelanggaran
            </div>

            <!-- Notifikasi sukses -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success alert-dismissible fade show"
                     role="alert">
                    <?php
                    echo htmlspecialchars(
                        $_SESSION['pesan_sukses'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    unset($_SESSION['pesan_sukses']);
                    ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Tutup"></button>
                </div>
            <?php } ?>

            <!-- Notifikasi error -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show"
                     role="alert">
                    <?php
                    echo htmlspecialchars(
                        $_SESSION['pesan_error'],
                        ENT_QUOTES,
                        'UTF-8'
                    );
                    unset($_SESSION['pesan_error']);
                    ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Tutup"></button>
                </div>
            <?php } ?>

            <!-- Form catat pelanggaran -->
            <div class="form-card">

                <h3 class="section-title">
                    Form Catat Pelanggaran
                </h3>

                <form action="proses_simpan_pelanggaran.php"
                      method="POST">

                    <div class="row">

                        <!-- Siswa -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="siswa_id">
                                Siswa
                            </label>

                            <select name="siswa_id"
                                    id="siswa_id"
                                    class="form-select"
                                    required>
                                <option value="">-- Pilih Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                    <option value="<?php echo (int) $s['id']; ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            $s['nis'] . ' - ' . $s['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Tahun ajaran -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tahun_ajaran_id">
                                Tahun Ajaran
                            </label>

                            <select name="tahun_ajaran_id"
                                    id="tahun_ajaran_id"
                                    class="form-select"
                                    required>
                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>

                                <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                    <option value="<?php echo (int) $ta['id']; ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            $ta['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Kelas -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="kelas_id">
                                Kelas
                            </label>

                            <select name="kelas_id"
                                    id="kelas_id"
                                    class="form-select"
                                    required>
                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?php echo (int) $k['id']; ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            $k['nama'] . ' - Tingkat ' .
                                            $k['tingkat'] . ' - ' .
                                            $k['jurusan'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Jenis pelanggaran -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="pelanggaran">
                                Jenis Pelanggaran
                            </label>

                            <select name="pelanggaran_id"
                                    id="pelanggaran"
                                    class="form-select"
                                    required>
                                <option value="">
                                    -- Pilih Pelanggaran --
                                </option>

                                <?php while ($p = mysqli_fetch_assoc($pelanggaran)) { ?>
                                    <option
                                        value="<?php echo (int) $p['id']; ?>"
                                        data-poin="<?php echo (int) $p['poin']; ?>"
                                        data-kategori="<?php echo (int) $p['pelanggaran_kategori_id']; ?>">

                                        <?php
                                        echo htmlspecialchars(
                                            $p['nama'] . ' (' .
                                            $p['poin'] . ' poin)',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <input type="hidden"
                                   name="pelanggaran_kategori_id"
                                   id="kategori">
                        </div>

                        <!-- Guru pelapor -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="guru_id">
                                Guru Pelapor
                            </label>

                            <select name="guru_id"
                                    id="guru_id"
                                    class="form-select"
                                    required>
                                <option value="">-- Pilih Guru --</option>

                                <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                                    <option value="<?php echo (int) $g['id']; ?>">
                                        <?php
                                        echo htmlspecialchars(
                                            $g['nama'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tanggal">
                                Tanggal
                            </label>

                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   class="form-control"
                                   value="<?php echo date('Y-m-d'); ?>"
                                   required>
                        </div>

                        <!-- Poin -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="poin">
                                Poin Pelanggaran
                            </label>

                            <input type="number"
                                   name="poin"
                                   id="poin"
                                   class="form-control"
                                   min="0"
                                   required>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="status">
                                Status
                            </label>

                            <select name="status"
                                    id="status"
                                    class="form-select"
                                    required>
                                <option value="Ditindaklanjuti">
                                    Ditindaklanjuti
                                </option>
                                <option value="Proses">Proses</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="keterangan">
                                Keterangan
                            </label>

                            <textarea name="keterangan"
                                      id="keterangan"
                                      class="form-control"
                                      rows="3"></textarea>
                        </div>

                        <!-- Tindakan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tindakan">
                                Tindakan
                            </label>

                            <textarea name="tindakan"
                                      id="tindakan"
                                      class="form-control"
                                      rows="3"></textarea>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">
                        Simpan Pelanggaran
                    </button>

                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>

                    <a href="dashboard.php" class="btn btn-outline-secondary">
                        Kembali
                    </a>

                </form>
            </div>

            <!-- Data catatan pelanggaran -->
            <div class="form-card">

                <h3 class="section-title">
                    Data Catatan Pelanggaran
                </h3>

                <div class="table-responsive">

                    <table id="tabelPelanggaran"
                           class="table table-bordered table-striped">

                        <thead>
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

                            while ($row = mysqli_fetch_assoc($hasil)) {
                                $status = $row['status'] ?? '';
                                $kelasTingkat = trim(
                                    ($row['tingkat'] ?? '') . ' ' .
                                    ($row['jurusan'] ?? '')
                                );
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['nama_siswa'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            trim(
                                                ($row['nama_kelas'] ?? '-') .
                                                ' ' . $kelasTingkat
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['tahun_ajaran'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['nama_pelanggaran'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['nama_guru'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['tanggal'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td><?php echo (int) ($row['poin'] ?? 0); ?></td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['tindakan'] ?? '-',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $classStatus = 'status-ditindaklanjuti';

                                        if ($status === 'Proses') {
                                            $classStatus = 'status-proses';
                                        } elseif ($status === 'Selesai') {
                                            $classStatus = 'status-selesai';
                                        }
                                        ?>

                                        <span class="status-badge <?php echo $classStatus; ?>">
                                            <?php
                                            echo htmlspecialchars(
                                                $status ?: '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                        </span>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
// Mengisi poin dan kategori otomatis saat pelanggaran dipilih
const pilihanPelanggaran = document.getElementById('pelanggaran');
const inputPoin = document.getElementById('poin');
const inputKategori = document.getElementById('kategori');

pilihanPelanggaran.addEventListener('change', function () {
    const opsi = this.options[this.selectedIndex];

    inputPoin.value = opsi.dataset.poin || '';
    inputKategori.value = opsi.dataset.kategori || '';
});

// Aktifkan DataTables
$(document).ready(function () {
    $('#tabelPelanggaran').DataTable({
        language: {
            search: "Cari:",
            searchPlaceholder: "Cari catatan pelanggaran...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Data yang dicari tidak ditemukan",
            emptyTable: "Belum ada catatan pelanggaran",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false
            }
        ]
    });
});
</script>

</body>
</html>
