<?php
// datalapangan.php
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

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

$sql = "SELECT * FROM lapangan";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin - Data Lapangan</title>

    <link rel="stylesheet" href="../dist/assets/css/main/app.css">
    <link rel="stylesheet" href="../dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.png" type="image/png">

    <style>
        #toggle-dark {
            display: none;
        }
        /* Menyamakan warna teks tabel agar terang di dark mode */
        .table td, .table th {
            color: #d1d1d1 !important;
        }
        .btn-edit {
            background-color: #008000;
            color: white;
        }
        /* Memastikan teks label di modal terlihat jelas */
        .modal-body label {
            color: #333; /* Default modal biasanya background putih */
            font-weight: bold;
            margin-top: 10px;
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
                        <li class="sidebar-item active">
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
                            <h3>Data Lapangan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="adminhome.php">Dasbor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Lapangan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section mt-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom">
                            <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#inlineForm">
                                <i class="bi bi-plus-circle"></i> Tambah Lapangan
                            </button>
                        </div>

                        <div class="card-body mt-3">
                            <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel33">Tambah Lapangan</h4>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                                                <i class="bi bi-x fs-3"></i>
                                            </button>
                                        </div>
                                        <form action="../lapangan.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <label>Nama Lapangan:</label>
                                                <div class="form-group">
                                                    <input type="text" name="nama_lapangan" placeholder="Contoh: Lapangan Matras 1" class="form-control" required>
                                                </div>
                                                <label>Harga Sewa (Rp):</label>
                                                <div class="form-group">
                                                    <input type="number" name="harga_sewa" placeholder="Contoh: 100000" class="form-control" min="0" required>
                                                </div>
                                                <label>Unggah Gambar:</label>
                                                <div class="form-group mt-2">
                                                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" name="submit" class="btn btn-primary ml-1">Tambahkan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered mb-0" id="table1" style="width: 100%; white-space: nowrap;">
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Lapangan</th>
                                            <th>Harga Sewa</th>
                                            <th>Gambar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $nomor = 1;
                                        while ($row = mysqli_fetch_assoc($result)) :
                                        ?>
                                            <tr class="align-middle text-center">
                                                <td><?= $nomor++; ?></td>
                                                <td class="text-start"><?= htmlspecialchars($row['nama_lapangan']); ?></td>
                                                <td class="fw-bold text-success">Rp <?= number_format($row['harga_sewa'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <img src="../<?= $row['gambar']; ?>" class="rounded border shadow-sm" style="width: 100px; height: 60px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <a href="../hapuslapangan.php?id=<?= $row['id_lapangan'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?');">
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