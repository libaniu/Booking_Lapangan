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

    // Mendapatkan total pembayaran dari tabel formsewa berdasarkan username
    $sql = "SELECT order_id, total_bayar FROM formsewa";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_id = $row["order_id"];
        $total_bayar = $row["total_bayar"];
    } else {
        $order_id = "Tidak ada";
        $total_bayar = 0;
    }
} else {
    echo "Pengguna belum login.";
}

$sqlFormSewa = "SELECT fs.id, fs.nama, fs.tanggal, fs.jam_mulai, fs.jam_selesai, lapangan.nama_lapangan
FROM formsewa fs
JOIN lapangan ON lapangan.id_lapangan = fs.id_lapangan";
$resultFormSewa = mysqli_query($conn, $sqlFormSewa);

if (!$resultFormSewa) {
    die("Kesalahan query: " . mysqli_error($conn));
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
                        <li class="sidebar-item active">
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
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Sewa</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
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
                                    <li class="breadcrumb-item"><a href="adminhome.php">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table">
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
                        <td><?php echo htmlspecialchars($username); ?></td>
                        <td><?php echo htmlspecialchars($order_id); ?></td>
                        <td><?php echo htmlspecialchars($total_bayar); ?></td>
                        <?php
                        $formSewa = mysqli_fetch_assoc($resultFormSewa);
                        ?>
                        <td><a href="bookingpdf.php?booking_id=<?php echo $formSewa['id']; ?>" target="_blank">Unduh Bukti Booking</a></td>
                    </tr>
                </tbody>
            </table>
            <a href="midtrans/examples/snap/checkout-process-simple-version.php?order_id=<?php echo urlencode($order_id); ?>" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Bayar</a>
        </div>
    </div>
   
   <script>
        // Function to handle delete button click
        function handleDelete(event) {
            const row = event.target.closest('tr'); // Find the closest row to the delete button
            const bookingId = event.target.dataset.id; // Get the booking ID from the data-id attribute
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                row.remove(); // Remove the row from the table
                // Perform additional steps here to delete data from the server if required
                // You can use AJAX to send a request to the server to delete the data from the database.
            }
        }

        // Add click event listeners to all delete buttons
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach((button) => {
            button.addEventListener('click', handleDelete);
        });

        try {
            const response = await fetch('placepayement.php', {
                method: 'post',
                body: data,
            })
        } catch(err) {
            console.log(eer.message);
        }
        window.snap.pay('TRANSACTION_TOKEN_HERE');
    </script>

    <script src="dist/assets/js/bootstrap.js"></script>
    <script src="dist/assets/js/app.js"></script>

    <!-- Need: Apexcharts -->
    <script src="dist/assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="dist/assets/js/pages/dashboard.js"></script>
</body>

</html>
<?php
// Menutup koneksi database
$conn->close();
?>
