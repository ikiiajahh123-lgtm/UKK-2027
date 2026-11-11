
<?php
// tambah_tahun_ajaran.php

include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Tahun Ajaran</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS SIDEBAR UTAMA -->
    <link rel="stylesheet" href="layout/style.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        :root {
            --bg: #101827;
            --panel: #1d2a3a;
            --border: rgba(161,179,205,.16);
            --muted: #9aa9bd;
            --text: #f4f7fb;
            --accent: #397cf6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', Arial, sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* =========================
           LAYOUT UTAMA
        ========================= */

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .main-area {
            min-width: 0;
            flex: 1;
        }

        .content-area {
            padding: 34px 4.2%;
            max-width: 1800px;
            margin: auto;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .app-shell > .sidebar {
            width: 104px;
            flex: 0 0 104px;
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #1c2939;
            border-right: 1px solid var(--border);
            padding: 18px 10px;
        }

        /* Menghilangkan tulisan MENU UTAMA */
        .sidebar-caption {
            display: none !important;
        }

        /* Menu tersusun vertikal */
        .sidebar-nav {
            width: 100%;
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch;
            gap: 8px;
        }

        .sidebar-link {
            width: 100%;
            min-height: 65px;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center;
            align-items: center;
            gap: 6px;
            color: #9eacbf;
            font-size: 11px;
            font-weight: 500;
            text-align: center;
            padding: 8px 3px;
            border-radius: 13px;
            transition: .2s;
        }

        .sidebar-link i {
            font-size: 20px;
        }

        .sidebar-link:hover {
            color: white;
            background: rgba(255,255,255,.06);
        }

        .sidebar-link.active {
            color: white;
            background: linear-gradient(135deg,#397cf6,#9853e8);
            box-shadow: 0 8px 20px rgba(57,124,246,.2);
        }

        .sidebar-bottom {
            width: 100%;
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding-top: 14px;
        }

        .logout-link {
            color: #ff777f !important;
        }

        /* =========================
           JUDUL HALAMAN
        ========================= */

        .page-heading {
            margin-bottom: 15px;
        }

        .page-heading h1 {
            font-size: 30px;
            font-weight: 800;
            color: var(--text);
            margin: 0 0 8px;
            letter-spacing: -.5px;
        }

        .page-heading p {
            color: var(--muted);
            font-size: 14px;
            margin: 0;
        }

        /* =========================
           TOMBOL KEMBALI
        ========================= */

        .btn-back {
            display: inline-block;
            margin-bottom: 24px;
            padding: 9px 15px;
            border-radius: 9px;
            background: #66717e;
            border-color: #66717e;
            color: white;
            font-size: 13px;
        }

        .btn-back:hover {
            background: #56616d;
            color: white;
        }

        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            max-width: 640px;
            width: 100%;
            margin: 0 auto;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }

        .form-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: #202f42;
        }

        .form-card-header h3 {
            margin: 0;
            color: white;
            font-size: 19px;
            font-weight: 700;
        }

        .form-card-header p {
            color: var(--muted);
            font-size: 12px;
            margin: 6px 0 0;
        }

        .form-card-body {
            padding: 25px;
        }

        /* =========================
           LABEL DAN INPUT
        ========================= */

        .form-label {
            color: #dce6f4;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            height: 44px;
            background-color: #29384b;
            color: white;
            border: 1px solid #40516a;
            border-radius: 8px;
            font-size: 13px;
            padding: 10px 13px;
        }

        .form-control::placeholder {
            color: #8f9db0;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #29384b;
            color: white;
            border-color: #397cf6;
            box-shadow: 0 0 0 3px rgba(57,124,246,.15);
        }

        .form-select option {
            background: #29384b;
            color: white;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        /* =========================
           TOMBOL
        ========================= */

        .btn {
            font-size: 13px;
            font-weight: 500;
            padding: 10px 17px;
            border-radius: 8px;
        }

        .btn-success {
            background: #159b70;
            border-color: #159b70;
        }

        .btn-success:hover {
            background: #10845f;
            border-color: #10845f;
        }

        .btn-secondary {
            background: #66717e;
            border-color: #66717e;
        }

        .btn-secondary:hover {
            background: #56616d;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {
            .content-area {
                padding: 25px 20px;
            }
        }

        @media (max-width: 700px) {

            .app-shell > .sidebar {
                width: 70px;
                flex-basis: 70px;
                padding: 12px 6px;
            }

            .sidebar-link {
                min-height: 58px;
                font-size: 9px;
            }

            .sidebar-link i {
                font-size: 18px;
            }

            .content-area {
                padding: 22px 12px;
            }

            .page-heading h1 {
                font-size: 24px;
            }

            .form-card {
                border-radius: 13px;
            }

            .form-card-header {
                padding: 17px;
            }

            .form-card-body {
                padding: 18px;
            }

            .btn {
                padding: 9px 12px;
            }
        }
    </style>

</head>

<body>

    <div class="app-shell">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <div class="main-area">

            <main class="content-area">

                <!-- HEADER -->
                <div class="page-heading">

                    <h1>Tambah Tahun Ajaran</h1>

                    <p>
                        Tambahkan data tahun ajaran baru ke dalam sistem.
                    </p>

                </div>

                <!-- TOMBOL KEMBALI -->
                <a href="kelola_tahun_ajaran.php"
                   class="btn btn-secondary btn-back">

                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Kelola Tahun Ajaran

                </a>

                <!-- FORM CARD -->
                <div class="form-card">

                    <div class="form-card-header">

                        <h3>
                            <i class="fas fa-calendar-plus me-2"></i>
                            Tambah Tahun Ajaran
                        </h3>

                        <p>
                            Lengkapi informasi tahun ajaran di bawah ini.
                        </p>

                    </div>

                    <div class="form-card-body">

                        <form action="proses_tambah_tahun_ajaran.php"
                              method="POST">

                            <!-- NAMA TAHUN AJARAN -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Nama Tahun Ajaran
                                </label>

                                <input
                                    type="text"
                                    name="nama_tahun_ajaran"
                                    class="form-control"
                                    placeholder="Contoh: 2026/2027"
                                    required>

                            </div>

                            <!-- TANGGAL MULAI -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Tanggal Mulai
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_mulai"
                                    class="form-control"
                                    required>

                            </div>

                            <!-- TANGGAL SELESAI -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Tanggal Selesai
                                </label>

                                <input
                                    type="date"
                                    name="tanggal_selesai"
                                    class="form-control"
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

                                    <option value="1">Aktif</option>

                                    <option value="0">Tidak Aktif</option>

                                </select>

                            </div>

                            <!-- TOMBOL -->
                            <div class="d-flex gap-2">

                                <button type="submit"
                                        class="btn btn-success">

                                    <i class="fas fa-save me-1"></i>
                                    Simpan

                                </button>

                                <a href="kelola_tahun_ajaran.php"
                                   class="btn btn-secondary">

                                    <i class="fas fa-arrow-left me-1"></i>
                                    Kembali

                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </main>

        </div>

    </div>

    <!-- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
