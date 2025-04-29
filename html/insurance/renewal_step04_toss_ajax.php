<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
include $_SERVER["DOCUMENT_ROOT"]."/_config/Mobile_Detect.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.coupon.php";

function generateOrderId() {
    $timestamp = time() * 1000; // 밀리초 단위 타임스탬프 (자바스크립트의 getTime()과 유사)
    $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6); // 랜덤 영숫자 6자리
    return "TOSS_{$timestamp}_{$random}";
}

// 주문 ID 생성
$order_id = generateOrderId();

// POST 데이터
$data = $_POST;

// 배열 데이터를 JSON으로 변환
$companions = [];
if (isset($data['add_gender']) && is_array($data['add_gender'])) {
    for ($i = 0; $i < count($data['add_gender']); $i++) {
        $companions[] = [
            'gender' => $data['add_gender'][$i],
            'birth' => $data['add_birth'][$i],
            'user_name' => $data['add_user_name'][$i],
            'rnumber' => $data['add_rnumber'][$i],
            'en_secur' => $data['add_en_secur'][$i],
            'en_name' => $data['add_en_name'][$i]
        ];
    }
}
$companions_json = addslashes(json_encode($companions));
$pr_notice_json = isset($_POST['pr_notice']) ? addslashes(json_encode($_POST['pr_notice'])) : '[]';
$pr_notice_a_json = isset($_POST['pr_notice_a']) ? addslashes(json_encode($_POST['pr_notice_a'])) : '[]';
if ($_POST['gopaymethod'] == 'undefined' || empty($_POST['gopaymethod'])) {
    $_POST['gopaymethod'] = 'Card'; // 기본값으로 'Card' 설정
}
// 데이터 이스케이프
$PR_SEQ = addslashes($_POST['PR_SEQ']);
$plan_seq = addslashes($_POST['plan_seq']);
$s_date = addslashes($_POST['s_date']);
$s_date_time = addslashes($_POST['s_date_time']);
$e_date = addslashes($_POST['e_date']);
$e_date_time = addslashes($_POST['e_date_time']);
$gender = addslashes($_POST['gender']);
$birth = addslashes($_POST['birth']);
$user_name = addslashes($_POST['user_name']);
$en_name = addslashes($_POST['en_name']);
$user_rnumber = addslashes($_POST['user_rnumber']);
$user_hp = addslashes($_POST['user_hp']);
$email = addslashes($_POST['email']);
$email2 = addslashes($_POST['email2']);
$c_name = addslashes($_POST['c_name']);
$c_code = addslashes($_POST['c_code']);
$en_secur = addslashes($_POST['en_secur']);
$purpose = addslashes($_POST['purpose']);
$t_amt = intval($_POST['t_amt']);
$service_amt = intval($_POST['service_amt']);
$select_add_people = intval($_POST['select_add_people']);
$recommend_cd = addslashes($_POST['recommend_cd']);
$cp_cd = addslashes($_POST['cp_cd']);
$is_abroad_resident = addslashes($_POST['is_abroad_resident']);
$join_ch = addslashes($_POST['join_ch']);
$depth0 = addslashes($_POST['depth0']);
$depth1 = addslashes($_POST['depth1']);
$depth2 = addslashes($_POST['depth2']);
$depth3 = addslashes($_POST['depth3']);
$gopaymethod = addslashes($_POST['gopaymethod']);
// SQL 쿼리 작성
$SQL= "INSERT INTO tbl_toss_temp_orders (
            order_id,
            PR_SEQ,
            plan_seq,
            s_date,
            s_date_time,
            e_date,
            e_date_time,
            gender,
            birth,
            user_name,
            en_name,
            user_rnumber,
            user_hp,
            email,
            email2,
            c_name,
            c_code,
            en_secur,
            purpose,
            t_amt,
            service_amt,
            select_add_people,
            recommend_cd,
            cp_cd,
            is_abroad_resident,
            join_ch,
            depth0,
            depth1,
            depth2,
            depth3,
            companions,
            pr_notice,
            pr_notice_a,
            gopaymethod
        ) VALUES (
            '$order_id',
            '$PR_SEQ',
            '$plan_seq',
            '$s_date',
            '$s_date_time',
            '$e_date',
            '$e_date_time',
            '$gender',
            '$birth',
            '$user_name',
            '$en_name',
            '$user_rnumber',
            '$user_hp',
            '$email',
            '$email2',
            '$c_name',
            '$c_code',
            '$en_secur',
            '$purpose',
            $t_amt,
            $service_amt,
            $select_add_people,
            '$recommend_cd',
            '$cp_cd',
            '$is_abroad_resident',
            '$join_ch',
            '$depth0',
            '$depth1',
            '$depth2',
            '$depth3',
            '$companions_json',
            '$pr_notice_json',
            '$pr_notice_a_json',
            '$gopaymethod'
        )";

$result = mysql_query_exe($SQL, $dbcon->dbcon);



if ($result) {
    // 성공 응답

	$SQL_PLAN = "select * from tbl_board_plan where seq=".$plan_seq." and s_date<='".$s_date."' and e_date>='".$s_date."' and plan_status='Y' AND secret='Y' ";
	//echo $SQL_PLAN;
	$result_plan = $dbcon -> query($SQL_PLAN);
	$row_plan = $dbcon -> fetch_array($result_plan);
	
	$product_name = $row_plan["ins_plan_name"];
	if($row_plan["chk_service"] == "A" || $row_plan["chk_service"] == "B") {
		$product_name .= " ".$Arr_txt_plus[$row_plan["chk_service"]];
	}

	$response = [
        'success' => true,
        'order_id' => $order_id,
        'amount' => $t_amt,
		'order_name'=>$product_name,
        'message' => '데이터가 성공적으로 저장되었습니다.'
    ];
} else {
    // 실패 응답 - 상세 에러 정보 추가
    $db_error = mysqli_error($dbcon->dbcon);
    $response = [
        'success' => false,
        'error' => $db_error ? $db_error : '알 수 없는 오류',
        'sql' => $SQL, // 디버깅 목적으로 SQL 쿼리 포함 (보안상 주의)
        'message' => '데이터 저장 중 오류가 발생했습니다.'
    ];
}

echo json_encode($response); ?>

