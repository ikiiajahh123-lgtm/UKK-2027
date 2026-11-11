
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

switch ($_SESSION['role']) {

    case 'admin':
        header("Location: admin/dashboard.php");
        break;

    case 'guru':
        header("Location: guru/dashboard.php");
        break;

    case 'wali_kelas':
        header("Location: wali_kelas/dashboard.php");
        break;

    case 'kepala_sekolah':
        header("Location: kepala_sekolah/dashboard.php");
        break;

    case 'siswa':
        header("Location: siswa/dashboard.php");
        break;

    default:
        session_unset();
        session_destroy();
        header("Location: auth/login.php");
        exit;
}

exit;
