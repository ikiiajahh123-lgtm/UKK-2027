<?php
// proses_tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_guru.php");
    exit;
}

// Ambil data dari form
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';
$status_aktif = $_POST['status_aktif'] ?? '';

// Fungsi menampilkan pesan error
function gagal($pesan) {
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
        <meta charset='UTF-8'>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
        <title>Gagal Menyimpan</title>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                " . htmlspecialchars($pesan) . "
                <br><br>
                <a href='tambah_guru.php' class='btn btn-secondary'>
                    Kembali
                </a>
            </div>
        </div>
    </body>
    </html>";
    exit;
}

// Validasi
if (
    $nip === '' ||
    $nama === '' ||
    $email === '' ||
    $password === '' ||
    $konfirmasi === '' ||
    $status_aktif === ''
) {
    gagal("Semua data wajib diisi.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    gagal("Format email tidak valid.");
}

if (strlen($password) < 8) {
    gagal("Password minimal 8 karakter.");
}

if ($password !== $konfirmasi) {
    gagal("Konfirmasi password tidak sama.");
}

if (!in_array($status_aktif, ['0', '1'], true)) {
    gagal("Status aktif tidak valid.");
}

// Enkripsi password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    // 1. Cek NIP
    $cek_nip = mysqli_prepare(
        $koneksi,
        "SELECT nip FROM t_guru WHERE nip = ?"
    );

    mysqli_stmt_bind_param($cek_nip, "s", $nip);
    mysqli_stmt_execute($cek_nip);
    mysqli_stmt_store_result($cek_nip);

    if (mysqli_stmt_num_rows($cek_nip) > 0) {
        mysqli_stmt_close($cek_nip);
        throw new Exception("NIP sudah terdaftar.");
    }

    mysqli_stmt_close($cek_nip);

    // 2. Cek email pada tabel t_users
    $cek_email = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_users WHERE email = ?"
    );

    mysqli_stmt_bind_param($cek_email, "s", $email);
    mysqli_stmt_execute($cek_email);
    mysqli_stmt_store_result($cek_email);

    if (mysqli_stmt_num_rows($cek_email) > 0) {
        mysqli_stmt_close($cek_email);
        throw new Exception("Email sudah digunakan oleh akun lain.");
    }

    mysqli_stmt_close($cek_email);

    // 3. Simpan akun ke tabel t_users
    $role = 'guru';

    $stmt_user = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_users (name, email, password, role)
         VALUES (?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt_user,
        "ssss",
        $nama,
        $email,
        $password_hash,
        $role
    );

    mysqli_stmt_execute($stmt_user);

    // Ambil ID user yang baru dibuat
    $user_id = mysqli_insert_id($koneksi);

    mysqli_stmt_close($stmt_user);

    // 4. Simpan data guru ke tabel t_guru
    $status = (int) $status_aktif;

    $stmt_guru = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_guru
         (user_id, nip, nama, email, status_aktif)
         VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt_guru,
        "isssi",
        $user_id,
        $nip,
        $nama,
        $email,
        $status
    );

    mysqli_stmt_execute($stmt_guru);

    mysqli_stmt_close($stmt_guru);

    // 5. Jika semua berhasil, simpan transaksi
    mysqli_commit($koneksi);

    // Kembali ke halaman kelola guru
    header("Location: kelola_guru.php?pesan=tambah_berhasil");
    exit;

} catch (Throwable $e) {

    // Batalkan semua perubahan jika terjadi error
    mysqli_rollback($koneksi);

    gagal("Gagal menyimpan data guru: " . $e->getMessage());
}
?>