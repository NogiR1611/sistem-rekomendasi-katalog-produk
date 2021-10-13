<?php

    include '../config/config.php';

    session_start();
    
    if($_SESSION['hak_akses'] !== 'pengguna'){
        header("location: login.php");
    }

    if (!isset($_SESSION['nama'])){
        header("Location: login.php");
    }

    if(isset($_GET['id'])){
        $pkid = $_GET['id'];
    }
    else{
        die('Sorry. No id selected');
    }

    $alert = "";

    //ambil data produk
    $ambil_data_produk = "SELECT * FROM produk_kulit WHERE pkid=:id";
    
    $data_produk = $db->prepare($ambil_data_produk);

    $data_produk->bindParam(':id',$pkid);

    $data_produk->execute();

    $hasil = $data_produk->fetch(PDO::FETCH_ASSOC);

    //ambil data rating untuk user pada produk yang sudah diberi rating
    $ambil_rating_user = "SELECT user.userid as pengguna_id, user.nama as pengguna, produk_kulit.namapk as produk, rating.ratingvalue as rating from rating inner join user on rating.userid=user.userid inner join produk_kulit on rating.pkid=produk_kulit.pkid where produk_kulit.pkid=:produk_id and user.nama=:nama_user";

    $rating_user = $db->prepare($ambil_rating_user);

    $rating_user->bindParam('produk_id',$pkid);
    $rating_user->bindParam('nama_user',$_SESSION['nama']);

    $rating_user->execute();

    $hasil_rating_user = $rating_user->fetch(PDO::FETCH_ASSOC);

    //ambil data rating untuk produk
    $ambil_rating = "SELECT AVG(rating.ratingvalue)'rating_avg' FROM rating WHERE pkid=:id";

    $data_rating = $db->prepare($ambil_rating);
    
    $data_rating->bindParam(':id',$pkid);

    $data_rating->execute();

    $rating = $data_rating->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['tambah'])){

        if(!isset($_POST['rating'])){
            $_POST['rating'] = 5;
        }

        $baca_id_pengguna = "SELECT userid FROM user WHERE nama=:nama";

        $query1 = $db->prepare($baca_id_pengguna);

        $query1->bindParam(":nama",$_SESSION['nama']);

        $query1->execute();

        $hasil1 = $query1->fetch(PDO::FETCH_ASSOC);

        $userid = $hasil1["userid"];

        if($hasil_rating_user){
            $edit_rating_produk = "UPDATE rating set ratingvalue=:rating where pkid=:produk_id and userid=:pengguna_id";
            
            $proses_edit_rating = $db->prepare($edit_rating_produk);

            $proses_edit_rating->bindParam(':rating', $_POST['rating']);
            $proses_edit_rating->bindParam(':produk_id',$pkid);
            $proses_edit_rating->bindParam(':pengguna_id',$userid);
            
            $proses_edit_rating->execute();

            $alert = '
                <div class="alert alert-success" role="alert">
                    Rating berhasil diganti
                </div>  
            ';
        }
        else{
            $tambah_rating = "INSERT INTO rating (pkid, userid, ratingvalue) VALUES (:pkid, :userid, :ratingvalue)"; 
                
            $query2 = $db->prepare($tambah_rating);

            $params = array(
                ":pkid" => $pkid,
                ":userid" =>$userid,
                ":ratingvalue" => $_POST["rating"]
            );

            $query2->execute($params);

            $query2->fetch(PDO::FETCH_ASSOC);
            
            $alert = '
                <div class="alert alert-success" role="alert">
                    Rating berhasil ditambahkan
                </div>  
            ';
        }
    }


    function rupiah($rupiah){
        $hasil_rupiah = "Rp " . number_format($rupiah,2,',','.');
        return $hasil_rupiah;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
        <link rel="stylesheet" type="text/css" href="/css/star.css" />
        <title>Sepatu Converse | INTANA LEATHER COLLECTION</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    </head>
    <body class="bg-light">
        <section>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand fs-6" href="../index.php">
                        <img src="/assets/images/logo.png" class="img-logo" alt="" />
                        INTANA LEATHER COLLECTION
                    </a>
                    <div class="d-flex collapse navbar-collapse me-4" id="navbarSupportedContent">
                        <form class="d-flex flex-fill" method="GET" action="../pencarian.php">
                            <input class="flex-auto form-control me-2" name="hasil" type="search" placeholder="Cari produk disini" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Cari</button>
                        </form>
                    </div>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['nama']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="../pengaturan.php">Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="..utils/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div className="d-flex justify-content-center p-3 min-vh-100 w-100">
                <div class="d-flex flex-column justify-content-center w-50 mx-auto">
                    <?php echo $alert; ?>
                    <img src="../admin/assets/images/produk/<?php echo $hasil['foto']; ?>" class="ratio ratio-1x1 mt-5 w-50 h-50 image-produk mx-auto" />
                    <div class="my-3">
                        <p class="text-dark fs-3 fw-bold text-center"><?php echo $hasil['namapk']; ?></p>
                        <p class="text-dark fs-6 mb-3 fw-bold">Harga  : <?php echo rupiah($hasil['harga']); ?></p>
                        <p class="text-dark fs-6 mb-3 fw-bold">Rating : <?php echo ''.($rating ? number_format((float)$rating["rating_avg"],1,'.','') : '0').''; ?>/5.0<i class="fa fa-star yellow-star"></i></p>
                        <div class="flex justify-content-center w-100">
                        <button type="button" class="btn btn-primary d-block mx-auto" data-toggle="modal" data-target="#exampleModal">
                            Tambah Rating
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Silahkan berikan rating untuk produk ini</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <?php
                                        if($hasil_rating_user && $hasil_rating_user['rating']){
                                            echo '
                                                <div class="d-flex justify-content-center mx-2 text-center">
                                                    <p class="fw-bold">Anda sebelumnya sudah memberi rating pada produk ini. Ingin merubahnya lagi?</p>
                                                </div>
                                            ';
                                        }
                                    ?>
                                    <form method="POST">
                                        <div class="d-flex justify-content-center my-2">
                                            <div class="star-icon">
                                                    <input type="radio" name="rating" value="1" id="rating1" <?php echo ''.($hasil_rating_user && $hasil_rating_user['rating'] === '1' ? 'checked' : '').''; ?>>
                                                    <label for="rating1" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="2" id="rating2" <?php echo ''.($hasil_rating_user && $hasil_rating_user['rating'] === '2' ? 'checked' : '').''; ?>>
                                                    <label for="rating2" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="3" id="rating3" <?php echo ''.($hasil_rating_user && $hasil_rating_user['rating'] === '3' ? 'checked' : '').''; ?>>
                                                    <label for="rating3" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="4" id="rating4" <?php echo ''.($hasil_rating_user && $hasil_rating_user['rating'] === '4' ? 'checked' : '').''; ?>>
                                                    <label for="rating4" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="5" id="rating5" <?php echo ''.($hasil_rating_user && $hasil_rating_user['rating'] === '5' ? 'checked' : '').''; ?>>
                                                    <label for="rating5" class="fa fa-star"></label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                                            <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>    
                </div>
            </div>
            <footer class="text-center text-lg-start bg-light text-muted">
                <div class="text-center p-4">
                    Copyright &copy; 2021 - Powered by Intana Leather Collection 
                </div>
            </footer> 
        </section>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/5ea815c1d0.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    </body>
</html>