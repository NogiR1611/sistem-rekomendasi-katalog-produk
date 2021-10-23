<?php
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
    
    function _group_by($array, $key){
        $return = array();
        
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }
    
    function similarity($a, $b){
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

    function cari_nilai_rating($v, $rating_pengguna){
        foreach($rating_pengguna as $keys6){
            if($keys6['namapk'] === $v){
                return $keys6['ratingvalue'];
            }
        }
    }

    function rupiah($rupiah){
        $hasil_rupiah = "Rp " . number_format($rupiah,2,',','.');
        return $hasil_rupiah;
    }
?>