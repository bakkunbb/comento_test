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



//검색을 위한 쿠폰의 group 정보를 가져옴
require_once './api/get_coupon_stat.php';
$coupon_stat_array = json_decode($coupon_stat_json);
$count_array = count($coupon_stat_array);

?>

<div class="col-md-6">
    <table class="table">
        <thead>
        <tr>
            <th scope="col" style="text-align:center; vertical-align: middle;">그룹</th>
            <th scope="col" style="text-align:center; vertical-align: middle;">사용인원</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //게시글이 없거나 검색결과가 없음
        if($count_array == 0) {
            echo '<tr>';
            echo '<td colspan=6 style="text-align:center; vertical-align: middle;"><h2>생성된 쿠폰이 없습니다.</h2></td>';
            echo '</tr>';
        } else {
            for ($i = 0; $i < $count_array; $i++) {

                //선택할 수 있는 옵션(GROUP 구분)
                //GROUP은 쿠폰 생성 날짜와 생성된 고정 prefix로 구별
                //Y-m-d 생성 / 식별자 : prefix

                $date = explode(' ', $coupon_stat_array[$i]->coupon_published_date);
                $group_string = $date[0].' 생성 / 식별자 : '.$coupon_stat_array[$i]->coupon_prefix;
                $used_count = $coupon_stat_array[$i]->used_count;

                echo '<tr>';
                echo '<td style="text-align:center; vertical-align: middle;">'.$group_string.'</td>';
                echo '<td style="cursor:pointer">'.$used_count.'</td>';
                echo '</tr>';
            }
        }
        ?>
        </tbody>
    </table>
</div>




<?php

require_once './footer.php';

?>
