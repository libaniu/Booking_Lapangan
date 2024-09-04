<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";
$conn = mysqli_connect($host, $user, $password, $db);
$dataJadwal = [];
$tgl = date('Y-m-d');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tgl = $_POST['tgl'];
}

$querylpg = mysqli_query($conn, "SELECT id_lapangan, nama_lapangan FROM lapangan");
$lpg = mysqli_fetch_all($querylpg, MYSQLI_ASSOC);

$queryjadwal = mysqli_query($conn, "SELECT id_lapangan, tanggal, jam_mulai, jam_selesai FROM formsewa WHERE tanggal = '$tgl'");
$jdwl = mysqli_fetch_all($queryjadwal, MYSQLI_ASSOC);

$dataJadwal = array_map(function ($jadwal) use ($jdwl) {
    $data = [];
    foreach ($jdwl as $value) {
        if ($value['id_lapangan'] === $jadwal['id_lapangan']) {
            $data[] = $value;
        }
        $jadwal['jadwal_booking'] = $data;
    }
    return $jadwal;
}, $lpg);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWK FUTSAL</title>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;700&display=swap" rel="stylesheet">

    <!-- feather icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- my style -->
    <link rel="stylesheet" href="css/style.css">

    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="SB-Mid-client-Xj2FgW1fBxDt0jOh"></script>

</head>

<body>
    <!-- Navbar start -->
    <nav class="navbar">
        <a href="#" class="navbar-logo"><span>AWK</span> Futsal.</a>

        <div class="navbar-nav">
            <a href="#home">Home</a>
            <a href="#lapangan">Lapangan</a>
            <a href="#jadwal">Jadwal</a>
            <a href="#lokasi">Lokasi</a>
        </div>

        <div class="navbar-extra">
            <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>
    <!-- Navbar end -->

    <!-- Hero section start -->
    <section class="hero" id="home">
        <main class="content">
            <h1>BOOKING LAPANGAN AWK FUTSAL</h1>
            <p>Login terlebih dahulu untuk melakukan booking</p>
            <a href="login.php" class="login">Login</a>
            <a href="register.php" class="register">Register</a>
        </main>
    </section>
    <!-- Hero section end -->

    <!-- about section start -->
    <section id="lapangan" class="lapangan">
        <h2><span>Lapangan</span> Kami</h2>

        <div class="row">
            <?php
            $sql = "SELECT * FROM lapangan";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                $gambar = $row['gambar'];
                $nama_lapangan = $row['nama_lapangan'];
                $harga_sewa = $row['harga_sewa'];

                echo '<div class="lapangan-card">';
                echo '<img src="' . $gambar . '" alt="' . $nama_lapangan . '" class="menu-card-img">';
                echo '<h3 class="lapangan-card-title">' . $nama_lapangan . '</h3>';
                echo '<p class="lapangan-card-price">Harga Rp.' . $harga_sewa . '</p>';
                echo '</div>';
            }

            mysqli_close($conn);
            ?>
        </div>
    </section>
    <!-- about section end -->

    <!-- jadwal section  start-->
    <section id="jadwal" class="jadwal">
    <h2><span>Jadwal</span> Kami</h2>

    <h4 style="text-align:center">Pilih Tanggal</h4>
    <form action="index.php#jadwal" method="POST" class="form form-vertical">
        <div class="form-group row">
            <input type="date" name="tgl" value="<?php echo isset($tgl) ? $tgl : ''; ?>" class="form-control col-3 col" style="margin: 0.5cm 0 1cm;">
            <button type="submit" name="submit" class="btn btn-primary form-control col">Cari</button>
        </div>
    </form>
    <?php foreach ($dataJadwal as $val) : ?>
        <div class="row">
            <label class="col-3"><?php echo $val['nama_lapangan']; ?></label>
            <div class="progress-bar-root-container col">
                <div class="label-start">09:00</div>
                <div class="progress-bar-container">
                    <div class="progress-bar" data-start="09:00" data-end="22:00">
                        <?php if (!empty($val['jadwal_booking'])) : ?>
                            <?php foreach ($val['jadwal_booking'] as $waktu) : ?>
                                <div class="progress" data-start="<?php echo date('H:i', strtotime($waktu['jam_mulai'])); ?>" data-end="<?php echo date('H:i', strtotime($waktu['jam_selesai'])); ?>">
                                    <span class="progress-tooltip"><?php echo date('H:i', strtotime($waktu['jam_mulai'])) . ' - ' . date('H:i', strtotime($waktu['jam_selesai'])); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="label-end">22:00</div>
            </div>
        </div>
    <?php endforeach; ?>
    <label class="label-1">*Arahkan kursor untuk melihat jam booking</label>
    </div>
    <div class="box-container mt1-5">
        <div class="small-box" id="my-progress-bar"></div>
        <label class="mb1-5">Sudah Dibooking</label>
    </div>
    <div class="box-container col-lp">
        <div class="small-box1" id="my-progress-bar"></div>
        <label class="mb1-5">Belum Dibooking</label>
    </div>

</section>
    <!-- jadwal section  end-->

    <!-- Lokasi section start -->
    <section id="lokasi" class="lokasi">
        <h2><span>Lokasi</span> Kami</h2>

        <div class="alamat">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.9263259549243!2d107.01230327464194!3d-6.273418093715344!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698e0cdcd980b7%3A0x8b0def22d0f3a954!2sFutsal%20AWK!5e0!3m2!1sid!2sid!4v1684331083322!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>
        </div>

    </section>
    <!-- Lokasi section end -->


    <!-- feather icons -->
    <script>
        feather.replace()
    </script>

    <!-- My Javascript -->
    <script src="js/script.js"></script>
    <script>
        window.onload = function() {

            const progress_bars = document.getElementsByClassName('progress-bar')
            for (var i = 0; i < progress_bars.length; i++) {

                const progress_bar = progress_bars[i];
                const bar_start = timeToMinutes(progress_bar.dataset.start)
                const bar_end = timeToMinutes(progress_bar.dataset.end)
                const bar_width = bar_end - bar_start

                const progreses = progress_bar.getElementsByClassName('progress');
                for (var j = 0; j < progreses.length; j++) {
                    const progress_element = progreses[j]
                    const progress_start = timeToMinutes(progress_element.dataset.start)
                    const progress_end = timeToMinutes(progress_element.dataset.end)

                    const progress_left = progress_start - bar_start
                    const progress_width = progress_end - progress_start;

                    const left = Math.round(100 * progress_left / bar_width)
                    const width = Math.round(100 * progress_width / bar_width)

                    progress_element.style.width = width + '%';
                    progress_element.style.left = left + '%';
                }
            };
        }

        function timeToMinutes(time) {
            let [hour, minute] = time.split(':')
            hour = parseInt(hour)
            minute = parseInt(minute)

            return (hour * 60 + minute)
        }
    </script>

</body>

</html>