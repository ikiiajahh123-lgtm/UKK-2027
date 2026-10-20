<?php
// tambah_walikelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Wali Kelas</title>

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
                    <h3 class="mb-0">Tambah Wali Kelas</h3>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <form action="proses_tambah_walikelas.php" method="POST">

                        <!-- Tahun Ajaran -->
                        <div class="mb-3">
                            <label for="tahun_ajaran_id" class="form-label">
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
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <!-- Kelas -->
                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">
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
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <!-- Guru -->
                        <div class="mb-3">
                            <label for="guru_id" class="form-label">
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
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">
                                Tanggal Mulai
                            </label>

                            <input
                                type="date"
                                name="tanggal_mulai"
                                id="tanggal_mulai"
                                class="form-control"
                                required
                            >
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">
                                Tanggal Selesai
                            </label>

                            <input
                                type="date"
                                name="tanggal_selesai"
                                id="tanggal_selesai"
                                class="form-control"
                                required
                            >
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status_aktif" class="form-label">
                                Status
                            </label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required
                            >
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Simpan
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

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>