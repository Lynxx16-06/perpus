<?php 
require "../../../data/config.php";
$dataDenda = queryReadData("SELECT pengembalian.id_pengembalian, pengembalian.id_buku, buku.judul, pengembalian.nisn, member.nama, member.jurusan, admin.nama_admin, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.nisn = member.nisn
INNER JOIN admin ON pengembalian.id_admin = admin.id
WHERE pengembalian.denda > 0");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/admin/pinjam-buku.css">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Kelola denda buku || admin</title>
  </head>

  <body>
    <nav class="navbar">
      <div class="navbar-container">
        <a href="#">
          <img src="/images/logoNav.png" alt="logo" class="logo">
        </a>
        <a class="dashboard-btn" href="../dashboard.php">Dashboard</a>
      </div>
    </nav>
    
    <div class="main-content">
        <section class="table-section">
        <h2>List denda</h2>
        <div class="table-container">
            <table class="table">
            <thead>
                <tr>
                <th>id buku</th>
                <th>Judul buku</th>
                <th>Nisn</th>
                <th>Nama siswa</th>
                <th>Nama admin</th>
                <th>Hari pengembalian</th>
                <th>Keterlambatan</th>
                <th>Denda</th>
                </tr>
            </thead>
          <tbody>
            <?php foreach ($dataDenda as $item) : ?>
            <tr>
              <td><?= $item["id_buku"]; ?></td>
              <td><?= $item["judul"]; ?></td>
              <td><?= $item["nisn"]; ?></td>
              <td><?= $item["nama"]; ?></td>
              <td><?= $item["nama_admin"]; ?></td>
              <td><?= $item["buku_kembali"]; ?></td>
              <td><?= $item["keterlambatan"]; ?></td>
              <td><?= $item["denda"]; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    
    <footer class="footer">
      <div class="container">
        <p>Created by <span class="text-primary">Muya</span> © 2024</p>
        <p>versi 1.0</p>
      </div>
    </footer>
  </body>
</html>
