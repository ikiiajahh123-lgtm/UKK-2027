
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kelola_guru.php");
    exit;
}

$id = $_POST['id'] ?? '';
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$status = $_POST['status_aktif'] ?? '';
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

function kembaliDenganPesan($pesan) {
    echo "<script>
        alert(" . json_encode($pesan) . ");
        history.back();
    </script>";
    exit;
}

// Validasi
if (
    !ctype_digit((string)$id) ||
    $nip === '' ||
    $nama === '' ||
    $email === ''
) {
    kembaliDenganPesan("Data guru tidak lengkap.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    kembaliDenganPesan("Format email tidak valid.");
}

if (!in_array($status, ['0', '1'], true)) {
    kembaliDenganPesan("Status aktif tidak valid.");
}

if ($password !== '' || $konfirmasi !== '') {
    if (strlen($password) < 8) {
        kembaliDenganPesan("Password minimal 8 karakter.");
    }

    if ($password !== $konfirmasi) {
        kembaliDenganPesan("Konfirmasi password tidak sama.");
    }
}

try {
    $koneksi->begin_transaction();

    // Ambil data guru dan user_id
    $stmt = $koneksi->prepare(
        "SELECT user_id FROM t_guru WHERE id = ? LIMIT 1"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $guru = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$guru || empty($guru['user_id'])) {
        throw new Exception("Data guru atau akun login tidak ditemukan.");
    }

    $user_id = (int)$guru['user_id'];

    // Pastikan akun login tersedia
    $cekUser = $koneksi->prepare(
        "SELECT id FROM t_users WHERE id = ? LIMIT 1"
    );
    $cekUser->bind_param("i", $user_id);
    $cekUser->execute();

    if ($cekUser->get_result()->num_rows === 0) {
        throw new Exception("Akun login guru tidak ditemukan.");
    }
    $cekUser->close();

    // Cek NIP duplikat
    $cekNip = $koneksi->prepare(
        "SELECT id FROM t_guru WHERE nip = ? AND id != ? LIMIT 1"
    );
    $cekNip->bind_param("si", $nip, $id);
    $cekNip->execute();

    if ($cekNip->get_result()->num_rows > 0) {
        throw new Exception("NIP sudah digunakan guru lain.");
    }
    $cekNip->close();

    // Cek email duplikat
    $cekEmail = $koneksi->prepare(
        "SELECT id FROM t_users WHERE email = ? AND id != ? LIMIT 1"
    );
    $cekEmail->bind_param("si", $email, $user_id);
    $cekEmail->execute();

    if ($cekEmail->get_result()->num_rows > 0) {
        throw new Exception("Email sudah digunakan akun lain.");
    }
    $cekEmail->close();

    $waktu = date('Y-m-d H:i:s');

    // Update akun login: nama dan email
    $updateUser = $koneksi->prepare(
        "UPDATE t_users
         SET name = ?, email = ?, updated_at = ?
         WHERE id = ?"
    );

    $updateUser->bind_param(
        "sssi",
        $nama,
        $email,
        $waktu,
        $user_id
    );
    $updateUser->execute();
    $updateUser->close();

    // Update password jika diisi
    if ($password !== '') {
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $updatePassword = $koneksi->prepare(
            "UPDATE t_users
             SET password = ?, updated_at = ?
             WHERE id = ?"
        );

        $updatePassword->bind_param(
            "ssi",
            $passwordHash,
            $waktu,
            $user_id
        );

        $updatePassword->execute();
        $updatePassword->close();
    }

    // Update data guru
    $updateGuru = $koneksi->prepare(
        "UPDATE t_guru
         SET nip = ?, nama = ?, email = ?, status_aktif = ?
         WHERE id = ?"
    );

    $statusAngka = (int)$status;

    $updateGuru->bind_param(
        "sssii",
        $nip,
        $nama,
        $email,
        $statusAngka,
        $id
    );

    $updateGuru->execute();
    $updateGuru->close();

    // Simpan seluruh perubahan
    $koneksi->commit();

    header("Location: kelola_guru.php?pesan=edit_berhasil");
    exit;

} catch (Throwable $e) {

    $koneksi->rollback();

    kembaliDenganPesan("Gagal memperbarui data: " . $e->getMessage());
}
?>
