<?php
require('fpdf186/fpdf.php');

// Fungsi untuk membuat PDF bukti booking berdasarkan data booking yang diberikan
function createBookingPDF($bookingId)
{
    // Periksa apakah $bookingId tidak kosong atau valid
    if (empty($bookingId) || !is_numeric($bookingId)) {
        echo "ID booking tidak valid.";
        return; // Hentikan eksekusi fungsi jika $bookingId tidak valid
    }

    // Buat koneksi ke database (gantilah dengan informasi koneksi Anda)
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "sewalapangan";

    $data = mysqli_connect($host, $user, $password, $db);

    if ($data === false) {
        die("Kesalahan koneksi");
    }

    // Query untuk mengambil data bukti booking dari tabel 'formsewa' berdasarkan id
    $sql = "SELECT fs.id, fs.order_id, fs.nama, fs.tanggal, fs.jam_mulai, fs.jam_selesai, lapangan.nama_lapangan, lapangan.harga_sewa
            FROM formsewa fs
            JOIN lapangan ON fs.id_lapangan = lapangan.id_lapangan
            WHERE fs.id = '$bookingId'";

    $result = mysqli_query($data, $sql);

    if (!$result) {
        die("Kesalahan query: " . mysqli_error($data));
    }

    if (mysqli_num_rows($result) > 0) {
        $bookingData = mysqli_fetch_assoc($result);

        // Ambil jam_mulai dan jam_selesai dari data formsewa
        $jamMulai = $bookingData['jam_mulai'];
        $jamSelesai = $bookingData['jam_selesai'];

        // Ambil harga_sewa dari tabel lapangan berdasarkan id_lapangan
        $hargaPerJam = $bookingData['harga_sewa'];

        // Hitung lamaSewa berdasarkan jam_mulai dan jam_selesai
        $lamaSewa = round((strtotime($jamSelesai) - strtotime($jamMulai)) / 3600, 2);

        // Hitung total berdasarkan harga per jam dan lamaSewa
        $total = $hargaPerJam * $lamaSewa;

        // Buat objek FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        $cellWidth = $pdf->GetPageWidth() - 20;

        // Judul
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'AWK FUTSAL', 0, 1, 'C');
        $pdf->Ln(0);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($cellWidth, 10, 'Alamat: Jl. Garuda Raya No.8, RT.009/RW.019, Pengasinan, Kec. Rawalumbu, Kota Bks, Jawa Barat 17115', 0, 1, 'C');
        $pdf->Cell($cellWidth, 10, 'Telepon: 08123456789', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Bukti Booking', 0, 1, 'C');
        $pdf->Ln(10);

        // Informasi Booking
        $pdf->SetFont('Arial', '', 12);

        // Tentukan lebar cell agar sejajar
        $cellWidth = $pdf->GetPageWidth() - 20;

        // Fungsi untuk mencetak baris dengan teks sejajar
        function printAlignedTextWithColon($pdf, $leftText, $rightText, $width)
        {
            $pdf->Cell($width, 10, $leftText . ' : ' . $rightText, 0, 1, 'L');
        }

        // Informasi Booking
        printAlignedTextWithColon($pdf, 'Order ID', $bookingData['order_id'], $cellWidth);
        printAlignedTextWithColon($pdf, 'Nama', $bookingData['nama'], $cellWidth);
        printAlignedTextWithColon($pdf, 'Nama Lapangan', $bookingData['nama_lapangan'], $cellWidth);
        printAlignedTextWithColon($pdf, 'Tanggal', $bookingData['tanggal'], $cellWidth);
        printAlignedTextWithColon($pdf, 'Jam Mulai', $jamMulai, $cellWidth);

        // Tambahkan jam selesai, lama sewa, dan total ke dalam PDF
        printAlignedTextWithColon($pdf, 'Jam Selesai', $jamSelesai, $cellWidth);
        printAlignedTextWithColon($pdf, 'Lama Sewa', $lamaSewa . ' jam', $cellWidth);
        printAlignedTextWithColon($pdf, 'Total', $total . ' IDR', $cellWidth);

        $pdf->Ln(20);

        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 5, 'Terima kasih telah melakukan booking. Hubungi kami untuk informasi lebih lanjut.', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell($cellWidth, 10, '*Harap tunjukkan bukti booking ini kepada petugas di loket.', 0, 'J');

        // Simpan atau tampilkan PDF
        $fileName = 'Bukti_Booking_' . $bookingData['nama'] . '.pdf'; // Nama file PDF sesuai dengan nama pemesan
        header('Content-Disposition: attachment; filename="' . $fileName . '"'); // Atur header Content-Disposition dengan nama file
        $pdf->Output('D', $fileName); // Tampilkan di browser ('I') atau simpan sebagai file ('D')
    } else {
        echo "Data booking tidak ditemukan.";
    }
}

// Panggil fungsi untuk membuat PDF bukti booking berdasarkan id booking yang diberikan melalui parameter GET
if (isset($_GET['booking_id'])) {
    $bookingId = $_GET['booking_id'];
    createBookingPDF($bookingId);
} else {
    echo "ID booking tidak ditemukan.";
}
?>
