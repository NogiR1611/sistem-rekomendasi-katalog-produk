<?php

    session_start();

    include 'config/config.php';

    include 'utils/rekomendasi.php';

    if($_SESSION['hak_akses'] !== 'pengguna'){
        header("location: login.php");
    }

    if (!isset($_SESSION['nama'])){
        header("location: login.php");
    }

    //ambil data kategori
    $ambil_data_kategori = "SELECT produk_kulit.foto as foto, kategori.nama_kategori as nama_kategori from produk_kulit inner join kategori on produk_kulit.kategori_id=kategori.kategori_id group by kategori.nama_kategori";

    $sql = $db->prepare($ambil_data_kategori);

    $sql->execute();

    //ambil data rating dari user untuk semua produk untuk ditampilkan bila sudah di input rating, kalo belum input rating data tidak akan ditampilkan
    $ambil_rating_user = "select A.nama, B.namapk, C.ratingvalue from rating C inner join user A on C.userid=A.userid inner join produk_kulit B on C.pkid=B.pkid where A.nama = :nama";

    $rating_user = $db->prepare($ambil_rating_user);

    $rating_user->bindParam(':nama', $_SESSION['nama']);

    $rating_user->execute();

    $hasil_rating_user = $rating_user->fetchAll(PDO::FETCH_ASSOC);

    //ambil data rating dari user untuk semua produk untuk ditampilkan walaupun ada produk yang ratingnya masih kosong
    $ambil_rating_user2 = "SELECT A.userid, A.nama, B.pkid, B.namapk, B.foto, B.harga, C.ratingvalue FROM user A CROSS JOIN produk_kulit B LEFT JOIN rating C ON A.userid = C.userid AND C.pkid = B.pkid WHERE A.hak_akses != 'admin' AND A.nama =:nama ORDER BY A.userid, B.pkid";
    
    $rating_user2 = $db->prepare($ambil_rating_user2);

    $rating_user2->bindParam(':nama', $_SESSION['nama']);

    $rating_user2->execute();

    $hasil_rating_user2 = $rating_user2->fetchAll(PDO::FETCH_ASSOC);
    
    //ambil rating rata-rata dari produk yang direkomendasikan untuk user
    $ambil_rata2_rating = "SELECT produk_kulit.*, avg(rating.ratingvalue)'rating_real' from rating inner join produk_kulit on rating.pkid=produk_kulit.pkid inner join user on rating.userid=user.userid group by produk_kulit.pkid";

    $rata2_rating = $db->prepare($ambil_rata2_rating);

    $rata2_rating->execute();

    $hasil_rata2_rating = $rata2_rating->fetchAll(PDO::FETCH_CLASS);

    $array_terfilter = array();
    
    if($hasil_rating_user){
        foreach($weight_sum as $keys6 => $values6){
            foreach($hasil_rating_user2 as $key6 => $value6){
                if($keys6 === $value6['namapk']){
                    $value6['nilai_prediksi'] = $values6;
                    $array_terfilter[] = $value6;
                }
            }
        }
    }

    //print_r($hasil_rata2_rating);
    //print_r($array_terfilter);

    //memasukan nilai rating rata-rata produk ke array array_terfilter untuk bahan perhitungan MAE
    foreach(array_keys($array_terfilter) as $keys7){
        foreach(array_keys($hasil_rata2_rating) as $key7){
            if($array_terfilter[$keys7]['namapk'] === $hasil_rata2_rating[$key7]->namapk){
                $array_terfilter[$keys7]['rating_real'] = $hasil_rata2_rating[$key7]->rating_real;
            }
            // else{
            //     $array_terfilter[$keys7]['stopping'] = $key7;
            //     $array_terfilter[$keys7]['rating_real'] = 0;
            // }
        }
    }

    //print_r($array_terfilter);
    //mulai perhitungan MAE pada array_terfilter
    $sums = 0;

    foreach($array_terfilter as $keys8 => $values8){
        $sums += abs($values8['nilai_prediksi'] - $values8['rating_real']);
    }

    $MAE = $sums / count($array_terfilter);
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
            <nav class="navbar navbar-expand-lg navbar-light bg-light">l
                <div class="container-fluid">
                    <a class="navbar-brand fs-6" href="#">
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
                                <li><a class="dropdown-item" href="pengaturan.php">Pengaturan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="utils/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="row justify-content-start w-100 min-vh-100 mx-auto mt-3 mb-5">
                <?php
                    while($hasil = $sql->fetch(PDO::FETCH_ASSOC)){
                        echo '
                            <div class="col-md-4">
                                <div class="mx-1 my-1 position-relative cursor-pointer" style="background-color: rgba(0,0,0,0.5); aspect-ratio: 1/1; width: 85%; height: 85%;">
                                    <a href="kategori.php?kategori='.$hasil['nama_kategori'].'">
                                        <img src="admin/assets/images/produk/'.$hasil['foto'].'" class="w-100 h-100 position-absolute object-fill brightness-75 d-block mx-auto" alt="" />
                                        <p class="position-absolute top-50 start-50 translate-middle fs-2 text-light text-break fw-bold">'.$hasil['nama_kategori'].'</p>
                                    </a>    
                                </div>
                            </div>
                        ';
                    }
                ?>
            </div>
 
            <!-- Produk di rekomendasikan -->
            <?php 
                if($hasil_rating_user){
            ?>
            <div class="mx-auto mt-3 mb-5">
            <h4 class="text-center">Produk yang direkomendasi untuk anda</h4>
            <p class="text-center fw-bolder">MAE : <?php echo $MAE; ?></p>
            <div class="row justify-content-start w-100 min-vh-100">
                <?php
                    foreach(array_slice($array_terfilter,0,5) as $keys => $values){
                        echo '
                            <div class="col-md-4">
                                <div class="mx-1 my-1 pb-1 position-relative cursor-pointer rounded shadow-sm" style="background-color: white; aspect-ratio: 1/1;">
                                    <a href="/kategori/produk.php?id='.$values['pkid'].'" class="text-decoration-none">
                                        <img src="admin/assets/images/produk/'.$values['foto'].'" class="w-75 h-75 position-relative object-fill d-block mx-auto">
                                        <div class="p-3">
                                            <p class="text-dark fs-3 text-break fw-bold whitespace-nowrap overflow-hidden text-ellipsis mb-0">'.$values['namapk'].'</p>
                                            <p class="text-dark fs-6 mb-0">'.rupiah($values['harga']).'</p>
                                            <p class="text-dark fs-6 mb-0">Nilai Prediksi : '.$values['nilai_prediksi'].'</p>
                                            <p class="text-dark fs-6 mb-0">Rating : '.$values['rating_real'].'</p>    
                                        </div>        
                                        <p class="text-center text-dark mt-3 fs-6">Lihat Produk</p> 
                                    </a>    
                                </div>      
                            </div>            
                        ';
                    }
                ?>
            </div>
            <?php
                }
            ?>
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