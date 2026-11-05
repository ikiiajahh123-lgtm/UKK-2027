
<?php
// tambah_pelanggaran_kategori.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori Pelanggaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 211px;
            height: 100vh;
            background: #212529;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 10px 2px;
            overflow-y: auto;
        }

        .sidebar h4 {
            font-size: 23px;
            font-weight: normal;
            margin-bottom: 25px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 11px 16px;
            margin-bottom: 5px;
            border-radius: 7px;
            font-size: 15px;
        }

        .sidebar a:hover {
            background: #343a40;
        }

        .sidebar a.active {
            background: #0d6efd;
        }

        .sidebar hr {
            border-color: #495057;
            margin: 20px 0;
        }

        .sidebar .logout {
            color: #dc3545;
        }

        /* KONTEN UTAMA */
        .main-content {
            margin-left: 211px;
            padding: 16px 18px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: normal;
            color: #102b50;
            margin: 0;
        }

        /* FORM */
        .btn-back {
            margin-bottom: 12px;
        }

        .form-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 7px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.12);
            overflow: hidden;
            max-width: 640px;
            margin: 0 auto;
        }

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

        .form-card-body {
            padding: 20px 16px;
        }

        .form-label {
            font-size: 15px;
        }

        .form-control,
        .form-select {
            font-size: 15px;
        }

        .form-control:not(textarea),
        .form-select {
            height: 38px;
        }

        textarea.form-control {
            min-height: 110px;
            resize: vertical;
        }

        .btn {
            font-size: 14px;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {

            .sidebar {
                width: 170px;
            }

            .main-content {
                margin-left: 170px;
                padding: 15px;
            }

            .page-header h1 {
                font-size: 26px;
            }

        }

        @media (max-width: 500px) {

            .sidebar {
                width: 125px;
            }

            .sidebar h4 {
                font-size: 17px;
            }

            .sidebar a {
                font-size: 12px;
                padding: 10px 8px;
            }

            .main-content {
                margin-left: 125px;
                padding: 12px;
            }

        }

    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h4>Pelanggaran<br>Siswa</h4>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="data_siswa.php">
            Data Siswa
        </a>

        <a href="data_guru.php">
            Data Guru
        </a>

        <a href="kelola_kelas.php">
            Data Kelas
        </a>

        <a href="kelola_tahun_ajaran.php">
            Data Tahun Ajaran
        </a>

        <a href="kelola_walikelas.php">
            Data Wali Kelas
        </a>

        <a href="penempatan_siswa.php">
            Penempatan Siswa
        </a>

        <a href="kelola_pelanggaran_kategori.php" class="active">
            Kategori Pelanggaran
        </a>

        <a href="about.php">
            About Me
        </a>

        <hr>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>


    <!-- KONTEN UTAMA -->
    <div class="main-content">

        <!-- HEADER -->
        <div class="page-header">

            <h1>Tambah Kategori Pelanggaran</h1>

        </div>


        <!-- TOMBOL KEMBALI -->
        <a
            href="kelola_pelanggaran_kategori.php"
            class="btn btn-secondary btn-back">

            Kembali ke Kelola Kategori

        </a>


        <!-- FORM CARD -->
        <div class="form-card">

            <div class="form-card-header">

                <h3>Tambah Kategori Pelanggaran</h3>

            </div>

            <div class="form-card-body">

                <form
                    action="proses_tambah_pelanggaran_kategori.php"
                    method="POST">


                    <!-- NAMA KATEGORI -->
                    <div class="mb-3">

                        <label for="nama" class="form-label">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            placeholder="Contoh: Pelanggaran Ringan"
                            maxlength="100"
                            required>

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="mb-3">

                        <label for="deskripsi" class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="deskripsi"
                            id="deskripsi"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan deskripsi kategori pelanggaran"></textarea>

                    </div>


                    <!-- STATUS AKTIF -->
                    <div class="mb-3">

                        <label for="status_aktif" class="form-label">
                            Status Aktif
                        </label>

                        <select
                            name="status_aktif"
                            id="status_aktif"
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
                            href="kelola_pelanggaran_kategori.php"
                            class="btn btn-secondary">

                            Kembali

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
