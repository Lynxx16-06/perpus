<?php
session_start(); // Memulai sesi

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = "";
$dbname = "perpustakaan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memproses login jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_admin = $_POST['username']; // Menggunakan nama_admin sebagai username
    $admin_password = $_POST['password'];

    // Query untuk memeriksa kredensial admin
    $sql = "SELECT * FROM admin WHERE nama_admin = ?"; // Mengambil data berdasarkan nama_admin saja
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nama_admin);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika admin ditemukan, cek password
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Verifikasi password secara langsung tanpa hashing
        if ($admin_password === $admin['password']) {
            // Login berhasil, simpan informasi admin di sesi
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id']; // Menyimpan ID admin
            $_SESSION['admin_username'] = $admin['nama_admin']; // Nama admin

            // Redirect ke halaman dashboard admin
            header("Location: /Layout/dashboardAdmin/dashboard.php"); // Ganti dengan halaman dashboard admin Anda
            exit();
        } else {
            $error_message = "Password yang Anda masukkan salah.";
        }
    } else {
        $error_message = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../../styles/admin/sign-min.css"> <!-- Link ke file CSS Anda -->
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="logo-container">
                <img src="../../images/adminLogo.png" alt="Admin Logo">
            </div>
            <h2>Login Admin</h2>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nama Admin:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <a href="/Layout/dashboardMember/utama.php">Kembali</a>
        </div>
    </div>
</body>
</html>