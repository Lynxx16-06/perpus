<?php

require "../../../data/config.php"; // Pastikan config.php di-include hanya sekali

// Mengambil kategori buku
$kategori = queryReadData("SELECT * FROM kategori_buku");

if (isset($_POST["tambah"])) {
    // Proses upload file cover
    $cover = uploadFile($_FILES['cover'], $_SERVER['DOCUMENT_ROOT'] . '/imgDB/');
    
    if ($cover === false) {
        // Jika file gagal diupload, tampilkan alert
        echo "<script>alert('Gagal mengunggah cover buku!');</script>";
    } else {
        // Setelah upload berhasil, lanjutkan proses tambah buku
        if (tambahBuku($_POST, $cover) > 0) {
            echo "<script>alert('Data buku berhasil ditambahkan');</script>";
        } else {
            echo "<script>alert('Data buku gagal ditambahkan!');</script>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku | Admin</title>
    <link rel="stylesheet" href="/styles/admin/tambah-buku.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand">
                <img src="/images/logoNav.png" alt="logo" width="120px">
            </a>
            <div class="navbar-links">
                <a href="../dashboard.php" class="nav-link">Dashboard</a>
                <a class="nav-link text-success" href="daftarBuku.php">Browse</a>
            </div>
        </div>
    </nav>

    <main class="main-container">
        <div class="form-container">
            <h1>Form Tambah Buku</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cover">Cover Buku</label>
                    <input type="file" id="cover" name="cover" required>
                </div>
                <div class="form-group">
                    <label for="id_buku">Id Buku</label>
                    <input type="text" id="id_buku" name="id_buku" placeholder="Contoh: inf01" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih</option>
                        <?php foreach ($kategori as $item): ?>
                            <option><?= $item["kategori"]; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="judul">Judul Buku</label>
                    <input type="text" id="judul" name="judul" placeholder="Judul Buku" required>
                </div>
                <div class="form-group">
                    <label for="pengarang">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang" placeholder="Nama Pengarang" required>
                </div>
                <div class="form-group">
                    <label for="penerbit">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" placeholder="Nama Penerbit" required>
                </div>
                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input type="date" id="tahun_terbit" name="tahun_terbit" required>
                </div>
                <div class="form-group">
                    <label for="jumlah_halaman">Jumlah Halaman</label>
                    <input type="number" id="jumlah_halaman" name="jumlah_halaman" required>
                </div>
                <div class="form-group">
                    <label for="buku_deskripsi">Sinopsis</label>
                    <textarea id="buku_deskripsi" name="buku_deskripsi" placeholder="Sinopsis tentang buku ini"
                        required></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" name="tambah" class="btn-submit">Tambah</button>
                    <button type="reset" class="btn-reset">Reset</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>Created by <span class="highlight">Muya</span> © 2024</p>
            <p>versi 1.0</p>
        </div>
    </footer>
</body>

</html>