<?php
//검색결과에 맞는 쿠폰 정보를 query
//쿠폰 고유번호, prefix, 쿠폰 코드, 쿠폰 생성 날짜, 사용여부, 사용한 회원 고유 번호, 사용한 회원 ID(JOIN 사용), 사용한 날짜 return

include('./db_connect/db_connect.php');

try{

    //전체 결과 가져올 필요 없이 한 페이지에 보여줘야할 갯수만큼만 query
    $limit = ($coupon_count_one_page * $page) - $coupon_count_one_page;
    $rows = array();

    if(isset($group_prefix)){  //그룹별 검색
        $get_coupon_list_stmt = $pdo_db -> prepare('SELECT public.coupon.* , public.user.user_id
                                                      FROM public.coupon
                                                      LEFT JOIN public.user
                                                      ON public.user.user_seq = public.coupon.used_user
                                                      WHERE coupon_prefix = :coupon_prefix
                                                      ORDER BY public.coupon.coupon_seq ASC
                                                      LIMIT '.$coupon_count_one_page.' OFFSET '.$limit.';');
        $get_coupon_list_stmt -> bindParam(':coupon_prefix', $group_prefix);
        $get_coupon_list_stmt -> execute();

    } else {  //검색 X 결과

        $get_coupon_list_stmt = $pdo_db -> prepare('SELECT public.coupon.* , public.user.user_id
                                                      FROM public.coupon
                                                      LEFT JOIN public.user
                                                      ON public.user.user_seq = public.coupon.used_user
                                                      ORDER BY public.coupon.coupon_seq ASC
                                                      LIMIT '.$coupon_count_one_page.' OFFSET '.$limit.';');
        $get_coupon_list_stmt -> execute();

    }

    while($row = $get_coupon_list_stmt->fetch()){

        $JSONres = array(
            "coupon_seq" => $row['coupon_seq'],
            "coupon_prefix" => $row['coupon_prefix'],
            "coupon_code" => $row['coupon_code'],
            "coupon_published_date" => $row['coupon_published_date'],
            "used_bool" => $row['used_bool'],
            "used_user_seq" => $row['used_user'],
            "used_user_id" => $row['user_id'],
            "used_date" => $row['used_date'],
        );
        array_push($rows, $JSONres);
    }

    $coupon_list_json = json_encode($rows);

}catch (Exception $e) {
    $e -> getMessage();
}

$pdo_db = null;

?>