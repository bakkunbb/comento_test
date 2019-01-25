<?php
session_start();
//로그인 진행
//입력받은 id, pw로 DB 비교

include('../db_connect/db_connect.php');

$user_id = $_POST['user_id'];
$user_password = $_POST['user_password'];

try{
    $stmt = $pdo_db -> prepare('SELECT * FROM public."user" WHERE user_id = :user_id');
    $stmt -> bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt -> execute();

    if($stmt->rowCount() < 1) {  //id값이 존재하지 않는 경우
        $msg = 'not_exist_id';
        echo json_encode(array('result' => 'fail', 'msg' =>$msg));  //결과값 반환
    } else {  //id 값이 존재하는 경우
        $row = $stmt -> fetch();

        //비밀번호 비교
        if(password_verify($user_password, $row['user_password'])){

            //세션에 사용자 고유 번호와 ID, 계정 정보(관리자 or 일반) 저장
            $_SESSION['user_num'] = $row['user_seq'];  //
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_permission'] = $row['user_permission'];

            echo json_encode(array('result' => 'success', 'user_permission' => $row['user_permission']));  //결과값 반환
        } else {  //비밀번호가 틀린경우
            $msg = 'pw_wrong';
            echo json_encode(array('result' => 'fail', 'msg' =>$msg));  //결과값 반환
        }

    }

} catch(Exception $e){
    $e -> getMessage();
}

$pdo_db = null;
?>