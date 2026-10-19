
<?php
// edit_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$nis = $_GET['nis'] ?? '';

if ($nis === '') {
    die("NIS siswa tidak ditemukan. Silakan kembali ke halaman Kelola Siswa.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_siswa WHERE nis = ?"
);

mysqli_stmt_bind_param($stmt, "s", $nis);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    die("Data siswa tidak ditemukan. Silakan kembali ke halaman Kelola Siswa.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
</head>

<body>

    <h1>Edit Siswa</h1>

    <form action="proses_edit_siswa.php" method="POST">

        <input type="hidden" name="id"
               value="<?php echo htmlspecialchars($data['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

        <table>

            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>
                    <input type="text" name="nis"
                           value="<?php echo htmlspecialchars($data['nis'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           required>
                </td>
            </tr>

            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>
                    <input type="text" name="nisn"
                           value="<?php echo htmlspecialchars($data['nisn'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           required>
                </td>
            </tr>

            <tr>
                <td>Nama Siswa</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama"
                           value="<?php echo htmlspecialchars($data['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           required>
                </td>
            </tr>

            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <select name="jenis_kelamin" required>

                        <option value="L"
                            <?php
                            if (($data['jenis_kelamin'] ?? '') == 'L')
                                echo 'selected';
                            ?>>
                            Laki-laki
                        </option>

                        <option value="P"
                            <?php
                            if (($data['jenis_kelamin'] ?? '') == 'P')
                                echo 'selected';
                            ?>>
                            Perempuan
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td>
                    <input type="date" name="tanggal_lahir"
                           value="<?php echo htmlspecialchars($data['tanggal_lahir'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           required>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <textarea name="alamat"
                              rows="4"
                              cols="30"
                              required><?php echo htmlspecialchars($data['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </td>
            </tr>

            <tr>
                <td>Status Aktif</td>
                <td>:</td>
                <td>
                    <select name="status_aktif" required>

                        <option value="1"
                            <?php
                            if (($data['status_aktif'] ?? '') == 1)
                                echo 'selected';
                            ?>>
                            Aktif
                        </option>

                        <option value="0"
                            <?php
                            if (($data['status_aktif'] ?? '') == 0)
                                echo 'selected';
                            ?>>
                            Tidak Aktif
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <input type="submit" value="Update">
                </td>
            </tr>

        </table>

    </form>

    <p>
        <a href="kelola_siswa.php">Kembali ke Kelola Siswa</a>
    </p>

</body>
</html>
