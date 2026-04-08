<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json'); // Set header untuk JSON response

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "sewalapangan";

    $data = mysqli_connect($host, $user, $password, $db);

    if ($data === false) {
        echo json_encode(["success" => false, "message" => "Database connection error."]);
        exit;
    }

    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validasi input dasar
    if (empty($name) || empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Semua field harus diisi."]);
        exit;
    }

    // Cek ketersediaan username menggunakan prepared statement
    $stmt = $data->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Username sudah digunakan, silakan pilih yang lain."]);
        $stmt->close();
        $data->close();
        exit;
    }
    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru menggunakan prepared statement
    $stmt = $data->prepare("INSERT INTO users (name, username, password, usertype) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("sss", $name, $username, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registrasi berhasil!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Terjadi kesalahan saat registrasi."]);
    }

    $stmt->close();
    $data->close();
    exit; // Penting untuk menghentikan eksekusi script PHP setelah mengirim JSON
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

        input[type="text"],
        input[type="password"] {
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

        input[type="text"]:focus, input[type="text"]:hover,
        input[type="password"]:focus, input[type="password"]:hover {
            outline: none;
            border-color: rgba(30, 144, 255, 0.8);
            background-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 15px rgba(30, 144, 255, 0.2);
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

        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            color: #a3e9a4;
            border: 1px solid rgba(46, 204, 113, 0.4);
        }

        .login-link {
            margin-top: 25px;
            font-size: 14px;
            color: #eee;
        }

        .login-link a {
            color: #1e90ff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #00c6ff;
            text-decoration: none;
        }

        .text-danger {
            display: none;
        }
    </style>
    <title>Form Registrasi</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault();

                var form = $(this);
                var submitBtn = form.find('input[type="submit"]');
                var originalBtnText = submitBtn.val();
                var alertBox = $('.alert');

                submitBtn.prop('disabled', true).val('Memproses...');
                alertBox.fadeOut(200);

                $.ajax({
                    url: 'register.php',
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        alertBox.removeClass('alert-danger alert-success').text('');

                        if (response.success) {
                            submitBtn.val('Berhasil!');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Registrasi sukses, mengalihkan ke login...',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                                background: 'rgba(0, 0, 0, 0.8)',
                                color: '#fff'
                            }).then(function() {
                                window.location.href = 'login.php';
                            });
                        } else {
                            alertBox.addClass('alert-danger').text(response.message).fadeIn(300);
                            submitBtn.prop('disabled', false).val(originalBtnText);
                        }
                    },
                    error: function() {
                        alertBox.removeClass('alert-danger alert-success').text('');
                        alertBox.addClass('alert-danger').text('Terjadi kesalahan. Silakan coba lagi.').fadeIn(300);
                        submitBtn.prop('disabled', false).val(originalBtnText);
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Buat Akun Baru</h1>
        <div class="alert"></div>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label>Nama</label>
                <i data-feather="user"></i>
                <input class="form-control" type="text" name="name" required placeholder="Masukkan Nama">
            </div>
            <div class="form-group">
                <label>Username</label>
                <i data-feather="at-sign"></i>
                <input class="form-control" type="text" name="username" required placeholder="Masukkan Username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <i data-feather="lock"></i>
                <input class="form-control" type="password" name="password" required placeholder="Masukkan Password">
            </div>
            <div>
                <input type="submit" value="Daftar">
            </div>
            <div class="login-link">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </form>
    </div>
    <script>
        feather.replace()
    </script>
</body>

</html>