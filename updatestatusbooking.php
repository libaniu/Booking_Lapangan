<?php

header('Content-Type: application/json');

session_name("admin_session");
session_start();

$response = [];

if (!isset($_SESSION["username"])) {
    $response = ['success' => false, 'message' => 'Akses ditolak. Silakan login kembali.'];
    echo json_encode($response);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
       
    if (isset($_POST['id_booking']) && isset($_POST['new_status'])) {
        
        $id_booking = $_POST['id_booking'];
        $new_status = $_POST['new_status'];
        
        if ($new_status == 'Approved' || $new_status == 'Rejected' || $new_status == 'Pending') {
            
            // Koneksi ke database
            $host = "localhost";
            $user = "root";
            $password = "";
            $db = "sewalapangan";
            $conn = mysqli_connect($host, $user, $password, $db);

            if ($conn) {
                $sql = "UPDATE formsewa SET status_booking = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $new_status, $id_booking);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        $response = ['success' => true];
                    } else {
                        $response = ['success' => false, 'message' => 'Eksekusi query database gagal.'];
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $response = ['success' => false, 'message' => 'Gagal mempersiapkan statement SQL.'];
                }
                mysqli_close($conn);
            } else {
                $response = ['success' => false, 'message' => 'Koneksi database gagal.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Status yang dikirim tidak valid.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Parameter id_booking atau new_status tidak ada.'];
    }
} else {
    $response = ['success' => false, 'message' => 'Metode request tidak valid, harus POST.'];
}

echo json_encode($response);
exit();

?>