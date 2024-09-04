<?php
// userhome.php
session_name("user_session");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "sewalapangan";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$username = $_SESSION['username'];

$sql = "SELECT name FROM users WHERE username = '$username'";
$result = $conn->query($sql);

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

$sqlFormSewa = "SELECT fs.id, fs.nama, fs.tanggal, fs.jam_mulai, fs.jam_selesai, lapangan.nama_lapangan
FROM formsewa fs
JOIN lapangan ON lapangan.id_lapangan = fs.id_lapangan";
$resultFormSewa = mysqli_query($conn, $sqlFormSewa);

if (!$resultFormSewa) {
    die("Kesalahan query: " . mysqli_error($conn));
}
?>

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>

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
                            <a href="adminhome.php">AWK Futsal.</a>
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
                            <a href="adminhome.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data user</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="datauser.php">User</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Lapangan</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="datalapangan.php">Lapangan</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Data Booking</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="datasewa.php">Booking</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data pembayaran</span>
                            </a>
                            <ul class="submenu ">
                                <li class="submenu-item ">
                                    <a href="databayar.php">Status Pembayaran</a>
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
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Data Pembayaran</h3>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="adminhome.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">DataBayar</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped" id="table1" style="max-width: 800px;">
                                    <thead>
                                        <tr>
                                            <th>Order ID </th>
                                            <th>Nama </th>
                                            <th>Total </th>
                                            <th>Status Pembayaran </th>
                                            <th>Aksi </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $host = "localhost";
                                        $user = "root";
                                        $password = "";
                                        $db = "sewalapangan";

                                        $data = mysqli_connect($host, $user, $password, $db);

                                        if ($data === false) {
                                            die("Connection error");
                                        }

                                        $no = 1;
                                        $sqlFormSewa = "SELECT * FROM formsewa JOIN lapangan ON lapangan.id_lapangan = formsewa.id_lapangan";
                                        $resultFormSewa = mysqli_query($data, $sqlFormSewa);

                                        if (!$resultFormSewa) {
                                            die("Query error: " . mysqli_error($data));
                                        }
                                        ?>

                                        <?php while ($formSewa = mysqli_fetch_assoc($resultFormSewa)) : ?>
                                            <?php
                                            $nama = $formSewa['nama'];
                                            $namaLapangan = $formSewa['nama_lapangan'];
                                            $sqlHarga = "SELECT harga_sewa FROM lapangan WHERE nama_lapangan = '$namaLapangan'";
                                            $resultHarga = mysqli_query($data, $sqlHarga);

                                            if (!$resultHarga) {
                                                die("Query error: " . mysqli_error($data));
                                            }

                                            if (mysqli_num_rows($resultHarga) > 0) {
                                                $hargaRow = mysqli_fetch_assoc($resultHarga);
                                                $harga = (float) $hargaRow['harga_sewa'];
                                            } else {
                                                $harga = 0;
                                            }
                                            $tanggal = $formSewa['tanggal'];
                                            $jamMulai = $formSewa['jam_mulai'];
                                            $jamSelesai = $formSewa['jam_selesai'];
                                            $lamaSewa = round((strtotime($jamSelesai) - strtotime($jamMulai)) / 3600, 2);

                                            $total = $harga * $lamaSewa;
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($order_id); ?></td>
                                                <td><?php echo $nama; ?></td>
                                                <td><?php echo $total_bayar; ?></td>
                                                <td><a href="https://dashboard.sandbox.midtrans.com/beta/transactions" class="btn btn-primary">Cek Pembayaran</a></td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex gap-4">
                                                            <form method="post" action="hapusdatasewa.php">
                                                                <input type="hidden" name="id" value="<?php echo $formSewa['id']; ?>">
                                                                <button type="submit" class="btn btn-danger mt-3" name="delete">Hapus</button>
                                                            </form>
                                                        </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <script src="dist/assets/js/bootstrap.js"></script>
            <script src="dist/assets/js/app.js"></script>

            <!-- Need: Apexcharts -->
            <script src="dist/assets/extensions/apexcharts/apexcharts.min.js"></script>
            <script src="dist/assets/js/pages/dashboard.js"></script>

</body>

</html>