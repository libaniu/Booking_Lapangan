<?php

session_name("admin_session");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

if (isset($_POST['submit'])) {

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "sewalapangan";
    $conn = mysqli_connect($host, $user, $password, $db);
    
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $nama_lapangan = mysqli_real_escape_string($conn, $_POST['nama_lapangan']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);

    // Cek apakah ada file gambar yang diunggah tanpa error
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; 
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Buat folder uploads jika belum ada
        }
        $nama_file = uniqid() . '-' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $nama_file;
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO lapangan (nama_lapangan, harga_sewa, gambar) VALUES ('$nama_lapangan', '$harga_sewa', '$target_file')";
            if (mysqli_query($conn, $sql)) {
                header("Location: Admin/datalapangan.php?status=tambah_sukses");
                exit();
            }
        }
    }
    
    header("Location: Admin/datalapangan.php?status=upload_gagal");
    mysqli_close($conn);
    exit();
}
?>