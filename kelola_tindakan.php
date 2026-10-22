<?php
// tindakan.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID guru dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Validasi ID
if ($id <= 0) {
    echo "<script>
        alert('ID guru tidak valid!');
        window.location.href = 'kelola_guru.php';
    </script>";
    exit;
}

// Ambil data guru berdasarkan ID
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nip, nama, email, status_aktif
     FROM t_guru
     WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// Periksa apakah data ditemukan
if (!$guru) {
    echo "<script>
        alert('Data guru tidak ditemukan!');
        window.location.href = 'kelola_guru.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tindakan Data Guru</title>

    <!-- Bootstrap 5.3.3 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="card shadow">

                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Tindakan Data Guru</h4>
                </div>

                <div class="card-body">

                    <h5 class="mb-3">Informasi Guru</h5>

                    <table class="table table-bordered">
                        <tr>
                            <th width="35%">NIP</th>
                            <td>
                                <?= htmlspecialchars($guru['nip']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Nama Guru</th>
                            <td>
                                <?= htmlspecialchars($guru['nama']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>
                                <?= htmlspecialchars($guru['email']); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ($guru['status_aktif'] == 1) : ?>
                                    <span class="badge bg-success">
                                        Aktif
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary">
                                        Tidak Aktif
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h5 class="mb-3">Pilih Tindakan</h5>

                    <div class="d-flex flex-wrap gap-2">

                        <!-- Detail -->
                        <a
                            href="detail_guru.php?id=<?= (int) $guru['id']; ?>"
                            class="btn btn-info text-white">
                            Detail
                        </a>

                        <!-- Edit -->
                        <a
                            href="edit_guru.php?id=<?= (int) $guru['id']; ?>"
                            class="btn btn-warning">
                            Edit
                        </a>

                        <!-- Hapus -->
                        <form
                            action="proses_hapus_guru.php"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus data guru ini?');">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $guru['id']; ?>">

                            <button
                                type="submit"
                                class="btn btn-danger">
                                Hapus
                            </button>
                        </form>

                        <!-- Kembali -->
                        <a
                            href="kelola_guru.php"
                            class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>