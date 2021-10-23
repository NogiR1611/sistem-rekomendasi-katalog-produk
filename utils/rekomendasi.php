<?php

    include 'helper.php';

    //ambil data jumlah produk
    $ambil_data_produk = "SELECT pkid,namapk from produk_kulit";
    
    $data_produk = $db->prepare($ambil_data_produk);

    $data_produk->execute();

    $hasil_data_produk = $data_produk->fetchAll(PDO::FETCH_ASSOC);

    //ambil data jumlah pengguna
    $ambil_data_pengguna = "SELECT distinct nama from user where hak_akses='pengguna'";
    
    $data_pengguna = $db->prepare($ambil_data_pengguna);

    $data_pengguna->execute();

    $hasil_data_pengguna = $data_pengguna->fetchAll(PDO::FETCH_ASSOC);

    //ambil data untuk skenario rating
    $ambil_data_skenario_rating = "SELECT A.userid, A.nama, B.namapk, C.ratingvalue FROM user A CROSS JOIN produk_kulit B LEFT JOIN rating C ON A.userid = C.userid AND C.pkid = B.pkid WHERE A.hak_akses != 'admin' ORDER BY A.userid, B.pkid";

    $data_skenario_rating = $db->prepare($ambil_data_skenario_rating);

    $data_skenario_rating->execute();

    $hasil_skenario_rating = $data_skenario_rating->fetchAll(PDO::FETCH_ASSOC);

    //1. proses normalisasi data pada skenario rating
    $x = 0;
    $array_normalisasi_data = array();
    
    //print_r($hasil_skenario_rating);

    //lakukan pengulangan pada data hasil_skenario_rating untuk menormalisasi data dengan fungsi normalisasi_data dari helper.php
    //kemudian hasil normalisasi akan ditampung di variabel array_normalisasi_data
    for($i=0; $i<count($hasil_skenario_rating); $i++){
        if($i===0 || $hasil_skenario_rating[$i]['nama'] !== $hasil_skenario_rating[$i === 0 ? 0 : $i-1]['nama']){
            $x = $i;
            $array_normalisasi_data = array_merge($array_normalisasi_data, normalisasi_data($hasil_skenario_rating, $x, count(_group_by($hasil_skenario_rating,'nama')[$hasil_skenario_rating[$i]['nama']])));   
        }
    }
    
    //membuat array untuk produk yang belum terisi rating oleh user dengan fungsi _group_by() dari helper.php dengan mengambil key '$_SESSION['nama']'
    //kemudian data akan ditampung ke variabel produk_rating_kosong
    $nama_pengguna = _group_by($array_normalisasi_data, 'nama')[$_SESSION['nama']];
    $list_produk_similarity = _group_by($array_normalisasi_data,'namapk');

    //print_r($nama_pengguna);

    for($j=0; $j<count($nama_pengguna); $j++){
        if(!$nama_pengguna[$j]['ratingvalue']){
            $produk_rating_kosong[] = $nama_pengguna[$j]['namapk'];
        }
    }
    
    //2. proses penentuan pasangan similarity antara produk belum terisi rating dengan semua produk
    $urutan_produk_similarity = array();
    $list_produk = array_keys($list_produk_similarity);

    //produk_rating_kosong yang sudah ditentukan sebelumnya akan dipasangkan dengan semua produk yang ada sebagai (x,y)
    //yang kemudian akan ditentukan similaritynya 
    foreach($produk_rating_kosong as $key1 => $values1){
        foreach($list_produk as $key2 => $values2){
            if($key1 === 0){
                $urutan_produk_similarity[$values1][] = $values2;
            }
            else{
                $y = array_diff($urutan_produk_similarity[$produk_rating_kosong[$key1-1]],[$produk_rating_kosong[$key1-1]]);
                $urutan_produk_similarity[$values1] = array_values($y);
            }
        }
    }

    //lakukan perhitungan similarity(x,y) antara produk belum terisi rating dengan semua produk
    foreach($urutan_produk_similarity as $key3 => $values3){
        foreach($values3 as $item){
            $total_similarity = 0;
            $x = _group_by($array_normalisasi_data,'namapk')[$key3];
            $y = _group_by($array_normalisasi_data,'namapk')[$item];

            $perkalian_antar_produk = array_map('similarity',$x,$y);
            $jumlah_antar_produk = array_sum($perkalian_antar_produk);
            $akar_produk_x = jumlah_kuadrat_bilangan($x);
            $akar_produk_y = jumlah_kuadrat_bilangan($y);
            if($akar_produk_x * $akar_produk_y > 0){
                $total_similarity = ($jumlah_antar_produk / $akar_produk_x * $akar_produk_y);
            }

            $kumpulan_similarity[$key3][$item] = number_format($total_similarity, 2, '.', '');
        }
    }   
        
    //3. perhitungan prediksi dengan menggunakan metode weight sum
    foreach($kumpulan_similarity as $keys4 => &$values4){
        foreach($values4 as $key4 => $value4){
            if($key4 === $keys4){
                unset($values4[$key4]);
            }   
            
            arsort($values4);
        }
            
        $array_tertinggi = array_slice($values4,0,2);
        $kumpulan_similarity[$keys4] = $array_tertinggi;
    }
    
    $rating_pengguna = _group_by($hasil_skenario_rating,'nama')[$_SESSION['nama']];
    $weight_sum = array();

    //proses perhitungan antara nilai similarity(S) dengan rating item(R) pada tiap produk
    foreach($kumpulan_similarity as $keys5 => $values5){
        $jumlah_similarity_rating = 0;
        $jumlah_similarity = 0;
        $weight_sum_per_produk = 0;

        foreach($values5 as $key5 => $value5){
            $rating = cari_nilai_rating($key5, $rating_pengguna);
            $rating = $rating === null ? 0 : $rating;
                    
            $jumlah_similarity_rating += $value5 * $rating;        
            $jumlah_similarity += $value5;

            if($jumlah_similarity > 0){
                $weight_sum_per_produk = ($jumlah_similarity_rating / $jumlah_similarity);
            }
        }

        $weight_sum[$keys5] = number_format($weight_sum_per_produk,2,'.','');
        
        arsort($weight_sum);
    }
?>