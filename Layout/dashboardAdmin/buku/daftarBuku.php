<?php
require "../../../data/config.php";
$buku = queryReadData("SELECT * FROM buku");

// Mengaktifkan tombol search engine
if (isset($_POST["search"])) {
    // Ambil apa saja yang diketikkan user di dalam input dan kirimkan ke function search
    $buku = search($_POST["keyword"]);
}
session_start()
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/daftar-buku.css"> <!-- Link ke file CSS Anda -->
    <title>Kelola Buku</title>
</head>

<body>

    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="/images/logoNav.png" alt="logo" width="120px">
            </a>
            <div class="dropdown">
                <button style="border: none; background-color: transparent;" class="btn-dropdown" onclick="toggleDropdown()">
                    <img src="/images/adminLogo.png" alt="adminLogo" width="40px">
                </button>
                <ul id="dropdown-menu" class="dropdown-menu hidden">
                    <li class="text-center">
                        <a class="dropdown-item" href="#">
                            <img src="/images/adminLogo.png" alt="adminLogo" width="30px">
                        </a>
                    </li>
                    <li class="text-center">
                        <span class="text-capitalize"><?php echo $_SESSION['admin_username']; ?></span>
                    </li>
                    <hr>
                    <li class="text-center mb-2">
                        <a class="dropdown-item" href="#">Akun Terverifikasi <span
                                class="text-primary">&#x2714;</span></a>
                    </li>
                    <li class="text-center">
                        <a class="dropdown-item p-2 bg-danger text-light rounded" href="/Layout/dashboardAdmin/signOut.php">Sign Out &#x2794;</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <aside>
        <div class="sidebar">
            <main class="logo-utama">
                <img style="width: 20%;" src="/images/logo.png" alt="">
                <div class="utama">
                    <h2>DASHBOARD</h2>
                    <?php
                    // Mendapatkan tanggal dan waktu saat ini
                    $day = date('l');
                    $dayOfMonth = date('d');
                    $month = date('F');
                    $year = date('Y');
                    ?>
                    <span><?php echo "$day $dayOfMonth $month $year"; ?></span>
                </div>
            </main>
            <hr>
            <ul>
                <a href="/Layout/dashboardAdmin/member/member.php">Member</a>
                <a href="/Layout/dashboardAdmin/buku/daftarBuku.php">Buku</a>
                <a href="/Layout/dashboardAdmin/peminjaman/peminjamanBuku.php/">Peminjaman</a>
            </ul>
        </div>
    </aside>

    <section>
        <div class="content">

        </div>
    </section>
    <nav class="navbar-box">
        <div class="navbar-links">
            <form action="" method="post" class="search-form">
                <input class="search-input" type="text" name="keyword" id="keyword" placeholder="Cari data buku...">
                <button class="search-button" type="submit" name="search"><i
                class="fa-solid fa-magnifying-glass"></i>Cari</button>
                <a href="tambahBuku.php" class="nav-link">Tambah Buku</a>
            </form>
            </div>
        </div>
    </nav>

    <!-- Form Pencarian (posisi fixed di pojok kanan) -->

    <div class="content">
        <!-- Card Buku -->
        <div class="card-container">
            <?php foreach ($buku as $item): ?>
                <div class="card">
                    <img src="/imgDB/<?= $item["cover"]; ?>" alt="coverBuku" class="card-img">
                    <div class="card-body">
                        <h5 class="card-title"><?= $item["judul"]; ?></h5>
                        <hr>
                        <ul>
                            <li>Kategori: <?= $item["kategori"]; ?></li>
                            <li>ID Buku: <?= $item["id_buku"]; ?></li>
                        </ul>
                        <hr>
                        <div class="card-actions">
                            <a href="updateBuku.php?idReview=<?= $item["id_buku"]; ?>" class="btn btn-edit">Edit</a>
                            <a href="deleteBuku.php?id=<?= $item["id_buku"]; ?>" class="btn btn-delete"
                                onclick="return confirm('Yakin ingin menghapus data buku?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>