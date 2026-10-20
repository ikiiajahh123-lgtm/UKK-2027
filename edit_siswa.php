<?php
// edit_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Cek apakah ID tersedia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kelola_siswa.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data siswa
$sql = "SELECT * FROM t_siswa WHERE id = '$id'";
$hasil = mysqli_query($koneksi, $sql);

// Cek query
if (!$hasil) {
    die("Query error: " . mysqli_error($koneksi));
}

// Cek data ditemukan
if (mysqli_num_rows($hasil) == 0) {
    echo "<script>
            alert('Data siswa tidak ditemukan!');
            window.location='kelola_siswa.php';
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($hasil);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Siswa</title>

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
                        Edit Siswa
                    </h3>

                </div>

                <!-- Body -->
                <div class="card-body">

                    <form action="proses_edit_siswa.php"
                          method="POST">

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars($data['id']); ?>">

                        <!-- NIS -->
                        <div class="mb-3">

                            <label class="form-label">
                                NIS
                            </label>

                            <input
                                type="text"
                                name="nis"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nis']); ?>"
                                required>

                        </div>


                        <!-- NISN -->
                        <div class="mb-3">

                            <label class="form-label">
                                NISN
                            </label>

                            <input
                                type="text"
                                name="nisn"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nisn']); ?>"
                                required>

                        </div>


                        <!-- Nama -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Siswa
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama']); ?>"
                                required>

                        </div>


                        <!-- Jenis Kelamin -->
                        <div class="mb-3">

                            <label class="form-label">
                                Jenis Kelamin
                            </label>

                            <select
                                name="jenis_kelamin"
                                class="form-select"
                                required>

                                <option value="L"
                                    <?php
                                    if ($data['jenis_kelamin'] == 'L') {
                                        echo 'selected';
                                    }
                                    ?>>
                                    Laki-laki
                                </option>

                                <option value="P"
                                    <?php
                                    if ($data['jenis_kelamin'] == 'P') {
                                        echo 'selected';
                                    }
                                    ?>>
                                    Perempuan
                                </option>

                            </select>

                        </div>


                        <!-- Tanggal Lahir -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                name="tanggal_lahir"
                                class="form-control"
                                value="<?= htmlspecialchars($data['tanggal_lahir']); ?>"
                                required>

                        </div>


                        <!-- Alamat -->
                        <div class="mb-3">

                            <label class="form-label">
                                Alamat
                            </label>

                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="4"
                                required><?= htmlspecialchars($data['alamat']); ?></textarea>

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
                                    <?php
                                    if ($data['status_aktif'] == 1) {
                                        echo 'selected';
                                    }
                                    ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?php
                                    if ($data['status_aktif'] == 0) {
                                        echo 'selected';
                                    }
                                    ?>>
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
                                href="kelola_siswa.php"
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