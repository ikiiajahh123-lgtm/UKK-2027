
<?php
session_start();
require_once __DIR__ . "/config/koneksi.php";

// Pastikan form login menggunakan method POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    header("Location: login.php?pesan=gagal");
    exit;
}

// Cari pengguna berdasarkan nama atau email
$sql = "SELECT name, email, password, role
        FROM t_users
        WHERE name = ? OR email = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "ss", $username, $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    mysqli_stmt_close($stmt);
    header("Location: login.php?pesan=gagal");
    exit;
}

$passwordDB = (string) $user["password"];

// Mendukung password hash dan password lama berbentuk teks biasa
$valid = password_verify($password, $passwordDB);

if (!$valid && hash_equals($passwordDB, $password)) {
    $valid = true;

    // Ubah password teks biasa menjadi hash setelah login berhasil
    $hashBaru = password_hash($password, PASSWORD_DEFAULT);

    $update = mysqli_prepare(
        $koneksi,
        "UPDATE t_users SET password = ? WHERE email = ?"
    );

    if ($update) {
        mysqli_stmt_bind_param(
            $update,
            "ss",
            $hashBaru,
            $user["email"]
        );
        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
    }
}

mysqli_stmt_close($stmt);

if (!$valid) {
    header("Location: login.php?pesan=gagal");
    exit;
}

// Login berhasil
session_regenerate_id(true);

$_SESSION["login"] = true;
$_SESSION["username"] = $user["name"];
$_SESSION["nama_user"] = $user["name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

// Masuk ke dashboard
header("Location: dashboard.php");
exit;
?>
