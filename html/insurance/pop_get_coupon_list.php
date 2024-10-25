<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가

include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가
$user_hp = all_seed_enc(trim($_GET["usr_cd"]));	// 회원전화번호

header("Content-Type: application/json");
if (!$user_hp){
	echo(json_encode(array("success"=>"-1","msg"=>"등록된 쿠폰이 없습니다.")));
	exit;
}
$today = date("Y-m-d");
$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];

$depth0 = REQSTR($_POST['depth0'], "");
$depth1 = REQSTR($_POST['depth1'], "");
$depth2 = REQSTR($_POST['depth2'], "");
$depth3 = REQSTR($_POST['depth3'], "");
//변수 처리
if ($_POST["s_date"] && $_POST["e_date"]){
	$s_date = $_POST["s_date"];					// 기간 시작
	$e_date = $_POST["e_date"];					// 기간 종료
	$s_date_time = $_POST["s_date_time"];		// 기간 시작 시간
	$e_date_time = $_POST["e_date_time"];		// 기간 종료 시간
			
	if($chk_p == "Y") { //단기
		$t_s_date = $s_date." ".$s_date_time;
		$t_e_date = $e_date." ".$e_date_time;
		
		$s_date_text = $t_s_date.":00";
		$e_date_text = $t_e_date.":00";
		
		$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
		$period_day = $arr_period["day"];
		$period = $arr_period["day"];
		$period_month = $arr_period["month"];
		
	} else if($chk_p == "N") { //장기
		$t_s_date = $s_date;
		$t_e_date = $e_date;
		
		$s_date_text = $t_s_date;
		$e_date_text = $t_e_date;
		
		$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
		$period_day = $arr_period["day"];
		$period = $arr_period["month"];
		$period_month = $arr_period["month"];
	}
	$select_add_people = $_POST["select_add_people"];		// 동반인명수
	if (!$select_add_people){$select_add_people = 0;}
	$t_select_add_people = $select_add_people + 1;
}
//쿠폰 검색
$SQL_CP =  " 
SELECT 
	(CASE 
		WHEN B.partner_coupon IS NULL 
		THEN A.subject 
		ELSE A.partner_coupon_name 
		END) AS subject 
	, A.duplicate_status_yn
	, A.insurance_discount_applied
	, A.service_fee_discount_applied
	, A.insurance_discount_rate
	, A.insurance_max_discount_amount
	, A.service_fee_discount_rate
	, A.service_fee_max_discount_amount
	, A.subscription_start_day
	, A.subscription_end_day
	, A.event_category_master_seq
	, A.min_companion
	, A.max_companion
	, B.temp_discount 
	, B.seq
	, B.temp_discount
	, B.start_date
	, B.end_date
	, B.event_seq 
FROM tbl_board_event A 
INNER JOIN tbl_board_coupon_history B on A.seq=B.event_seq  
LEFT JOIN tbl_event_coupon_category C ON A.event_category_master_seq = C.event_category_master_seq
"; 
//결제일 기준 쿠폰조회 추후 쿠폰 전체 조회 후 사용가능한 쿠폰만 선택되도록 변경 예정
$SQL_WHERE .= " 
WHERE B.mobile = '".$user_hp."'
	AND B.use_yn='N' 
	AND B.start_date <='".$today."' 
	AND B.end_date >='".$today."'
	AND (A.subscription_start_day IS NULL OR A.subscription_start_day <= ".$period_day.")
	AND (A.subscription_end_day IS NULL OR A.subscription_end_day >= ".$period_day.")
	AND (A.min_companion IS NULL OR A.min_companion <= ".$select_add_people.")
	AND (A.max_companion IS NULL OR A.max_companion >= ".$select_add_people.")
	AND (C.depth0 IS NULL OR C.depth0 = '".$depth0."')
	AND (C.depth1 IS NULL OR C.depth1 = '".$depth1."')
	AND (C.depth2 IS NULL OR C.depth2 = '".$depth2."')
	AND (C.depth3 IS NULL OR C.depth3 = '".$depth3."')
";

if($_SESSION["ss_partner_seq"]){ //제휴사 쿠폰 조회
	$SQL_WHERE .= "and A.event_partnership_code in (SELECT partnership_code FROM tbl_board_partner WHERE seq in ('01', '".$_SESSION["ss_partner_seq"]."')
				AND start_Partner_period <= '".$today."' AND end_Partner_period >= '".$today."' ORDER BY seq ASC)";
} else { //제휴사 쿠폰 제외
	$SQL_WHERE .= "and A.event_partnership_code in (SELECT partnership_code FROM tbl_board_partner WHERE seq in ('01')
				AND start_Partner_period <= '".$today."' AND end_Partner_period >= '".$today."' ORDER BY seq ASC)";
}
$result_cp = $dbcon -> query($SQL_CP.$SQL_WHERE);
if($result_cp) {
	$list = array();
	while($row_cp = $dbcon -> fetch_array($result_cp)){
		$list[] = $row_cp;
	}
	echo(json_encode(
			array(
				"success"=>"1"
				,"list"=>$list
			)
		)
	);
	exit;
} else {
	echo(json_encode(array("success"=>"-1","msg"=>"등록된 쿠폰이 없습니다.")));
	exit;
}
?>