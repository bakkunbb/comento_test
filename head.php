<?php
//세션은 항상 head 에서 유지
header('content-type: text/html; charset=utf-8');
ini_set('display_errors', 'Off'); // 디버깅을 할때 필요함 On 디버그 모드 Off 아님
session_start();

include('./function.php');
?>

<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Coupon Code Generator</title>

    <!-- 부트스트랩 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
