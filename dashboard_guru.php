<?php
session_start();
include "config/koneksi.php";

// Cek apakah sudah login dan role-nya adalah guru
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "guru") {
    header("Location: login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Ambil daftar kolom yang benar-benar ada di tabel t_pelanggaran_siswa secara otomatis
$table_columns = [];
$col_query = mysqli_query($koneksi, "SHOW COLUMNS FROM t_pelanggaran_siswa");
if ($col_query) {
    while($c = mysqli_fetch_assoc($col_query)){
        $table_columns[] = $c['Field'];
    }
}

// --- PROSES SIMPAN / TAMBAH PELANGGARAN (OTOMATIS MENYESUAIKAN KOLOM) ---
if (isset($_POST['simpan_pelanggaran'])) {
    $input_nama        = $_POST['nama_siswa'] ?? '';
    $input_pelanggaran = $_POST['pelanggaran'] ?? '';
    $input_tanggal     = $_POST['tanggal'] ?? date('Y-m-d');
    $input_poin        = $_POST['poin'] ?? 0;
    $input_tindakan    = $_POST['tindakan'] ?? '';
    $input_guru        = $_SESSION['name'] ?? 'Guru';

    // Petakan data ke berbagai kemungkinan nama kolom di database
    $data_kandidat = [
        'nama_siswa'        => $input_nama,
        'nama'              => $input_nama,
        'pelanggaran'       => $input_pelanggaran,
        'jenis_pelanggaran' => $input_pelanggaran,
        'keterangan'        => $input_pelanggaran,
        'tanggal'           => $input_tanggal,
        'poin'              => $input_poin,
        'tindakan'          => $input_tindakan,
        'guru'              => $input_guru
    ];

    $fields = [];
    $values = [];

    // Hanya masukkan kolom yang benar-benar ada di database Anda
    foreach ($data_kandidat as $col_name => $val) {
        if (in_array($col_name, $table_columns)) {
            $fields[] = $col_name;
            $values[] = "'" . mysqli_real_escape_string($koneksi, $val) . "'";
        }
    }

    if (!empty($fields)) {
        $sql_insert = "INSERT INTO t_pelanggaran_siswa (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
        $query_tambah = mysqli_query($koneksi, $sql_insert);
        
        if($query_tambah) {
            header("Location: dashboard_guru.php?page=riwayat");
            exit;
        } else {
            $db_error = mysqli_error($koneksi);
        }
    } else {
        $db_error = "Tidak ada kolom yang cocok antara form dan tabel database.";
    }
}

// --- PROSES HAPUS PELANGGARAN ---
if (isset($_GET['id_hapus'])) {
    $id = $_GET['id_hapus'];
    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_siswa WHERE id='$id'");
    header("Location: dashboard_guru.php?page=riwayat");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Sistem Informasi Pelanggaran Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; font-family: Arial, sans-serif; margin: 0; }
        .sidebar { width: 250px; min-height: 100vh; background: #212529; color: white; position: fixed; top: 0; left: 0; display: flex; flex-direction: column; padding: 20px 15px; z-index: 100; }
        .sidebar h4 { color: #ffffff; font-size: 18px; font-weight: bold; margin-bottom: 30px; padding-left: 10px; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 15px; display: block; border-radius: 6px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover, .sidebar a.active { color: #ffffff; background: #0d6efd; }
        .sidebar .logout-btn { color: #dc3545; margin-top: auto; padding-left: 15px; text-decoration: none; font-weight: 500; }
        .sidebar .logout-btn:hover { color: #ff4d4d; }
        .main-content { margin-left: 250px; padding: 40px; }
        .card-custom { background: white; border: 1px solid #dee2e6; border-radius: 6px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); margin-bottom: 20px; }
        .stat-card h5 { font-size: 16px; color: #212529; margin-bottom: 15px; font-weight: normal; }
        .stat-card h3 { font-size: 32px; color: #212529; font-weight: normal; margin-bottom: 0; }
    </style>
</head>
<body>

    <!-- Sidebar Navigasi -->
    <div class="sidebar">
        <h4>Pelanggaran Siswa</h4>
        <div class="nav flex-column">
            <a href="dashboard_guru.php?page=dashboard" class="<?= ($page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
            <a href="dashboard_guru.php?page=catat" class="<?= ($page == 'catat') ? 'active' : ''; ?>">Catat Pelanggaran</a>
            <a href="dashboard_guru.php?page=riwayat" class="<?= ($page == 'riwayat') ? 'active' : ''; ?>">Riwayat Pelanggaran</a>
            <a href="dashboard_guru.php?page=tindakan" class="<?= ($page == 'tindakan') ? 'active' : ''; ?>">Referensi Tindakan</a>
            <a href="dashboard_guru.php?page=rekap" class="<?= ($page == 'rekap') ? 'active' : ''; ?>">Rekap Poin Siswa</a>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Konten Utama -->
    <div class="main-content">
        <?php if (!empty($db_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal Menyimpan!</strong> <?= htmlspecialchars($db_error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($page == 'dashboard'): ?>
            <!-- DASHBOARD -->
            <h2>Dashboard Guru</h2>
            <p>Selamat datang, <?= htmlspecialchars($_SESSION['name'] ?? ''); ?>. Berikut ringkasan sistem pelanggaran siswa.</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-custom stat-card">
                        <h5>Total Siswa Terdaftar</h5>
                        <h3 class="text-primary">50</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom stat-card">
                        <h5>Total Guru</h5>
                        <h3>25</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom stat-card">
                        <h5>Jumlah Kelas</h5>
                        <h3>12</h3>
                    </div>
                </div>
            </div>

        <?php elseif ($page == 'catat'): ?>
            <!-- FORM CATAT PELANGGARAN -->
            <h2>Form Catat Pelanggaran Siswa</h2>
            <p class="text-muted">Masukkan data pelanggaran siswa dengan benar.</p>
            
            <div class="card-custom">
                <form action="" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" name="nama_siswa" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bentuk Pelanggaran</label>
                            <input type="text" name="pelanggaran" class="form-control" placeholder="Contoh: Terlambat hadir" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Poin Pelanggaran</label>
                            <input type="number" name="poin" class="form-control" placeholder="Contoh: 10" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan / Sanksi</label>
                        <input type="text" name="tindakan" class="form-control" placeholder="Contoh: Teguran lisan" required>
                    </div>
                    <button type="submit" name="simpan_pelanggaran" class="btn btn-primary">Simpan Pelanggaran</button>
                </form>
            </div>

        <?php elseif ($page == 'riwayat'): ?>
            <!-- RIWAYAT PELANGGARAN -->
            <h2>Riwayat Pelanggaran Siswa</h2>
            <p class="text-muted">Daftar seluruh catatan pelanggaran yang dilaporkan.</p>
            
            <div class="card-custom">
                <a href="dashboard_guru.php?page=catat" class="btn btn-success mb-3">+ Tambah Pelanggaran Baru</a>
                
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Pelanggaran</th>
                            <th>Tanggal</th>
                            <th>Poin</th>
                            <th>Tindakan</th>
                            <th>Pelapor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa ORDER BY id DESC");
                        if($sql && mysqli_num_rows($sql) > 0) {
                            while($d = mysqli_fetch_assoc($sql)){
                                $id_data     = $d['id'] ?? '';
                                $nama_siswa  = $d['nama_siswa'] ?? ($d['nama'] ?? '-');
                                $pelanggaran = $d['pelanggaran'] ?? ($d['jenis_pelanggaran'] ?? ($d['keterangan'] ?? '-'));
                                $tanggal     = $d['tanggal'] ?? '-';
                                $poin        = $d['poin'] ?? '0';
                                $tindakan    = $d['tindakan'] ?? '-';
                                $guru        = $d['guru'] ?? '-';

                                echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$nama_siswa}</td>
                                    <td>{$pelanggaran}</td>
                                    <td>{$tanggal}</td>
                                    <td><span class='badge bg-danger'>{$poin}</span></td>
                                    <td>{$tindakan}</td>
                                    <td>{$guru}</td>
                                    <td>
                                        <a href='dashboard_guru.php?page=edit&id={$id_data}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='dashboard_guru.php?id_hapus={$id_data}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center text-muted'>Belum ada data pelanggaran.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($page == 'edit'): ?>
            <!-- HALAMAN EDIT DATA -->
            <?php
            $id_edit = $_GET['id'] ?? '';
            $q_edit = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_siswa WHERE id='$id_edit'");
            $data_edit = mysqli_fetch_assoc($q_edit);

            if(isset($_POST['update_pelanggaran'])) {
                $ns  = mysqli_real_escape_string($koneksi, $_POST['nama_siswa']);
                $pel = mysqli_real_escape_string($koneksi, $_POST['pelanggaran']);
                $tgl = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
                $pn  = mysqli_real_escape_string($koneksi, $_POST['poin']);
                $tnd = mysqli_real_escape_string($koneksi, $_POST['tindakan']);

                // Update secara dinamis berdasarkan kolom yang ada
                $update_cols = [];
                if(in_array('nama_siswa', $table_columns)) $update_cols[] = "nama_siswa='$ns'";
                elseif(in_array('nama', $table_columns)) $update_cols[] = "nama='$ns'";

                if(in_array('pelanggaran', $table_columns)) $update_cols[] = "pelanggaran='$pel'";
                elseif(in_array('jenis_pelanggaran', $table_columns)) $update_cols[] = "jenis_pelanggaran='$pel'";
                elseif(in_array('keterangan', $table_columns)) $update_cols[] = "keterangan='$pel'";

                if(in_array('tanggal', $table_columns)) $update_cols[] = "tanggal='$tgl'";
                if(in_array('poin', $table_columns)) $update_cols[] = "poin='$pn'";
                if(in_array('tindakan', $table_columns)) $update_cols[] = "tindakan='$tnd'";

                if(!empty($update_cols)) {
                    mysqli_query($koneksi, "UPDATE t_pelanggaran_siswa SET " . implode(', ', $update_cols) . " WHERE id='$id_edit'");
                }

                header("Location: dashboard_guru.php?page=riwayat");
                exit;
            }
            ?>
            <h2>Edit Data Pelanggaran Siswa</h2>
            <div class="card-custom">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Siswa</label>
                        <input type="text" name="nama_siswa" class="form-control" value="<?= $data_edit['nama_siswa'] ?? ($data_edit['nama'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bentuk Pelanggaran</label>
                        <input type="text" name="pelanggaran" class="form-control" value="<?= $data_edit['pelanggaran'] ?? ($data_edit['jenis_pelanggaran'] ?? ($data_edit['keterangan'] ?? '')); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $data_edit['tanggal'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number" name="poin" class="form-control" value="<?= $data_edit['poin'] ?? ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tindakan</label>
                        <input type="text" name="tindakan" class="form-control" value="<?= $data_edit['tindakan'] ?? ''; ?>" required>
                    </div>
                    <button type="submit" name="update_pelanggaran" class="btn btn-success">Perbarui Data</button>
                    <a href="dashboard_guru.php?page=riwayat" class="btn btn-secondary">Batal</a>
                </form>
            </div>

        <?php elseif ($page == 'tindakan'): ?>
            <!-- REFERENSI TINDAKAN -->
            <h2>Referensi Tindakan Berdasarkan Poin</h2>
            <div class="card-custom">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Akumulasi Poin</th>
                            <th>Jenis Tindakan / Sanksi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>10 - 25</td><td>Teguran Lisan</td><td>Diberikan langsung oleh guru piket atau wali kelas.</td></tr>
                        <tr><td>2</td><td>26 - 50</td><td>Pembinaan Khusus</td><td>Surat pernyataan dan pembinaan kedisiplinan.</td></tr>
                        <tr><td>3</td><td>> 50</td><td>Pemanggilan Orang Tua / Wali</td><td>Pemanggilan resmi orang tua murid ke sekolah.</td></tr>
                    </tbody>
                </table>
            </div>

        <?php elseif ($page == 'rekap'): ?>
            <!-- REKAP POIN -->
            <h2>Rekap Akumulasi Poin Siswa</h2>
            <div class="card-custom">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Total Poin Terakumulasi</th>
                            <th>Status Kedisiplinan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>1</td><td>Siswa 100</td><td>20</td><td><span class="badge bg-warning text-dark">Aman / Dalam Pengawasan</span></td></tr>
                        <tr><td>2</td><td>Siswa 99</td><td>35</td><td><span class="badge bg-danger">Peringatan / Pembinaan</span></td></tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>