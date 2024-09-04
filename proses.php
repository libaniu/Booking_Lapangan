<?php
// Load file koneksi.php
include "koneksi.php";
// 

$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$email = $_POST['email'];
$biaya = 100000;
$order_id= rand();
$transaction_status= 1;
 
// mengalihkan halaman kembali ke index.php
header("location:./midtrans/examples/snap/checkout-process-simple-version.php?order_id=$order_id");


?>

