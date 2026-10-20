<?php
// edit_tahun_ajaran.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? '';

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

if (!$data) {
    die("Data tahun ajaran tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Tahun Ajaran</title>

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
                        method="POST">

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars($data['id']); ?>">


                        <!-- Nama Tahun Ajaran -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Tahun Ajaran
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama']); ?>"
                                placeholder="Contoh: 2026/2027"
                                required>

                        </div>


                        <!-- Tanggal Mulai -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="tanggal_mulai"
                                class="form-control"
                                value="<?= htmlspecialchars($data['tanggal_mulai']); ?>"
                                required>

                        </div>


                        <!-- Tanggal Selesai -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="tanggal_selesai"
                                class="form-control"
                                value="<?= htmlspecialchars($data['tanggal_selesai']); ?>"
                                required>

                        </div>


                        <!-- Status Aktif -->
                        <div class="mb-4">

                            <label class="form-label">
                                Status Aktif
                            </label>

                            <select
                                name="status_aktif"
                                class="form-select"
                                required>

                                <option value="1"
                                    <?= ($data['status_aktif'] == 1) ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= ($data['status_aktif'] == 0) ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>

                            </select>

                        </div>


                        <!-- Tombol -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success">

                                Update

                            </button>

                            <a
                                href="kelola_tahun_ajaran.php"
                                class="btn btn-secondary">

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