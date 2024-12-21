<?php
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}
require "../../../config/config.php";

function bayarDenda($data) {
    global $conn; // Ambil koneksi database
    
    $id_pengembalian = htmlspecialchars($data["id_pengembalian"]);
    $bayarDenda = htmlspecialchars($data["bayarDenda"]);

    // Validasi bahwa jumlah bayar harus sesuai atau lebih dari denda
    $queryDenda = mysqli_query($conn, "SELECT denda FROM pengembalian WHERE id_pengembalian = '$id_pengembalian'");
    $row = mysqli_fetch_assoc($queryDenda);
    $denda = $row["denda"];

    if ($bayarDenda < $denda) {
        return 0; // Bayar kurang dari denda
    }

    // Update data denda di database (bayar lunas)
    $query = "UPDATE pengembalian SET denda = 0 WHERE id_pengembalian = '$id_pengembalian'";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


$dendaSiswa = $_GET["id"];
$query = queryReadData("SELECT pengembalian.id_pengembalian, buku.judul, member.nama, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.nisn = member.nisn
WHERE pengembalian.id_pengembalian = $dendaSiswa");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/member/from-denda.css">
    <title>Form Bayar Denda || Member</title>

</head>

<body>
    <nav>
        <a href="#">
            <img src="/assets/logoNav.png" alt="logo">
        </a>
        <a class="btn" href="../dashboardMember.php">Dashboard</a>
    </nav>

    <div class="container">
        <form action="" method="post">
            <h3>Form Bayar Denda</h3>
            <?php foreach ($query as $item): ?>
                <input type="hidden" name="id_pengembalian" value="<?= $item["id_pengembalian"]; ?>">

                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="<?= $item["nama"]; ?>" readonly>

                <label for="judul">Buku yang Dipinjam</label>
                <input type="text" id="judul" name="judul" value="<?= $item["judul"]; ?>" readonly>

                <label for="buku_kembali">Tanggal Dikembalikan</label>
                <input type="date" id="buku_kembali" name="buku_kembali" value="<?= $item["buku_kembali"]; ?>" readonly>

                <label for="denda">Besar Denda</label>
                <input type="number" id="denda" name="denda" value="<?= $item["denda"]; ?>" readonly>

                <label for="bayarDenda">Jumlah Denda yang Dibayar</label>
                <input type="number" id="bayarDenda" name="bayarDenda" required>
            <?php endforeach; ?>

            <button type="reset" class="btn btn-warning">Reset</button>
            <button type="submit" class="btn" name="bayar">Bayar</button>
        </form>
    </div>

    <footer>
        <p>Created by <strong>Mangandaralam Sakti</strong> &copy; 2023 - versi 1.0</p>
    </footer>
</body>

</html>