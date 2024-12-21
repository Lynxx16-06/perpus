<?php 
session_start();

// Cek apakah member sudah login
if(!isset($_SESSION["signIn"])) {
    header("Location: ../login/member/sign_in.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/dash-admin.css"> <!-- Link ke file CSS Anda -->
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Dashboard Member</title>
</head>
<body>

<nav class="navbar fixed-top bg-body-tertiary shadow-sm">
    <div class="container-fluid p-3">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="/images/logoNav.png" alt="logo" width="120px">
        </a>
        
        <!-- Dropdown untuk Member -->
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/images/memberLogo.png" alt="memberLogo" width="40px">
            </button>
            <ul class="dropdown-menu position-absolute mt-2 p-2">
                <!-- Profile Icon -->
                <li class="text-center">
                    <a class="dropdown-item" href="#">
                        <img src="/images/memberLogo.png" alt="memberLogo" width="30px">
                    </a>
                </li>
                <!-- Member's Name -->
                <li class="text-center text-secondary">
                    <span class="text-capitalize"><?php echo $_SESSION['member']['nama']; ?></span>
                </li>
                <hr>
                <!-- Member's Role (Optional) -->
                <li class="text-center mb-2">
                    <a class="dropdown-item" href="#">Member</a>
                </li>
                <!-- Sign Out Button -->
                <li class="text-center">
                    <a class="dropdown-item p-2 bg-danger text-light rounded" href="signOut.php">Sign Out <i class="fa-solid fa-right-to-bracket"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="mt-5 p-4">
    <?php
    // Mendapatkan tanggal dan waktu saat ini
    $day = date('l');
    $dayOfMonth = date('d');
    $month = date('F');
    $year = date('Y');
    ?>

    <h1 class="mt-5 fw-bold">Dashboard - <span class="fs-4 text-secondary"><?php echo "$day $dayOfMonth $month $year"; ?></span></h1>

    <div class="alert alert-success" role="alert">
        Selamat datang member - <span class="fw-bold text-capitalize"><?php echo $_SESSION['member']['nama']; ?></span> di Dashboard perpustakaan MTs MMH
    </div>

    <div class="mt-3 p-3">
        <h3>Layanan Perpustakaan yang tersedia</h3>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <div class="cardImg">
                <a href="/Layout/dashboardMember/buku/daftarBuku.php">
                    <img src="/images/dashboardCardMember/daftarBuku.png" alt="Daftar Buku">
            </div>
            <div class="cardImg">
                <a href="/Layout/dashboardMember/formPeminjaman/transaksiPeminjaman.php">
                    <img src="/images/dashboardCardMember/peminjaman.png" alt="Peminjaman">
                </a>
            </div>
            <div class="cardImg">
                <a href="/Layout/dashboardMember/formPeminjaman/transaksiPengembalian.php">
                    <img src="/images/dashboardCardMember/pengembalian.png" alt="Pengembalian">
                </a>
            </div>
            <div class="cardImg">
                <a href="/Layout/dashboardMember/formPeminjaman/transakasiDenda.php">
                    <img src="/images/dashboardCardMember/denda.png" alt="Denda">
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
