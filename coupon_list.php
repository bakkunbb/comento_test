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

//페이징을 위해 get parameter로 페이지 정보를 받음
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {  //설정되어있지 않을때는 1페이지
    $page = 1;
}

//그룹별로 검색했을 경우 검색하고자 한 그룹 정보 받아옴
if(isset($_GET['group'])){
    $group_prefix = $_GET['group']; //get parameter의 group = 쿠폰 앞 3자리 고유 식별자를 의미
}


//검색조건에 맞는 총 쿠폰의 수를 계산
require_once './api/get_coupon_count.php';

$count = $coupon_cnt['cnt']; //검색조건을 충족하는 게시글 수

$coupon_count_one_page = 100;  //한 페이지에 보여줄 게시글 수

$coupon_count_all_page = ceil($count / $coupon_count_one_page); //전체 페이지 수

if ($page < 1 || ($coupon_count_all_page && $page > $coupon_count_all_page)) {
    back_caution('존재하지 않는 페이지 입니다.');
}

$coupon_count_one_section = 10; //한번에 선택할 수 있는 페이지 수 = section
$coupon_count_current_section = ceil($page / $coupon_count_one_section); //현재 section
$coupon_count_all_section = ceil($coupon_count_all_page / $coupon_count_one_section); //전체 section의 수

$coupon_count_first_page = ($coupon_count_current_section * $coupon_count_one_section) - ($coupon_count_one_section - 1); //현재 section의 첫 페이지

if ($coupon_count_current_section == $coupon_count_all_section) {  //현재 section이 마지막 section일 경우
    $last_page = $coupon_count_all_page;  //현재 section의 마지막 page는 전체의 마지막 페이지
} else {  //현재 section이 마지막 section이 아닐 경우
    $last_page = $coupon_count_current_section * $coupon_count_one_section;  //현재 section의 마지막 page는 (현재 section의 순서 * 한 section에 보여지는 페이지 수(10))
}

$prev_page = (($coupon_count_current_section - 1) * $coupon_count_one_section); //이전 페이지
$next_page = (($coupon_count_current_section + 1) * $coupon_count_one_section) - ($coupon_count_one_section - 1); //다음 페이지
$final_page = $coupon_count_all_page; //마지막 페이지


//검색 조건에 맞게 100개씩 쿠폰의 정보를 가져옴
require_once './api/get_coupon_list.php';
$coupon_list_array = json_decode($coupon_list_json);
$count_array = count($coupon_list_array);

//검색을 위한 쿠폰의 group 정보를 가져옴
require_once './api/get_coupon_group.php';
$coupon_group_array = json_decode($coupon_group_json);

?>

<div class="col-md-6">
    <table class="table">
        <thead>
        <tr>
            <th scope="col" style="text-align:center; vertical-align: middle;">번호</th>
            <th scope="col" style="text-align:center; vertical-align: middle;">쿠폰코드</th>
            <th scope="col" style="text-align:center; vertical-align: middle;">코드사용일시</th>
            <th scope="col" style="text-align:center; vertical-align: middle;">코드사용회원</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //게시글이 없거나 검색결과가 없음
        if($count == 0) {
            echo '<tr>';
            echo '<td colspan=6 style="text-align:center; vertical-align: middle;"><h2>생성된 쿠폰이 없습니다.</h2></td>';
            echo '</tr>';
        } else {
            for ($i = 0; $i < $count_array; $i++) {
                $coupon_code = $coupon_list_array[$i]->coupon_code;  //쿠폰 코드
                $used_bool = $coupon_list_array[$i]->used_bool;  //쿠폰 사용 여부
                if($used_bool){  //쿠폰이 사용 되었을 경우 사용 날짜와 사용자 정보를 보여줘야 함
                    $coupon_used_date = $coupon_list_array[$i]->used_date;  //쿠폰 사용 일시
                    $coupon_user = $coupon_list_array[$i]->used_user_id;  //쿠폰 사용자
                }

                $number = (($page-1) * $coupon_count_one_page + $i)+1;  //고유 번호가 아니라 목록 내에서의 임의로 부여하는 번호

                echo '<tr>';
                echo '<td style="text-align:center; vertical-align: middle;">'.$number.'</td>';
                echo '<td style="cursor:pointer">'.$coupon_code.'</td>';
                if($used_bool){
                    echo '<td style="text-align:center; vertical-align: middle;">'.$coupon_used_date.'</td>';
                    echo '<td style="text-align:center; vertical-align: middle;">'.$coupon_user.'</td>';
                } else {
                    echo '<td style="text-align:center; vertical-align: middle;">-</td>';
                    echo '<td style="text-align:center; vertical-align: middle;">-</td>';
                }
                echo '</tr>';
            }
        }
        ?>
        </tbody>
    </table>
