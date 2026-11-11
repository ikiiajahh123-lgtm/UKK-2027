
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

$menu = [];

// MENU ADMIN
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

// MENU GURU
} elseif ($role === 'guru') {

    $menu = [
        'dashboard_guru.php' => 'Dashboard',
        'catat_pelanggaran.php' => 'Catat Pelanggaran',
        'kelola_tindakan.php' => 'Tindakan',
        'kelola_riwayat.php' => 'Riwayat',
        'kelola_rekap_poin.php' => 'Rekap Poin',
        'kelola_laporan.php' => 'laporan'
    ];
}
?>

<!-- SIDEBAR UTAMA -->
<div class="bg-dark min-vh-100 flex-shrink-0 p-0"
     style="width:230px; flex:0 0 230px;">

    <!-- JUDUL SIDEBAR -->
    <h4 class="text-white fw-normal mb-4"
        style="
            font-size:23px;
            padding:8px 0 0 0;
            line-height:1.3;
        ">
        Pelanggaran<br>Siswa
    </h4>

    <!-- DAFTAR MENU -->
    <ul class="nav nav-pills flex-column">

        <?php foreach ($menu as $link => $nama): ?>

            <?php
            // Menentukan menu aktif
            $aktif = ($current_page === $link);

            // Menu Dashboard Guru
            if (
                $role === 'guru' &&
                $link === 'dashboard_guru.php' &&
                $current_page === 'dashboard.php'
            ) {
                $aktif = true;
            }

            // Menu Tindakan Guru
            if (
                $role === 'guru' &&
                $link === 'kelola_tindakan.php' &&
                $current_page === 'kelola_guru.php'
            ) {
                $aktif = true;
            }
            ?>

            <li class="nav-item mb-2">

                <a href="<?= htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>"
                   class="nav-link <?= $aktif ? 'active' : 'text-white'; ?>"
                   style="
                       display:block;
                       width:calc(100% - 8px);
                       padding:10px 15px;
                       margin-right:8px;
                       border-radius:6px;
                       box-sizing:border-box;
                   ">

                    <?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>

                </a>

            </li>

        <?php endforeach; ?>

        <!-- GARIS PEMISAH -->
        <hr class="text-secondary"
            style="margin:20px 16px 20px 0;">

        <!-- LOGOUT -->
        <li class="nav-item">

            <a href="logout.php"
               class="nav-link text-danger"
               style="
                   display:block;
                   width:calc(100% - 8px);
                   padding:10px 15px;
                   margin-right:8px;
                   border-radius:6px;
                   box-sizing:border-box;
               ">

                Logout

            </a>

        </li>

    </ul>

</div>
