<?php

session_name("admin_session");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_lapangan = $_GET['id'];

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "sewalapangan";
    $conn = mysqli_connect($host, $user, $password, $db);

    if ($conn) {
        $sql = "DELETE FROM lapangan WHERE id_lapangan = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_lapangan);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: adminhome.php?status=hapus_sukses");
            exit();
        } else {
            echo "Error: Gagal menghapus data.";
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "Error: Gagal terhubung ke database.";
    }
} else {
    header("Location: datalapangan.php");
    exit();
}
?>