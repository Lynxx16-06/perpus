<?php
require "../../data/koneksi.php"; // Sesuaikan path koneksi Anda

// Ganti dengan nama admin dan password yang ingin dienkripsi
$nama_admin = "admin"; // Contoh: admin
$password_plain = "password123"; // Contoh: password123

// Enkripsi password
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// Update password yang sudah dienkripsi ke database
$stmt = $connect->prepare("UPDATE admin SET password = ? WHERE nama_admin = ?");
$stmt->bind_param("ss", $password_hashed, $nama_admin);

if ($stmt->execute()) {
    echo "Password berhasil dienkripsi dan disimpan.";
} else {
    echo "Terjadi kesalahan: " . $stmt->error;
}

$stmt->close();
$connect->close();
