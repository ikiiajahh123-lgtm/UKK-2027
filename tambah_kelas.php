
<?php
// tambah_kelas.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            padding: 16px 24px;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 40px;
            font-weight: normal;
            color: #102b50;
            margin: 0;
        }

        /* TOMBOL KEMBALI */
        .btn-back {
            margin-bottom: 16px;
        }

        /* FORM CARD */
        .form-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 7px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            overflow: hidden;
            max-width: 850px;
            margin: 0 auto;
        }

        .form-card-header {
            background: #198754;
            color: white;
            padding: 12px 16px;
        }

        .form-card-header h3 {
            margin: 0;
            font-size: 27px;
            font-weight: bold;
        }

        .form-card-body {
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
                font-size: 28px;
            }

            .form-card-header h3 {
                font-size: 23px;
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
<?php include 'layout/sidebar.php'; ?>


    <!-- KONTEN UTAMA -->
     <main class="p-4 flex-grow-1" style="min-width: 0; flex-basis: 0;"">
    <div class="main-content">

        <!-- HEADER -->
        <div class="page-header">

            <h1>Tambah Kelas</h1>

        </div>


        <!-- TOMBOL KEMBALI -->
        <a href="kelola_kelas.php" class="btn btn-secondary btn-back">
            Kembali ke Kelola Kelas
        </a>


        <!-- FORM TAMBAH KELAS -->
        <div class="form-card">

            <div class="form-card-header">
                <h3>Tambah Kelas</h3>
            </div>

            <div class="form-card-body">

                <form action="proses_tambah_kelas.php" method="POST">

                    <!-- Nama Kelas -->
                    <div class="mb-3">

                        <label class="form-label">Nama Kelas</label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Contoh: XI RPL 1"
                            required>

                    </div>


                    <!-- Tingkat -->
                    <div class="mb-3">

                        <label class="form-label">Tingkat</label>

                        <input
                            type="text"
                            name="tingkat"
                            class="form-control"
                            placeholder="Contoh: 11"
                            required>

                    </div>


                    <!-- Jurusan -->
                    <div class="mb-3">

                        <label class="form-label">Jurusan</label>

                        <input
                            type="text"
                            name="jurusan"
                            class="form-control"
                            placeholder="Contoh: Rekayasa Perangkat Lunak"
                            required>

                    </div>


                    <!-- Status Aktif -->
                    <div class="mb-4">

                        <label class="form-label">Status Aktif</label>

                        <select
                            name="status_aktif"
                            class="form-select"
                            required>

                            <option value="1">Aktif</option>

                            <option value="0">Tidak Aktif</option>

                        </select>

                    </div>


                    <!-- TOMBOL -->
                    <div class="d-flex gap-2">

                        <button type="submit" class="btn btn-success">
                            Simpan
                        </button>

                        <a href="kelola_kelas.php" class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>
        </main>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
