
<?php
// edit_walikelas.php

include "includes/cek_session.php";
include "config/koneksi.php";

// =====================================
// PROSES UPDATE DALAM FILE YANG SAMA
// =====================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id'] ?? 0);
    $tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $guru_id = (int)($_POST['guru_id'] ?? 0);
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = (int)($_POST['status_aktif'] ?? -1);

    if (
        $id <= 0 ||
        $tahun_ajaran_id <= 0 ||
        $kelas_id <= 0 ||
        $guru_id <= 0 ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === '' ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        echo "<script>
            alert('Data tidak lengkap atau tidak valid!');
            window.history.back();
        </script>";
        exit;
    }

    if ($tanggal_selesai < $tanggal_mulai) {
        echo "<script>
            alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
            window.history.back();
        </script>";
        exit;
    }

    $sqlUpdate = "
        UPDATE t_wali_kelas
        SET
            tahun_ajaran_id = ?,
            kelas_id = ?,
            guru_id = ?,
            tanggal_mulai = ?,
            tanggal_selesai = ?,
            status_aktif = ?
        WHERE id = ?
    ";

    $stmt = mysqli_prepare($koneksi, $sqlUpdate);

    if (!$stmt) {
        die("Query update gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "iiissii",
        $tahun_ajaran_id,
        $kelas_id,
        $guru_id,
        $tanggal_mulai,
        $tanggal_selesai,
        $status_aktif,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

        echo "<script>
            alert('Data wali kelas berhasil diperbarui!');
            window.location='kelola_walikelas.php';
        </script>";
        exit;
    }

    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    die("Gagal memperbarui data: " . htmlspecialchars($error));
}

// =====================================
// AMBIL ID DATA
// =====================================

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '' || !is_numeric($id)) {
    echo "<script>
        alert('ID wali kelas tidak ditemukan');
        window.location='kelola_walikelas.php';
    </script>";
    exit;
}

$id = (int)$id;

// =====================================
// AMBIL DATA WALI KELAS
// =====================================

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

// =====================================
// AMBIL DATA DROPDOWN
// =====================================

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

if (!$tahun || !$kelas || !$guru) {
    die("Data dropdown gagal diambil: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Wali Kelas</title>

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
                    <h3 class="mb-0">Edit Wali Kelas</h3>
                </div>

                <div class="card-body">

                    <!-- FORM DIKIRIM KE FILE INI SENDIRI -->
                    <form action="edit_walikelas.php?id=<?= $id; ?>" method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= (int)$data['id']; ?>"
                        >

                        <div class="mb-3">
                            <label for="tahun_ajaran_id" class="form-label">
                                Tahun Ajaran
                            </label>

                            <select name="tahun_ajaran_id" id="tahun_ajaran_id"
                                    class="form-select" required>

                                <option value="">-- Pilih Tahun Ajaran --</option>

                                <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>
                                    <option
                                        value="<?= (int)$row['id']; ?>"
                                        <?= ($data['tahun_ajaran_id'] == $row['id']) ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">
                                Kelas
                            </label>

                            <select name="kelas_id" id="kelas_id"
                                    class="form-select" required>

                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                                    <option
                                        value="<?= (int)$row['id']; ?>"
                                        <?= ($data['kelas_id'] == $row['id']) ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="guru_id" class="form-label">
                                Guru
                            </label>

                            <select name="guru_id" id="guru_id"
                                    class="form-select" required>

                                <option value="">-- Pilih Guru --</option>

                                <?php while ($row = mysqli_fetch_assoc($guru)) { ?>
                                    <option
                                        value="<?= (int)$row['id']; ?>"
                                        <?= ($data['guru_id'] == $row['id']) ? 'selected' : ''; ?>
                                    >
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">
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

                        <div class="mb-3">
                            <label for="tanggal_selesai" class="form-label">
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

                        <div class="mb-3">
                            <label for="status_aktif" class="form-label">
                                Status Aktif
                            </label>

                            <select name="status_aktif" id="status_aktif"
                                    class="form-select" required>

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

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                Update
                            </button>

                            <a href="kelola_walikelas.php"
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
