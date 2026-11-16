
<?php
// tambah_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ======================================
// AMBIL DATA TAHUN AJARAN
// ======================================

$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

if (!$tahun) {
    die("Query tahun ajaran gagal: " . mysqli_error($koneksi));
}

// ======================================
// AMBIL DATA KELAS
// ======================================

$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

if (!$kelas) {
    die("Query kelas gagal: " . mysqli_error($koneksi));
}

// ======================================
// AMBIL DATA GURU
// ======================================

$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);

if (!$guru) {
    die("Query guru gagal: " . mysqli_error($koneksi));
}

// ======================================
// KEAMANAN OUTPUT
// ======================================

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Wali Kelas</title>

    <!-- BOOTSTRAP 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- FONT AWESOME -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #0f172a !important;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        /* KONTEN UTAMA */
        .main-content {
            min-width: 0;
            min-height: 100vh;
            background: #0f172a;
        }

        /* JUDUL HALAMAN */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 36px;
            font-weight: normal;
            color: white;
            margin: 0;
        }

        /* TOMBOL KEMBALI */
        .btn-back {
            margin-bottom: 29px;
        }

        /* CARD FORM */
        .form-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 7px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.12);
            overflow: hidden;
            width: 100%;
            max-width: 805px;
            margin: 0 auto;
        }

        /* HEADER FORM */
        .form-card-header {
            background: #198754;
            color: white;
            padding: 10px 15px;
        }

        .form-card-header h3 {
            margin: 0;
            font-size: 27px;
            font-weight: bold;
        }

        /* BODY FORM */
        .form-card-body {
            padding: 20px 19px;
        }

        .form-label {
            font-size: 15px;
        }

        .form-control,
        .form-select {
            height: 38px;
            font-size: 15px;
        }

        .btn {
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            .main-content {
                padding: 15px !important;
            }

            .page-header h1 {
                font-size: 27px;
            }

            .form-card-header h3 {
                font-size: 23px;
            }

        }

    </style>

</head>

<body>

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <aside
        class="bg-dark flex-shrink-0 min-vh-100"
        style="width:215px; min-width:215px;"
    >

        <?php include __DIR__ . '/layout/sidebar.php'; ?>

    </aside>


    <!-- KONTEN UTAMA -->
    <main class="main-content flex-grow-1 p-3 p-md-4">

        <!-- HEADER -->
        <div class="page-header">

            <h1>Tambah Wali Kelas</h1>

        </div>


        <!-- TOMBOL KEMBALI -->
        <a
            href="kelola_walikelas.php"
            class="btn btn-secondary btn-back"
        >

            Kembali ke Kelola Wali Kelas

        </a>


        <!-- CARD FORM -->
        <div class="form-card">

            <!-- HEADER CARD -->
            <div class="form-card-header">

                <h3>Tambah Wali Kelas</h3>

            </div>


            <!-- BODY CARD -->
            <div class="form-card-body">

                <form
                    action="proses_tambah_walikelas.php"
                    method="POST"
                >

                    <!-- TAHUN AJARAN -->
                    <div class="mb-3">

                        <label class="form-label">
                            Tahun Ajaran
                        </label>

                        <select
                            name="tahun_ajaran_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Tahun Ajaran --
                            </option>

                            <?php while ($row = mysqli_fetch_assoc($tahun)): ?>

                                <option value="<?= e($row['id']); ?>">
                                    <?= e($row['nama']); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- KELAS -->
                    <div class="mb-3">

                        <label class="form-label">
                            Kelas
                        </label>

                        <select
                            name="kelas_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            <?php while ($row = mysqli_fetch_assoc($kelas)): ?>

                                <option value="<?= e($row['id']); ?>">
                                    <?= e($row['nama']); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- GURU -->
                    <div class="mb-3">

                        <label class="form-label">
                            Guru
                        </label>

                        <select
                            name="guru_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Guru --
                            </option>

                            <?php while ($row = mysqli_fetch_assoc($guru)): ?>

                                <option value="<?= e($row['id']); ?>">
                                    <?= e($row['nama']); ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- TANGGAL MULAI -->
                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="tanggal_mulai"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- TANGGAL SELESAI -->
                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="tanggal_selesai"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- STATUS -->
                    <div class="mb-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status_aktif"
                            class="form-select"
                            required
                        >

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Tidak Aktif
                            </option>

                        </select>

                    </div>


                    <!-- TOMBOL -->
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

    </main>

</div>


<!-- JAVASCRIPT BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
