<?php
session_start();

// Pastikan koneksi sudah di-include dengan benar
require "data/koneksi.php";  // Pastikan path ini sesuai dengan file koneksi kamu

if (isset($_POST["signIn"])) {
    $nama = strtolower($_POST["nama"]);
    $nisn = $_POST["nisn"];
    $password = $_POST["password"];

    // Menggunakan $conn untuk koneksi yang benar
    $query = "SELECT * FROM member WHERE nama = ? AND nisn = ?";
    $stmt = mysqli_prepare($conn, $query); // Ganti $connect dengan $conn

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "si", $nama, $nisn);
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $pw = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $pw["password"])) {
            // Set session jika login berhasil
            $_SESSION["signIn"] = true;
            $_SESSION["member"]["nama"] = $nama;
            $_SESSION["member"]["nisn"] = $nisn;

            // Redirect ke dashboard member
            header("Location: /Layout/dashboardMember/utama.php");
            exit;
        }
    }

    // Menampilkan pesan error jika login gagal
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In || Member</title>
    <link href="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/member/sign-mem.css">
</head>

<body>

    <div class="container">
        <div class="card">
            <div class="logo-container">
                <img src="/images/memberLogo.png" alt="adminLogo">
            </div>
            <h1 class="text-center">Sign In</h1>
            <hr>
            <form action="" method="post" class="login-form">
                <label for="nama">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="nama" id="nama" required>
                </div>

                <label for="nisn">Nisn</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fa-solid fa-hashtag"></i></span>
                    <input type="number" name="nisn" id="nisn" required>
                </div>

                <label for="password">Password</label>
                <div class="input-group">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit" name="signIn">Sign In</button>
                    <a class="btn btn-cancel" href="index.php">Batal</a>
                </div>

                <p>Don't have an account yet? <a href="/login/member/sign_up.php" class="signup-link">Sign Up</a></p>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">Nama / Nisn / Password tidak sesuai!</div>
        <?php endif; ?>
    </div>

    <script>
        (() => {
            'use strict'

            const forms = document.querySelectorAll('.login-form')

            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

</body>

</html>