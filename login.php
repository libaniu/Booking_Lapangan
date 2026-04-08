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
    header('Content-Type: application/json'); // Paksa response menjadi JSON murni
    $input_username = trim($_POST["username"]); // Hapus spasi di awal/akhir
    $input_password = $_POST["password"];

    // Hindari SQL Injection dengan metode query standar yang lebih stabil
    $username_safe = mysqli_real_escape_string($data, $input_username);
    $sql = "SELECT * FROM users WHERE username = '$username_safe'";
    $result = mysqli_query($data, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($input_password, $row["password"]) || $input_password === $row["password"]) {
            if ($row["usertype"] == "user") {
                session_name("user_session");
                session_start();
                $_SESSION["username"] = $row["username"];
                $_SESSION["name"] = $row["name"];
                echo json_encode(array("success" => true, "redirect" => "user/userhome.php"));
                exit;
            } elseif ($row["usertype"] == "admin") {
                session_name("admin_session");
                session_start();
                $_SESSION["username"] = $row["username"];
                $_SESSION["name"] = $row["name"];
                echo json_encode(array("success" => true, "redirect" => "admin/adminhome.php"));
                exit;
            }
        }
    }

    // Jika tidak ada kondisi yang terpenuhi, berarti login gagal
    echo json_encode(array("success" => false, "message" => "Username atau password salah!"));
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        html,
        body {
            font-family: 'Poppins', sans-serif;
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
 
        .container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            text-align: center;
        }

        h1 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 10px;
            color: #fff;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
            position: relative;
        }

        label {
            display: block;
            font-weight: 400;
            margin-bottom: 0;
            color: #eee;
            font-size: 14px;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 30px;
            color: rgba(255, 255, 255, 0.5);
            transition: color 0.3s ease;
        }

        .form-control:focus, .form-control:hover {
            outline: none;
            border-color: rgba(30, 144, 255, 0.8);
            background-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 15px rgba(30, 144, 255, 0.2);
            color: #fff;
        }

        .form-group:focus-within i {
            color: #1e90ff;
        }

        input[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #1e90ff 0%, #007bff 100%);
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        input[type="submit"]:hover {
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }

        input[type="submit"]:disabled {
            background: rgba(255, 255, 255, 0.2);
            color: #aaa;
            cursor: not-allowed;
            box-shadow: none;
        }

        .text-danger {
            display: none;
        }

        .alert {
            display: none;
            margin-top: 0;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: rgba(255, 69, 58, 0.2);
            color: #ffcccb;
            border: 1px solid rgba(255, 69, 58, 0.4);
        }

        .register-link {
            margin-top: 25px;
            font-size: 14px;
            color: #eee;
        }

        .register-link a {
            color: #1e90ff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #00c6ff;
            text-decoration: none;
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
                var submitBtn = form.find('input[type="submit"]');
                var originalBtnText = submitBtn.val();

                // UX: Disable tombol dan beri animasi loading
                submitBtn.prop('disabled', true).val('Memproses...');
                $('.alert').fadeOut(200); // Sembunyikan alert sebelumnya secara halus

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            submitBtn.val('Berhasil!');
                            Swal.fire({
                                icon: 'success',
                                title: 'Selamat Datang!',
                                text: 'Login berhasil, mengalihkan...',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                                background: 'rgba(0, 0, 0, 0.8)',
                                color: '#fff'
                            }).then(function() {
                                window.location.href = response.redirect;
                            });
                        } else {
                            $('.alert').text(response.message).fadeIn(300); 
                            submitBtn.prop('disabled', false).val(originalBtnText);
                        }
                    },
                    error: function() {
                        $('.alert').text('Terjadi kesalahan. Silakan coba lagi.').fadeIn(300);
                        submitBtn.prop('disabled', false).val(originalBtnText);
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Selamat Datang</h1>
        <div class="alert alert-danger"></div>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Username</label>
                <i data-feather="user"></i>
                <input class="form-control" type="text" name="username" required placeholder="Masukkan Username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <i data-feather="lock"></i>
                <input class="form-control" type="password" name="password" required placeholder="Masukkan Password">
            </div>
            <div>
                <input type="submit" value="Masuk">
            </div>
            <div class="register-link">
                <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
            </div>
        </form>
    </div>
    <script>
        feather.replace()
    </script>
</body>

</html>