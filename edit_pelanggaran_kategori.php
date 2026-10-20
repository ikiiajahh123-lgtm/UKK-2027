<?php
// edit_pelanggaran_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? '';

$sql = "SELECT * FROM t_pelanggaran_kategori WHERE id = '$id'";
$hasil = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($hasil);

if (!$data) {
    die("Data kategori pelanggaran tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Kategori Pelanggaran</title>

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
                        Edit Kategori Pelanggaran
                    </h3>

                </div>

                <!-- Body -->
                <div class="card-body">

                    <form
                        action="proses_edit_pelanggaran_kategori.php"
                        method="POST">

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars($data['id']); ?>">


                        <!-- Nama Kategori -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Kategori
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama']); ?>"
                                required>

                        </div>


                        <!-- Deskripsi -->
                        <div class="mb-3">

                            <label class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="4"><?= htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea>

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

                                <option
                                    value="1"
                                    <?= ($data['status_aktif'] == 1)
                                        ? 'selected'
                                        : ''; ?>>

                                    Aktif

                                </option>

                                <option
                                    value="0"
                                    <?= ($data['status_aktif'] == 0)
                                        ? 'selected'
                                        : ''; ?>>

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
                                href="kelola_pelanggaran_kategori.php"
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