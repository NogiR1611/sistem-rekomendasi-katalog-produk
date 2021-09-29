<?php

    include '../config/config.php';

    session_start();
    if (!isset($_SESSION['nama']) && $_SESSION['hak_akses'] == 'pengguna'){
        header("Location: login.php");
    }

    if(isset($_GET['id'])){
        $pkid = $_GET['id'];
    }
    else{
        die('Sorry. No id selected');
    }

    $alert = "";

    $ambil_data_produk = "SELECT * FROM produk_kulit WHERE pkid=:id";

    $sql = $db->prepare($ambil_data_produk);

    $sql->bindParam(':id',$pkid);

    $sql->execute();

    $hasil = $sql->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['tambah'])){
        
        $baca_id_pengguna = "SELECT userid FROM user WHERE nama=:nama";

        $query1 = $db->prepare($baca_id_pengguna);

        $query1->bindParam(":nama",$_SESSION['nama']);

        $query1->execute();

        $hasil1 = $query1->fetch(PDO::FETCH_ASSOC);

        $userid = $hasil1["userid"];

        $tambah_rating = "INSERT INTO rating (pkid, userid, ratingvalue) VALUES (:pkid, :userid, :ratingvalue)"; 
        
        $query2 = $db->prepare($tambah_rating);

        $params = array(
            ":pkid" => $pkid,
            ":userid" =>$userid,
            ":ratingvalue" => $_POST["rating"]
        );

        $query2->execute($params);

        $rating = $query2->fetch(PDO::FETCH_ASSOC);
        
        $alert = '
            <div class="alert alert-success" role="alert">
                Rating berhasil ditambahkan
            </div>  
        ';
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
                        <form class="d-flex flex-fill">
                            <input class="flex-auto form-control me-2" type="search" placeholder="Cari produk disini" aria-label="Search">
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
                                <li><a class="dropdown-item" href="../utils/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div className="d-flex justify-content-center p-3 min-vh-100 w-100">
                <div class="w-50 mx-auto">
                    <?php echo $alert; ?>
                    <img src="../admin/assets/images/produk/<?php echo $hasil['foto']; ?>" class="ratio ratio-1x1 mt-5" />
                    <div class="my-3">
                        <p class="text-dark fs-3 fw-bold text-center"><?php echo $hasil['namapk']; ?></p>
                        <p class="text-dark fs-6 mb-3 fw-bold">Harga  : <?php echo rupiah($hasil['harga']); ?></p>
                        <p class="text-dark fs-6 mb-3 fw-bold">Rating : 4.3/5.0 <i class="fa fa-star yellow-star"></i></p>
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
                                    <form method="POST">
                                        <div cladal-body d-flex ss="mojustify-content-center">
                                            <div class="star-icon">
                                                    <input type="radio" name="rating" value="1" id="rating1">
                                                    <label for="rating1" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="2" id="rating2">
                                                    <label for="rating2" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="3" id="rating3">
                                                    <label for="rating3" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="4" id="rating4">
                                                    <label for="rating4" class="fa fa-star"></label>
                                                    <input type="radio" name="rating" value="5" id="rating5">
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