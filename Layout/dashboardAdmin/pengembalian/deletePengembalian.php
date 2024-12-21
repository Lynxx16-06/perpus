<?php
require "../../../data/config.php";

$id = $_GET["id"];

// Validasi input
if (!is_numeric($id)) {
    die("ID tidak valid!");
}

$query = "DELETE FROM pengembalian WHERE id_pengembalian = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
        alert('Data berhasil dihapus!');
        window.location.href = 'kembaliBuku.php'; // Redirect ke halaman utama
    </script>";
} else {
    echo "<script>
        alert('Gagal menghapus data: " . $stmt->error . "');
    </script>";
}

$stmt->close();
$connection->close();
?>
