
<?php
session_start();

require_once 'config/koneksi.php';

// Cek login admin
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    $_SESSION['role'] !== 'admin'
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

    // Validasi
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

            // Cek NIP sudah digunakan
            $cekNip = $koneksi->prepare(
                "SELECT nip FROM t_guru WHERE nip = ? LIMIT 1"
            );

            $cekNip->bind_param("s", $nip);
            $cekNip->execute();

            if ($cekNip->get_result()->num_rows > 0) {
                throw new Exception("NIP guru sudah terdaftar.");
            }

            $cekNip->close();

            // Cek email sudah digunakan
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

            // Simpan akun login guru
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

            // Ambil ID akun yang baru dibuat
            $user_id = $koneksi->insert_id;

            $queryUser->close();

            // Simpan data guru dan hubungkan dengan t_users
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

            // Simpan transaksi
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

    <title>Tambah Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">

    <style>
        .guru-content {
            padding: 30px 4%;
            max-width: 1500px;
            margin: auto;
        }

        .guru-heading h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #f4f7fb;
        }

        .guru-heading p {
            color: #9aa9bd;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .guru-back {
            display: inline-block;
            background: #687582;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .guru-back:hover {
            background: #536171;
            color: white;
        }

        .guru-form-card {
            max-width: 650px;
            margin: 10px auto 30px;
            background: #1d2a3a;
            border: 1px solid rgba(161,179,205,.16);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,.15);
        }

        .guru-form-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(161,179,205,.16);
        }

        .guru-form-header h2 {
            color: white;
            font-size: 19px;
            font-weight: 700;
            margin: 0;
        }

        .guru-form-body {
            padding: 25px;
        }

        .guru-form-body label {
            color: #dce6f4;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .guru-form-body .form-control,
        .guru-form-body .form-select {
            background-color: #29384b;
            border: 1px solid rgba(161,179,205,.22);
            color: white;
            padding: 11px 13px;
            border-radius: 8px;
            font-size: 13px;
        }

        .guru-form-body .form-control::placeholder {
            color: #91a0b5;
        }

        .guru-form-body .form-control:focus,
        .guru-form-body .form-select:focus {
            background-color: #29384b;
            color: white;
            border-color: #397cf6;
            box-shadow: 0 0 0 .2rem rgba(57,124,246,.18);
        }

        .guru-form-body .form-select option {
            background: #1d2a3a;
            color: white;
        }

        .guru-form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-simpan {
            background: #397cf6;
            color: white;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-simpan:hover {
            background: #2868dc;
            color: white;
        }

        .btn-kembali {
            background: #687582;
            color: white;
            text-decoration: none;
            padding: 11px 20px;
            border-radius: 8px;
            font-size: 13px;
        }

        .btn-kembali:hover {
            background: #536171;
            color: white;
        }

        .guru-alert {
            max-width: 650px;
            margin: 0 auto 15px;
        }

        @media(max-width: 700px) {
            .guru-content {
                padding: 20px 14px;
            }

            .guru-heading h1 {
                font-size: 24px;
            }

            .guru-form-body {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="app-shell">

    <?php include "layout/sidebar.php"; ?>

    <main class="main-area">

        <div class="guru-content">

            <div class="guru-heading">
                <h1>Tambah Guru</h1>
                <p>Tambahkan data guru baru ke dalam sistem.</p>
            </div>

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
                            <small style="color:#9aa9bd;">
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
