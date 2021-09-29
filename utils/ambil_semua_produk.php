<?php

    include '../config/config.php';

    $ambil_data_kategori = "SELECT foto,kategori FROM produk_kulit GROUP BY kategori";

    $sql = $db->prepare($ambil_data_kategori);

    $sql->execute();

    $hasil = $sql->fetchAll(PDO::FETCH_ASSOC);

    die(json_encode($hasil));
?>