<?php
require "../../../data/config.php";

// Ambil data dari URL
$review = $_GET["idReview"];
$reviewData = queryReadData("SELECT * FROM buku WHERE id_buku = '$review'")[0];

// Data kategori buku
$kategori = queryReadData("SELECT * FROM kategori_buku"); 

// Proses pembaruan data buku jika form di-submit
if (isset($_POST["update"])) {
    if (updateBuku($_POST) > 0) {
        echo "<script>
        alert('Data buku berhasil diupdate!');
        document.location.href = 'daftarBuku.php';
        </script>";
    } else {
        echo "<script>
        alert('Data buku gagal diupdate!');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Data Buku || Admin</title>
    <link rel="stylesheet" href="/styles/admin/updateBuku.css"> <!-- External custom CSS file -->
</head>

<body>
    <nav>
        <div class="container">
            <a href="#" class="logo">
                <img src="/images/logoNav.png" alt="logo" width="120px">
            </a>
            <div class="navbar">
                <a href="../dashboardAdmin.php">Dashboard</a>
                <a href="daftarBuku.php" class="active">Browse</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1 class="title">Form Edit Buku</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="coverLama" value="<?= $reviewData["cover"]; ?>">
                    <img src="/imgDB/<?= $reviewData["cover"]; ?>" width="80px" height="80px" alt="Cover">
                    <label for="formFileMultiple">Cover Buku</label>
                    <input type="file" name="cover" id="formFileMultiple">
                </div>

                <div class="form-group">
                    <label for="id_buku">Id Buku</label>
                    <input type="text" name="id_buku" id="id_buku" placeholder="example inf01" value="<?= $reviewData["id_buku"]; ?>">
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori">
                        <option selected><?= $reviewData["kategori"]; ?></option>
                        <?php foreach ($kategori as $item) : ?>
                            <option><?= $item["kategori"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="judul">Judul Buku</label>
                    <input type="text" name="judul" id="judul" placeholder="Judul Buku" value="<?= $reviewData["judul"]; ?>">
                </div>

                <div class="form-group">
                    <label for="pengarang">Pengarang</label>
                    <input type="text" name="pengarang" id="pengarang" placeholder="Nama Pengarang" value="<?= $reviewData["pengarang"]; ?>">
                </div>

                <div class="form-group">
                    <label for="penerbit">Penerbit</label>
                    <input type="text" name="penerbit" id="penerbit" placeholder="Nama Penerbit" value="<?= $reviewData["penerbit"]; ?>">
                </div>

                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input type="date" name="tahun_terbit" id="tahun_terbit" value="<?= $reviewData["tahun_terbit"]; ?>">
                </div>

                <div class="form-group">
                    <label for="jumlah_halaman">Jumlah Halaman</label>
                    <input type="number" name="jumlah_halaman" id="jumlah_halaman" value="<?= $reviewData["jumlah_halaman"]; ?>">
                </div>

                <div class="form-group">
                    <label for="buku_deskripsi">Sinopsis</label>
                    <textarea name="buku_deskripsi" id="buku_deskripsi" placeholder="Sinopsis tentang buku ini"><?= $reviewData["buku_deskripsi"]; ?></textarea>
                </div>

                <button type="submit" name="update" class="btn-success">Edit</button>
                <a href="daftarBuku.php" class="btn-danger">Batal</a>
            </form>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <p>Created by <span class="text-primary">Mangandaralam Sakti</span> © 2023</p>
            <p>versi 1.0</p>
        </div>
    </footer>

    <script src="scripts.js"></script> <!-- Optional custom JavaScript file -->
</body>

</html>
