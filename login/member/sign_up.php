<?php 
require "../../data/koneksi.php";  // Pastikan ini mengarah ke file koneksi.php
require "../../data/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $result = signUp($_POST);
  if ($result) {
      echo "Pendaftaran berhasil!";
  } else {
      echo "Terjadi kesalahan saat mendaftar.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up || Member</title>
    <link rel="stylesheet" href="../../styles/member/signUp-mem.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="logo">
        <img src="/images/memberLogo.png" alt="adminLogo" width="85px">
      </div>
      <h1>Sign Up</h1>
      <form action="" method="post" class="form" novalidate>
      
        <label for="nisn">Nisn</label>
        <input type="number" name="nisn" id="nisn" required>
        <div class="error">Nisn wajib diisi!</div>

        <label for="kode_member">Kode Member</label>
        <input type="text" name="kode_member" id="kode_member" required>
        <div class="error">Kode member wajib diisi!</div>

        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" required>
        <div class="error">Nama wajib diisi!</div>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <div class="error">Password wajib diisi!</div>

        <label for="confirmPw">Confirm Password</label>
        <input type="password" name="confirmPw" id="confirmPw" required>
        <div class="error">Konfirmasi password wajib diisi!</div>

        <label for="jenis_kelamin">Gender</label>
        <select name="jenis_kelamin" id="jenis_kelamin" required>
          <option value="">Choose</option>
          <option value="Laki laki">Laki laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>

        <label for="kelas">Kelas</label>
        <select name="kelas" id="kelas" required>
          <option value="">Choose</option>
          <option value="X">X</option>
          <option value="XI">XI</option>
          <option value="XII">XII</option>
          <option value="XIII">XIII</option>
        </select>

        <label for="no_tlp">No Telepon</label>
        <input type="number" name="no_tlp" id="no_tlp" required>
        <div class="error">No telepon wajib diisi!</div>

        <label for="tgl_pendaftaran">Tanggal Pendaftaran</label>
        <input type="date" name="tgl_pendaftaran" id="tgl_pendaftaran" required>
        <div class="error">Tanggal pendaftaran wajib diisi!</div>

        <button type="submit" name="signUp">Sign Up</button>
        <input type="reset" class="reset" value="Reset">
        
        <p>Already have an account? <a href="sign_in.php">Sign In</a></p>
      </form>
    </div>
  </div>
</body>
</html>
