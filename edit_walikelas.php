<?php
// edit_walikelas.php

include "includes/cek_session.php";
include "config/koneksi.php";

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '' || !is_numeric($id)) {
    echo "<script>
            alert('ID wali kelas tidak ditemukan');
            window.location='kelola_walikelas.php';
          </script>";
    exit;
}

$id = (int) $id;

/* Ambil data wali kelas */
$query = mysqli_query(
    $koneksi,
    "SELECT * FROM t_wali_kelas WHERE id = $id"
);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
            alert('Data wali kelas tidak ditemukan');
            window.location='kelola_walikelas.php';
          </script>";
    exit;
}

/* Ambil tahun ajaran */
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

if (!$tahun) {
    die("Data tahun ajaran gagal diambil: " . mysqli_error($koneksi));
}

/* Ambil kelas */
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

if (!$kelas) {
    die("Data kelas gagal diambil: " . mysqli_error($koneksi));
}

/* Ambil guru */
$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);

if (!$guru) {
    die("Data guru gagal diambil: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Wali Kelas</title>

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

                <div class="card-header bg-success text-white">

                    <h3 class="mb-0">
                        Edit Wali Kelas
                    </h3>

                </div>

                <div class="card-body">

                    <form
                        action="proses_edit_walikelas.php"
                        method="POST"
                    >

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?= $data['id']; ?>"
                        >

                        <!-- Tahun Ajaran -->
                        <div class="mb-3">

                            <label
                                for="tahun_ajaran_id"
                                class="form-label"
                            >
                                Tahun Ajaran
                            </label>

                            <select
                                name="tahun_ajaran_id"
                                id="tahun_ajaran_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>

                                    <option
                                        value="<?= $row['id']; ?>"
                                        <?= ($data['tahun_ajaran_id'] == $row['id']) ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Kelas -->
                        <div class="mb-3">

                            <label
                                for="kelas_id"
                                class="form-label"
                            >
                                Kelas
                            </label>

                            <select
                                name="kelas_id"
                                id="kelas_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>

                                    <option
                                        value="<?= $row['id']; ?>"
                                        <?= ($data['kelas_id'] == $row['id']) ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Guru -->
                        <div class="mb-3">

                            <label
                                for="guru_id"
                                class="form-label"
                            >
                                Guru
                            </label>

                            <select
                                name="guru_id"
                                id="guru_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Guru --
                                </option>

                                <?php while ($row = mysqli_fetch_assoc($guru)) { ?>

                                    <option
                                        value="<?= $row['id']; ?>"
                                        <?= ($data['guru_id'] == $row['id']) ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>

                                <?php } ?>

                            </select>

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
                                value="<?= htmlspecialchars($data['tanggal_mulai']); ?>"
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
                                value="<?= htmlspecialchars($data['tanggal_selesai']); ?>"
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
                                    <?= ($data['status_aktif'] == 1) ? 'selected' : ''; ?>
                                >
                                    Aktif
                                </option>

                                <option
                                    value="0"
                                    <?= ($data['status_aktif'] == 0) ? 'selected' : ''; ?>
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
                                href="kelola_walikelas.php"
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

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>