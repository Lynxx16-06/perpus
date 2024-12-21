<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: ../login/admin/sign_in.php");
    exit;
}

// Pastikan data admin tersedia dalam session
if (!isset($_SESSION['admin_username'])) {
    echo "Data admin tidak ditemukan!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/styles/dash-admin.css"> <!-- Link ke file CSS Anda -->
    <title>Admin Dashboard</title>
</head>

<body>

    <nav class="navbar">
        <div class="container-fluid p-3">
            <a class="navbar-brand" href="#">
                <img src= "/images/logoNav.png" alt="logo" width="120px">
            </a>
            <div class="dropdown">
                <button style="border: none; background-color: transparent;" class="btn-dropdown" onclick="toggleDropdown()  ">
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
                        <a class="dropdown-item" href="#">Akun Terverifikasi <span class="text-primary">&#x2714;</span></a>
                    </li>
                    <li class="text-center">
                        <a class="dropdown-item p-2 bg-danger text-light rounded" href="signOut.php">Sign Out &#x2794;</a>
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
                <a href="/Layout/dashboardAdmin/peminjaman/peminjamanBuku.php">Peminjaman</a>
            </ul>
        </div>
    </aside>

    <section>
        <div class="content">
            
        </div>
    </section>

    <script>
        // Fungsi untuk toggle dropdown menu
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.classList.toggle('hidden');
        }
    </script>

</body>

</html>
