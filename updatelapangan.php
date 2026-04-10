<?php
session_name("admin_session");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_lapangan = mysqli_real_escape_string($conn, $_POST['id_lapangan']);
    $nama_lapangan = mysqli_real_escape_string($conn, $_POST['nama_lapangan']);
    $harga_sewa = mysqli_real_escape_string($conn, $_POST['harga_sewa']);

    // Cek jika ada file gambar baru diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; 
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $nama_file = uniqid() . '-' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $nama_file;
        
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $sql = "UPDATE lapangan SET 
                        nama_lapangan = '$nama_lapangan', 
                        harga_sewa = '$harga_sewa', 
                        gambar = '$target_file' 
                    WHERE id_lapangan = '$id_lapangan'";
        } else {
            // PERUBAHAN DI SINI
            header("Location: datalapangan.php?status=upload_gagal");
            exit;
        }
    } else {
        $sql = "UPDATE lapangan SET 
                    nama_lapangan = '$nama_lapangan', 
                    harga_sewa = '$harga_sewa' 
                WHERE id_lapangan = '$id_lapangan'";
    }

    if (mysqli_query($conn, $sql)) {
        // PERUBAHAN DI SINI: Mengarahkan kembali ke halaman data lapangan
        header("Location: datalapangan.php?status=update_sukses");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
} else {
    // PERUBAHAN DI SINI: Jika diakses langsung, arahkan ke halaman data lapangan
    header("Location: datalapangan.php");
    exit();
}
?>