<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';

$query = "SELECT * FROM login"; // Sesuaikan nama tabel
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<p>ID User: " . $row['id_user'] . " - Nama: " . $row['nama'] . " - Email: " . $row['email'] . "</p>";
    }
} else {
    echo "Tidak ada data.";
}

$conn->close();
?>
