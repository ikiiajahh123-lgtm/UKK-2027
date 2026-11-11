
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// PENANDA MENU AKTIF
$menu_aktif = 'tindakan';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: kelola_guru.php");
    exit;
}

// Ambil data guru menggunakan prepared statement
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nip, nama, email, status_aktif
     FROM t_guru WHERE id = ?"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika guru tidak ditemukan
if (!$guru) {
    echo "<script>
        alert('Data guru tidak ditemukan!');
        window.location.href='kelola_guru.php';
    </script>";
    exit;
}

// Keamanan output
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tindakan Data Guru | SIPES</title>

    <!-- BOOTSTRAP 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">
</head>

<body class="bg-dark text-white">

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="flex-grow-1 min-vw-0">

        <div class="container-fluid p-3 p-md-4">

            <h2 class="fw-bold mb-4 text-white">
                Tindakan Data Guru
            </h2>

            <div class="card shadow-sm border-0">

                <div class="card-body p-3 p-md-4">

                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-person-badge me-2"></i>
                        Informasi Guru
                    </h5>

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped align-middle mb-0">
                            <tbody>

                                <tr>
                                    <th class="table-light" style="width:180px;">
                                        NIP
                                    </th>
                                    <td><?= e($guru['nip']); ?></td>
                                </tr>

                                <tr>
                                    <th class="table-light">Nama Guru</th>
                                    <td><?= e($guru['nama']); ?></td>
                                </tr>

                                <tr>
                                    <th class="table-light">Email</th>
                                    <td><?= e($guru['email']); ?></td>
                                </tr>

                                <tr>
                                    <th class="table-light">Status</th>
                                    <td>
                                        <?php if ((int)$guru['status_aktif'] === 1): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>

                    <h5 class="fw-bold text-dark mt-4 mb-3">
                        <i class="bi bi-tools me-2"></i>
                        Pilih Tindakan
                    </h5>

                    <div class="d-flex flex-wrap gap-2">

                        <a href="detail_guru.php?id=<?= (int)$guru['id']; ?>"
                           class="btn btn-info text-white">
                            <i class="bi bi-eye me-1"></i>
                            Detail
                        </a>

                        <a href="edit_guru.php?id=<?= (int)$guru['id']; ?>"
                           class="btn btn-warning text-dark">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit
                        </a>

                        <form action="proses_hapus_guru.php"
                              method="POST"
                              class="m-0"
                              onsubmit="return confirm('Yakin ingin menghapus data guru ini?');">

                            <input type="hidden"
                                   name="id"
                                   value="<?= (int)$guru['id']; ?>">

                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>
                                Hapus
                            </button>

                        </form>

                        <a href="kelola_guru.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>

                    </div>

                </div>
            </div>
        </div>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>