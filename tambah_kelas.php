
<?php
// tambah_kelas.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<<<<<<< HEAD
    <title>Tambah Kategori Pelanggaran</title>
=======
    <title>Tambah Wali Kelas</title>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
<<<<<<< HEAD

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

=======
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
        .form-card {
            border-radius: 7px;
<<<<<<< HEAD
            box-shadow: 0 5px 12px rgba(0,0,0,0.12);
            overflow: hidden;
            max-width: 640px;
            margin: 0 auto;
=======
            overflow: hidden;
            max-width: 850px;
            margin: 15px auto;
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
        }

        .form-card .card-header {
            background-color: #198754;
            color: white;
            padding: 10px 15px;
        }

        .form-card .card-header h3 {
            margin: 0;
            font-size: 27px;
            font-weight: bold;
        }

<<<<<<< HEAD
        .form-card-body {
            padding: 20px 16px;
=======
        .form-card .card-body {
            padding: 20px;
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
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

        @media (max-width: 768px) {
<<<<<<< HEAD

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
=======
            .form-card .card-body {
                padding: 16px;
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
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
