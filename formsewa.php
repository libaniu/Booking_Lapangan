<?php
// userhome.php

session_name("user_session"); // Gunakan nama sesi untuk pengguna
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}


// Mendapatkan data name dan username dari tabel users (menggunakan contoh koneksi database)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sewalapangan";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi database
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
$sql = "SELECT * FROM lapangan";
$lapangan = mysqli_query($conn, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $id_lapangan = $_POST['lapangan']; // Menggunakan nama_lapangan sebagai variabel
    $tanggal = $_POST['tanggal'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Cek apakah jadwal booking tersedia
    $query = "SELECT id 
    FROM formsewa
    WHERE id_lapangan = '{$id_lapangan}'
    AND tanggal = '{$tanggal}'
    AND ((jam_mulai <= '{$jam_mulai}' AND jam_selesai >= '{$jam_mulai}')
    OR (jam_mulai <= '{$jam_selesai}' AND jam_selesai >= '{$jam_selesai}'));";

    $result = $conn->query($query);
    if ($result->num_rows > 0) { // Jika jadwal jam tersebut ada, maka tidak tersedia
        header("Location: formsewa.php?gagal=1");
        $conn->close();
        die;
    }

    // Menyiapkan pernyataan INSERT SQL dengan kolom nama_lapangan
    $sql = "INSERT INTO formsewa (nama, id_lapangan, tanggal, jam_mulai, jam_selesai) 
    VALUES ('$nama', '$id_lapangan', '$tanggal', '$jam_mulai', '$jam_selesai')";

    if ($conn->query($sql) === TRUE) {
        header("Location: formsewa.php?sukses=1");
        die;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Menutup koneksi
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman User</title>

    <link rel="stylesheet" href="dist/assets/css/main/app.css">
    <link rel="stylesheet" href="dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="dist/assets/images/logo/favicon.png" type="image/png">

    <style>
        #toggle-dark {
            display: none;
        }
    </style>

    <link rel="stylesheet" href="dist/assets/css/shared/iconly.css">
    <link rel="stylesheet" href="dist/assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="dist/assets/extensions/sweetalert2/sweetalert2.min.css">

</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="userhome.php">AWK Futsal.</a>
                        </div>
                        <div class="theme-toggle d-flex gap-2  align-items-center mt-2">

                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input  me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label"></label>
                            </div>

                        </div>
                        <div class="sidebar-toggler  x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item active ">
                            <a href="userhome.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="dataprofile.php" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Profile</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="dataprofile.php">Profil</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Sewa</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="formsewa.php">Form Pemesanan</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="statussewa.php">Status Pemesanan</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-title">Sign-Out</li>

                        <div class="sidebar-item  has-sub"></div>
                        <a href="logout.php" class='sidebar-link'>
                            <i class="bi bi-hexagon-fill"></i>
                            <span>Logout</span>
                        </a>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="col-md-10 offset-md-1 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Pemesanan Lapangan AWK Futsal</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <?php if (isset($_SESSION['alert'])) : ?>
                                <div class="alert alert-danger"><?php echo $_SESSION['alert'] ?></div>
                                <?php unset($_SESSION['alert']) ?>
                            <?php endif; ?>
                            <form action="formsewa.php" method="POST" class="form form-vertical">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <input type="text" id="nama" class="form-control" name="nama" placeholder="Sewa atas nama" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="lapangan">Lapangan</label>
                                                <select id="lapangan" class="form-control" name="lapangan" required>
                                                    <option selected disabled>Pilih Lapangan</option>
                                                    <?php foreach ($lapangan as $lpg) : ?>
                                                        <option value="<?= $lpg['id_lapangan'] ?>"><?= $lpg['nama_lapangan'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="tanggal">Tanggal</label>
                                                <input type="date" id="tanggal" class="form-control" name="tanggal" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="jam_mulai">Jam Mulai</label>
                                                <input type="time" id="jam_mulai" class="form-control" name="jam_mulai" required>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="jam_selesai">Jam Selesai</label>
                                                <input type="time" id="jam_selesai" class="form-control" name="jam_selesai" required>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="dist/assets/js/bootstrap.js"></script>
    <script src="dist/assets/js/app.js"></script>

    <script src="dist/assets/extensions/toastify-js/src/toastify.js"></script>
    <script src="dist/assets/extensions/sweetalert2/sweetalert2.min.js"></script>

    <script>
        document.addEventListener('readystatechange', function() {
            <?php if (isset($_GET['sukses']) && $_GET['sukses'] == 1) : ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Data sewa berhasil ditambahkan'
                })
            <?php endif ?>

            <?php if (isset($_GET['gagal']) && $_GET['gagal'] == 1) : ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Jam sewa yang dipilih sudah penuh'
                })
            <?php endif ?>
        })
    </script>

</body>

</html>