</div>

<div class="col-md-10 col-md-offset-1" style="margin-bottom: 2%;">
    <div>
        <div class="col-md-4">
            <div class="single-input mt-0">
                <select class="form-control" name="bil-country" id="select_coupon_group">
                    <?php
                    echo '<option value="select">쿠폰 GRUOP 선택</option>';
                    echo '<option value="hole">전체보기</opiton>';
                    for($j = 0; $j < count($coupon_group_array); $j ++) {

                        //선택할 수 있는 옵션(GROUP 구분)
                        //GROUP은 쿠폰 생성 날짜와 생성된 고정 prefix로 구별
                        //Y-m-d 생성 / 식별자 : prefix

                        $date = explode(' ', $coupon_group_array[$j]->coupon_published_date);
                        $group_string = $date[0].' 생성 / 식별자 : '.$coupon_group_array[$j]->coupon_prefix;

                        echo '<option value="'.$coupon_group_array[$j]->coupon_prefix.'">'.$group_string.'</opiton>';

                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>

<?php
if ($count != 0) {
    ?>
    <div class="col-md-12">
        <ul class="htc__pagenation">
            <?php
            //첫번째 섹션이 아닐 경우 처음페이지나 이전 섹션으로 이동할 수 있어야 함
            if ($coupon_count_current_section != 1) {

                if(isset($group_prefix)){ //그룹별 검색 결과
                    echo '<li><a href="./coupon_list.php?group='.$group_prefix.'">처음 페이지</a></li>';
                    echo '<li><a href="./coupon_list.php?group='.$group_prefix.'&page='.$prev_page.'">이전 페이지</a></li>';
                } else {  //검색 X
                    echo '<li><a href="./coupon_list.php">처음 페이지</a></li>';
                    echo '<li><a href="./coupon_list.php?page='.$prev_page.'">이전 페이지</a></li>';
                }
            }

            for ($paging = $coupon_count_first_page; $paging <= $last_page; $paging++) {
                if ($paging == $page) {
                    echo '<li class="active"><a>' . $paging . '</a></li>';
                } else {
                    if(isset($group_prefix)){  //검색 O
                        echo '<li><a href="./coupon_list.php?group='.$group_prefix.'&page='.$paging.'">'.$paging.'</a></li>';
                    } else { //검색 X
                        echo '<li><a href="./coupon_list.php?page='.$paging.'">'.$paging.'</a></li>';
                    }
                }
            }

            //마지막 섹션이 아닐 경우 마지막 페이지나 다음 섹션으로 이동할 수 있어야 함
            if ($coupon_count_current_section != $coupon_count_all_section) {
                if(isset($group_prefix)){ //그룹별 검색 결과
                    echo '<li><a href="./coupon_list.php?group='.$group_prefix.'&page='.$next_page.'">다음 페이지</a></li>';
                    echo '<li><a href="./coupon_list.php?group='.$group_prefix.'&page='.$final_page.'">마지막 페이지</a></li>';
                } else {  //검색 X
                    echo '<li><a href="./coupon_list.php?page='.$next_page.'">다음 페이지</a></li>';
                    echo '<li><a href="./coupon_list.php?page='.$final_page.'">마지막 페이지</a></li>';
                }
            }
            ?>
        </ul>
    </div>
    <?php
}

require_once './footer.php';

?>
