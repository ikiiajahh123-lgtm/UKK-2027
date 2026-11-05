
<?php
$current_page = basename($_SERVER['PHP_SELF']);

if ($_SESSION['role'] === "admin") {
?>

<link rel="stylesheet" href="style.css">

<!-- SIDEBAR ADMIN -->
<div class="bg-dark min-vh-100 p-0"
     style="width:210px; flex:0 0 210px;">

    <h4 class="text-white fw-normal mb-4"
        style="font-size:23px; padding:8px 0 0 0; line-height:1.3;">
        Pelanggaran<br>Siswa
    </h4>

    <ul class="nav nav-pills flex-column">

        <li class="nav-item mb-2">
            <a href="dashboard.php"
               class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_siswa.php"
               class="nav-link <?= ($current_page == 'kelola_siswa.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Data Siswa
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_guru.php"
               class="nav-link <?= ($current_page == 'kelola_guru.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Data Guru
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_kelas.php"
               class="nav-link <?= ($current_page == 'kelola_kelas.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Data Kelas
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_tahun_ajaran.php"
               class="nav-link <?= ($current_page == 'kelola_tahun_ajaran.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Data Tahun Ajaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_walikelas.php"
               class="nav-link <?= ($current_page == 'kelola_walikelas.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Data Wali Kelas
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="penempatan_siswa.php"
               class="nav-link <?= ($current_page == 'penempatan_siswa.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Penempatan Siswa
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="kelola_pelanggaran_kategori.php"
               class="nav-link <?= ($current_page == 'kelola_pelanggaran_kategori.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Kategori Pelanggaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="about_me.php"
               class="nav-link <?= ($current_page == 'about_me.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                About Me
            </a>
        </li>

        <hr class="text-secondary" style="margin:20px 16px 20px 0;">

        <li class="nav-item">
            <a href="logout.php"
               class="nav-link text-danger"
               style="padding:10px 15px; margin-right:8px;">
                Logout
            </a>
        </li>

    </ul>
</div>

<?php
} elseif ($_SESSION['role'] === "guru") {
?>

<!-- SIDEBAR GURU -->
<div class="bg-dark min-vh-100 p-0"
     style="width:210px; flex:0 0 210px;">

    <h4 class="text-white fw-normal mb-4"
        style="font-size:23px; padding:8px 0 0 0; line-height:1.3;">
        Pelanggaran<br>Siswa
    </h4>

    <ul class="nav nav-pills flex-column">

        <li class="nav-item mb-2">
            <a href="dashboard.php"
               class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="catat_pelanggaran.php"
               class="nav-link <?= ($current_page == 'catat_pelanggaran.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Catat Pelanggaran
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="tindakan.php"
               class="nav-link <?= ($current_page == 'tindakan.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Tindakan
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="riwayat.php"
               class="nav-link <?= ($current_page == 'riwayat.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Riwayat
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="rekap_poin.php"
               class="nav-link <?= ($current_page == 'rekap_poin.php') ? 'active' : 'text-white' ?>"
               style="padding:10px 15px; margin-right:8px; border-radius:6px;">
                Rekap Poin
            </a>
        </li>

        <hr class="text-secondary" style="margin:20px 16px 20px 0;">

        <li class="nav-item">
            <a href="logout.php"
               class="nav-link text-danger"
               style="padding:10px 15px; margin-right:8px;">
                Logout
            </a>
        </li>

    </ul>
</div>

<?php
}
?>
