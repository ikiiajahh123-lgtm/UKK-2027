
<?php
session_start();

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Informasi Pelanggaran Siswa</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f0ff, #f8f9fa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
        }

        .login-logo {
            width: 65px;
            height: 65px;
            border-radius: 16px;
            background: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin: 0 auto 15px;
        }

        .form-control {
            padding: 12px;
            border-radius: 10px;
        }

        .btn-login {
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
        }

        .app-title {
            font-size: 19px;
            line-height: 1.5;
        }
    </style>
</head>

<body>

<div class="container px-3">
    <div class="card login-card mx-auto">
        <div class="card-body p-4 p-md-5">

            <h3 class="text-center fw-bold app-title mb-2">
                Sistem Informasi Pelanggaran Siswa
            </h3>

            <p class="text-center text-secondary mb-4">
                Silakan masuk ke akun Anda
            </p>

            <?php if (isset($_GET["pesan"]) && $_GET["pesan"] === "gagal"): ?>
                <div class="alert alert-danger">
                    Username/email atau password salah.
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username atau Email
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        placeholder="Masukkan username atau email"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-login w-100"
                >
                    Login
                </button>

            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                Sistem Informasi Pelanggaran Siswa &copy; 2026
            </p>

        </div>
    </div>
</div>

</body>
</html>
