<?php

// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$database = "db_ukk_2026";

// Membuat koneksi
try {

    $koneksi = new mysqli(
        $host,
        $username,
        $password,
        $database
    );

    // Mengatur karakter database
    $koneksi->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {

    die("Koneksi database gagal: " . $e->getMessage());

}

?>