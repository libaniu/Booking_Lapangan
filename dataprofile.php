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

// Mendapatkan data name dan username dari tabel users
$sql = "SELECT name, username FROM users";
$result = $conn->query($sql);

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Mendapatkan data name dari tabel users berdasarkan username
    $sql = "SELECT name FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
    } else {
        echo "Tidak ada data yang ditemukan.";
    }
} else {
    echo "Pengguna belum login.";
}

// Menangkap data email dan nomor telepon dari form
$email = "";
$notelp = "";

if (isset($_POST['email'])) {
    $email = $_POST['email'];
}

if (isset($_POST['notelp'])) {
    $notelp = $_POST['notelp'];
}

// Memasukkan email dan nomor telepon ke dalam tabel users
if (!empty($email) && !empty($notelp)) {
    $sql = "UPDATE users SET email='$email', notelp='$notelp' WHERE username='$username'";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diperbarui.";
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Mendapatkan data name dari tabel users berdasarkan username
    $sql = "SELECT name, email, notelp FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $email = $row["email"];
        $notelp = $row["notelp"];
    } else {
        echo "Tidak ada data yang ditemukan.";
    }
} else {
    echo "Pengguna belum login.";
}

// Menutup koneksi database
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
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Profile</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="#">Profil</a>
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
                                    <a href="formsewa.php">Form Penyewaan</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="statussewa.php">Status Penyewaan</a>
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

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Data Profile Penyewa</h3>

                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="userhome.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Profile</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="buttons">
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#inlineForm">
                                            Lengkapi Profile
                                        </button>
                                    </div>
                                    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myModalLabel33">Lengkapi Profile</h4>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                                                        <i data-feather="x"></i>
                                                    </button>
                                                </div>
                                                <form action="#" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <label>NAMA</label>
                                                        <div class="form-group">
                                                            <input type="text" name="nama" placeholder="Nama" class="form-control" value="<?php echo $name; ?>" readonly>
                                                        </div>
                                                        <label>USERNAME</label>
                                                        <div class="form-group">
                                                            <input type="text" name="username" placeholder="Username" class="form-control" value="<?php echo $username; ?>" readonly>
                                                        </div>
                                                        <label>EMAIL</label>
                                                        <div class="form-group">
                                                            <input type="email" name="email" placeholder="Email" class="form-control">
                                                        </div>
                                                        <label>NOMOR TELP/WA</label>
                                                        <div class="form-group">
                                                            <input type="tel" name="notelp" placeholder="Nomor Telepon" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                                            <i class="bx bx-x d-inline d-sm-none"></i>
                                                            <span class="d-inline d-sm-none">Tutup</span>
                                                            <span class="d-none d-sm-inline">Tutup</span>
                                                        </button>
                                                        <button type="submit" name="submit" class="btn btn-primary ml-1">
                                                            <i class="bx bx-check d-inline d-sm-none"></i>
                                                            <span class="d-inline d-sm-none">Lengkapi</span>
                                                            <span class="d-none d-sm-inline">Lengkapi</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <section class="section">
                                        <div class="row" id="table-bordered">
                                            <div class="col-12">
                                                <div class="card">

                                                    <div class="card-content">
                                                        <!-- table bordered -->
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th>Nama:</th>
                                                                        <td><?php echo $name; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Username:</th>
                                                                        <td><?php echo $username; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Email:</th>
                                                                        <td><?php echo $email; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Nomor Telepon:</th>
                                                                        <td><?php echo $notelp; ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    </div>
    <script src="dist/assets/js/bootstrap.js"></script>
    <script src="dist/assets/js/app.js"></script>

    <!-- Need: Apexcharts -->
    <script src="dist/assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="dist/assets/js/pages/dashboard.js"></script>
</body>

</html>