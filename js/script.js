

//로그인 버튼 클릭 이벤트
$('#btn_signin').on('click', function(){

    var user_id = $('#input_user_id').val();
    var user_password = $('#input_user_pw').val();

    if(user_id ==''){
        alert('ID를 입력해 주세요');
        $('#input_user_id').focus();
    } else if(user_password == ''){
        alert('비밀번호를 입력해 주세요');
        $('#input_user_pw').focus();
    } else {
        $.post('./api/user_signin.php',
            {
                user_id : user_id,
                user_password : user_password,
            },
            function(response, status){
                // console.log(response);
                var json_response = JSON.parse(response);

                if(json_response.result == 'success') {  //로그인 성공
                    if(json_response.user_permission){  //관리자인 경우
                        location.replace('./coupon_publish.php');  //쿠폰 발행 페이지로 이동
                    } else {  //일반 계정인 경우
                        location.replace('./coupon_check.php');  //쿠폰 확인 페이지로 이동
                    }
                } else if(json_response.result == 'fail') {  //로그인 실패
                    if(json_response.msg == 'not_exist_id'){  //ID가 틀린경우
                        alert('존재하지 않는 ID입니다.');
                        $('#input_user_id').val('');
                        $('#input_user_pw').val('');
                        $('#input_user_id').focus();
                    } else if(json_response.msg == 'pw_wrong'){  //비밀번호가 틀린경우
                        alert('비밀번호가 일치하지 않습니다.');
                        $('#input_user_pw').val('');
                        $('#input_user_pw').focus();
                    }
                }
            });
    }
});

//이메일이나 비밀번호 input에 포커스 되어있을 경우 엔터키 입력시 로그인 진행
if($('#input_user_id').focus() || $('#input_user_pw').focus()){
    $('#input_user_id').keydown(function(key){
        if(key.keyCode == 13){//키가 13이면 실행 (엔터는 13)
            $('#btn_signin').click();
        }
    });

    $('#input_user_pw').keydown(function(key){
        if(key.keyCode == 13){//키가 13이면 실행 (엔터는 13)
            $('#btn_signin').click();
        }
    });
}


//쿠폰 생성 버튼 클릭 이벤트
$('#btn_generate_coupon').on('click', function () {
    var prefix = $('#input_prefix').val();  //prefix 3자리
    var user_num = $('#user_num').val();  //쿠폰 생성한 사용자의 고유 번호

    var prefix_regex = /^[A-Za-z0-9+]*$/;  //영문 숫자만 가능한 정규식

    if(!prefix_regex.test(prefix)){
        alert('prefix는 영문 및 숫자만 사용할 수 있습니다.');
    } else if(prefix.length < 3){
        alert('prefix는 3자리입니다.');
    } else {
        alert('쿠폰을 생성 중입니다. 잠시 기다려주세요.');
        $.post('./api/insert_coupon.php',
            {
                user_num : user_num,
                prefix: prefix,
            },
            function(response, status){
                // console.log(response);
                var json_response = JSON.parse(response);

                if(json_response.result == 'success'){
                    alert('쿠폰 생성 성공');
                } else {
                    if(json_response.msg == 'used_prefix'){
                        alert('사용할 수 없는 prefix입니다.');
                    } else {
                        alert('쿠폰 생성 실패');
                    }
                }
            });
    }
});

//쿠폰 목록 페이지로 이동
$('#btn_coupon_list').on('click', function(){
    location.href = './coupon_list.php';
});

//쿠폰 코드 통계 페이지로 이동
$('#btn_coupon_stat').on('click', function(){
    location.href = './coupon_stat.php';
});

//쿠폰 그룹 선택
$('#select_coupon_group').on('change', function(){

    var coupon_prefix = $(this).val();

    //선택된 옵션에 따라 get parameter 전달
    if(coupon_prefix == 'hole'){
        location.href = './coupon_list.php';
    } else {
        location.href = './coupon_list.php?group='+coupon_prefix;
    }

});

//쿠폰 사용 확인 체크 버튼 클릭 이벤트
$('#btn_check_code').on('click', function(){

    var input_code = $('#input_code').val();  //사용자가 입력한 코드

    var prefix_regex = /^[A-Za-z0-9+]*$/;  //영문 숫자만 가능한 정규식

    if(input_code.length < 16){
        alert('코드 16자리를 모두 입력해 주세요');
    } else if(!prefix_regex.test(input_code)){
        alert('영문과 숫자를 제외한 문자는 사용할 수 없습니다.');
    } else {
        $.post('./api/get_coupon_check.php',
            {
                code : input_code
            },
            function(response, status){
                // console.log(response);
                var json_response = JSON.parse(response);

                if(json_response.result == 'success'){
                    if(json_response.msg == 'not_exist'){
                        alert('존재하지 않는 쿠폰번호 입니다.');
                    } else if(json_response.msg == 'used') {
                        alert('이미 사용한 쿠폰입니다.');
                    } else if(json_response.msg == 'usable') {
                        alert('사용가능한 쿠폰입니다.');
                    }
                } else {
                    alert('알 수 없는 오류로 확인할 수 없습니다.');
                }
            });
    }

});