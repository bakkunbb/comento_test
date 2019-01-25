<?php

//검색에 필요한 쿠폰 그룹 분류
//그룹명은 생성날짜 + prefix 필요

include('./db_connect/db_connect.php');

try{
    $group_rows = array();

    $get_coupon_group_stmt = $pdo_db -> prepare('SELECT coupon_prefix, coupon_published_date FROM public.coupon GROUP BY coupon_prefix, coupon_published_date');

    $get_coupon_group_stmt -> execute();
    while($group_row = $get_coupon_group_stmt->fetch()){
        $JSONres = array(
            "coupon_prefix" => $group_row['coupon_prefix'],
            "coupon_published_date" => $group_row['coupon_published_date'],
        );
        array_push($group_rows, $JSONres);
    }

    $coupon_group_json = json_encode($group_rows);
//    }

} catch(Exception $e){
    $e -> getMessage();
}

$pdo_db = null;
?>