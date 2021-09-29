<?php
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "sistem_rekomendasi_katalog_online";

    try{
        //membuat koneksi PDO
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user , $db_pass);
    }
    catch(PDOException $e){
        //melihat error
        die("terjadi masalah: " - $e->getMessage());
    }
?>