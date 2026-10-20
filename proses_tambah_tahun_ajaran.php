<?php
// edit_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

/*
|--------------------------------------------------------------------------
| Ambil ID
|--------------------------------------------------------------------------
| Mendukung id dari URL:
| edit_tahun_ajaran.php?id=1
| atau
| edit_tahun_ajaran.php?id_tahun_ajaran=1
|--------------------------------------------------------------------------
*/

$id = '';

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $_GET['id'];
} elseif (isset($_GET['id_tahun_ajaran']) && $_GET['id_tahun_ajaran'] !== '') {
    $id = $_GET['id_tahun_ajaran'];
}

/* Validasi ID */
if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kelola_tahun_ajaran.php");
    exit;
}

$id = (int)$id;

/*
|--------------------------------------------------------------------------
| Ambil data tahun ajaran
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id, nama, tanggal_mulai, tanggal_selesai, status_aktif
     FROM t_tahun_ajaran
     WHERE id = ?"
);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| Jika data tidak ditemukan
|--------------------------------------------------------------------------
*/

if (!$data) {
    die("
        <div style='font-family: Arial; padding: 30px;'>
            <h3>Data tahun ajaran tidak ditemukan.</h3>
            <p>ID yang dicari: <b>" . htmlspecialchars((string)$id) . "</b></p>
            <a href='kelola_tahun_ajaran.php'>Kembali ke Kelola Tahun Ajaran</a>
        </div>
    ");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Tahun Ajaran</title>

    <!-- Bootstrap 5.3.3 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow">

                <!-- Header -->
                <div class="card-header bg-success text-white">

                    <h3 class="mb-0">
                        Edit Tahun Ajaran
                    </h3>

                </div>

                <!-- Body -->
                <div class="card-body">

                    <form
                        action="proses_edit_tahun_ajaran.php"
                        method="POST"
                    >

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars($data['id']) ?>"
                        >

                        <!-- Nama -->
                        <div class="mb-3">

                            <label
                                for="nama"
                                class="form-label"
                            >
                                Nama Tahun Ajaran
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                maxlength="20"
                                value="<?= htmlspecialchars($data['nama']) ?>"
                                placeholder="Contoh: 2026/2027"
                                required
                            >

                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-3">

                            <label
                                for="tanggal_mulai"
                                class="form-label"
                            >
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="tanggal_mulai"
                                id="tanggal_mulai"
                                class="form-control"
                                value="<?= htmlspecialchars($data['tanggal_mulai']) ?>"
                                required
                            >

                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-3">

                            <label
                                for="tanggal_selesai"
                                class="form-label"
                            >
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="tanggal_selesai"
                                id="tanggal_selesai"
                                class="form-control"
                                value="<?= htmlspecialchars($data['tanggal_selesai']) ?>"
                                required
                            >

                        </div>

                        <!-- Status -->
                        <div class="mb-3">

                            <label
                                for="status_aktif"
                                class="form-label"
                            >
                                Status Aktif
                            </label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    <?= (string)$data['status_aktif'] === '1' ? 'selected' : '' ?>
                                >
                                    Aktif
                                </option>

                                <option
                                    value="0"
                                    <?= (string)$data['status_aktif'] === '0' ? 'selected' : '' ?>
                                >
                                    Tidak Aktif
                                </option>

                            </select>

                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Update
                            </button>

                            <a
                                href="kelola_tahun_ajaran.php"
                                class="btn btn-secondary"
                            >
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>