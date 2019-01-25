<?php


include('../db_connect/db_connect.php');
include('../function.php');

$user_num = $_POST['user_num'];  //쿠폰을 등록하는 사용자 고유 번호
$prefix = $_POST['prefix'];  //입력한 식별자 3자리


try{
    //중복된 prefix인지 확인
    $prefix_check_stmt = $pdo_db -> prepare('SELECT count(coupon_seq) FROM public.coupon WHERE coupon_prefix = :coupon_prefix;');
    $prefix_check_stmt -> bindParam(':coupon_prefix', $prefix);

    if($prefix_check_stmt -> execute()){
        $prefix_check_row = $prefix_check_stmt -> fetch();

        if($prefix_check_row['count'] > 1) {  //중복된 prefix의 경우

            $msg = 'used_prefix';
            echo json_encode(array('result' => 'failed', 'msg' => $msg));

        } else {   //사용가능한 prefix -> 쿠폰 생성 시작
            $coupon_list = array();  //쿠폰을 담을 배열

            while(count($coupon_list) < 100000){
                $code = coupon_generator($prefix, 13);  //코드 생성

                $used_random = rand(1, 10); //1부터 10까지의 난수 생성

                if($used_random == 1) { //10%의 확률로 false 값 부여 -> true 값은 사용한 쿠폰으로 가정
                    $coupon_list[$code] = true;
                } else {
                    $coupon_list[$code] = false;  //배열은 중복된 키의 값은 반환하지 않음 -> count가 중복된 키 제외 100000개가 될 때까지 무한 생성
                }
            }

            $insert_query = "INSERT INTO public.coupon(coupon_prefix, coupon_code, coupon_publisher, coupon_published_date, used_bool, used_user, used_date ) VALUES ";
            $first = true;
            foreach ($coupon_list as $code => $value){
                $timestamp = Date('Y-m-d H:i:s');

                //query문에 ,를 삽입해야 함
                if($first){
                    $first = false;
                } else {
                    $insert_query .= ', ';
                }

                if(!$value){  //사용 안한 쿠폰의 경우
                    $insert_query .= '(\''.$prefix.'\', \''.$code.'\', \''.$user_num.'\', \''.$timestamp.'\', false, NULL, NULL)';
                } else {
                    $random_date = randomDate('2019-01-01 00:00:00', '2019-01-24 00:00:00');
                    $used_random = mt_rand(1, 10); //1부터 10까지의 난수 생성

                    if($used_random == 1) { //10% * 30%의 확률로 true 값 부여 -> true 값은 사용한 쿠폰으로 가정
                        $insert_query .= '(\''.$prefix.'\', \''.$code.'\', \''.$user_num.'\', \''.$timestamp.'\', true, 1, \''.$random_date.'\')';
                    } else if($used_random == 2) {
                        $insert_query .= '(\''.$prefix.'\', \''.$code.'\', \''.$user_num.'\', \''.$timestamp.'\', true, 2, \''.$random_date.'\')';
                    } else if($used_random == 3) {
                        $insert_query .= '(\''.$prefix.'\', \''.$code.'\', \''.$user_num.'\', \''.$timestamp.'\', true, 3, \''.$random_date.'\')';
                    } else {
                        $insert_query .= '(\''.$prefix.'\', \''.$code.'\', \''.$user_num.'\', \''.$timestamp.'\', false, NULL, NULL)';
                    }
                }
            }
            $insert_query .= ';';

            $stmt = $pdo_db -> prepare($insert_query);

            if($stmt -> execute()){
                echo json_encode(array('result' => 'success'));
            } else {
                $msg = 'insert_fail';
                echo json_encode(array('result' => 'failed', 'msg'=>$msg));
            }
        }
    }

} catch(Exception $e){
    $e -> getMessage();
}

$pdo_db = null;

?>