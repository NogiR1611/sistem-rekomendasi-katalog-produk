<?php
    include 'config/config.php';

    $alertErr = $usernameErr = $passwordErr = "";

    if (isset($_POST['register'])){
        $userTest = filtered_input($_POST['username']);
        $passTest = filtered_input($_POST['password']);
        $conPassTest = filtered_input($_POST['confirm-password']);
        
        // sebelum parameter dikirim ke server dilakukan validasi terlebih dahulu
        if($passTest !== $conPassTest){
            $passwordErr = "<div class='text-danger'>Password dan konfirmasi password harus sama</div>"; 
        }
        else if(strlen($userTest) < 8){
            $usernameErr = "<div class='text-danger'>Username harus diisi minimal 8 karakter</div>";
        }
        else if(!preg_match("/^[a-zA-Z0-9]*$/",$userTest)){
            $usernameErr = "<div class='text-danger'>Hanya boleh menggunakan huruf dan angka</div>";
        }
        else if(strlen($passTest) < 8){
            $passwordErr = "<div class='text-danger'>Password harus diisi minimal 8 karakter</div>";
        }
        else{
            //filter data yang akan diinput
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            //cek akun yang ingin didaftar
            $cek_akun = "SELECT nama FROM user WHERE nama=:nama";
            $buat_akun = $db->prepare($cek_akun);

            $buat_akun->bindParam(':nama',$username);

            $buat_akun->execute();

            if($buat_akun->rowCount() > 0){
                $alertErr = "<div class='alert alert-danger' role='alert'>Maaf akun sudah terdaftar</div>";
            }
            else{
                //daftar kan akun jika belum terdaftar
                $daftar_akun = "INSERT INTO user (nama, password, hak_akses) VALUES (:username, :password, :hak_akses)";
                $kirim_query = $db->prepare($daftar_akun);

                //ikat parameter ke query
                $params = array(
                    ":username" => $username,
                    ":password" => $password,
                    ":hak_akses" => 'admin'
                );

                //eksekusi query untuk menyimpan ke database
                $terdaftar = $kirim_query->execute($params);
                
                //jika query berhasil menyimpan data maka user dialihkan menuju halaman login
                if($terdaftar) header("location: login.php");   
            }
        }
    }

    function filtered_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Kodinger">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Daftar | INTANA LEATHER COLLECTION</title>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    	<link rel="stylesheet" type="text/css" href="dist/css/login.css">
    </head>
    <body class="my-login-page">
        <section class="min-vh-100">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="card-wrapper">
                        <div class="brand">
                            <img src="/assets/images/logo.png" alt="logo">
                        </div>
                        <div class="card fat">
                            <div class="card-body">
                                <h4 class="card-title text-center">Daftar Admin</h4>
                                <?php echo $alertErr; ?>
                                <form method="POST" class="my-login-validation" onSubmit="">
                                    <div class="form-group">
                                        <label for="name">Username</label>
                                        <input id="username" type="text" class="form-control" name="username" required autofocus>
                                        <?php echo $usernameErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" class="form-control" name="password" required data-eye>
                                        <?php echo $passwordErr; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Konfirmasi Password</label>
                                        <input id="confirm-password" type="password" class="form-control" name="confirm-password" required data-eye>
                                        <?php echo $passwordErr; ?>
                                    </div>
                                    <div class="form-group m-0">
                                        <button type="submit" class="btn btn-primary btn-block" name="register">
                                            Daftar
                                        </button>
                                    </div>
                                    <div class="mt-4 text-center">
                                        Apakah anda sudah mempunyai akun? <a href="login.php">Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>