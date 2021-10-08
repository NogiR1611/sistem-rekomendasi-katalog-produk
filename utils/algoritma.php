<?php

    include '../config/config.php';

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

    //proses penyajian data
    function _group_by($array, $key){
        $return = array();
        
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    function normalisasi_data($arr, $start, $length){

        //array hasil skenario akan dipotong - potong berdasarkan nama user
        $potongArr = array_slice($arr, $start, $length);

        $jumlah = 0;

        //menghitung jumlah total rating yang ada di tiap array
        for($i=0; $i<$length; $i++){
            $jumlah += $potongArr[$i]['ratingvalue'];
        }

        //menghitung jumlah user yang menginput rating tiap array
        $total = 0;

        for($j=0; $j<count($potongArr); $j++){
            if($potongArr[$j]['ratingvalue']){
                $total += 1;
            }
            else{
                $total += 0;
            }
        }

        //merubah nilai rating yang ada dengan dikurangi nilai rata - rata di tiap array
        for($x=0; $x<$length; $x++){
            if($potongArr[$x]['ratingvalue']){
                $potongArr[$x]['ratingvalue'] = number_format($potongArr[$x]['ratingvalue'] - ($jumlah/$total),1,'.','');
            }
            else{
                $potongArr[$x]['ratingvalue'] = null;
            }
        }

        return $potongArr;
    }

    $x = 0;
    $array_normalisasi_data = array();

    for($i=0; $i<count($hasil_skenario_rating); $i++){
        if($i===0 || $hasil_skenario_rating[$i]['nama'] !== $hasil_skenario_rating[$i === 0 ? 0 : $i-1]['nama']){
            $x = $i;
            $array_normalisasi_data = array_merge($array_normalisasi_data, normalisasi_data($hasil_skenario_rating, $x, count(_group_by($hasil_skenario_rating,'nama')[$hasil_skenario_rating[$i]['nama']])));   
        }
    }
    
    $nama_pengguna = _group_by($array_normalisasi_data, 'nama')['username5'];
    $list_produk_similarity = _group_by($array_normalisasi_data,'namapk');

    for($j=0; $j<count($nama_pengguna); $j++){
        if(!$nama_pengguna[$j]['ratingvalue']){
            $produk_rating_kosong[] = $nama_pengguna[$j]['namapk'];
        }
    }

    function similarity($a,$b){
        $ratingA = is_string($a['ratingvalue']) ? floatval(trim($a['ratingvalue'])) : null;
        $ratingB = is_string($b['ratingvalue']) ? floatval(trim($b['ratingvalue'])) : null;
        
        return $ratingA * $ratingB;
    }

    function jumlah_kuadrat_bilangan($arr){
        $total = 0;
        
        foreach($arr as $keys => $values){
            $total += pow($values['ratingvalue'],2);
        }   

        return sqrt($total);
    }

    $urutan_produk_similarity = array();

    $list_produk = array_keys($list_produk_similarity);

    //proses penentuan pasangan similarity antara produk belum terisi rating dengan semua produk
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

    //proses perhitungan similarity antara produk belum terisi rating dengan semua produk
    $x = array();

    foreach($urutan_produk_similarity as $key3 => $values3){

        foreach($values3 as $item){
            //echo ''.$key3.''.$item.'<br/>';
            $x = _group_by($array_normalisasi_data,'namapk')[$key3];
            $y = _group_by($array_normalisasi_data,'namapk')[$item];

            $perkalian_antar_produk = array_map('similarity',$x,$y);
            $jumlah_antar_produk = array_sum($perkalian_antar_produk);
            $akar_produk_x = jumlah_kuadrat_bilangan($x);
            $akar_produk_y = jumlah_kuadrat_bilangan($y);
            $total_similarity = ($jumlah_antar_produk / $akar_produk_x * $akar_produk_y);
            $kumpulan_similarity[$key3][$item] = number_format($total_similarity, 2, '.', '');
        }
    }   
    
    //perhitungan prediksi dengan menggunakan metode weight sum
    

    //$grup_namapk_1 = _group_by($array_normalisasi_data,'namapk')['Sampel Dompet 1'];
    //$grup_namapk_2 = _group_by($array_normalisasi_data,'namapk')['Sampel Hand bag 1'];

    print_r($kumpulan_similarity);
    //print_r(jumlah_kuadrat_bilangan($grup_namapk_1));
    //print_r(array_map('similarity',$grup_namapk_1, $grup_namapk_2,));
        
?>