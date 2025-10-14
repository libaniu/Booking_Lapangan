<?php
// lapangan.php

// Gunakan nama sesi yang sama agar sesi login terbaca
session_name("admin_session");
session_start();

// Jika tidak ada sesi login, tendang ke halaman login
if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

// Pastikan form disubmit dengan tombol submit
if (isset($_POST['submit'])) {

    // Koneksi ke database
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "sewalapangan";
    $conn = mysqli_connect($host, $user, $password, $db);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil data dari form
    $nama_lapangan = mysqli_real_escape_string($conn, $_POST['nama_lapangan']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);
    
    // Proses upload gambar
    $gambar_path = ''; // default path kosong
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Pastikan folder 'uploads' ada
        
        // Buat folder jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $nama_file = uniqid() . '-' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $nama_file;

        // Pindahkan file yang diunggah
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar_path = $target_file; // simpan path jika berhasil diunggah
        } else {
            // Gagal upload, kembali dengan pesan error
            header("Location: datalapangan.php?status=upload_gagal");
            exit;
        }
    }

    // Buat query untuk memasukkan data
    $sql = "INSERT INTO lapangan (nama_lapangan, harga_sewa, gambar) VALUES (?, ?, ?)";
    
    // Gunakan prepared statement untuk keamanan
    $stmt = mysqli_prepare($conn, $sql);
    // 'sss' berarti tiga variabelnya adalah string
    mysqli_stmt_bind_param($stmt, "sss", $nama_lapangan, $harga_sewa, $gambar_path);
    
    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, arahkan kembali ke halaman data lapangan yang benar
        header("Location: datalapangan.php?status=tambah_sukses");
        exit();
    } else {
        echo "Error: Gagal menambahkan data. " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Jika file diakses langsung tanpa submit form, arahkan kembali
    header("Location: datalapangan.php");
    exit();
}
?>