<?php
// tambah_guru.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Guru</title>

    <!-- Bootstrap 5.3.3 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="card shadow">
                <!-- Header -->
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Tambah Guru</h3>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <form
                        action="proses_tambah_guru.php"
                        method="POST"
                        id="formTambahGuru">

                        <!-- NIP -->
                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input
                                type="text"
                                name="nip"
                                class="form-control"
                                placeholder="Masukkan NIP"
                                required>
                        </div>

                        <!-- Nama Guru -->
                        <div class="mb-3">
                            <label class="form-label">Nama Guru</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Masukkan nama guru"
                                required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Masukkan email"
                                required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                minlength="8"
                                required>
                            <div class="form-text">
                                Password minimal 8 karakter.
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label class="form-label">
                                Konfirmasi Password
                            </label>
                            <input
                                type="password"
                                name="konfirmasi_password"
                                id="konfirmasi_password"
                                class="form-control"
                                placeholder="Ulangi password"
                                minlength="8"
                                required>

                            <div
                                id="pesanPassword"
                                class="form-text">
                            </div>
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

                        <!-- Tombol -->
                        <div class="d-flex gap-2">
                            <button
                                type="submit"
                                class="btn btn-success">
                                Simpan
                            </button>

                            <a
                                href="kelola_guru.php"
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

<!-- Validasi Password -->
<script>
const form = document.getElementById('formTambahGuru');
const password = document.getElementById('password');
const konfirmasi = document.getElementById('konfirmasi_password');
const pesan = document.getElementById('pesanPassword');

function cekPassword() {
    if (konfirmasi.value === '') {
        konfirmasi.setCustomValidity('');
        pesan.textContent = '';
        pesan.className = 'form-text';
        return;
    }

    if (password.value !== konfirmasi.value) {
        konfirmasi.setCustomValidity('Password tidak sama.');
        pesan.textContent = 'Password tidak sama.';
        pesan.className = 'form-text text-danger';
    } else {
        konfirmasi.setCustomValidity('');
        pesan.textContent = 'Password cocok.';
        pesan.className = 'form-text text-success';
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

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>