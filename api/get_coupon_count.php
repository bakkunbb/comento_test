<?php
//검색결과에 맞게 보여줘야할 쿠폰의 갯수를 query

include('./db_connect/db_connect.php');

try{

    if(isset($group_prefix)){  //그룹별 갯수
        $get_coupon_count_stmt = $pdo_db -> prepare('SELECT count(*) as cnt FROM public.coupon WHERE coupon_prefix = :coupon_prefix');
        $get_coupon_count_stmt -> bindParam(':coupon_prefix', $group_prefix);
        $get_coupon_count_stmt -> execute();
        $coupon_cnt = $get_coupon_count_stmt-> fetch();
    } else {  //전체 갯수
        $get_coupon_count_stmt = $pdo_db -> prepare('SELECT count(*) as cnt FROM public.coupon');
        $get_coupon_count_stmt -> execute();
        $coupon_cnt = $get_coupon_count_stmt-> fetch();
    }

} catch(Exception $e){
    $e -> getMessage();
}

$pdo_db = null;
?>