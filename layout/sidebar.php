
<?php

$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

$menu = [];
$isWaliKelas = false;

<<<<<<< HEAD
// ==========================================
// CEK GURU WALI KELAS
// ==========================================

if (
    $role === 'guru' &&
    !empty($_SESSION['email']) &&
    isset($koneksi) &&
    $koneksi instanceof mysqli
) {
    $emailLogin = trim($_SESSION['email']);

    $sqlWali = "
        SELECT wk.id
        FROM t_users u
        INNER JOIN t_guru g
            ON g.user_id = u.id
        INNER JOIN t_wali_kelas wk
            ON wk.guru_id = g.id
        WHERE LOWER(TRIM(u.email)) = LOWER(TRIM(?))
          AND wk.status_aktif = 1
        LIMIT 1
    ";

    $stmtWali = $koneksi->prepare($sqlWali);

    if ($stmtWali) {
        $stmtWali->bind_param("s", $emailLogin);
        $stmtWali->execute();
        $stmtWali->store_result();

        $isWaliKelas = ($stmtWali->num_rows > 0);

        $stmtWali->close();
    }
}

// ==========================================
// MENU ADMIN
// ==========================================

=======
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
if ($role === 'admin') {

    $menu = [
        'dashboard.php' => 'Dashboard',
        'kelola_siswa.php' => 'Data Siswa',
        'kelola_guru.php' => 'Data Guru',
        'kelola_kelas.php' => 'Data Kelas',
        'kelola_tahun_ajaran.php' => 'Data Tahun Ajaran',
        'kelola_walikelas.php' => 'Data Wali Kelas',
        'penempatan_siswa.php' => 'Penempatan Siswa',
        'kelola_pelanggaran_kategori.php' => 'Kategori Pelanggaran',
        'about_me.php' => 'About Me'
    ];

<<<<<<< HEAD
// ==========================================
// MENU GURU
// ==========================================

=======
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
} elseif ($role === 'guru') {

    $menu = [
        'dashboard_guru.php' => 'Dashboard',
        'catat_pelanggaran.php' => 'Catat Pelanggaran',
        'kelola_tindakan.php' => 'Tindakan',
        'kelola_riwayat.php' => 'Riwayat',
<<<<<<< HEAD
        'kelola_rekap_poin.php' => 'Rekap Poin'
=======
        'kelola_rekap_poin.php' => 'Rekap Poin',
        'kelola_laporan.php' => 'Laporan'
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
    ];

    // Hanya Wali Kelas yang mendapatkan menu Laporan
    if ($isWaliKelas) {
        $menu['kelola_laporan.php'] = 'Laporan';
    }
}
?>

<aside
    class="bg-dark text-white flex-shrink-0"
    style="
        width: 230px;
        min-width: 230px;
        flex: 0 0 230px;
        height: 100vh;
        position: sticky;
        position: -webkit-sticky;
        top: 0;
        align-self: flex-start;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 12px;
        box-sizing: border-box;
        z-index: 1000;
    "
>

    <div class="mb-4">
        <h5 class="fw-bold text-white mb-1 text-nowrap">
            Pelanggaran Siswa
        </h5>

        <small class="text-secondary text-nowrap">
            Sistem Informasi Sekolah
        </small>
    </div>

    <hr class="border-secondary">

    <nav class="d-flex flex-column gap-2">

        <?php foreach ($menu as $link => $namaMenu): ?>

            <?php
<<<<<<< HEAD
            $aktif = ($current_page === $link);

            if (
                $role === 'guru' &&
                $link === 'dashboard_guru.php' &&
                $current_page === 'dashboard.php'
            ) {
                $aktif = true;
            }

            if (
                $role === 'guru' &&
                $link === 'kelola_tindakan.php' &&
                $current_page === 'kelola_guru.php'
            ) {
                $aktif = true;
            }
=======
            $url = explode('?', $link)[0];
            $aktif = ($current_page === $url);
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557
            ?>

            <a
                href="<?= htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>"
                class="text-decoration-none rounded-2 px-3 py-2 text-nowrap"
                style="
                    display: block;
                    width: 100%;
                    box-sizing: border-box;
                    color: #ffffff;
                    background-color: <?= $aktif ? '#0d6efd' : 'transparent'; ?>;
                "
            >
                <?= htmlspecialchars($namaMenu, ENT_QUOTES, 'UTF-8'); ?>
            </a>

        <?php endforeach; ?>

<<<<<<< HEAD
        <hr class="text-secondary"
            style="margin:20px 16px 20px 0;">
=======
    </nav>
>>>>>>> 38794a9e71b5d33a40183c56e32f1393f7685557

    <hr class="border-secondary my-4">

    <a
        href="logout.php"
        class="d-block text-danger text-decoration-none px-2 py-2"
    >
        Logout
    </a>

</aside>
