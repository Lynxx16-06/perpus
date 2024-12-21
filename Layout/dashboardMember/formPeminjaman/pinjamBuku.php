<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$db_name = "perpustakaan";

// Create a connection to the database
$connection = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION["signIn"])) {
  header("Location: ../../login/member/sign_in.php");
  exit;
}

function queryReadData($query)
{
  global $connection;
  $result = mysqli_query($connection, $query);
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  return $data;
}

function pinjamBuku($data)
{
  global $connection;

  $nisn = htmlspecialchars($data["nisn"]);

  // Count the number of books the user has already borrowed
  $checkPinjam = mysqli_query($connection, "SELECT COUNT(*) as count FROM peminjaman WHERE nisn = '$nisn' AND status_pengembalian = 'belum'");
  $result = mysqli_fetch_assoc($checkPinjam);
  $borrowedBooksCount = $result['count'];

  // Check if the user has already borrowed 3 or more books
  if ($borrowedBooksCount >= 3) {
    return 0; // User has already borrowed 3 books or more
  }

  $id_buku = htmlspecialchars($data["id_buku"]);
  $id_admin = htmlspecialchars($data["id_admin"]);
  $tgl_peminjaman = htmlspecialchars($data["tgl_peminjaman"]);
  $tgl_pengembalian = htmlspecialchars($data["tgl_pengembalian"]);

  if (empty($id_admin) || empty($tgl_peminjaman) || empty($tgl_pengembalian)) {
    return 0; // Data tidak lengkap
  }

  // Prepared Statement
  $stmt = mysqli_prepare($connection, "INSERT INTO peminjaman (id_buku, nisn, id_admin, tgl_peminjaman, tgl_pengembalian) 
                                          VALUES (?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "sssss", $id_buku, $nisn, $id_admin, $tgl_peminjaman, $tgl_pengembalian);

  mysqli_stmt_execute($stmt);

  return mysqli_affected_rows($connection);
}

// Tangkap id buku dari URL (GET)
if (!isset($_GET["id"]) || empty($_GET["id"])) {
  echo "ID buku tidak ditemukan. Silakan akses halaman ini melalui link yang benar.";
  exit;
}

$idBuku = $_GET["id"];
$query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");

// Menampilkan data siswa yang sedang login
$nisnSiswa = $_SESSION["member"]["nisn"];
$dataSiswa = queryReadData("SELECT * FROM member WHERE nisn = '$nisnSiswa'");

// Query untuk data admin
$idAdmin = queryReadData("SELECT * FROM admin");

// Peminjaman buku
if (isset($_POST["pinjam"])) {
  $result = pinjamBuku($_POST);
  if ($result > 0) {
    echo "<script>
                alert('Buku berhasil dipinjam!');
                document.location.href = '/Layout/dashboardMember/utama.php';
              </script>";
  } else {
    echo "<script>alert('Gagal meminjam buku. Anda sudah meminjam 3 buku mohon kembalian buku terlebih dahulu jika ingin meminjam lagi');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Form Pinjam Buku || Member</title>
  <link rel="stylesheet" href="/styles/member/pinjamBuku.css">
</head>

<body>
  <!-- Header -->
  <nav class="navbar">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="/images/logoNav.png" alt="logo" class="logo">
      </a>
      <a class="btn dashboard-btn" href="/Layout/dashboardMember/utama.php">Dashboard</a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container">
    <h2>Form Peminjaman Buku</h2>

    <!-- Card Buku -->
    <div class="card">
      <div class="card-header">Data Lengkap Buku</div>
      <div class="card-box">
        <?php foreach ($query as $item): ?>
          <img src="/imgDB/<?= $item["cover"]; ?>" alt="coverBuku">
          <form action="" method="post">
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">Id Buku</span>
              <input type="text" value="<?= $item["id_buku"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">Kategori</span>
              <input type="text" value="<?= $item["kategori"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">Judul</span>
              <input type="text" value="<?= $item["judul"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">Pengarang</span>
              <input type="text" value="<?= $item["pengarang"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">Penerbit</span>
              <input type="text" value="<?= $item["penerbit"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 30%; padding: 5px;">Tahun Terbit</span>
              <input type="text" value="<?= $item["tahun_terbit"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 50%; padding: 5px;">Jumlah Halaman</span>
              <input type="text" value="<?= $item["jumlah_halaman"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 38%; padding: 5px;">Deskripsi Buku</span>
              <input type="text" value="<?= $item["buku_deskripsi"]; ?>" readonly>
            </div>
          </form>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Card Siswa -->
  <div class="container">
    <div class="card">
      <div class="card-header">Data Lengkap Siswa</div>
      <div class="card-box">
        <img style="height: 170px;" src="/images/memberLogo.png" alt="memberLogo">
        <form action="" method="post">
          <?php foreach ($dataSiswa as $item): ?>
            <div class="input-group">
              <span style="width: 15%; padding: 5px;">NISN</span>
              <input type="number" value="<?= $item["nisn"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 40%; padding: 5px;">Kode member</span>
              <input type="text" value="<?= $item["kode_member"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 15%; padding: 5px;">Nama</span>
              <input type="text" value="<?= $item["nama"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 40%; padding: 5px;">Jenis kelamin</span>
              <input type="text" value="<?= $item["jenis_kelamin"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">Kelas</span>
              <input type="text" value="<?= $item["kelas"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 20%; padding: 5px;">No Tlp</span>
              <input type="text" value="<?= $item["no_tlp"]; ?>" readonly>
            </div>
            <div class="input-group">
              <span style="width: 40%; padding: 5px;">Tanggal Daftar</span>
              <input type="text" value="<?= $item["tgl_pendaftaran"]; ?>" readonly>
            </div>
          <?php endforeach; ?>
        </form>
      </div>
    </div>
  </div>
  </div>



  <!-- Alert -->
  <section class="box-card">
    <div class="alert">Silakan periksa kembali data di atas, pastikan sudah benar sebelum meminjam buku! Jika ada
      kesalahan data, harap hubungi admin.</div>

    <!-- Form Pinjam Buku -->
    <main class="card-box-card">
      <div class="card-header">Form Pinjam Buku</div>
      <div class="card-box-box">
        <form action="" method="post">
          <!-- Ambil data id buku -->
          <?php $item = $query[0]; ?>
          <div class="input-group">
            <span style="width: 4%; padding: 5px;">Id Buku</span>
            <input type="text" name="id_buku" value="<?= $item["id_buku"]; ?>" readonly>
          </div>

          <!-- Ambil data NISN user yang login -->
          <?php $itemSiswa = $dataSiswa[0]; ?>
          <div class="input-group">
            <span style="width: 3%; padding: 5px;">NISN</span>
            <input type="text" name="nisn" value="<?= $itemSiswa["nisn"]; ?>" readonly>
          </div>
          <div class="input-group">
            <span style="width: 3%; padding: 5px;">Nama</span>
            <input type="text" value="<?= $itemSiswa["nama"]; ?>" readonly>
          </div>

          <!-- Dropdown Admin -->
          <div class="input-group">
            <label for="id_admin" style="width: 9%; padding: 5px;">Pilih</label>
            <select name="id_admin" required>
              <option value="">Pilih Admin</option>
              <?php foreach ($idAdmin as $item): ?>
                <option value="<?= $item["id"]; ?>"><?= $item["id"]; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Tanggal Pinjam & Pengembalian -->
          <div class="input-group">
            <label for="tgl_peminjaman" style="width: 9%; padding: 5px;">Tanggal Pinjam</label>
            <input type="date" name="tgl_peminjaman" id="tgl_peminjaman" required>
          </div>
          <div class="input-group">
            <label for="tgl_pengembalian" style="width: 13%; padding: 5px;">Tanggal Pengembalian</label>
            <input type="date" name="tgl_pengembalian" id="tgl_pengembalian" readonly>
          </div>

          <button type="submit" name="pinjam">Pinjam</button>
        </form>
      </div>
    </main>

    <script>
      // Logika untuk mengisi tanggal pengembalian otomatis
      document.getElementById("tgl_peminjaman").addEventListener("change", function () {
        const tglPeminjaman = new Date(this.value);
        const tglPengembalian = new Date(tglPeminjaman);
        tglPengembalian.setDate(tglPeminjaman.getDate() + 7); // 7 hari ke depan

        document.getElementById("tgl_pengembalian").value = tglPengembalian.toISOString().split("T")[0];
      });
    </script>

    <!-- Catatan -->
    <div class="alert mt-4"><strong>Catatan:</strong> Setiap keterlambatan pengembalian buku akan dikenakan denda.</div>
  </section>

  <!-- Footer -->
  <footer>
    <p>Created by <span class="text-primary">Perpustakaan MTs MMH</span> © 2024 | Versi 1.0</p>
  </footer>

  <script src="../../scripts/pinjamBuku.js"></script>
</body>

</html>