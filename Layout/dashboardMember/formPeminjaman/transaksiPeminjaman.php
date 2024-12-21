<?php 
session_start();

// Check if session variable 'member' and 'nisn' exist
if (!isset($_SESSION["member"]) || !isset($_SESSION["member"]["nisn"])) {
    // If not set, redirect to login or handle the error
    header("Location: /login/member/sign_in.php");
    exit;
}

require "../../../data/config.php";
$akunMember = $_SESSION["member"]["nisn"];

// Ensure the 'nisn' is an integer to prevent SQL injection
$akunMember = (int) $akunMember;

// Prepared statement to get borrowing data
$query = "
    SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, peminjaman.nisn, member.nama, admin.nama_admin, peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian, peminjaman.status_pengembalian
    FROM peminjaman
    INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
    INNER JOIN member ON peminjaman.nisn = member.nisn
    INNER JOIN admin ON peminjaman.id_admin = admin.id
    WHERE peminjaman.nisn = ?
";

$dataPinjam = queryReadData($query, [$akunMember], "i"); // Use prepared statement with binding

$jumlahPinjaman = count($dataPinjam); // Assuming $dataPinjam is an array of borrowed books data
$showAddPinjamButton = $jumlahPinjaman < 3;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transaksi peminjaman Buku || Member</title>
  <link rel="stylesheet" href="/styles/member/trans-pinjaman.css">
</head>
<body>

  <nav class="navbar">
    <div class="container">
      <img src="/images/logoNav.png" alt="logo" styles="width: 50%;">
      <a href="/Layout/dashboardMember/utama.php">Kembali</a>
    </div>
  </nav>

  <div class="container-box">
    <div class="alert">
      Riwayat transaksi Peminjaman Buku Anda - <span class="fw-bold text-capitalize"><?= htmlentities($_SESSION["member"]["nama"]); ?></span>
    </div>

    <?php if ($showAddPinjamButton): ?>
      <div class="alert alert-success">
        Anda dapat melakukan peminjaman baru.
      </div>
    <?php else: ?>
      <div class="alert alert-warning">
        Anda sudah memiliki 3 peminjaman aktif. Tidak dapat menambah peminjaman baru.
      </div>
    <?php endif; ?>

    <?php
    // Loop through each transaction and check if there's a fine for overdue books
    foreach ($dataPinjam as $item) :
        $tglPengembalian = new DateTime($item["tgl_pengembalian"]);
        $tglSekarang = new DateTime(); // Current date

        // Check if the book is overdue (current date > return date)
        if ($tglSekarang > $tglPengembalian) {
            // Calculate overdue days
            $interval = $tglSekarang->diff($tglPengembalian);
            $lateDays = $interval->days; // Calculate overdue days

            // Calculate fine (for example, assume fine is 1000 per day)
            $fine = $lateDays * 5000;
    ?>
      <div class="alert alert-danger">
        <strong>Perhatian!</strong> Buku "<em><?= $item['judul']; ?></em>" terlambat dikembalikan sejak <?= $tglPengembalian->format('d-m-Y'); ?>. Anda dikenakan denda sebesar <?= number_format($fine, 0, ',', '.'); ?> IDR. Temui penjaga perpustakaan atau bisa menghubungi admin. Terima Kasih.
      </div>
    <?php 
        }
    endforeach;
    ?>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Id Peminjaman</th>
            <th>Id Buku</th>
            <th>Judul Buku</th>
            <th>Nisn</th>
            <th>Nama</th>
            <th>Nama Admin</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Status Pengembalian</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dataPinjam as $item) : ?>
            <tr>
              <td><?= $item["id_peminjaman"]; ?></td>
              <td><?= $item["id_buku"]; ?></td>
              <td><?= $item["judul"]; ?></td>
              <td><?= $item["nisn"]; ?></td>
              <td><?= $item["nama"]; ?></td>
              <td><?= $item["nama_admin"]; ?></td>
              <td><?= $item["tgl_peminjaman"]; ?></td>
              <td><?= $item["tgl_pengembalian"]; ?></td>
              <td><?= $item["status_pengembalian"]; ?></td>
              <td>
                <a class="btn" href="pengembalianBuku.php?id=<?= $item["id_peminjaman"]; ?>">Kembalikan</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer>
    <p>Created by <span class="text-primary">Mangandaralam Sakti</span> © 2023</p>
    <p>Versi 1.0</p>
  </footer>

</body>
</html>
