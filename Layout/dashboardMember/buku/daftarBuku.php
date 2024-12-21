<?php
session_start();

if (!isset($_SESSION["signIn"])) {
  header("Location: ../../login/member/sign_in.php");
  exit;
}

require "../../../data/config.php";
// query read semua buku
$buku = queryReadData("SELECT * FROM buku");
//search buku
if (isset($_POST["search"])) {
  $buku = search($_POST["keyword"]);
}
//read buku informatika
if (isset($_POST["informatika"])) {
  $buku = queryReadData("SELECT * FROM buku WHERE kategori = 'informatika'");
}
//read buku bisnis
if (isset($_POST["bisnis"])) {
  $buku = queryReadData("SELECT * FROM buku WHERE kategori = 'bisnis'");
}
//read buku filsafat
if (isset($_POST["filsafat"])) {
  $buku = queryReadData("SELECT * FROM buku WHERE kategori = 'filsafat'");
}
//read buku novel
if (isset($_POST["novel"])) {
  $buku = queryReadData("SELECT * FROM buku WHERE kategori = 'novel'");
}
//read buku sains
if (isset($_POST["sains"])) {
  $buku = queryReadData("SELECT * FROM buku WHERE kategori = 'sains'");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Daftar Buku || Member</title>
  <link rel="stylesheet" href="/styles/member/buku-mem.css"> <!-- Link to your custom CSS -->
</head>

<body>
  <nav class="navbar">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="/images/logoNav.png" alt="logo" class="logo">
      </a>
      <a class="dashboard-btn" href="../dashboard.php">Dashboard</a>
    </div>
  </nav>

  <div class="content">
    <!-- Btn filter data kategori buku -->
    <div class="filter-buttons">
      <form action="" method="post">
        <div class="button-group">
          <button class="btn btn-primary" type="submit">Semua</button>
          <button type="submit" name="informatika" class="btn btn-outline">Informatika</button>
          <button type="submit" name="bisnis" class="btn btn-outline">Bisnis</button>
          <button type="submit" name="filsafat" class="btn btn-outline">Filsafat</button>
          <button type="submit" name="novel" class="btn btn-outline">Novel</button>
          <button type="submit" name="sains" class="btn btn-outline">Sains</button>
        </div>
      </form>
    </div>

    <form action="" method="post" class="search-form">
      <div class="search-bar">
        <input class="search-input" type="text" name="keyword" id="keyword"
          placeholder="Cari Judul atau Kategori Buku...">
        <button class="search-button" type="submit" name="search">
          Cari
        </button>
      </div>
    </form>

    <!-- Card Buku -->
    <div class="card-container">
      <?php foreach ($buku as $item): ?>
        <div class="card">
          <img src="/imgDB/<?= $item["cover"]; ?>" alt="coverBuku" class="card-img">
          <div class="card-body">
            <h5 class="card-title"><?= $item["judul"]; ?></h5>
          </div>
          <ul class="card-info">
            <li>Kategori : <?= $item["kategori"]; ?></li>
          </ul>
          <div class="card-footer">
            <a class="btn btn-success" href="detailBuku.php?id=<?= $item["id_buku"]; ?>">Detail</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>

  <footer class="footer">
    <div class="footer-container">
      <p class="author">Created by Perpustakaan MTs MMH © 2024</p>
      <p>versi 1.0</p>
    </div>
  </footer>
  </div>
</body>

</html>