<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

$menuAdmin = [
    ['dashboard.php', 'Dashboard', 'bi-speedometer2'],
    ['kelola_siswa.php', 'Data Siswa', 'bi-people'],
    ['kelola_guru.php', 'Data Guru', 'bi-person-workspace'],
    ['kelola_kelas.php', 'Data Kelas', 'bi-door-open'],
    ['kelola_tahun_ajaran.php', 'Data Tahun Ajaran', 'bi-calendar3'],
    ['kelola_walikelas.php', 'Data Wali Kelas', 'bi-person-badge'],
    ['penempatan_siswa.php', 'Penempatan Siswa', 'bi-diagram-3'],
    ['kelola_pelanggaran_kategori.php', 'Kategori Pelanggaran', 'bi-clipboard2-check'],
    ['about_me.php', 'About Me', 'bi-info-circle']
];
$menuGuru = [
    ['dashboard_guru.php', 'Dashboard', 'bi-speedometer2'],
    ['catat_pelanggaran.php', 'Catat Pelanggaran', 'bi-pencil-square'],
    ['tindakan.php', 'Tindakan', 'bi-clipboard2-check'],
    ['riwayat.php', 'Riwayat', 'bi-clock-history'],
    ['rekap_poin.php', 'Rekap Poin', 'bi-bar-chart']
];
$menus = $role === 'admin' ? $menuAdmin : $menuGuru;
?>
<aside class="sidebar">
    <a class="sidebar-logo" href="<?= $role === 'admin' ? 'dashboard.php' : 'dashboard_guru.php' ?>">
        <i class="bi bi-buildings"></i>
    </a>
    <div class="sidebar-caption">MENU UTAMA</div>
    <nav class="sidebar-nav">
        <?php foreach ($menus as $menu): ?>
            <a href="<?= htmlspecialchars($menu[0], ENT_QUOTES, 'UTF-8') ?>"
               class="sidebar-link <?= $current_page === $menu[0] ? 'active' : '' ?>">
                <i class="bi <?= htmlspecialchars($menu[2], ENT_QUOTES, 'UTF-8') ?>"></i>
                <span><?= htmlspecialchars($menu[1], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-bottom">
        <a href="logout.php" class="sidebar-link logout-link"><i class="bi bi-box-arrow-left"></i><span>Logout</span></a>
    </div>
</aside>
