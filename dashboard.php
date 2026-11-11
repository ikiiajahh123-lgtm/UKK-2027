<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}
if (($_SESSION['role'] ?? '') !== 'admin') {
    if (($_SESSION['role'] ?? '') === 'guru') {
        header('Location: dashboard_guru.php');
        exit;
    }
    header('Location: login.php');
    exit;
}

// Hitung data secara aman. Jika tabel belum tersedia, tampilkan 0.
function jumlahData(mysqli $db, string $tabel): int {
    $tabelAman = str_replace('`', '', $tabel);
    $cek = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($tabelAman) . "'");
    if (!$cek || $cek->num_rows === 0) return 0;
    $hasil = $db->query("SELECT COUNT(*) AS total FROM `$tabelAman`");
    if (!$hasil) return 0;
    return (int)($hasil->fetch_assoc()['total'] ?? 0);
}

$totalSiswa = jumlahData($koneksi, 't_siswa');
$totalGuru = jumlahData($koneksi, 't_guru');
$totalKelas = jumlahData($koneksi, 't_kelas');
$totalPelanggaran = jumlahData($koneksi, 't_pelanggaran_siswa');

$nama = htmlspecialchars($_SESSION['nama'] ?? 'Administrator', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin | Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <div class="main-area">
        <header class="topbar">
            <div class="school-brand">
                <div class="brand-icon"><i class="bi bi-buildings"></i></div>
                <div>
                    <strong>Sistem Informasi Pelanggaran Siswa</strong>
                    <small>Panel Administrator</small>
                </div>
            </div>
            <div class="topbar-right">
                <div class="top-search"><i class="bi bi-search"></i><span>Cari menu...</span></div>
                <button class="top-icon" type="button" aria-label="Notifikasi"><i class="bi bi-bell"></i></button>
                <div class="user-info">
                    <div class="avatar"><i class="bi bi-person-fill"></i></div>
                    <div><strong><?= $nama ?></strong><small><?= $email ?></small></div>
                </div>
            </div>
        </header>

        <main class="content-area">
            <div class="page-heading">
                <div>
                    <h1>Dashboard</h1>
                    <p>Ringkasan data Sistem Informasi Pelanggaran Siswa</p>
                </div>
                <span class="role-chip"><i class="bi bi-shield-check"></i> Administrator</span>
            </div>

            <section class="stats-grid">
                <article class="stat-card">
                    <div class="stat-copy"><span>Total Siswa</span><strong><?= number_format($totalSiswa) ?></strong><small><i class="bi bi-people"></i> Data siswa terdaftar</small></div>
                    <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                </article>
                <article class="stat-card">
                    <div class="stat-copy"><span>Total Guru</span><strong><?= number_format($totalGuru) ?></strong><small><i class="bi bi-person-check"></i> Data guru terdaftar</small></div>
                    <div class="stat-icon purple"><i class="bi bi-mortarboard-fill"></i></div>
                </article>
                <article class="stat-card">
                    <div class="stat-copy"><span>Total Kelas</span><strong><?= number_format($totalKelas) ?></strong><small><i class="bi bi-journal-bookmark"></i> Kelas tersedia</small></div>
                    <div class="stat-icon orange"><i class="bi bi-book-half"></i></div>
                </article>
                <article class="stat-card">
                    <div class="stat-copy"><span>Total Pelanggaran</span><strong><?= number_format($totalPelanggaran) ?></strong><small><i class="bi bi-clipboard-data"></i> Catatan pelanggaran</small></div>
                    <div class="stat-icon green"><i class="bi bi-graph-up-arrow"></i></div>
                </article>
            </section>
