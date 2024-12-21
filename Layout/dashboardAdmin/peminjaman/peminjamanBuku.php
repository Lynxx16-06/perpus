<?php 
session_start(); // Start session if not already started
// Halaman pengelolaan peminjaman buku perpustakaan
require "../../../data/config.php";

// Query untuk mengambil data peminjaman
$dataPeminjam = queryReadData("SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, peminjaman.nisn, member.nama, member.kelas, peminjaman.id_admin, peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian 
FROM peminjaman 
INNER JOIN member ON peminjaman.nisn = member.nisn
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku");

// Handle delete action
if (isset($_GET['delete_id'])) {
    $id_peminjaman = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM peminjaman WHERE id_peminjaman = ?";
    $stmt = $connection->prepare($deleteQuery);
    $stmt->bind_param("i", $id_peminjaman); // Bind the ID as an integer
    $stmt->execute();
    header("Location: peminjamanBuku.php"); // Redirect after deletion
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/styles/pinjam-buku.css">
  <title>Admin Dashboard</title>
</head>

<body>

  <nav class="navbar">
    <div class="container-fluid p-3">
      <a class="navbar-brand" href="#">
        <img src="/images/logoNav.png" alt="logo" style="width: 120px;">
      </a>
      <div class="dropdown">
        <button style="border: none; background-color: transparent;" class="btn-dropdown" onclick="toggleDropdown()">
          <img src="/images/adminLogo.png" alt="adminLogo" width="40px">
        </button>
        <ul id="dropdown-menu" class="dropdown-menu hidden">
          <li class="text-center">
            <a class="dropdown-item" href="#">
              <img src="/images/adminLogo.png" alt="adminLogo" width="30px">
            </a>
          </li>
          <li class="text-center">
            <span class="text-capitalize"><?php echo $_SESSION['admin_username']; ?></span>
          </li>
          <hr>
          <li class="text-center mb-2">
            <a class="dropdown-item" href="#">Akun Terverifikasi <span class="text-primary">&#x2714;</span></a>
          </li>
          <li class="text-center">
            <a class="dropdown-item p-2 bg-danger text-light rounded" href="/Layout/dashboardAdmin/signOut.php">Sign Out &#x2794;</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <aside>
    <div class="sidebar">
      <main class="logo-utama">
        <img style="width: 20%;" src="/images/logo.png" alt="">
        <div class="utama">
          <h2>DASHBOARD</h2>
          <?php
          // Mendapatkan tanggal dan waktu saat ini
          $day = date('l');
          $dayOfMonth = date('d');
          $month = date('F');
          $year = date('Y');
          ?>
          <span><?php echo "$day $dayOfMonth $month $year"; ?></span>
        </div>
      </main>
      <hr>
      <ul>
        <a href="/Layout/dashboardAdmin/member/member.php">Member</a>
        <a href="/Layout/dashboardAdmin/buku/daftarBuku.php">Buku</a>
        <a href="/Layout/dashboardAdmin/peminjaman/peminjamanBuku.php">Peminjaman</a>
      </ul>
    </div>
  </aside>

  <main class="main-content">
    <section class="table-section">
      <h2>List Peminjaman</h2>
      <div class="table-container">
        <table class="table">
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
            <?php foreach ($dataPeminjam as $item) : ?>
              <tr>
                <td><?= $item["id_peminjaman"]; ?></td>
                <td><?= $item["id_buku"]; ?></td>
                <td><?= $item["judul"]; ?></td>
                <td><?= $item["nisn"]; ?></td>
                <td><?= $item["nama"]; ?></td>
                <td><?= $item["id_admin"]; ?></td>
                <td><?= $item["tgl_peminjaman"]; ?></td>
                <td><?= $item["tgl_pengembalian"]; ?></td>
                <td>
                  <?php
                    // Status pengembalian: jika tgl_pengembalian sudah lewat, berarti terlambat
                    if (strtotime($item["tgl_pengembalian"]) < time()) {
                        echo "Terlambat";
                    } else {
                        echo "Belum Kembali";
                    }
                  ?>
                </td>
                <td>
                  <!-- Tindakan seperti edit, hapus bisa ditambahkan di sini -->
                  <a href="editPeminjaman.php?id=<?= $item['id_peminjaman']; ?>">Edit</a> | 
                  <a href="?delete_id=<?= $item['id_peminjaman']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main> 

  <script>
    // Fungsi untuk toggle dropdown menu
    function toggleDropdown() {
      const dropdownMenu = document.getElementById('dropdown-menu');
      dropdownMenu.classList.toggle('hidden');
    }
  </script>

</body>

</html>
