<?php
// datasewa.php
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

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error");
}

// Logika Pengurutan
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';
$orderBy = "formsewa.id DESC"; // Default

if ($sort === 'tanggal_asc') {
    $orderBy = "formsewa.tanggal ASC, formsewa.jam_mulai ASC";
} elseif ($sort === 'tanggal_desc') {
    $orderBy = "formsewa.tanggal DESC, formsewa.jam_mulai DESC";
} elseif ($sort === 'nama_asc') {
    $orderBy = "formsewa.nama ASC";
}

$sqlFormSewa = "SELECT formsewa.*, lapangan.nama_lapangan, lapangan.harga_sewa 
                FROM formsewa 
                JOIN lapangan ON lapangan.id_lapangan = formsewa.id_lapangan 
                ORDER BY $orderBy";
$resultFormSewa = mysqli_query($data, $sqlFormSewa);

if (!$resultFormSewa) {
    die("Query error: " . mysqli_error($data));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin - Data Pemesanan</title>

    <link rel="stylesheet" href="../dist/assets/css/main/app.css">
    <link rel="stylesheet" href="../dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.png" type="image/png">

    <style>
        #toggle-dark {
            display: none;
        }
        /* Memaksa warna teks isi tabel agar terang dan jelas */
        .table td, .table th {
            color: #d1d1d1 !important;
        }
        /* Memberikan sedikit variasi warna untuk nomor agar tidak monoton */
        .text-muted-light {
            color: #d1d1d1 !important;
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
                        <li class="sidebar-item">
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
                        <li class="sidebar-item active">
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
                            <h3>Data Pemesanan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="adminhome.php">Dasbor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Pemesanan</li>
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
                                    <li><a class="dropdown-item <?= ($sort == 'tanggal_asc') ? 'active' : ''; ?>" href="?sort=tanggal_asc">Tanggal Sewa (Terdekat)</a></li>
                                    <li><a class="dropdown-item <?= ($sort == 'tanggal_desc') ? 'active' : ''; ?>" href="?sort=tanggal_desc">Tanggal Sewa (Terjauh)</a></li>
                                    <li><a class="dropdown-item <?= ($sort == 'nama_asc') ? 'active' : ''; ?>" href="?sort=nama_asc">Nama Pemesan (A-Z)</a></li>
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
                                            <th>Lapangan</th>
                                            <th>Tanggal</th>
                                            <th>Jam Mulai</th>
                                            <th>Jam Selesai</th>
                                            <th>Lama Sewa</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while ($formSewa = mysqli_fetch_assoc($resultFormSewa)) : 
                                            $harga = (float) $formSewa['harga_sewa'];
                                            $jamMulai = $formSewa['jam_mulai'];
                                            $jamSelesai = $formSewa['jam_selesai'];
                                            $lamaSewa = round((strtotime($jamSelesai) - strtotime($jamMulai)) / 3600, 2);
                                            $total = $harga * $lamaSewa;
                                        ?>
                                            <tr class="align-middle text-center">
                                                <td class="text-muted-light"><?= $no++; ?></td>
                                                <td class="text-start"><?= htmlspecialchars($formSewa['nama']); ?></td>
                                                <td class="text-start"><?= htmlspecialchars($formSewa['nama_lapangan']); ?></td>
                                                <td><span class="badge bg-light-primary"><?= date('d-m-Y', strtotime($formSewa['tanggal'])); ?></span></td>
                                                <td><?= date('H:i', strtotime($jamMulai)); ?></td>
                                                <td><?= date('H:i', strtotime($jamSelesai)); ?></td>
                                                <td><?= $lamaSewa; ?> jam</td>
                                                <td class="fw-bold text-success">Rp <?= number_format($total, 0, ',', '.'); ?></td>
                                                <td>
                                                    <form method="post" action="../hapusdatasewa.php" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $formSewa['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger" name="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">
                                                            <i class="bi bi-trash-fill"></i> Hapus
                                                        </button>
                                                    </form>
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