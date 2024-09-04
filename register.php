<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection error");
}

$message = ""; // Variabel untuk menyimpan pesan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Lakukan validasi dan sanitasi data yang diterima jika diperlukan

    // Cek ketersediaan username
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($data, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $message = "Username sudah digunakan";
    } else {
        $sql = "INSERT INTO users (name, username, password, usertype) VALUES ('$name', '$username', '$password', 'user')";

        if (mysqli_query($data, $sql)) {
            session_start();
            $_SESSION["username"] = $username;
            header("location: login.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($data);
        }
    }

    mysqli_close($data);
}

?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .text-danger {
            color: red;
        }
    </style>
    <title>Form Registrasi</title>
</head>

<body>
    <div class="container">
        <h1>Registration Form</h1>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="name" required placeholder="Masukkan Nama Anda">
            </div>
            <div class="form-group">
                <label>Username <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="username" required placeholder="Masukkan Username Anda">
            </div>
            <div class="form-group">
                <label>Password <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="password" required placeholder="Masukkan Password Anda">
            </div>
            <div>
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
</body>

</body>

</html>