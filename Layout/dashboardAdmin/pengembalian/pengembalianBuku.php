<?php
// Koneksi ke database
$connection = new mysqli("localhost", "root", "", "perpustakaan");

if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}

// Fungsi untuk membaca data pengembalian
function getReturnedBooks() {
    global $connection;
    $query = "
        SELECT 
            peminjaman.id_peminjaman,
            peminjaman.id_buku,
            buku.judul,
            buku.kategori,
            peminjaman.nisn,
            member.nama AS nama_peminjam,
            member.kelas,
            member.jurusan,
            admin.nama_admin,
            pengembalian.buku_kembali,
            pengembalian.keterlambatan,
            pengembalian.denda
        FROM peminjaman
        INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
        INNER JOIN member ON peminjaman.nisn = member.nisn
        LEFT JOIN pengembalian ON peminjaman.id_peminjaman = pengembalian.id_peminjaman
        INNER JOIN admin ON pengembalian.id_admin = admin.id
        WHERE pengembalian.id_peminjaman IS NOT NULL;
    ";

    $result = $connection->query($query);
    if (!$result) {
        die("Query gagal: " . $connection->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Ambil data buku yang sudah dikembalikan
$dataPengembalian = getReturnedBooks();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Halaman kelola pengembalian buku untuk admin.">
    <link rel="stylesheet" href="/styles/admin/kembali-buku.css">
    <title>Kelola Pengembalian Buku || Admin</title>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-container">
        <a href="#">
            <img src="/images/logoNav.png" alt="logo" class="logo">
        </a>
        <a class="dashboard-btn" href="../dashboard.php">Dashboard</a>
    </div>
</nav>

<!-- Main Content -->
<main class="main-content">
    <section class="table-section">
        <h2>List Pengembalian Buku</h2>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Id Pengembalian</th>
                        <th>Id Buku</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Nama Admin</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Keterlambatan</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dataPeminjam)): ?>
                        <tr>
                            <td colspan="13" class="empty-data">Data tidak ditemukan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dataPeminjam as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item["id_pengembalian"]) ?></td>
                                <td><?= htmlspecialchars($item["id_buku"]) ?></td>
                                <td><?= htmlspecialchars($item["judul"]) ?></td>
                                <td><?= htmlspecialchars($item["kategori"]) ?></td>
                                <td><?= htmlspecialchars($item["nisn"]) ?></td>
                                <td><?= htmlspecialchars($item["nama"]) ?></td>
                                <td><?= htmlspecialchars($item["kelas"]) ?></td>
                                <td><?= htmlspecialchars($item["nama_admin"]) ?></td>
                                <td><?= htmlspecialchars($item["buku_kembali"]) ?></td>
                                <td><?= htmlspecialchars($item["keterlambatan"]) ?></td>
                                <td><?= htmlspecialchars($item["denda"]) ?></td>
                                <td>
                                    <a href="?delete_id=<?= $item["id_pengembalian"] ?>" 
                                       class="delete-btn" 
                                       onclick="return confirm('Yakin ingin menghapus data ini?');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="footer">
    <p>Created by <span class="author">Muya</span> &copy; 2024</p>
    <p>Versi 1.0</p>
</footer>

</body>
</html>
