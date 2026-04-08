<?php
// datauser.php
session_name("admin_session"); 
session_start();

if (!isset($_SESSION["username"])) {
    header("location: ../login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$data_conn = mysqli_connect($host, $user, $password, $db);

if ($data_conn === false) {
    die("Kesalahan koneksi");
}

// Logika Pengurutan
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';
$orderBy = "id DESC"; 

if ($sort === 'nama_asc') {
    $orderBy = "name ASC";
} elseif ($sort === 'nama_desc') {
    $orderBy = "name DESC";
} elseif ($sort === 'tipe') {
    $orderBy = "usertype ASC, name ASC";
}

$sql = "SELECT * FROM users ORDER BY $orderBy";
$result = mysqli_query($data_conn, $sql);

if (!$result) {
    die("Kesalahan query: " . mysqli_error($data_conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin - Data Pengguna</title>

    <link rel="stylesheet" href="../dist/assets/css/main/app.css">
    <link rel="stylesheet" href="../dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.png" type="image/png">

    <style>
        #toggle-dark {
            display: none;
        }
        /* Warna teks isi tabel: Abu-abu terang (tidak terlalu putih tajam) */
        .table td, .table th {
            color: #d1d1d1 !important; 
        }
        /* Menjaga header tetap sedikit lebih terang agar kontras */
        .table thead th {
            color: #e9ecef !important;
            background-color: rgba(255, 255, 255, 0.05);
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
                            <a href="adminhome.php">AWK Futsal.</a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark">
                                <label class="form-check-label"></label>
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
                            <a href="adminhome.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dasbor</span>
                            </a>
                        </li>
                        <li class="sidebar-item active">
                            <a href="datauser.php" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Data Pengguna</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="datalapangan.php" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Lapangan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="datasewa.php" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Data Pemesanan</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="databayar.php" class='sidebar-link'>
                                <i class="bi bi-wallet2"></i>
                                <span>Data Pembayaran</span>
                            </a>
                        </li>
                    </ul>
                </div>
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
                            <h3>Data Pengguna</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="adminhome.php">Dasbor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Pengguna</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section mt-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm px-3 shadow-sm dropdown-toggle" type="button" id="sortMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-sort-down"></i> Urutkan
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortMenuButton">
                                    <li><a class="dropdown-item <?= ($sort == 'terbaru') ? 'active' : ''; ?>" href="?sort=terbaru">Terbaru</a></li>
                                    <li><a class="dropdown-item <?= ($sort == 'nama_asc') ? 'active' : ''; ?>" href="?sort=nama_asc">Nama (A-Z)</a></li>
                                    <li><a class="dropdown-item <?= ($sort == 'nama_desc') ? 'active' : ''; ?>" href="?sort=nama_desc">Nama (Z-A)</a></li>
                                    <li><a class="dropdown-item <?= ($sort == 'tipe') ? 'active' : ''; ?>" href="?sort=tipe">Tipe Pengguna</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered mb-0" id="table1" style="width: 100%; white-space: nowrap;">
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Nama Pengguna</th>
                                            <th>Email</th>
                                            <th>Nomor Telepon</th>
                                            <th>Tipe Pengguna</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while ($user_row = mysqli_fetch_assoc($result)) : 
                                        ?>
                                            <tr class="align-middle text-center">
                                                <td><?= $no++; ?></td>
                                                <td class="text-start"><?= htmlspecialchars($user_row['name']); ?></td>
                                                <td class="text-start"><?= htmlspecialchars($user_row['username']); ?></td>
                                                <td class="text-start"><?= htmlspecialchars($user_row['email']); ?></td>
                                                <td><?= htmlspecialchars($user_row['notelp']); ?></td>
                                                <td>
                                                    <span class="badge <?= ($user_row['usertype'] == 'admin') ? 'bg-primary' : 'bg-secondary'; ?>">
                                                        <?= ucfirst($user_row['usertype']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="../hapususer.php?id=<?= $user_row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                        <i class="bi bi-trash-fill"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script src="../dist/assets/js/bootstrap.js"></script>
    <script src="../dist/assets/js/app.js"></script>
</body>
</html>