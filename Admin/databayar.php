<?php
// adminhome.php
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
    die("Connection error");
}

$sqlFormSewa = "SELECT * FROM formsewa JOIN lapangan ON lapangan.id_lapangan = formsewa.id_lapangan";
$resultFormSewa = mysqli_query($conn, $sqlFormSewa);
if (!$resultFormSewa) {
    die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>

    <link rel="stylesheet" href="../dist/assets/css/main/app.css">
    <link rel="stylesheet" href="../dist/assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="../dist/assets/images/logo/favicon.png" type="image/png">

    <style>
        #toggle-dark {
            display: none;
        }
        /* Menyamakan warna teks di dalam tabel agar kontras dan konsisten */
        .table td, .table th {
            color: #d1d1d1 !important; /* Memaksa warna putih/terang */
        }
        /* Jika ingin ID Pesanan tetap sedikit berbeda namun tetap jelas, 
           hapus baris di bawah ini jika ingin putih polos semua */
        .text-id {
            color: #e0e0e0 !important;
        }
    </style>

    <link rel="stylesheet" href="../dist/assets/css/shared/iconly.css">
    <link rel="stylesheet" href="../dist/assets/extensions/sweetalert2/sweetalert2.min.css">
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
                        <li class="sidebar-item ">
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
                        <li class="sidebar-item">
                            <a href="datasewa.php" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Data Pemesanan</span>
                            </a>
                        </li>
                        <li class="sidebar-item active">
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
                            <h3>Status Pembayaran</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="adminhome.php">Dasbor</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data Pembayaran</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <section class="section mt-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover table-bordered mb-0" id="table1" style="width: 100%; white-space: nowrap;">
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th>ID Pesanan</th>
                                            <th>Nama</th>
                                            <th>Total</th>
                                            <th>Status Pembayaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($formSewa = mysqli_fetch_assoc($resultFormSewa)) : ?>
                                            <tr class="align-middle text-center">
                                                <td class="text-id"><?= htmlspecialchars($formSewa['order_id']); ?></td>
                                                <td class="text-start"><?= htmlspecialchars($formSewa['nama']); ?></td>
                                                <td class="fw-bold text-success">Rp <?= number_format($formSewa['total_bayar'], 0, ',', '.'); ?></td>
                                                <td>
                                                    <a href="https://dashboard.sandbox.midtrans.com/beta/transactions" class="btn btn-sm btn-primary" target="_blank">
                                                        <i class="bi bi-search"></i> Cek Pembayaran
                                                    </a>
                                                </td>
                                                <td>
                                                    <form method="post" action="../hapusdatasewa.php" class="m-0">
                                                        <input type="hidden" name="id" value="<?= $formSewa['id']; ?>">
                                                        <input type="hidden" name="delete" value="true">
                                                        <button type="button" class="btn btn-sm btn-danger btn-delete-bayar">
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
    <script src="../dist/assets/extensions/sweetalert2/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete-bayar');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    
                    Swal.fire({
                        title: 'Hapus Riwayat Pembayaran?',
                        text: "Apakah Anda yakin ingin menghapus riwayat pembayaran ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-danger me-3',
                            cancelButton: 'btn btn-secondary'
                        },
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
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