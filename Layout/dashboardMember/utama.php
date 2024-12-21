<?php
session_start();
require "../../data/config.php";
require "../../data/koneksi.php";  // Make sure this path is correct for your connection file

if (isset($_POST["signIn"])) {
    $nama = strtolower($_POST["nama"]);
    $nisn = $_POST["nisn"];
    $password = $_POST["password"];

    // Use $conn for the correct database connection
    $query = "SELECT * FROM member WHERE nama = ? AND nisn = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind parameters and execute query
    mysqli_stmt_bind_param($stmt, "si", $nama, $nisn);
    mysqli_stmt_execute($stmt);

    // Get query results
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $pw = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $pw["password"])) {
            // Set session variables if login is successful
            $_SESSION["signIn"] = true;
            $_SESSION["member"]["nama"] = $nama;
            $_SESSION["member"]["nisn"] = $nisn;

            // Redirect to member dashboard
            header("Location: /Layout/dashboardMember/buku/detailBuku.php");
            exit;
        }
    }

    // Display error message if login fails
    $error = true;
}

// Default query (all books)
$buku = queryReadData("SELECT * FROM buku");

// Filter by category or search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['keyword'])) {
        $buku = search($_POST['keyword']);
    } elseif (!empty($_POST['kategori'])) {
        $kategori = $_POST['kategori'];
        $buku = queryReadData("SELECT * FROM buku WHERE kategori = ?", [$kategori]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Perpustakaan MTs Matholi'ul Huda dengan koleksi buku terlengkap untuk mendukung pembelajaran.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500;600;700;800&family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="/styles/index.css">
    <title>Perpustakaan MTs MMH</title>
    <style>
        .logo-image {
            width: 80px;
            height: auto;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar" id="navbar">
        <div class="logo">
            <img src="/images/logo.png" alt="logo madrasah" class="logo-image">
            <h1 class="logo-title">Perpustakaan</h1>
            <span class="logo-subtitle">MTs MMH</span>
        </div>
        <div class="content">
            <ul class="menu">
                <li><a href="/Layout/dashboardMember/formPeminjaman/transaksiPeminjaman.php" class="menu-item"><i class='bx bx-store'></i> Peminjaman</a></li>
                <li><a href="/login/admin/sign_in.php" class="menu-item-box"><i class='bx bx-library'></i> Admin-Log</a></li>
            </ul>
        </div>
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
    </nav>

    <h1 class="judul">HALLO SELAMAT DATANG DI PERPUSTAKAAN MTs MMH</h1>

    <section class="welcome-section">
        <article class="welcome-article">
            <p>
                Perpustakaan MTs Matholi'ul Huda adalah pusat informasi dan pembelajaran yang menyediakan berbagai jenis
                buku untuk mendukung proses belajar siswa di sekolah. Kami memiliki koleksi buku yang beragam, mulai
                dari buku pelajaran, novel, hingga ensiklopedia.
            </p>
            <p>
                Dengan suasana yang nyaman dan fasilitas modern, perpustakaan ini berkomitmen untuk menjadi sumber
                inspirasi dan pengetahuan bagi semua pengunjung.
            </p>
        </article>

        <figure class="carousel-container" id="carousel">
            <button class="carousel-btn prev" onclick="prevSlide()">&#10094;</button>
            <div class="carousel">
                <img src="/images/library1.jpg" alt="Foto Perpustakaan 1" class="carousel-image">
                <img src="/images/library2.jpg" alt="Foto Perpustakaan 2" class="carousel-image">
                <img src="/images/library3.jpg" alt="Foto Perpustakaan 3" class="carousel-image">
            </div>
            <button class="carousel-btn next" onclick="nextSlide()">&#10095;</button>
            <figcaption>Foto interior perpustakaan kami.</figcaption>
        </figure>
    </section>

    <section>
        <main class="interior">
            <h1>Buku yang Terlaris Dipinjam</h1>
            <div class="interior-box-box">
                <div class="interior-box">
                    <img style="width: 50%;" src="/imgDB/654e4a1c80441.jpg" alt="Lap Komputer">
                    <h2>Python</h2>
                </div>
                <div class="interior-box">
                    <img style="width: 50%;" src="/imgDB/654e4417e323e.jpeg" alt="Pulang">
                    <h2>Pulang</h2>
                </div>
                <div class="interior-box">
                    <img style="width: 50%;" src="/imgDB/654e48e1a1680.jpg" alt="Dasar Dasar Pemrograman">
                    <h2>Dasar Dasar Pemrograman</h2>
                </div>
                <div class="interior-box">
                    <img style="width: 50%;" src="/imgDB/654e456c2e275.jpg" alt="Susah Senang untuk Tuhan">
                    <h2>Susah Senang untuk Tuhan</h2>
                </div>
            </div>
        </main>
    </section>

    <section>
        <main class="daf_buku">
            <h1 style="padding-left: 50px; font-size: 30px;">Daftar Buku</h1>
            <div class="content">
                <!-- <div class="filter-buttons">
                    <form action="" method="post">
                        <div class="button-group">
                            <button type="submit" name="kategori" value="" class="btn btn-primary">Semua</button>
                            <button type="submit" name="kategori" value="informatika"
                                class="btn btn-outline">Informatika</button>
                            <button type="submit" name="kategori" value="bisnis" class="btn btn-outline">Bisnis</button>
                            <button type="submit" name="kategori" value="filsafat"
                                class="btn btn-outline">Filsafat</button>
                            <button type="submit" name="kategori" value="novel" class="btn btn-outline">Novel</button>
                            <button type="submit" name="kategori" value="sains" class="btn btn-outline">Sains</button>
                        </div>
                    </form>
                </div> -->

                <form action="" method="post" class="search-form">
                    <div class="search-bar">
                        <input class="search-input" type="text" name="keyword" id="keyword"
                            placeholder="Cari Judul atau Kategori Buku...">
                        <button class="search-button" type="submit">Cari</button>
                    </div>
                </form>

                <div class="card-container">
                    <?php foreach ($buku as $item): ?>
                        <div class="card">
                            <img src="/imgDB/<?= htmlspecialchars($item["cover"]) ?>"
                                alt="<?= htmlspecialchars($item["judul"]) ?>" class="card-img">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item["judul"]) ?></h5>
                            </div>
                            <ul class="card-info">
                                <li>Kategori : <?= htmlspecialchars($item["kategori"]) ?></li>
                            </ul>
                            <div class="card-footer">
                                    <a class="btn btn-success"
                                        href="/Layout/dashboardMember/buku/detailBuku.php?id=<?= $item["id_buku"] ?>">Detail</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </section>

    <footer>
        <p>&copy; 2024 Perpustakaan MTs Matholi'ul Huda. All rights reserved.</p>
    </footer>

    <script src="/scripts/script.js"></script>
    <script src="/scripts/main.js"></script>
</body>

</html>