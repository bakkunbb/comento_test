<?php

require_once './head.php';

if(isset($_SESSION)){
    $user_num = $_SESSION['user_num'];
    $user_permission = $_SESSION['user_permission'];
}

//관리자 계정이 아니면 이전 페이지로 돌아가도록
if(!$user_permission){
    back_caution('잘못된 접근입니다.');
}

echo '<input id="user_num" style="display:none;" value="'.$_SESSION['user_num'].'">';  //쿠폰 생성 시 계정 고유번호를 javascript에 넘겨주기 위함
?>



    <body>
    <div class="col-md-12">
        <h1> Coupon Code Generator</h1>
        <div class="col-md-12">
            <div class="col-md-12" style="margin-top: 2%;">
                <div class="col-md-12" style="align-items: center;">
                    <div class="col-md-3">
                        <span style="font-size: 1.2em;">prefix</span>
                    </div>
                    <div class="col-md-3">
                        <span style="font-size: 1.2em;">prefix는 영문과 숫자의 조합 3자리를 입력해주세요</span>
                    </div>
                    <div class="col-md-5">
                        <div class="single-input">
                            <input type="text" class="form-control" maxlength="3" id="input_prefix" placeholder="영문 & 숫자 3자리">
                        </div>
                    </div>
                </div>
                <br><br>
                <br><br>
                <div class="col-md-12" style="margin-bottom: 2%;">
                    <a role="button" id="btn_coupon_list" class="btn btn-danger btn-xs">쿠폰 목록</a>
                    <a role="button" id="btn_coupon_stat" class="btn btn-danger btn-xs">쿠폰 사용 통계</a>
                    <a role="button" id="btn_generate_coupon" class="btn btn-primary btn-xs">쿠폰 생성</a>
                </div>
            </div>
        </div>
    </div>

<?php

require_once './footer.php';

?>