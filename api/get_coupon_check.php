<?php

//쿠폰 사용 여부 확인

include('../db_connect/db_connect.php');

$input_code = $_POST['code'];


//소문자로 입력받은 쿠폰을 형식에 맞게 변환
$input_code = strtoupper($input_code);
$input_code = substr_replace($input_code, '-', 4, 0);
$input_code = substr_replace($input_code, '-', 9, 0);
$input_code = substr_replace($input_code, '-', 14, 0);

try{

    $check_code_stmt = $pdo_db -> prepare('SELECT used_bool FROM coupon WHERE coupon_code = :coupon_code');
    $check_code_stmt -> bindParam(':coupon_code', $input_code);

    if($check_code_stmt -> execute()){
        if($check_code_stmt -> rowCount() < 1){
            $msg = 'not_exist';  //존재하지 않는 쿠폰
        } else {
            $row = $check_code_stmt -> fetch();

            if($row['used_bool']){  //사용한 경우
                $msg = 'used';
            } else {  //미사용
                $msg = 'usable';
            }
        }
        echo json_encode(array('result' => 'success', 'msg' =>$msg));
    } else{
        echo json_encode(array('result' => 'fail', 'msg' => 'error'));
    }

} catch(Exception $e){
    $e -> getMessage();
}

$pdo_db = null;
?>