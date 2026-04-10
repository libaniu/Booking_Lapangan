<?php
// userhome.php

session_name("user_session"); // Gunakan nama sesi untuk pengguna
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
    die("Kesalahan koneksi: " . mysqli_connect_error());
}

// Ambil nama pengguna dari sesi
$name = isset($_SESSION['name']) ? $_SESSION['name'] : $_SESSION['username']; 
$safe_name = mysqli_real_escape_string($data, $name);

// Optimasi: Ambil harga_sewa langsung menggunakan JOIN agar tidak perlu query berulang di dalam looping
$sqlFormSewa = "
    SELECT fs.id, fs.nama, fs.tanggal, fs.jam_mulai, fs.jam_selesai, fs.status_booking, 
           lapangan.nama_lapangan, lapangan.harga_sewa 
    FROM formsewa fs
    JOIN lapangan ON lapangan.id_lapangan = fs.id_lapangan
    WHERE fs.nama = '$safe_name'
";

$resultFormSewa = mysqli_query($data, $sqlFormSewa);

if (!$resultFormSewa) {
    die("Kesalahan query: " . mysqli_error($data));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pemesanan - AWK Futsal</title>

    <link rel="stylesheet" href="../dist/assets/css/main/app.css">
    <link rel="stylesheet" href="../dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="../dist/assets/css/shared/iconly.css">
    <link rel="stylesheet" href="../dist/assets/extensions/sweetalert2/sweetalert2.min.css">

    <style>
        #toggle-dark {
            display: none;
        }
        #table1 tbody td {
            color: #D1D1D1 !important;
        }
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
                                <li class="submenu-item active">
                                    <a href="statussewa.php">Status Pemesanan</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="payement.php">Pembayaran</a>
                                </li>
                            </ul>
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
                            <h3>Status Pemesanan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="userhome.php">Dasbor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Status Pemesanan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                
                <section class="section">
                    <div class="card shadow-sm border-0">
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover table-bordered mb-0" id="table1" style="width: 100%; font-size: 0.9rem;">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Nama Lapangan</th>
                                            <th>Harga</th>
                                            <th>Tanggal</th>
                                            <th>Jam Mulai</th>
                                            <th>Jam Selesai</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if (mysqli_num_rows($resultFormSewa) > 0) {
                                            $no = 1; 
                                            while ($formSewa = mysqli_fetch_assoc($resultFormSewa)) : 
                                                $nama = $formSewa['nama'];
                                                $namaLapangan = $formSewa['nama_lapangan'];
                                                $harga = (float) $formSewa['harga_sewa']; // Diambil langsung dari JOIN
                                                $tanggal = $formSewa['tanggal'];
                                                $jamMulai = $formSewa['jam_mulai'];
                                                $jamSelesai = $formSewa['jam_selesai'];
                                                
                                                // Kalkulasi lama sewa dan total
                                                $lamaSewa = round((strtotime($jamSelesai) - strtotime($jamMulai)) / 3600, 2);
                                                $total = $harga * $lamaSewa;
                                        ?>
                                    <tr class="align-middle text-center">
                                            <td><?php echo $no++; ?></td>
                                            <td class="text-start"><?php echo htmlspecialchars($namaLapangan); ?></td>
                                            <td>Rp <?php echo number_format($harga, 0, ',', '.'); ?></td>
                                            <td><span class="badge bg-light-primary"><?php echo date('d-m-Y', strtotime($tanggal)); ?></span></td>
                                            <td><?php echo date('H:i', strtotime($jamMulai)); ?></td>
                                            <td><?php echo date('H:i', strtotime($jamSelesai)); ?></td>
                                            <td class="fw-bold text-success">Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="payement.php?id=<?php echo $formSewa['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-wallet2"></i> Bayar
                                                    </a>
                                                    <form method="post" action="../hapusdatasewauser.php" class="m-0">
                                                        <input type="hidden" name="id" value="<?php echo $formSewa['id']; ?>">
                                                        <input type="hidden" name="delete" value="true">
                                                        <button type="button" class="btn btn-sm btn-danger btn-batal">
                                                            <i class="bi bi-x-circle"></i> Batal
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                            endwhile; 
                                        } else {
                                        ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-3">Belum ada data pemesanan.</td>
                                        </tr>
                                        <?php } ?>
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
    <script src="../dist/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnBatal = document.querySelectorAll('.btn-batal');
            btnBatal.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    
                    Swal.fire({
                        title: 'Batalkan Pesanan?',
                        text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-danger me-3',
                            cancelButton: 'btn btn-secondary'
                        },
                        confirmButtonText: 'Ya, Batalkan!',
                        cancelButtonText: 'Tutup'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>