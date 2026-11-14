
<?php
// tambah_kelas.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Wali Kelas</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        .form-card {
            border-radius: 7px;
            overflow: hidden;
            max-width: 850px;
            margin: 15px auto;
        }

        .form-card .card-header {
            background-color: #198754;
            color: white;
            padding: 12px 16px;
        }

        .form-card .card-header h3 {
            margin: 0;
            font-size: 27px;
            font-weight: bold;
        }

        .form-card .card-body {
            padding: 20px;
        }

        .form-label {
            font-size: 15px;
        }

        .form-control,
        .form-select {
            height: 40px;
            font-size: 15px;
        }

        .btn {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .form-card .card-body {
                padding: 16px;
            }
        }
    </style>

</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->
        <main class="p-4 flex-grow-1" style="min-width: 0; flex-basis: 0;">

            <!-- JUDUL HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1>Tambah Kelas</h1>

            </div>

            <!-- TOMBOL KEMBALI -->
            <a href="kelola_kelas.php" class="btn btn-secondary mb-3">
                Kembali ke Kelola Kelas
            </a>

            <!-- FORM TAMBAH KELAS -->
            <div class="form-card">

                <div class="card shadow-sm">

                    <!-- HEADER -->
                    <div class="card-header">

                        <h3>Tambah Kelas</h3>

                    </div>

                    <!-- BODY -->
                    <div class="card-body">

                        <form action="proses_tambah_kelas.php" method="POST">

                            <!-- NAMA KELAS -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Kelas
                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Contoh: XI RPL 1"
                                    required>

                            </div>

                            <!-- TINGKAT -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Tingkat
                                </label>

                                <input
                                    type="text"
                                    name="tingkat"
                                    class="form-control"
                                    placeholder="Contoh: 11"
                                    required>

                            </div>

                            <!-- JURUSAN -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Jurusan
                                </label>

                                <input
                                    type="text"
                                    name="jurusan"
                                    class="form-control"
                                    placeholder="Contoh: Rekayasa Perangkat Lunak"
                                    required>

                            </div>

                            <!-- STATUS AKTIF -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Status Aktif
                                </label>

                                <select
                                    name="status_aktif"
                                    class="form-select"
                                    required>

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
                                    class="btn btn-success">

                                    Simpan

                                </button>

                                <a
                                    href="kelola_kelas.php"
                                    class="btn btn-secondary">

                                    Kembali

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
