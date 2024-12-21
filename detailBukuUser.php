<?php 
require "/data/config.php";
$idBuku = $_GET["id"];
$query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/member/detailBuku.css">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Detail Buku || Member</title>
  </head>
  <body>
    <nav class="navbar">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="/images/logoNav.png" alt="logo" class="logo">
        </a>
        <a class="btn dashboard-btn" href="../dashboard.php">Dashboard</a>
      </div>
    </nav>

    <div class="content">
      <h2 class="title">Detail Buku</h2>
      <div class="book-details">
        <?php foreach ($query as $item) : ?>
        <div class="card">
          <img src="/imgDB/<?= $item["cover"]; ?>" class="card-img" alt="coverBuku" height="250px">
          <div class="card-body">
            <h5 class="card-title"><?= $item["judul"]; ?></h5>
          </div>
          <ul class="card-list">
            <li>Id Buku: <?= $item["id_buku"]; ?></li>
            <li>Kategori: <?= $item["kategori"]; ?></li>
            <li>Pengarang: <?= $item["pengarang"]; ?></li>
            <li>Penerbit: <?= $item["penerbit"]; ?></li>
            <li>Tahun Terbit: <?= $item["tahun_terbit"]; ?></li>
            <li>Jumlah Halaman: <?= $item["jumlah_halaman"]; ?></li>
            <li>Deskripsi Buku: <?= $item["buku_deskripsi"]; ?></li>
          </ul>
          <div class="card-actions">
            <a href="daftarBuku.php" class="btn btn-danger">Batal</a>
            <a href="../formPeminjaman/pinjamBuku.php?id=<?= $item["id_buku"]; ?>" class="btn btn-success">Pinjam</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <footer>
      <div class="footer-content">
        <p>Created by <span class="text-primary"> Perpustakaan MTs MMH</span> © 2024</p>
        <p>versi 1.0</p>
      </div>
    </footer>
  </body>
</html>
