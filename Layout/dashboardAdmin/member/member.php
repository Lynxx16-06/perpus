<?php
session_start();

// Pastikan fungsi queryReadData dan searchMember ada di config.php
require "../../../data/config.php";

// Ambil data member jika form pencarian tidak disubmit
$member = queryReadData("SELECT * FROM member");

// Jika form pencarian disubmit
if (isset($_POST["search"])) {
    $keyword = $_POST["keyword"];
    $member = searchMember($keyword);  // Pastikan fungsi searchMember terdefinisi
}

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
    <link rel="stylesheet" href="/styles/users-admin.css">
    <title>Admin Dashboard</title>
</head>

<body>

    <nav class="navbar">
        <div class="container-fluid p-3">
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
                        <a class="dropdown-item" href="#">Akun Terverifikasi <span class="text-primary">&#x2714;</span></a>
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

    
    <!-- Main Content -->
    <main class="main-content">
        <form action="" method="post" class="search-form">
            <input class="search-input" type="text" name="keyword" id="keyword" placeholder="Cari data member...">
            <button class="search-button" type="submit" name="search">Cari</button>
        </form>
        
        <!-- Tabel Data Member -->
        <section class="table-section">
            <h2>List of Members</h2>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Kelas</th>
                            <th>No Telepon</th>
                            <th>Pendaftaran</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($member as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item["nisn"]); ?></td>
                            <td><?= htmlspecialchars($item["kode_member"]); ?></td>
                            <td><?= htmlspecialchars($item["nama"]); ?></td>
                            <td><?= htmlspecialchars($item["jenis_kelamin"]); ?></td>
                            <td><?= htmlspecialchars($item["kelas"]); ?></td>
                            <td><?= htmlspecialchars($item["no_tlp"]); ?></td>
                            <td><?= htmlspecialchars($item["tgl_pendaftaran"]); ?></td>
                            <td>
                                <a href="deleteMember.php?id=<?= urlencode($item["nisn"]); ?>" class="delete-button"
                                onclick="return confirm('Yakin ingin menghapus data member?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <!-- <footer class="footer">
        <div class="footer-container">
            <p>Created by <span class="author">Muya</span> &copy; 2024</p>
            <p>Versi 1.0</p>
        </div>
    </footer> -->
</body>

</html>