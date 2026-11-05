
<?php
// tambah_guru.php
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">

    <style>
        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        .main-content {
            min-height: 100vh;
            background: #f4f6fb;
            padding: 20px;
        }

        .page-title {
            font-size: 32px;
            color: #142d50;
            margin-bottom: 20px;
        }

        .form-card {
            max-width: 512px;
            width: 100%;
            margin: 15px auto;
            background: white;
            border-radius: 7px;
            overflow: hidden;
            box-shadow: 0 5px 12px rgba(0,0,0,0.12);
        }

        .form-card-header {
            background: #198754;
            color: white;
            padding: 8px 13px;
        }

        .form-card-header h3 {
            margin: 0;
            font-size: 23px;
            font-weight: bold;
        }

        .form-card-body {
            padding: 16px 13px;
        }

        .form-control,
        .form-select {
            height: 38px;
            font-size: 14px;
        }

        .form-label {
            font-size: 14px;
            margin-bottom: 7px;
        }

        .btn {
            font-size: 13px;
        }

        @media (max-width: 767px) {
            .main-content {
                padding: 15px;
            }

            .form-card {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 main-content">

            <h1 class="page-title">Tambah Guru</h1>

            <a href="kelola_guru.php"
               class="btn btn-secondary mb-3">
                Kembali ke Kelola Guru
            </a>

            <!-- FORM TAMBAH GURU -->
            <div class="form-card">

                <div class="form-card-header">
                    <h3>Tambah Guru</h3>
                </div>

                <div class="form-card-body">

                    <form action="proses_tambah_guru.php"
                          method="POST"
                          id="formTambahGuru">

                        <!-- NIP -->
                        <div class="mb-3">
                            <label class="form-label">NIP</label>

                            <input type="text"
                                   name="nip"
                                   class="form-control"
                                   placeholder="Masukkan NIP guru"
                                   maxlength="30"
                                   required>
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label class="form-label">Nama Guru</label>

                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   placeholder="Masukkan nama guru"
                                   maxlength="100"
                                   required>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   placeholder="Masukkan email guru"
                                   required>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control"
                                   placeholder="Masukkan password"
                                   minlength="8"
                                   required>

                            <small class="text-muted">
                                Password minimal 8 karakter.
                            </small>
                        </div>

                        <!-- KONFIRMASI PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">
                                Konfirmasi Password
                            </label>

                            <input type="password"
                                   name="konfirmasi_password"
                                   id="konfirmasi_password"
                                   class="form-control"
                                   placeholder="Ulangi password"
                                   minlength="8"
                                   required>

                            <small id="pesanPassword"></small>
                        </div>

                        <!-- STATUS -->
                        <div class="mb-4">
                            <label class="form-label">Status Aktif</label>

                            <select name="status_aktif"
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
                                Simpan
                            </button>

                            <a href="kelola_guru.php"
                               class="btn btn-secondary">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<!-- VALIDASI PASSWORD -->
<script>
const form = document.getElementById('formTambahGuru');
const password = document.getElementById('password');
const konfirmasi = document.getElementById('konfirmasi_password');
const pesan = document.getElementById('pesanPassword');

function cekPassword() {

    if (konfirmasi.value === '') {
        konfirmasi.setCustomValidity('');
        pesan.textContent = '';
        return;
    }

    if (password.value !== konfirmasi.value) {

        konfirmasi.setCustomValidity('Password tidak sama.');

        pesan.textContent = 'Password tidak sama.';
        pesan.className = 'text-danger';

    } else {

        konfirmasi.setCustomValidity('');

        pesan.textContent = 'Password cocok.';
        pesan.className = 'text-success';
    }
}

password.addEventListener('input', cekPassword);
konfirmasi.addEventListener('input', cekPassword);

form.addEventListener('submit', function(event) {

    cekPassword();

    if (!form.checkValidity()) {
        event.preventDefault();
        form.reportValidity();
    }

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
