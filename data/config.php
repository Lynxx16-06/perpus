<?php
// Database Configuration
$host = "localhost";
$username = "root";
$password = "";
$db_name = "perpustakaan";

// Create a connection to the database
$connection = mysqli_connect($host, $username, $password, $db_name);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch data from the database
function queryReadData($query, $params = [], $param_types = "") {
    global $connection;

    // Check if the query is empty
    if (empty($query)) {
        die("Query cannot be empty.");
    }

    // If there is a connection error
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Prepare the statement and bind parameters
    if (empty($params)) {
        // If no parameters are passed, simply run the query directly
        $result = $connection->query($query);
    } else {
        // If parameters are passed, prepare the statement and bind parameters
        $stmt = $connection->prepare($query);
        if ($stmt === false) {
            die("Error preparing statement: " . $connection->error);
        }
        if ($param_types) {
            $stmt->bind_param($param_types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }

    // Check if the result is valid
    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    // Check if there are rows returned
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Return as associative array
    } else {
        return []; // Return an empty array if no data is found
    }
}

// Function to execute insert, update, or delete queries
function executeQuery($query)
{
    global $connection;
    if (mysqli_query($connection, $query)) {
        return mysqli_affected_rows($connection);
    } else {
        die("Error: " . mysqli_error($connection));
    }
}

// Fungsi untuk mengupload file
function uploadFile($file, $uploadPath = '/imgDB/')
{
    $fileName = $file["name"];
    $fileSize = $file["size"];
    $fileError = $file["error"];
    $tmpName = $file["tmp_name"];

    if ($fileError === 4) {
        // Jika tidak ada file yang diupload, return false (file tidak diupload)
        return false;
    }

    // Validasi ekstensi file
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg', 'bmp', 'psd', 'tiff'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "<script>alert('Invalid file format!');</script>";
        return false;
    }

    // Validasi ukuran file
    if ($fileSize > 2000000) {
        echo "<script>alert('File size is too large!');</script>";
        return false;
    }

    // Validasi direktori upload
    if (!is_dir($uploadPath) || !is_writable($uploadPath)) {
        echo "<script>alert('Upload directory does not exist or is not writable!');</script>";
        return false;
    }

    // Membuat nama file baru yang unik
    $newFileName = uniqid() . '.' . $fileExtension;

    // Cek apakah file berhasil dipindahkan
    if (!move_uploaded_file($tmpName, $uploadPath . $newFileName)) {
        echo "<script>alert('Failed to upload file!');</script>";
        return false;
    }

    return $newFileName; // Return nama file yang telah diupload
}

// Fungsi untuk menambah buku
function tambahBuku($dataBuku, $cover)
{
    global $connection;

    // Ambil data dari form
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $kategoriBuku = $dataBuku["kategori"];
    $judulBuku = htmlspecialchars($dataBuku["judul"]);
    $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
    $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
    $tahunTerbit = DateTime::createFromFormat('Y-m-d', $dataBuku["tahun_terbit"]);
    $tahunTerbit = $tahunTerbit ? $tahunTerbit->format('Y-m-d') : null; // Formatkan menjadi Y-m-d
    $jumlahHalaman = (int)$dataBuku["jumlah_halaman"];
    $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);

    // Query untuk memasukkan data ke dalam database
    $query = "INSERT INTO buku 
              (id_buku, kategori, judul, pengarang, penerbit, tahun_terbit, jumlah_halaman, buku_deskripsi, cover)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Siapkan query untuk di-prepare
    $stmt = mysqli_prepare($connection, $query);
    if ($stmt === false) {
        echo "Error preparing statement: " . mysqli_error($connection);
        return 0;
    }

    // Bind parameters untuk prepared statement
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssis", // Sesuaikan dengan 9 parameter
        $idBuku,
        $kategoriBuku,
        $judulBuku,
        $pengarangBuku,
        $penerbitBuku,
        $tahunTerbit,
        $jumlahHalaman,
        $deskripsiBuku,
        $cover
    );

    // Eksekusi prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affected_rows;
    } else {
        echo "Error executing statement: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        return 0;
    }
}


// Fungsi untuk mengupdate buku
function updateBuku($dataBuku)
{
    global $connection;

    // Ambil data dari form
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $kategoriBuku = $dataBuku["kategori"];
    $judulBuku = htmlspecialchars($dataBuku["judul"]);
    $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
    $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
    $tahunTerbit = DateTime::createFromFormat('Y-m-d', $dataBuku["tahun_terbit"]);
    $tahunTerbit = $tahunTerbit ? $tahunTerbit->format('Y-m-d') : null;
    $jumlahHalaman = (int)$dataBuku["jumlah_halaman"];
    $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
    $gambarLama = htmlspecialchars($dataBuku["coverLama"]); // Menyimpan cover lama untuk update jika tidak diubah

    // Mengecek jika ada file cover yang baru di-upload
    if ($_FILES["cover"]["error"] === 4) {
        $cover = $gambarLama; // Jika tidak ada gambar baru, gunakan gambar lama
    } else {
        // Proses upload file cover
        $cover = uploadFile($_FILES['cover'], $_SERVER['DOCUMENT_ROOT'] . '/imgDB/');
    }

    // Query untuk memperbarui data buku
    $queryUpdate = "UPDATE buku SET 
        cover = ?, 
        kategori = ?, 
        judul = ?, 
        pengarang = ?, 
        penerbit = ?, 
        tahun_terbit = ?, 
        jumlah_halaman = ?, 
        buku_deskripsi = ? 
    WHERE id_buku = ?";

    // Siapkan query untuk di-prepare
    $stmt = mysqli_prepare($connection, $queryUpdate);
    if ($stmt === false) {
        echo "Error preparing statement: " . mysqli_error($connection);
        return 0;
    }

    // Bind parameters untuk prepared statement
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssis", // Tipe data untuk parameter
        $cover, 
        $kategoriBuku, 
        $judulBuku, 
        $pengarangBuku, 
        $penerbitBuku, 
        $tahunTerbit, 
        $jumlahHalaman, 
        $deskripsiBuku, 
        $idBuku
    );

    // Eksekusi prepared statement
    if (mysqli_stmt_execute($stmt)) {
        $affected_rows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affected_rows;
    } else {
        echo "Error executing statement: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        return 0;
    }
}


