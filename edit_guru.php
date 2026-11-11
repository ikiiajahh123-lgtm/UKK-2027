
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kelola_guru.php");
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_guru WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$guru) {
    header("Location: kelola_guru.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Guru - SIPES</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border-radius: 8px;
            overflow: hidden;
        }

        .card-header {
            padding: 10px 18px;
        }

        .card-header h3 {
            font-size: 30px;
            font-weight: 600;
        }

        .card-body {
            padding: 18px;
        }

        .form-label {
            font-size: 16px;
            color: #212529;
        }

        .form-control,
        .form-select {
            min-height: 42px;
            font-size: 16px;
        }

        .password-info {
            font-size: 13px;
            color: #6c757d;
        }

        .btn {
            font-size: 16px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow">

                <!-- Header Hijau -->
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Edit Guru</h3>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <form action="proses_edit_guru.php" method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= htmlspecialchars($guru['id']) ?>"
                        >

                        <!-- NIP -->
                        <div class="mb-3">
                            <label for="nip" class="form-label">
                                NIP
                            </label>

                            <input
                                type="text"
                                name="nip"
                                id="nip"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($guru['nip'] ?? '') ?>"
                            >
                        </div>

                        <!-- Nama Guru -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Guru
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($guru['nama'] ?? '') ?>"
                            >
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($guru['email'] ?? '') ?>"
                            >
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password Baru
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Masukkan password baru"
                                minlength="8"
                                autocomplete="new-password"
                            >

                            <small class="password-info">
                                Kosongkan jika tidak ingin mengganti password.
                                Password minimal 8 karakter.
                            </small>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3">
                            <label for="konfirmasi_password" class="form-label">
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                name="konfirmasi_password"
                                id="konfirmasi_password"
                                class="form-control"
                                placeholder="Ulangi password baru"
                                minlength="8"
                                autocomplete="new-password"
                            >
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <label for="status_aktif" class="form-label">
                                Status Aktif
                            </label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    <?= (string)$guru['status_aktif'] === '1' ? 'selected' : '' ?>
                                >
                                    Aktif
                                </option>

                                <option
                                    value="0"
                                    <?= (string)$guru['status_aktif'] === '0' ? 'selected' : '' ?>
                                >
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <!-- User ID -->
                        <div class="mb-3">
                            <label class="form-label">
                                User ID
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($guru['user_id'] ?? '-') ?>"
                                disabled
                            >
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Simpan Perubahan
                            </button>

                            <a
                                href="kelola_guru.php"
                                class="btn btn-secondary"
                            >
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const konfirmasi = document.getElementById('konfirmasi_password').value;

    if (password !== '' || konfirmasi !== '') {
        if (password.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter!');
            return;
        }

        if (password !== konfirmasi) {
            e.preventDefault();
            alert('Konfirmasi password tidak sama!');
            return;
        }
    }
});
</script>

</body>
</html>
