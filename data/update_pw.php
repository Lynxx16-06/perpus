<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "perpustakaan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Password yang ingin di-hash
$plaintext_password = '1111'; // Ganti dengan password asli yang ingin di-hash
$hashed_password = password_hash($plaintext_password, PASSWORD_DEFAULT); // Hash password

// Query untuk update password
$sql = "UPDATE admin SET password = '$hashed_password' WHERE nama_admin = 'muya'"; // Ganti 'muya' sesuai nama admin

// Eksekusi query
if ($conn->query($sql) === TRUE) {
    echo "Password berhasil diupdate!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
