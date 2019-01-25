<?php

require_once './head.php';

if(isset($_SESSION)){
    $user_num = $_SESSION['user_num'];
    $user_permission = $_SESSION['user_permission'];
}

//관리자 계정이 아니면 이전 페이지로 돌아가도록
if(!isset($user_num)){
    back_caution('잘못된 접근입니다.');
}
?>



    <body>
    <div class="col-md-12">
        <h1> Coupon Code CHECK</h1>
        <div class="col-md-12">
            <div class="col-md-12" style="margin-top: 2%;">
                <div class="col-md-12" style="align-items: center;">
                    <div class="col-md-3">
                        <span style="font-size: 1.2em;">code</span>
                    </div>
                    <div class="col-md-3">
                        <span style="font-size: 1.2em;">code는 '-'를 영문과 숫자의 조합 16자리를 입력해주세요</span>
                    </div>
                    <div class="col-md-2">
                        <div class="single-input">
                            <input type="text" class="form-control" maxlength="16" id="input_code" placeholder="****************">
                        </div>
                    </div>
                </div>
                <br><br>
                <br><br>
                <div class="col-md-12" style="margin-bottom: 2%;">
                    <a role="button" id="btn_check_code" class="btn btn-success btn-xs">CHECK</a>
                </div>
            </div>
        </div>
    </div>

<?php

require_once './footer.php';

?>