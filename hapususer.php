<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "sewalapangan";

    $data = mysqli_connect($host, $user, $password, $db);

    if ($data === false) {
        die("Kesalahan koneksi");
    }

    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($data, $sql);

    if ($result) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman datauser.php
        header("Location: datauser.php");
        exit;
    } else {
        // Jika terjadi kesalahan, tampilkan pesan kesalahan
        echo "Kesalahan saat menghapus data: " . mysqli_error($data);
    }

    mysqli_close($data);
} else {
    // Jika parameter "id" tidak ada atau tidak valid, tampilkan pesan kesalahan
    echo "ID data tidak valid.";
}
