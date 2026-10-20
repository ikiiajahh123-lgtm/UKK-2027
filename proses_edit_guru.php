
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '' || !ctype_digit((string)$id)) {
    header("Location: kelola_guru.php");
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$guru) {
    header("Location: kelola_guru.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Edit Guru - SIPES</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}

.container {
    width: 90%;
    max-width: 650px;
    margin: 45px auto;
}

.card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 18px rgba(0,0,0,.08);
}

.header {
    background: #212529;
    color: white;
    padding: 20px 25px;
}

.header h2 {
    margin: 0;
    font-size: 23px;
}

.content {
    padding: 25px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-size: 14px;
    font-weight: bold;
}

input, select {
    display: block;
    width: 100%;
    padding: 11px 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 15px;
    background: white;
}

input:focus, select:focus {
    outline: 2px solid #b6d4fe;
    border-color: #86b7fe;
}

input:disabled {
    background: #e9ecef;
}

.actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 24px;
}

.btn {
    display: inline-block;
    padding: 11px 17px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
}

.btn-primary {
    background: #0d6efd;
    color: white;
}

.btn-primary:hover {
    background: #0b5ed7;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #565e64;
}

@media (max-width: 480px) {
    .container {
        margin: 20px auto;
    }

    .content {
        padding: 18px;
    }
}
</style>
</head>

<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h2>Edit Guru</h2>
        </div>

        <div class="content">
            <form action="proses_edit_guru.php" method="POST">
                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($guru['id']) ?>">

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input id="nip" type="text" name="nip" required
                           value="<?= htmlspecialchars($guru['nip'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="nama">Nama Guru</label>
                    <input id="nama" type="text" name="nama" required
                           value="<?= htmlspecialchars($guru['nama'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required
                           value="<?= htmlspecialchars($guru['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="status_aktif">Status Aktif</label>
                    <select id="status_aktif" name="status_aktif" required>
                        <option value="1"
                            <?= (string)$guru['status_aktif'] === '1' ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="0"
                            <?= (string)$guru['status_aktif'] === '0' ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input id="user_id" type="text"
                           value="<?= htmlspecialchars((string)($guru['user_id'] ?? '-')) ?>"
                           disabled>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="kelola_guru.php" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
