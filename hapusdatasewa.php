<?php
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

    // Perform the delete operation
    $sqlDelete = "DELETE FROM formsewa WHERE id = '$id'";
    $resultDelete = mysqli_query($data, $sqlDelete);

    if ($resultDelete) {
        // Mengembalikan pengguna ke halaman asal (REFERER)
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: Admin/datasewa.php");
        }
        exit();
    } else {
        die("Delete error: " . mysqli_error($data));
    }
}
