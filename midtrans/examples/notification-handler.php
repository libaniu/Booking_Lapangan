<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for sample HTTP notifications:
// https://docs.midtrans.com/en/after-payment/http-notification?id=sample-of-different-payment-channels

namespace Midtrans;

require_once dirname(__FILE__) . '/../Midtrans.php';

// Set your server key and other configurations
Config::$isProduction = false; // Set to true for production
Config::$serverKey = SB-Mid-server-hgqasJLKir7ni7NhhL_kNN6e; // Ganti dengan Server Key Anda

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sewalapangan";

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

try {
    $notif = new Notification();
}
catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

// Create connection
$conn = new \mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Normally you would log this error to a file, not echo it.
    // echo "Connection failed: " . $conn->connect_error;
    exit();
}

if ($transaction == 'capture') {
    // For credit card transaction, we need to check whether transaction is challenge by FDS or not
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            // Set payment status in your database to 'challenge'
            $stmt = $conn->prepare("UPDATE formsewa SET status_booking = 'challenge' WHERE order_id = ?");
            $stmt->bind_param("s", $order_id);
            $stmt->execute();
        } else {
            // Set payment status in your database to 'success' or 'settlement'
            $stmt = $conn->prepare("UPDATE formsewa SET status_booking = 'settlement' WHERE order_id = ?");
            $stmt->bind_param("s", $order_id);
            $stmt->execute();
        }
    }
} else if ($transaction == 'settlement') {
    // Set payment status in your database to 'settlement'
    $stmt = $conn->prepare("UPDATE formsewa SET status_booking = 'settlement' WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
} else if ($transaction == 'pending') {
    // Set payment status in your database to 'pending'
    // (This is usually the initial state, so you might not need to update it)
} else if ($transaction == 'deny') {
    // Set payment status in your database to 'denied'
    $stmt = $conn->prepare("UPDATE formsewa SET status_booking = 'deny' WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
} else if ($transaction == 'expire') {
    // Set payment status in your database to 'expire'
    $stmt = $conn->prepare("UPDATE formsewa SET status_booking = 'expire' WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
} else if ($transaction == 'cancel') {
    // Set payment status in your database to 'cancel'
    $stmt = $conn->prepare("UPDATE formsewa SET status_booking = 'cancel' WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
}

$conn->close();

function printExampleWarningMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }   
}
