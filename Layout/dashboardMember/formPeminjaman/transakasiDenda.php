<?php
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}

require "../../../data/config.php";
$nisnSiswa = $_SESSION["member"]["nisn"];

// Use prepared statement to avoid SQL injection
$query = $connection->prepare("SELECT pengembalian.id_pengembalian, pengembalian.id_peminjaman, pengembalian.id_buku, buku.judul, pengembalian.nisn, member.nama, admin.nama_admin, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.nisn = member.nisn
INNER JOIN admin ON pengembalian.id_admin = admin.id
WHERE pengembalian.nisn = ? AND pengembalian.denda > 0");

$query->bind_param("i", $nisnSiswa); // Bind the nisn as an integer parameter
$query->execute();
$result = $query->get_result();
$dataDenda = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/member/trans-denda.css">
    <title>Transaksi Denda Buku || Member</title>
</head>

<body>
    <!-- Navbar -->
    <nav>
        <a href="#">
            <img src="/assets/logoNav.png" alt="logo">
        </a>
        <a class="btn" href="../dashboardMember.php">Dashboard</a>
    </nav>

    <!-- Content -->
    <div class="container">
        <div class="alert">Riwayat transaksi Denda Anda - <strong><?php echo htmlentities($_SESSION["member"]["nama"]); ?></strong></div>

        <table>
            <thead>
                <tr>
                    <th>ID Buku</th>
                    <th>Judul Buku</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Nama Admin</th>
                    <th>Hari Pengembalian</th>
                    <th>Keterlambatan</th>
                    <th>Denda</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataDenda as $item): ?>
                    <tr>
                        <td><?php echo $item["id_buku"]; ?></td>
                        <td><?php echo $item["judul"]; ?></td>
                        <td><?php echo $item["nisn"]; ?></td>
                        <td><?php echo $item["nama"]; ?></td>
                        <td><?php echo $item["nama_admin"]; ?></td>
                        <td><?php echo $item["buku_kembali"]; ?></td>
                        <td><?php echo $item["keterlambatan"]; ?></td>
                        <td><?php echo $item["denda"]; ?></td>
                        <td>
                            <a class="btn btn-success" href="formBayarDenda.php?id=<?php echo $item["id_pengembalian"]; ?>">Bayar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <p>Created by <strong>Mangandaralam Sakti</strong> &copy; 2023 - versi 1.0</p>
    </footer>
</body>

</html>
