<?php
//DB 연동 php 파일
//원래는 root 폴더 바깥에 있어야 함

$conn = "pgsql:host=119.205.235.148;port=5432;dbname=coupon;user=postgres;password=1q2w3e4r!@";

try{
    // create a PostgreSQL database connection
    $pdo_db = new PDO($conn);

}catch (PDOException $e){
    // report error message
    echo $e->getMessage();
}


?>
