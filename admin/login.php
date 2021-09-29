<?php
    include 'config/config.php';

    $usernameErr = $passwordErr = $alertErr = "";

    if (isset($_POST['login'])){
        $userTest = filtered_input($_POST['username']);
        $passTest = filtered_input($_POST['password']);

        // sebelum parameter dikirim ke server dilakukan validasi terlebih dahulu
        if(strlen($userTest) < 8){
            $usernameErr = "<div class='text-danger'>Username harus diisi minimal 8 karakter</div>";
        }
        else if(!preg_match("/^[a-zA-Z0-9 ]*$/",$userTest)){
            $usernameErr = "<div class='text-danger'>Hanya boleh menggunakan huruf dan angka</div>";
        }
        else if(strlen($passTest) < 8){
            $passwordErr = "<div class='text-danger'>Password harus diisi minimal 8 karakter</div>";
        }

        try{
            // bila validasi sudah selesai maka parameter akan dikirim ke database
            $sql = "SELECT * FROM user WHERE nama=:username";
            $stmt = $db->prepare($sql);

            // ikat parameter ke query
            $stmt->bindParam('username',$userTest,PDO::PARAM_STR);

            // jalankan query
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // jika user terdaftar
            if ($user) {
                // verifikasi password
                if(password_verify($passTest, $user["password"])){
                    // buat session    
                    session_start();
                    $_SESSION['nama'] = $userTest;
                    $_SESSION['password'] = $passTest;
                    $_SESSION['hak_akses'] = $user['hak_akses'];
                    // login sukses, alihkan ke halaman home
                    header("location: index.php");
                }
                $alertErr = "<div class='alert alert-danger' role='alert'>Maaf password anda salah</div>";
            }
            else{   
                $alertErr = "<div class='alert alert-danger' role='alert'>Maaf username anda salah</div>";
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
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
        <title>Login Admin | INTANA LEATHER COLLECTION</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="dist/css/login.css">
    </head>
    <body class="my-login-page">
        <section class="h-100">
            <div class="container h-100">
                <div class="row justify-content-md-center h-100">
                    <div class="card-wrapper">
                        <div class="brand">
                            <img src="assets/images/logo.png" alt="logo">
                        </div>
                        <div class="card fat">
                            <div class="card-body">
                                <h4 class="card-title text-center">Login Admin</h4>
                                <?php echo $alertErr; ?>
                                <form method="POST" class="my-login-validation" onSubmit="">
                                    <div class="form-group">
                                        <label for="email">Username</label>
                                        <input type="text" class="form-control" name="username" id="username" required autofocus>
                                        <div class="" id="invalid-feedback-username">
                                            <?php echo $usernameErr; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required data-eye>
                                        <div class="" id="invalid-feedback-password">
                                            <?php echo $passwordErr; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-checkbox custom-control">
                                            <input type="checkbox" name="remember" id="remember" class="custom-control-input">
                                            <label for="remember" class="custom-control-label">Ingat saya</label>
                                        </div>
                                    </div>
                                    <div class="form-group m-0">
                                        <button type="submit" class="btn btn-primary btn-block" name="login">
                                            Login
                                        </button>
                                    </div>
                                    <div class="mt-4 text-center">
                                        Tidak punya akun? <a href="daftar.php">Buat disini</a>
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