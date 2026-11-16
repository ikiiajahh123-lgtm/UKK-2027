
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

        <!-- JUDUL HALAMAN -->
        <div class="page-header">

            <h1>Tambah Kategori Pelanggaran</h1>

        </div>


        <!-- TOMBOL KEMBALI -->
        <a
            href="kelola_pelanggaran_kategori.php"
            class="btn btn-secondary btn-back"
        >

            Kembali ke Kelola Kategori

        </a>


        <!-- CARD FORM -->
        <div class="form-card">

            <!-- HEADER CARD -->
            <div class="form-card-header">

                <h3>Tambah Kategori Pelanggaran</h3>

            </div>


            <!-- BODY FORM -->
            <div class="form-card-body">

                <form
                    action="proses_tambah_pelanggaran_kategori.php"
                    method="POST"
                >

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
                            required
                        >

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
                            placeholder="Masukkan deskripsi kategori pelanggaran"
                        ></textarea>

                    </div>


                    <!-- STATUS AKTIF -->
                    <div class="mb-4">

                        <label for="status_aktif" class="form-label">
                            Status Aktif
                        </label>

                        <select
                            name="status_aktif"
                            id="status_aktif"
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
                            href="kelola_pelanggaran_kategori.php"
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


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
