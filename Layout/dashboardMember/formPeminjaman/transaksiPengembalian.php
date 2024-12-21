<?php
session_start();

// Pastikan pengguna login
if (!isset($_SESSION["signIn"])) {
    header("Location: ../../login/member/sign_in.php");
    exit;
}

$host = "localhost";
$username = "root";
$password = "";
$database = "perpustakaan";

// Koneksi ke database dengan pengecekan kesalahan
$conn = new mysqli($host, $username, $password, $database);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID peminjaman dari URL
$id_peminjaman = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_peminjaman) {
    die("ID peminjaman tidak valid. (ID tidak ditemukan di URL)");
}

// Ambil data peminjaman dari tabel
$query = "SELECT * FROM peminjaman WHERE id_peminjaman = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $id_peminjaman);
$stmt->execute();
$result = $stmt->get_result();

// Jika data tidak ditemukan, beri pesan kesalahan yang jelas
if ($result->num_rows === 0) {
    die("ID peminjaman tidak ditemukan di database.");
}

$data = $result->fetch_assoc();

if ($data) {
    // Pindahkan data ke tabel riwayat_pengembalian
    $queryInsert = "INSERT INTO riwayat_pengembalian (id_peminjaman, id_buku, nisn, id_admin, tgl_pengembalian, status_pengembalian)
                    VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    if ($stmtInsert === false) {
        die("Error preparing insert statement: " . $conn->error);
    }

    $stmtInsert->bind_param(
        "iisis",
        $data['id_peminjaman'],
        $data['id_buku'],
        $data['nisn'],
        $data['id_admin'],
        $data['status_pengembalian']
    );
    $stmtInsert->execute();

    // Perbarui status di tabel peminjaman
    $queryUpdate = "UPDATE peminjaman SET status_pengembalian = 'Dikembalikan', tgl_pengembalian = NOW() WHERE id_peminjaman = ?";
    $stmtUpdate = $conn->prepare($queryUpdate);
    if ($stmtUpdate === false) {
        die("Error preparing update statement: " . $conn->error);
    }

    $stmtUpdate->bind_param("i", $id_peminjaman);
    $stmtUpdate->execute();

    // Redirect dengan pesan sukses
    $_SESSION['success'] = "Buku berhasil dikembalikan dan dicatat di riwayat pengembalian.";
    header("Location: transaksiPengembalian.php");
    exit;
} else {
    $_SESSION['error'] = "Data peminjaman tidak ditemukan.";
    header("Location: transaksiPengembalian.php");
    exit;
}

// Jangan lupa menutup koneksi
$conn->close();

// Mengambil data peminjaman
$akunMember = $_SESSION["member"]["nisn"];
$queryPinjam = "
    SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, peminjaman.nisn, member.nama, admin.nama_admin, 
           peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian, peminjaman.status_pengembalian
    FROM peminjaman
    INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
    INNER JOIN member ON peminjaman.nisn = member.nisn
    INNER JOIN admin ON peminjaman.id_admin = admin.id
    WHERE peminjaman.nisn = ?
";

$stmtPinjam = $conn->prepare($queryPinjam);
if ($stmtPinjam === false) {
    die("Error preparing select statement: " . $conn->error);
}

$stmtPinjam->bind_param("i", $akunMember);
$stmtPinjam->execute();
$dataPinjam = $stmtPinjam->get_result()->fetch_all(MYSQLI_ASSOC);

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
      <img src="/images/logoNav.png" alt="logo">
      <a href="../dashboard.php">Dashboard</a>
    </div>
  </nav>

  <div class="container-box">
    <div class="alert">
      Riwayat transaksi Peminjaman Buku Anda - <span class="fw-bold text-capitalize"><?= htmlentities($_SESSION["member"]["nama"]); ?></span>
    </div>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert-success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert-error">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

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
              <td><?= $item["tgl_pengembalian"] ?: '-'; ?></td>
              <td><?= $item["status_pengembalian"] ?: 'Belum Dikembalikan'; ?></td>
              <td>
                <?php if ($item["status_pengembalian"] !== "Dikembalikan") : ?>
                    <a class="btn" href="pengembalianBuku.php?id=<?= $item["id_peminjaman"]; ?>">Kembalikan</a>
                <?php else : ?>
                    <span class="text-success">Sudah Dikembalikan</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer>
    <p>Created by <span class="text-primary">Mangandaralam Sakti</span> &copy; 2023</p>
    <p>Versi 1.0</p>
  </footer>

</body>
</html>
