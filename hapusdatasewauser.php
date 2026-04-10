<?php
session_name("user_session");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection error");
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Menggunakan prepared statement untuk mencegah SQL Injection
    $sqlDelete = "DELETE FROM formsewa WHERE id = ?";
    $stmt = mysqli_prepare($data, $sqlDelete);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $resultDelete = mysqli_stmt_execute($stmt);

    if ($resultDelete) {
        header("Location: User/statussewa.php");
        exit();
    } else {
        die("Delete error: " . mysqli_error($data));
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($data);
?>
