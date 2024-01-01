<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "sewalapangan";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection error");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Lakukan validasi dan sanitasi data yang diterima jika diperlukan

    // Gunakan prepared statements untuk mencegah SQL injection
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($data, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        if ($row["usertype"] == "user") {
            session_name("user_session");
            session_start();
            $_SESSION["username"] = $username;
            echo json_encode(array("success" => true, "redirect" => "userhome.php"));
            exit;
        } elseif ($row["usertype"] == "admin") {
            session_name("admin_session");
            session_start();
            $_SESSION["username"] = $username;
            echo json_encode(array("success" => true, "redirect" => "adminhome.php"));
            exit;
        } else {
            echo json_encode(array("success" => false, "message" => "Username or password incorrect"));
            exit;
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Username or password incorrect"));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($data);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            width: 600px;
            padding: 50px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container {
            max-width: 600px;
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

        .alert {
            display: none;
            margin-top: 20px;
        }
    </style>
    <title>Login Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault(); // Mencegah pengiriman form secara default

                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var formData = form.serialize();

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect; // Redirect ke halaman yang sesuai
                        } else {
                            $('.alert').text(response.message).show(); // Menampilkan pesan error
                        }
                    },
                    error: function() {
                        $('.alert').text('An error occurred. Please try again.').show();
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Login Form</h1>
        <div class="alert alert-danger"></div>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Username <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="username" required placeholder="Masukkan Username Anda">
            </div>
            <div class="form-group">
                <label>Password <span class="text-danger">*</span></label>
                <input class="form-control" type="password" name="password" required placeholder="Masukkan Password Anda">
            </div>
            <div>
                <input type="submit" value="Login">
            </div>
        </form>
    </div>
</body>

</html>