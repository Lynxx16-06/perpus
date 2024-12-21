<?php
include 'koneksi.php';

$query = "SELECT * FROM buku"; // Mengambil semua data dari tabel buku
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h1>Daftar Buku</h1>";
    while($row = $result->fetch_assoc()) {
        echo "<p>ID: " . $row['id_buku'] . 
             " - Judul: " . $row['judul_buku'] . 
             " - No Buku: " . $row['no_buku'] . 
             " - Kategori: " . $row['kategori'] . 
             " - Pengarang: " . $row['pengarang'] . 
             " - Stok: " . $row['stok'] . "</p>";
    }
} else {
    echo "Tidak ada buku.";
}

$conn->close();
?>
