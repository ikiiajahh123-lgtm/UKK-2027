<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}
if (($_SESSION['role'] ?? '') !== 'guru') {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header('Location: dashboard.php');
        exit;
    }
    header('Location: login.php');
    exit;
}
$nama = htmlspecialchars($_SESSION['nama'] ?? 'Guru', ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Guru | Pelanggaran Siswa</title>
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
                <div><strong>Sistem Informasi Pelanggaran Siswa</strong><small>Panel Guru</small></div>
            </div>
            <div class="topbar-right">
                <div class="top-search"><i class="bi bi-search"></i><span>Cari menu...</span></div>
                <button class="top-icon" type="button" aria-label="Notifikasi"><i class="bi bi-bell"></i></button>
                <div class="user-info"><div class="avatar"><i class="bi bi-person-fill"></i></div><div><strong><?= $nama ?></strong><small><?= $email ?></small></div></div>
            </div>
        </header>
        <main class="content-area">
            <div class="page-heading">
                <div><h1>Dashboard Guru</h1><p>Selamat datang, <?= $nama ?>. Kelola pencatatan pelanggaran siswa di sini.</p></div>
                <span class="role-chip"><i class="bi bi-person-check"></i> Guru</span>
            </div>
            <section class="stats-grid guru-stats">
                <article class="stat-card"><div class="stat-copy"><span>Catat Pelanggaran</span><strong class="stat-word">Mulai</strong><small>Input pelanggaran siswa</small></div><div class="stat-icon blue"><i class="bi bi-pencil-square"></i></div><a class="card-hit" href="catat_pelanggaran.php" aria-label="Catat pelanggaran"></a></article>
                <article class="stat-card"><div class="stat-copy"><span>Tindakan</span><strong class="stat-word">Kelola</strong><small>Proses tindak lanjut</small></div><div class="stat-icon purple"><i class="bi bi-clipboard2-check"></i></div><a class="card-hit" href="tindakan.php" aria-label="Tindakan"></a></article>
                <article class="stat-card"><div class="stat-copy"><span>Riwayat</span><strong class="stat-word">Lihat</strong><small>Riwayat pelanggaran</small></div><div class="stat-icon orange"><i class="bi bi-clock-history"></i></div><a class="card-hit" href="riwayat.php" aria-label="Riwayat"></a></article>
                <article class="stat-card"><div class="stat-copy"><span>Rekap Poin</span><strong class="stat-word">Buka</strong><small>Rekap poin siswa</small></div><div class="stat-icon green"><i class="bi bi-bar-chart-line"></i></div><a class="card-hit" href="rekap_poin.php" aria-label="Rekap poin"></a></article>
            </section>
            <section class="panel welcome-panel">
                <div class="welcome-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div><h2>Selamat bekerja!</h2><p>Gunakan menu di samping untuk mencatat pelanggaran, melihat riwayat, dan memantau rekap poin siswa.</p></div>
                <a href="catat_pelanggaran.php" class="primary-link">Catat Pelanggaran <i class="bi bi-arrow-right"></i></a>
            </section>
        </main>
    </div>
</div>
</body>
</html>
