
<?php
session_start();

// Hapus seluruh session
$_SESSION = [];

// Hapus session dari server
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;
