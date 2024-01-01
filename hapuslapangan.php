<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$id = $_GET['id'];

$conn = mysqli_connect($host, $user, $password, $db);
$sql = "DELETE FROM lapangan WHERE id_lapangan =$id ";
$result = mysqli_query($conn,$sql);
header("Location: http://localhost/sewalapanganfutsal/datalapangan.php");
