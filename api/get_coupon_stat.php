<?php

//쿠폰 사용 통계 query
//그룹별로 총 몇개의 쿠폰이 사용되었는지 count

include('./db_connect/db_connect.php');

try{
    $stat_rows = array();

    $get_coupon_stat_stmt = $pdo_db -> prepare('SELECT coupon_prefix, coupon_published_date, count(*)
                                                    FROM public.coupon
                                                    WHERE used_bool = true
                                                    GROUP BY coupon_prefix, coupon_published_date');

    $get_coupon_stat_stmt -> execute();
    while($stat_row = $get_coupon_stat_stmt->fetch()){
        $JSONres = array(
            "coupon_prefix" => $stat_row['coupon_prefix'],
            "coupon_published_date" => $stat_row['coupon_published_date'],
            "used_count" => $stat_row['count']
        );
        array_push($stat_rows, $JSONres);
    }

    $coupon_stat_json = json_encode($stat_rows);
//    }

} catch(Exception $e){
    $e -> getMessage();
}

$pdo_db = null;
?>