// Function to delete data from the 'pengembalian' table
function deleteDataPengembalian($idPengembalian)
{
    global $connection;
    $query = "DELETE FROM pengembalian WHERE id_pengembalian = ?";
    $stmt = $connection->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $idPengembalian);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }
    return 0;
}

// Function to search for member
function searchMember($keyword)
{
    global $connection;
    $keyword = mysqli_real_escape_string($connection, $keyword);
    $query = "SELECT * FROM member WHERE 
        nisn LIKE '%$keyword%' OR
        kode_member LIKE '%$keyword%' OR
        nama LIKE '%$keyword%' OR
        kelas LIKE '%$keyword%' OR
        jurusan LIKE '%$keyword%' OR
        no_tlp LIKE '%$keyword%'";
    return queryReadData($query);
}

// Function to delete a member
function deleteMember($nisn)
{
    global $connection;
    $nisn = mysqli_real_escape_string($connection, $nisn);
    $query = "DELETE FROM member WHERE nisn = '$nisn'";
    return mysqli_query($connection, $query);
}

// Function to search books
function search($keyword)
{
    global $connection;
    $keyword = mysqli_real_escape_string($connection, $keyword);
    $query = "SELECT * FROM buku WHERE
        judul LIKE '%$keyword%' OR
        kategori LIKE '%$keyword%' OR
        id_buku LIKE '%$keyword%'";
    return queryReadData($query);
}


// Function for member sign-up
// Function signUp definition (ensure this is placed before usage)
function signUp($data)
{
    global $connection;

    $nisn = htmlspecialchars($data["nisn"]);
    $kode_member = htmlspecialchars($data["kode_member"]);
    $nama = htmlspecialchars($data["nama"]);
    $passwordPlain = htmlspecialchars($data["password"]);
    $confirmPw = htmlspecialchars($data["confirmPw"]);
    $jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
    $kelas = htmlspecialchars($data["kelas"]);
    $no_tlp = htmlspecialchars($data["no_tlp"]);
    $tgl_pendaftaran = htmlspecialchars($data["tgl_pendaftaran"]);

    if ($passwordPlain !== $confirmPw) {
        echo "<script>alert('Passwords do not match!');</script>";
        return false;
    }

    $hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);

    $result = $connection->query("SELECT nisn FROM member WHERE nisn = '$nisn' OR kode_member = '$kode_member'");
    if ($result->num_rows > 0) {
        echo "<script>alert('NISN or Member Code already exists!');</script>";
        return false;
    }

    $query = "INSERT INTO member (nisn, kode_member, nama, password, jenis_kelamin, kelas, no_tlp, tgl_pendaftaran)
              VALUES ('$nisn', '$kode_member', '$nama', '$hashedPassword', '$jenis_kelamin', '$kelas', '$no_tlp', '$tgl_pendaftaran')";

    if ($connection->query($query) === TRUE) {
        return true;
    } else {
        echo "<script>alert('Error: " . $connection->error . "');</script>";
        return false;
    }
}


// Fungsi untuk meminjam buku


function pinjamBuku($data)
{
    global $conn;

    $id_buku = htmlspecialchars($data["id_buku"]);
    $nisn = htmlspecialchars($data["nisn"]);
    $id_admin = htmlspecialchars($data["id_admin"]);
    $tgl_peminjaman = htmlspecialchars($data["tgl_peminjaman"]);
    $tgl_pengembalian = htmlspecialchars($data["tgl_pengembalian"]);

    if (empty($id_admin) || empty($tgl_peminjaman) || empty($tgl_pengembalian)) {
        return 0; // Data tidak lengkap
    }

    // Prepared Statement
    $stmt = mysqli_prepare($conn, "INSERT INTO peminjaman (id_buku, nisn, id_admin, tgl_peminjaman, tgl_pengembalian) 
                                   VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $id_buku, $nisn, $id_admin, $tgl_peminjaman, $tgl_pengembalian);

    mysqli_stmt_execute($stmt);

    return mysqli_affected_rows($conn);
}


function pengembalianBuku($dataBuku)
{
    global $connection;

    // Remove the var_dump or any debug code that outputs data
    // var_dump($dataBuku); // Comment or remove this line to stop printing the data

    $idPeminjaman = $dataBuku["id_peminjaman"];

    // Start transaction
    $connection->begin_transaction();

    try {
        // Query to delete the peminjaman record
        $hapusDataPeminjam = "DELETE FROM peminjaman WHERE id_peminjaman = ?";
        $stmtHapus = $connection->prepare($hapusDataPeminjam);
        $stmtHapus->bind_param("i", $idPeminjaman);

        if (!$stmtHapus->execute()) {
            throw new Exception("Failed to delete peminjaman data: " . $stmtHapus->error);
        }

        // Commit the transaction
        $connection->commit();

        return $stmtHapus->affected_rows;

    } catch (Exception $e) {
        $connection->rollback();
        echo "Error: " . $e->getMessage();
        return 0;
    }
}



?>