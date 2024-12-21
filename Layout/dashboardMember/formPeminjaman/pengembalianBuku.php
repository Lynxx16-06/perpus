<?php
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}
require "../../../data/config.php";
$idPeminjaman = $_GET["id"];

if (!isset($idPeminjaman)) {
    die("ID peminjaman tidak ditemukan.");
}

$query = $connection->prepare("SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, peminjaman.nisn, member.nama, peminjaman.id_admin, peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian
FROM peminjaman
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
INNER JOIN member ON peminjaman.nisn = member.nisn
WHERE peminjaman.id_peminjaman = ?");
$query->bind_param("i", $idPeminjaman);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

if (empty($data)) {
    echo "No data found for this id_peminjaman.";
    exit;
}

if (isset($_POST["kembalikan"])) {
    $inputCode = $_POST["code_pengembalian"];
    $validCode = "12345"; // Kode pengembalian

    if ($inputCode !== $validCode) {
        echo "<script>
        alert('Code Pengembalian salah! Harap masukkan kode yang benar. Atau anda bisa menghubungi admin untuk meminta code pengembalian');
        </script>";
    } else {
        if (pengembalianBuku($_POST) > 0) {
            echo "<script>
            alert('Terimakasih telah mengembalikan buku!');
            window.location.href = 'TransaksiPeminjaman.php';
            </script>";
        } else {
            echo "<script>
            alert('Buku gagal dikembalikan');
            </script>";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/member/kembali-buku.css">
    <title>Form Pengembalian Buku || Member</title>
</head>
<body>
    <nav>
        <a href="../dashboardMember.php">Dashboard</a>
        <a href="#">Logout</a>
    </nav>

    <div class="container">
        <h3>Form Pengembalian Buku</h3>
        <form action="" method="post">
            <?php foreach ($data as $item): ?>
                <label>Id Peminjaman</label>
                <input type="number" name="id_peminjaman" value="<?= $item["id_peminjaman"] ?>" readonly>

                <label>Id Buku</label>
                <input type="text" name="id_buku" value="<?= $item["id_buku"] ?>" readonly>

                <label>Judul Buku</label>
                <input type="text" name="judul" value="<?= $item["judul"] ?>" readonly>

                <label>Nisn Siswa</label>
                <input type="number" name="nisn" value="<?= $item["nisn"] ?>" readonly>

                <label>Nama Siswa</label>
                <input type="text" name="nama" value="<?= $item["nama"] ?>" readonly>

                <label>Id Admin Perpustakaan</label>
                <input type="number" name="id_admin" value="<?= $item["id_admin"] ?>" readonly>

                <label>Tanggal Buku Dipinjam</label>
                <input type="date" name="tgl_peminjaman" value="<?= $item["tgl_peminjaman"] ?>" readonly>

                <label>Tenggat Pengembalian Buku</label>
                <input type="date" name="tgl_pengembalian" value="<?= $item["tgl_pengembalian"] ?>" readonly>

                <label>Hari Pengembalian Buku</label>
                <input type="date" name="buku_kembali" value="<?php echo date('Y-m-d'); ?>" readonly>

                <label>Keterlambatan</label>
                <input type="text" name="keterlambatan" id="keterlambatan" oninput="hitungDenda()" readonly>

                <label>Denda</label>
                <input type="number" name="denda" id="denda" readonly>

                <label for="">Code Pengembalian</label>
                <input type="text" name="code_pengembalian" id="code_pengembalian">
            <?php endforeach; ?>

            <a href="TransaksiPeminjaman.php" class="btn-danger">Batal</a>
            <button type="submit" name="kembalikan">Kembalikan</button>
        </form>
    </div>

    <div class="footer">
        Created by <span style="color: #17a2b8;">Mangandaralam Sakti</span> &copy; 2023 | versi 1.0
    </div>
</body>
</html>
