<?php
// userhome.php

session_name("user_session"); // Gunakan nama sesi untuk pengguna
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}
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
                                <li class="submenu-item ">
                                    <a href="payement.php">Pembayaran</a>
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
                <h3>Selamat Datang, <?php echo $_SESSION["username"] ?> </h3>
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