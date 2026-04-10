<?php
header('Content-Type: application/json');

// Pastikan Anda sudah menyertakan library Midtrans. 
// Sesuaikan path require_once di bawah ini dengan lokasi folder library midtrans-php Anda.
// Jika Anda menggunakan composer: require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/midtrans/Midtrans.php'; 

// 1. Ambil order_id dari request POST frontend
if (!isset($_POST['order_id'])) {
    echo json_encode(['error' => 'Order ID tidak ditemukan']);
    exit;
}

$order_id = $_POST['order_id'];

// 2. Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Koneksi database gagal']);
    exit;
}

// 3. Ambil data transaksi dari tabel formsewa berdasarkan order_id
// Kita juga men-join dengan tabel users untuk mendapatkan email dan nomor HP (jika ada)
$sql = "SELECT fs.order_id, fs.total_bayar, fs.nama, u.email, u.notelp 
        FROM formsewa fs 
        LEFT JOIN users u ON fs.nama = u.name OR fs.nama = u.username
        WHERE fs.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Data transaksi tidak ditemukan']);
    exit;
}

$transaction = $result->fetch_assoc();

// 4. Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-hgqasJLKir7ni7NhhL_kNN6e'; // GANTI DENGAN SERVER KEY ANDA!
\Midtrans\Config::$isProduction = false; // Set false untuk mode Sandbox/Testing
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// 5. Susun parameter untuk dikirim ke Midtrans
$params = array(
    'transaction_details' => array(
        'order_id' => $transaction['order_id'],
        'gross_amount' => $transaction['total_bayar'],
    ),
    'customer_details' => array(
        'first_name' => $transaction['nama'],
        'email' => !empty($transaction['email']) ? $transaction['email'] : 'pelanggan@example.com',
        'phone' => !empty($transaction['notelp']) ? $transaction['notelp'] : '081234567890',
    ),
);

// 6. Request Snap Token dan kembalikan ke Frontend sebagai JSON
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo json_encode(['token' => $snapToken]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>