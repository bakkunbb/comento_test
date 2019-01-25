<?php

//함수들 모아놓은 파일


//쿠폰 코드 생성 함수
function coupon_generator($prefix, $length) {

    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789'; // 쿠폰에 들어갈 문자열

    srand((double)microtime()*1000000);

    $i = 0;
    $code = $prefix; //쿠폰은 관리자가 입력한 고정 3자리로 시작

    while ($i < $length) {  //지정된 길이 만큼 반복(여기서는 13번)
        $num = rand() % strlen($chars);  //임의의 난수 설정
        $tmp = substr($chars, $num, 1);  //?번쨰 문자열 추출하여
        $code .= $tmp;  //코드에 추가
        $i++;
    }

    $code = strtoupper($code);  //코드 대문자로 저장

    //4자리마다 '-' 삽입
    $code = substr_replace($code, '-', 4, 0);
    $code = substr_replace($code, '-', 9, 0);
    $code = substr_replace($code, '-', 14, 0);
    return $code;
}

//임의의 날짜 생성
//쿠폰 생성시 임의로 사용된 날짜 채워넣기 위함
//(Y-m-d H:i:s) 형태의 문자열 필요
function randomDate($start_date, $end_date, $format = 'Y-m-d H:i:s') {

    $min_date = strtotime($start_date);  //시작 날짜
    $max_date = strtotime($end_date);  //끝나는 날짜

    $result = mt_rand($min_date, $max_date);  //시작과 끝사이 날짜/시간 임의로 생성
    
    return date($format, $result);
}

// 접근 불가한 페이지 접근시
// 해당 메시지를 띄우고 이전 페이지로 이동
function back_caution($msg){

    $str = '<script>';
    $str .= 'alert(\''.$msg.'\');';
    $str .= 'history.back();';
    $str .= '</script>';
    echo $str;
}
?>