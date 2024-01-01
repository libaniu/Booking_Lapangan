<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$nama_lapangan = $_POST['nama_lapangan'];
$harga_sewa = $_POST['harga_sewa'];

// Memperoleh informasi gambar yang diunggah
$gambar = $_FILES['gambar'];
$gambar_name = $gambar['name'];
$gambar_tmp = $gambar['tmp_name'];

// Memindahkan gambar ke direktori yang diinginkan
$target_dir = "img/"; // Ganti dengan direktori tujuan yang diinginkan
$target_file = $target_dir . basename($gambar_name);
move_uploaded_file($gambar_tmp, $target_file);

$conn = mysqli_connect($host, $user, $password, $db);
$sql = "INSERT INTO lapangan (nama_lapangan, harga_sewa, gambar) VALUES ('$nama_lapangan', $harga_sewa, '$target_file')";
$result = mysqli_query($conn, $sql);

if ($result) {
    $response = array(
        'status' => 'success',
        'message' => 'Data telah ditambahkan ke database.'
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Error: ' . mysqli_error($conn)
    );
}

// Menutup koneksi database
mysqli_close($conn);

// Mengkonversi respon ke format JSON
$response_json = json_encode($response);

// Mengirim respon ke halaman datalapangan.php
header("Location: http://localhost/sewalapanganfutsal/datalapangan.php?response=" . urlencode($response_json));
