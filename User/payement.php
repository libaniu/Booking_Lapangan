<?php
// userhome.php

session_name("user_session"); // Gunakan nama sesi untuk pengguna
session_start();

if (!isset($_SESSION["username"])) {
    header("location: ../login.php");
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

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : $username;
    $safe_name = mysqli_real_escape_string($conn, $name);
} else {
    echo "Pengguna belum login.";
    exit;
}

$sqlFormSewa = "SELECT fs.id, fs.order_id, fs.total_bayar, fs.status_booking
FROM formsewa fs
WHERE fs.nama = '$safe_name'
ORDER BY fs.id DESC";
$resultFormSewa = mysqli_query($conn, $sqlFormSewa);

if (!$resultFormSewa) {
    die("Kesalahan query: " . mysqli_error($conn));
}

$first_pending_order_id = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman User</title>

    <link rel="stylesheet" href="../dist/assets/css/main/app.css">
    <link rel="stylesheet" href="../dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.png" type="image/png">

    <style>
        #toggle-dark {
            display: none;
        }
    </style>

    <link rel="stylesheet" href="../dist/assets/css/shared/iconly.css">

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
                                <span>Dasbor</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Profil</span>
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
                                    <a href="formsewa.php">Formulir Pemesanan</a>
                                </li>
                               <li class="submenu-item ">
                                    <a href="statussewa.php">Status Pemesanan</a>
                                </li>
                                <li class="submenu-item active">
                                    <a href="payement.php">Pembayaran</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- Wrapper menu keluar di pojok bawah -->
                <div class="sidebar-menu position-absolute bottom-0 w-100 pb-3">
                    <ul class="menu mb-0">
                        <li class="sidebar-item">
                            <a href="../logout.php" class='sidebar-link text-danger'>
                                <i class="bi bi-box-arrow-left text-danger"></i>
                                <span>Keluar</span>
                            </a>
                        </li>
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
                                    <li class="breadcrumb-item"><a href="userhome.php">Dasbor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card shadow-sm border-0">
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover table-bordered mb-0" style="width: 100%; white-space: nowrap;">
                                    <thead class="table-dark text-white">
                                        <tr class="text-center">
                                            <th>Username</th>
                                            <th>Order ID</th>
                                            <th>Total Pembayaran</th>
                                            <th>Bukti Booking</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($resultFormSewa) > 0) : ?>
                                            <?php while ($formSewa = mysqli_fetch_assoc($resultFormSewa)) : ?>
                                                <?php
                                                // Bersihkan spasi & ubah ke huruf kecil untuk pengecekan yang lebih akurat
                                                $status = strtolower(trim((string)$formSewa['status_booking']));
                                                $is_lunas = in_array($status, ['approved']);

                                                if (!$is_lunas && $first_pending_order_id === null) {
                                                    $first_pending_order_id = $formSewa['order_id'];
                                                }
                                                ?>
                                                <tr class="align-middle text-center">
                                                    <td class="text-start"><?php echo htmlspecialchars($username); ?></td>
                                                    <td><?php echo htmlspecialchars($formSewa['order_id']); ?></td>
                                                    <td class="fw-bold">Rp <?php echo number_format($formSewa['total_bayar'], 0, ',', '.'); ?></td>
                                                    <td>
                                                        <?php if ($is_lunas) : ?>
                                                            <a href="../bookingpdf.php?booking_id=<?php echo $formSewa['id']; ?>" target="_blank" class="btn btn-success btn-sm"><i class="bi bi-download"></i> Unduh Bukti</a>
                                                        <?php else : ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($is_lunas) : ?>
                                                            <span class="badge bg-success">Lunas</span>
                                                        <?php else : ?>
                                                            <span class="badge bg-warning">Pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data pembayaran.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if ($first_pending_order_id !== null) : ?>
                                <div class="d-flex justify-content-end mt-3">
                                    <a href="../midtrans/examples/snap/checkout-process-simple-version.php?order_id=<?php echo urlencode($first_pending_order_id); ?>" class="btn btn-primary shadow-sm" role="button" aria-pressed="true"><i class="bi bi-wallet2"></i> Bayar Sekarang</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
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
            const response = await fetch('../placepayement.php', {
                method: 'post',
                body: data,
            })
        } catch(err) {
            console.log(eer.message);
        }
        window.snap.pay('TRANSACTION_TOKEN_HERE');
    </script>

    <script src="../dist/assets/js/bootstrap.js"></script>
    <script src="../dist/assets/js/app.js"></script>

    <!-- Need: Apexcharts -->
    <script src="../dist/assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="../dist/assets/js/pages/dashboard.js"></script>
</body>

</html>
<?php
// Menutup koneksi database
$conn->close();
?>
