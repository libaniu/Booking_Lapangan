<?php
// payement.php

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

// Menggunakan koneksi yang konsisten (object-oriented)
$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Kesalahan koneksi: " . $conn->connect_error);
}

// Inisialisasi variabel untuk menghindari error
$name = '';
$username_session = $_SESSION['username'];
$booking_id = null;
$order_id = 'Tidak ada booking';
$total_bayar = 0;
$status_booking = '';

// 1. Dapatkan 'name' dari tabel 'users' berdasarkan username sesi
$stmt_user = $conn->prepare("SELECT name FROM users WHERE username = ?");
$stmt_user->bind_param("s", $username_session);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_row = $result_user->fetch_assoc();
    $name = $user_row['name'];
}
$stmt_user->close();


// 2. Jika 'name' ditemukan, dapatkan data booking TERBARU dari 'formsewa'
if ($name) {
    // Mengambil booking terbaru dari user yang sedang login
    $stmt_sewa = $conn->prepare("SELECT id, order_id, total_bayar, status_booking FROM formsewa WHERE nama = ? ORDER BY id DESC LIMIT 1");
    $stmt_sewa->bind_param("s", $name);
    $stmt_sewa->execute();
    $result_sewa = $stmt_sewa->get_result();

    if ($result_sewa->num_rows > 0) {
        $sewa_row = $result_sewa->fetch_assoc();
        $booking_id = $sewa_row['id'];
        $order_id = $sewa_row['order_id'];
        $total_bayar = $sewa_row['total_bayar'];
        $status_booking = $sewa_row['status_booking'];
    }
    $stmt_sewa->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembayaran</title>
    <link rel="stylesheet" href="dist/assets/css/main/app.css">
    <link rel="stylesheet" href="dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="dist/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="dist/assets/css/shared/iconly.css">
    <style>
        #toggle-dark { display: none; }
    </style>
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
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label" for="toggle-dark"></label>
                            </div>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                         <li class="sidebar-title">Menu</li>
                         <li class="sidebar-item">
                            <a href="userhome.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="dataprofile.php" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Profile</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="dataprofile.php">Profil</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item active has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Sewa</span>
                            </a>
                            <ul class="submenu active">
                                <li class="submenu-item">
                                    <a href="formsewa.php">Form Pemesanan</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="statussewa.php">Status Pemesanan</a>
                                </li>
                                <li class="submenu-item active">
                                    <a href="payement.php">Pembayaran</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-title">Sign-Out</li>
                        <div class="sidebar-item has-sub"></div>
                        <a href="logout.php" class='sidebar-link'>
                            <i class="bi bi-hexagon-fill"></i>
                            <span>Logout</span>
                        </a>
                    </ul>
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
                            <h3>Form Pembayaran</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="userhome.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Total Pembayaran</th>
                                    <th scope="col">Bukti Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlspecialchars($username_session); ?></td>
                                    <td><?php echo htmlspecialchars($order_id); ?></td>
                                    <td><?php echo "Rp " . number_format($total_bayar, 0, ',', '.'); ?></td>
                                    <td>
                                        <?php
                                        // --- KONDISI UNTUK MENAMPILKAN LINK UNDUH ---
                                        if ($status_booking == 'Approved') {
                                            // Jika status 'Approved', tampilkan link
                                            echo '<a href="bookingpdf.php?booking_id=' . $booking_id . '" target="_blank" class="btn btn-success">Unduh Bukti Booking</a>';
                                        } else {
                                            // Jika status bukan 'Approved', tampilkan pesan
                                            echo '<span class="badge bg-info">Menunggu Persetujuan Admin</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <?php if ($booking_id && $status_booking != 'Approved'): ?>
                            <a href="midtrans/examples/snap/checkout-process-simple-version.php?order_id=<?php echo urlencode($order_id); ?>" class="btn btn-primary btn-lg active mt-3" role="button" aria-pressed="true">Bayar Sekarang</a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
    
    <script src="dist/assets/js/bootstrap.js"></script>
    <script src="dist/assets/js/app.js"></script>
</body>
</html>
<?php
// Menutup koneksi database
$conn->close();
?>