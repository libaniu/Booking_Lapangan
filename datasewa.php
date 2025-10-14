<?php
// datasewa.php (Versi Final dengan AJAX)

session_name("admin_session");
session_start();

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit;
}

// Koneksi Database
$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";
$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) { die("Connection error"); }

// Query untuk mengambil data booking
$sqlFormSewa = "SELECT fs.id, fs.nama, fs.tanggal, fs.jam_mulai, fs.jam_selesai, fs.status_booking, l.nama_lapangan, l.harga_sewa
                FROM formsewa fs 
                JOIN lapangan l ON l.id_lapangan = fs.id_lapangan 
                ORDER BY fs.id DESC";
$resultFormSewa = mysqli_query($data, $sqlFormSewa);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin - Data Booking</title>

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
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data user</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="datauser.php">User</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data Lapangan</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="datalapangan.php">Lapangan</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub active">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>Data Booking</span>
                            </a>
                            <ul class="submenu active">
                                <li class="submenu-item">
                                    <a href="datasewa.php">Booking</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Data pembayaran</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item">
                                    <a href="databayar.php">Status Pembayaran</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-title">Sign-Out</li>
                        <li class="sidebar-item">
                            <a href="logout.php" class='sidebar-link'>
                                <i class="bi bi-hexagon-fill"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none"><i class="bi bi-justify fs-3"></i></a>
            </header>
            <div class="page-heading">
                <h3>Data Semua Booking</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="adminhome.php">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Booking</li>
                    </ol>
                </nav>
                <section class="section">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pemesan</th>
                                        <th>Lapangan</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php while ($formSewa = mysqli_fetch_assoc($resultFormSewa)) : ?>
                                        <?php
                                            $harga = (float) $formSewa['harga_sewa'];
                                            $lamaSewa = round((strtotime($formSewa['jam_selesai']) - strtotime($formSewa['jam_mulai'])) / 3600, 2);
                                            $total = $harga * $lamaSewa;
                                        ?>
                                        <tr id="booking-row-<?php echo $formSewa['id']; ?>">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($formSewa['nama']); ?></td>
                                            <td><?php echo htmlspecialchars($formSewa['nama_lapangan']); ?></td>
                                            <td><?php echo date('d M Y', strtotime($formSewa['tanggal'])); ?></td>
                                            <td><?php echo date('H:i', strtotime($formSewa['jam_mulai'])) . ' - ' . date('H:i', strtotime($formSewa['jam_selesai'])); ?></td>
                                            <td>Rp <?php echo number_format($total, 0, ',', '.'); ?></td>
                                            <td>
                                                <?php
                                                $status = $formSewa['status_booking'];
                                                $badge_class = 'bg-secondary';
                                                if ($status == 'Approved') $badge_class = 'bg-success';
                                                if ($status == 'Rejected') $badge_class = 'bg-danger';
                                                if ($status == 'Pending') $badge_class = 'bg-warning';
                                                ?>
                                                <span id="status-badge-<?php echo $formSewa['id']; ?>" class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($status); ?></span>
                                            </td>
                                            <td id="action-cell-<?php echo $formSewa['id']; ?>">
                                                <?php if ($formSewa['status_booking'] == 'Pending') : ?>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-success btn-sm" onclick="updateBookingStatus(<?php echo $formSewa['id']; ?>, 'Approved')">Approve</button>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="updateBookingStatus(<?php echo $formSewa['id']; ?>, 'Rejected')">Reject</button>
                                                    </div>
                                                <?php else: ?>
                                                    <span>-</span>
                                                <?php endif; ?>
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
    </div>
    
    <script src="dist/assets/js/bootstrap.js"></script>
    <script src="dist/assets/js/app.js"></script>
    <script src="dist/assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="dist/assets/js/pages/dashboard.js"></script>

    <script>
    function updateBookingStatus(bookingId, newStatus) {
        // Menggunakan Fetch API untuk mengirim data ke server
        fetch('updatestatusbooking.php', {
            method: 'POST',
            headers: {
                // Memberitahu server bahwa kita mengirim data dalam format form
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // Data yang dikirim dalam body request
            body: `id_booking=${bookingId}&new_status=${newStatus}`
        })
        .then(response => response.json()) // Mengubah respons dari server menjadi format JSON
        .then(data => {
            // 'data' adalah hasil dari 'updatestatusbooking.php' yang sudah di-json_encode
            if (data.success) {
                // Tampilkan pop-up notifikasi
                alert(`Booking berhasil di-${newStatus}!`);

                // --- Memperbarui Tampilan Tanpa Reload ---

                // 1. Update Badge Status
                const statusBadge = document.getElementById(`status-badge-${bookingId}`);
                statusBadge.textContent = newStatus;
                
                // Hapus class warna lama dan tambahkan yang baru
                statusBadge.classList.remove('bg-warning', 'bg-danger', 'bg-success');
                if (newStatus === 'Approved') {
                    statusBadge.classList.add('bg-success');
                } else if (newStatus === 'Rejected') {
                    statusBadge.classList.add('bg-danger');
                }

                // 2. Hapus Tombol Aksi
                const actionCell = document.getElementById(`action-cell-${bookingId}`);
                actionCell.innerHTML = '<span>-</span>'; // Ganti tombol dengan tanda strip

            } else {
                // Jika ada error dari server (misal, query gagal)
                alert('Gagal memperbarui status. Pesan: ' + data.message);
            }
        })
        .catch(error => {
            // Jika terjadi error koneksi atau masalah jaringan
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghubungi server.');
        });
    }
    </script>
    </body>
</html>