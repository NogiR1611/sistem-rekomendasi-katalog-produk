<?php
    include '../config/config.php';

    if(!isset($_POST['cari'])){
        $cari = "%".filtered_input($_POST['pencarian'])."%";

        $ambil_data_pencarian = "SELECT * from produk_kulit where namapk like :cari";
    
        $pencarian = $db->prepare($ambil_data_pencarian);

        $pencarian->bindParam(":cari",$cari);

        $pencarian->execute();
    }

    function filtered_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>