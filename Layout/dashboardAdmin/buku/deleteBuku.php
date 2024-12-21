<?php
require "../../../data/config.php"; // Pastikan ini mengarah ke konfigurasi database Anda

if (isset($_GET['id'])) {
    $idBuku = $_GET['id'];

    // Query untuk menghapus data
    $query = "DELETE FROM buku WHERE id_buku = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $idBuku);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                alert('Data buku berhasil dihapus!');
                document.location.href = 'daftarBuku.php';
            </script>";
        } else {
            echo "<script>
                alert('Data buku gagal dihapus!');
                document.location.href = 'daftarBuku.php';
            </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
            alert('Kesalahan dalam query SQL!');
            document.location.href = 'daftarBuku.php';
        </script>";
    }
} else {
    echo "<script>
        alert('ID buku tidak ditemukan!');
        document.location.href = 'daftarBuku.php';
    </script>";
}
?>
