
<?php
session_start();

require_once 'config/koneksi.php';

// Cek login admin
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    ($_SESSION['role'] ?? '') !== 'admin'
) {
    header("Location: login.php");
    exit;
}

$error = "";

// Proses simpan data guru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi = $_POST['konfirmasi_password'] ?? '';
    $status = $_POST['status_aktif'] ?? 'Aktif';

    if (
        $nip === '' ||
        $nama === '' ||
        $email === '' ||
        $password === '' ||
        $konfirmasi === ''
    ) {
        $error = "Semua kolom wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";

    } elseif (strlen($password) < 8) {
        $error = "Password minimal 8 karakter.";

    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";

    } elseif (!in_array($status, ['Aktif', 'Nonaktif'], true)) {
        $error = "Status tidak valid.";

    } else {

        try {
            $koneksi->begin_transaction();

            // Cek NIP
            $cekNip = $koneksi->prepare(
                "SELECT nip FROM t_guru WHERE nip = ? LIMIT 1"
            );

            $cekNip->bind_param("s", $nip);
            $cekNip->execute();

            if ($cekNip->get_result()->num_rows > 0) {
                throw new Exception("NIP guru sudah terdaftar.");
            }

            $cekNip->close();

            // Cek email
            $cekEmail = $koneksi->prepare(
                "SELECT id FROM t_users WHERE email = ? LIMIT 1"
            );

            $cekEmail->bind_param("s", $email);
            $cekEmail->execute();

            if ($cekEmail->get_result()->num_rows > 0) {
                throw new Exception("Email sudah digunakan.");
            }

            $cekEmail->close();

            // Enkripsi password
            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $waktu = date('Y-m-d H:i:s');

            // Simpan akun guru
            $queryUser = $koneksi->prepare(
                "INSERT INTO t_users
                (name, email, password, role, created_at, updated_at)
                VALUES (?, ?, ?, 'guru', ?, ?)"
            );

            $queryUser->bind_param(
                "sssss",
                $nama,
                $email,
                $passwordHash,
                $waktu,
                $waktu
            );

            $queryUser->execute();

            $user_id = $koneksi->insert_id;

            $queryUser->close();

            // Simpan data guru
            $queryGuru = $koneksi->prepare(
                "INSERT INTO t_guru
                (user_id, nip, nama, email, status_aktif)
                VALUES (?, ?, ?, ?, ?)"
            );

            $queryGuru->bind_param(
                "issss",
                $user_id,
                $nip,
                $nama,
                $email,
                $status
            );

            $queryGuru->execute();
            $queryGuru->close();

            $koneksi->commit();

            header("Location: kelola_guru.php?pesan=berhasil");
            exit;

        } catch (Exception $e) {

            $koneksi->rollback();
            $error = $e->getMessage();
        }
    }
}

function aman($nilai) {
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}
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
        /* AREA KONTEN */
        .guru-content {
            padding: 0 4% 30px;
            max-width: 1500px;
            margin: auto;
        }

        /* TOMBOL KEMBALI ATAS */
        .guru-back {
            display: inline-block;
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 7px;
            margin-bottom: 22px;
            font-size: 15px;
        }

        .guru-back:hover {
            background: #5c636a;
            color: white;
        }

        /* KARTU FORM */
        .guru-form-card {
            max-width: 886px;
            margin: 10px auto 30px;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 9px;
            overflow: hidden;
            box-shadow: none;
        }

        /* HEADER HIJAU */
        .guru-form-header {
            background: #198754;
            padding: 12px 18px;
            border-bottom: 1px solid #198754;
        }

        .guru-form-header h2 {
            color: #ffffff;
            font-size: 25px;
            font-weight: 700;
            margin: 0;
        }

        /* ISI FORM */
        .guru-form-body {
            padding: 25px 27px;
            background: #ffffff;
        }

        .guru-form-body label {
            display: block;
            color: #212529;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 8px;
        }

        /* INPUT PUTIH */
        .guru-form-body .form-control,
        .guru-form-body .form-select {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            color: #212529;
            padding: 9px 13px;
            border-radius: 7px;
            font-size: 16px;
            min-height: 42px;
        }

        .guru-form-body .form-control::placeholder {
            color: #6c757d;
            opacity: 1;
        }

        .guru-form-body .form-control:focus,
        .guru-form-body .form-select:focus {
            background-color: #ffffff;
            color: #212529;
            border-color: #86b7fe;
            box-shadow: 0 0 0 .2rem rgba(13,110,253,.15);
        }

        .guru-form-body .form-select option {
            background: #ffffff;
            color: #212529;
        }

        /* JARAK ANTAR INPUT */
        .guru-form-body .mb-3 {
            margin-bottom: 16px !important;
        }

        /* KETERANGAN PASSWORD */
        .password-info {
            color: #6c757d;
            font-size: 14px;
        }

        /* TOMBOL */
        .guru-form-actions {
            display: flex;
            gap: 10px;
            margin-top: 22px;
        }

        .btn-simpan {
            background: #0d6efd;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
        }

        .btn-simpan:hover {
            background: #0b5ed7;
            color: #ffffff;
        }

        .btn-kembali {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn-kembali:hover {
            background: #5c636a;
            color: white;
        }

        .guru-alert {
            max-width: 886px;
            margin: 0 auto 15px;
        }

        /* RESPONSIVE */
        @media(max-width: 700px) {
            .guru-content {
                padding: 0 14px 25px;
            }

            .guru-form-card {
                width: 100%;
            }

            .guru-form-body {
                padding: 18px;
            }

            .guru-form-header h2 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

<div class="d-flex min-vh-100">

    <?php include "layout/sidebar.php"; ?>

    <main class="flex-grow-1 p-3 p-md-4" style="min-width: 0;">

        <div class="guru-content">

            <a href="kelola_guru.php" class="guru-back">
                ← Kembali ke Kelola Guru
            </a>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger guru-alert">
                    <?= aman($error); ?>
                </div>
            <?php endif; ?>

            <div class="guru-form-card">

                <div class="guru-form-header">
                    <h2>Form Tambah Guru</h2>
                </div>

                <div class="guru-form-body">

                    <form method="POST" action="">

                        <div class="mb-3">
                            <label for="nip">NIP Guru</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nip"
                                name="nip"
                                placeholder="Masukkan NIP guru"
                                value="<?= aman($_POST['nip'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="nama">Nama Guru</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nama"
                                name="nama"
                                placeholder="Masukkan nama guru"
                                value="<?= aman($_POST['nama'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Masukkan email guru"
                                value="<?= aman($_POST['email'] ?? ''); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                minlength="8"
                                required>

                            <small class="password-info">
                                Password minimal 8 karakter.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="konfirmasi_password">
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="konfirmasi_password"
                                name="konfirmasi_password"
                                placeholder="Ulangi password"
                                minlength="8"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="status_aktif">Status Aktif</label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required>

                                <option value="Aktif"
                                    <?= (($_POST['status_aktif'] ?? 'Aktif') === 'Aktif') ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="Nonaktif"
                                    <?= (($_POST['status_aktif'] ?? '') === 'Nonaktif') ? 'selected' : ''; ?>>
                                    Nonaktif
                                </option>

                            </select>
                        </div>

                        <div class="guru-form-actions">

                            <button type="submit" class="btn-simpan">
                                Simpan Guru
                            </button>

                            <a href="kelola_guru.php" class="btn-kembali">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    </main>

</div>

</body>
</html>
