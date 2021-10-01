<?php

    include 'config/config.php';

    session_start();
    if (!isset($_SESSION['nama']) && $_SESSION['hak_akses'] == 'pengguna'){
        header("Location: login.php");
    }

    $alert = ''; 

    if(isset($_POST['ganti'])){
        $nama = filtered_input($_SESSION['nama']);
        $password_lama = filtered_input($_POST['password_lama']);
        $password_baru = filtered_input($_POST['password_baru']);
        $konf_password = filtered_input($_POST['konf_password']);

        $password = password_hash($password_lama, PASSWORD_DEFAULT);

        $cek_password_lama = "SELECT * FROM user WHERE nama= :nama";

        $sql = $db->prepare($cek_password_lama);

        $sql->bindParam(':nama', $nama, PDO::PARAM_STR);
        
        $sql->execute();
        
        $user = $sql->fetch(PDO::FETCH_ASSOC);

        if($user){
            if(password_verify($password_lama, $user["password"])){
                if($password_baru !== $konf_password){
                    $alert = '
                        <div class="alert alert-danger" role="alert">
                            Password baru harus sesuai dengan konfirmasi password baru
                        </div>    
                    ';
                }
                else{
                    try{
                        $ganti_password = "UPDATE user SET password=:password_baru WHERE nama=:nama";
                        
                        $password = password_hash($password_baru, PASSWORD_DEFAULT);
        
                        $sql = $db->prepare($ganti_password);
        
                        $data = array(
                            ':nama' => $nama,
                            ':password_baru' => $password
                        );
        
                        $sql->execute($data);
        
                        $alert = '
                            <div class="alert alert-success" role="alert">
                                Password berhasil diganti
                            </div>    
                        ';
                    }
                    catch(PDOException $e){
                        die($e->getMessage);
                    }
                }
            }
            else{
                $alert = '
                    <div class="alert alert-danger" role="alert">
                        Password anda salah
                    </div>    
                ';
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
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
        <title>Home | INTANA LEATHER COLLECTION</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    </head>
    <body class="bg-light">
        <section>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand fs-6" href="index.php">
                        <img src="/assets/images/logo.png" class="img-logo" alt="" />
                        INTANA LEATHER COLLECTION
                    </a>
                    <div class="d-flex collapse navbar-collapse me-4" id="navbarSupportedContent">
                        <form class="d-flex flex-fill" method="GET" action="pencarian.php">
                            <input class="flex-auto form-control me-2" name="hasil" type="search" placeholder="Cari produk disini" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Cari</button>
                        </form>
                    </div>
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['nama']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="./utils/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="d-flex justify-content-center w-100 mx-auto mt-3 mb-100 content">
                <div class="bg-white rounded w-50 mt-5 mb-100 pb-4 px-0">
                    <?php echo $alert; ?>
                    <div class="border-2 border-bottom border-secondary py-2 px-2 fs-5 fw-bold">
                        Ganti kata sandi
                    </div>
                    <form method="POST" class="my-login-validation mx-2" novalidate="">
                        <div class="form-group">
                            <label class="col-md-12">Nama</label>
                            <div class="col-md-12">
                                <input type="text" value=<?php echo $_SESSION['nama'];?>
                                    class="form-control form-control-line" name="nama" id="nama" disabled>
                            </div>
                        </div>
                        <div class="form-group my-2">
							<label for="password">Password Lama :</label>
							<input id="password_lama" type="password" class="form-control" name="password_lama" required data-eye>
						</div>
                        <div class="form-group my-2">
							<label for="password">Password Baru :</label>
							<input id="password_baru" type="password" class="form-control" name="password_baru" required data-eye>
						</div>
                        <div class="form-group my-2">
							<label for="password">Konfirmasi Password Baru :</label>
							<input id="konf_password" type="password" class="form-control" name="konf_password" required data-eye>
						</div>
						<div class="form-group mt-5">
							<button type="submit" class="btn btn-primary btn-block" name="ganti">
								Ganti Kata Sandi
							</button>
						</div>
					</form>
                </div>  
            </div>
            <footer class="text-center text-lg-start bg-light text-muted">
                <div class="text-center p-4">
                    Copyright &copy; 2021 - Powered by Intana Leather Collection 
                </div>
            </footer> 
        </section>  
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    </body>
</html>