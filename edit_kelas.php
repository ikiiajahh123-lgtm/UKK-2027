<?php
// edit_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM t_kelas WHERE id = '$id'";
$hasil = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($hasil);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kelas</title>

    <!-- Bootstrap 5.3.3 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow">

                <!-- Header -->
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Edit Kelas</h3>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <form action="proses_edit_kelas.php" method="POST">

                        <!-- ID -->
                        <input
                            type="hidden"
                            name="id"
                            value="<?php echo htmlspecialchars($data['id']); ?>"
                        >

                        <!-- Nama Kelas -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Kelas
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                value="<?php echo htmlspecialchars($data['nama']); ?>"
                                required
                            >
                        </div>

                        <!-- Tingkat -->
                        <div class="mb-3">
                            <label for="tingkat" class="form-label">
                                Tingkat
                            </label>

                            <input
                                type="text"
                                name="tingkat"
                                id="tingkat"
                                class="form-control"
                                value="<?php echo htmlspecialchars($data['tingkat']); ?>"
                                required
                            >
                        </div>

                        <!-- Jurusan -->
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">
                                Jurusan
                            </label>

                            <input
                                type="text"
                                name="jurusan"
                                id="jurusan"
                                class="form-control"
                                value="<?php echo htmlspecialchars($data['jurusan']); ?>"
                                required
                            >
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <label for="status_aktif" class="form-label">
                                Status Aktif
                            </label>

                            <select
                                name="status_aktif"
                                id="status_aktif"
                                class="form-select"
                                required
                            >

                                <option
                                    value="1"
                                    <?php
                                    if ($data['status_aktif'] == 1) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Aktif
                                </option>

                                <option
                                    value="0"
                                    <?php
                                    if ($data['status_aktif'] == 0) {
                                        echo 'selected';
                                    }
                                    ?>
                                >
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success"
                            >
                                Update
                            </button>

                            <a
                                href="kelola_kelas.php"
                                class="btn btn-secondary"
                            >
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>