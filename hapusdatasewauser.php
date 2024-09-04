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
        // Redirect back to the page after successful deletion
        header("Location: statussewa.php"); // Change "index.php" to the page where the table is located
        exit();
    } else {
        die("Delete error: " . mysqli_error($data));
    }
}
