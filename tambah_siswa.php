
<?php
// tambah_siswa.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- CSS Layout -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        .form-card {
            border-radius: 8px;
            overflow: hidden;
        }

        .form-card .card-header {
            background-color: #198754;
            color: white;
            padding: 12px 16px;
        }

        .form-card .card-header h4 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .form-card .card-body {
            padding: 25px;
        }

        .form-label {
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 40px;
        }

        .form-container {
            max-width: 850px;
            margin: 15px auto;
        }

        @media (max-width: 768px) {
            .form-card .card-body {
                padding: 16px;
            }

            .form-container {
                margin: 10px auto;
            }
        }
    </style>

</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR SAMA DENGAN KELOLA SISWA -->
        <?php include 'layout/sidebar.php'; ?>


        <!-- KONTEN -->
        <main class="p-4 flex-grow-1" style="min-width: 0; flex-basis: 0;">

            <!-- JUDUL HALAMAN -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1>Tambah Siswa</h1>

            </div>

            <!-- TOMBOL KEMBALI -->
            <a href="kelola_siswa.php" class="btn btn-secondary mb-3">
                Kembali ke Data Siswa
            </a>


            <!-- FORM TAMBAH SISWA -->
            <div class="form-container">

                <div class="card shadow-sm form-card">

                    <!-- HEADER -->
                    <div class="card-header">

                        <h4>Tambah Siswa</h4>

                    </div>


                    <!-- BODY -->
                    <div class="card-body">

                        <form action="proses_tambah_siswa.php" method="POST">

                            <!-- NIS -->
                            <div class="mb-3">

                                <label class="form-label">
                                    NIS
                                </label>

                                <input
                                    type="text"
                                    name="nis"
                                    class="form-control"
                                    placeholder="Masukkan NIS"
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
                                    placeholder="Masukkan NISN"
                                    required>

                            </div>


                            <!-- NAMA SISWA -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Siswa
                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Masukkan nama siswa"
                                    required>

                            </div>


                            <!-- JENIS KELAMIN -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Jenis Kelamin
                                </label>

                                <select
                                    name="jenis_kelamin"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        -- Pilih Jenis Kelamin --
                                    </option>

                                    <option value="L">
                                        Laki-laki
                                    </option>

                                    <option value="P">
                                        Perempuan
                                    </option>

                                </select>

                            </div>


                            <!-- TANGGAL LAHIR -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Tanggal Lahir
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_lahir"
                                    class="form-control"
                                    required>

                            </div>


                            <!-- ALAMAT -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Alamat
                                </label>

                                <textarea
                                    name="alamat"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Masukkan alamat siswa"
                                    required></textarea>

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
                                    href="kelola_siswa.php"
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


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
