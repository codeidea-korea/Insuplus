<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가

$log = new log();
$log->log_write("reommend");

header("Content-Type: application/json");

$today = date("Y-m-d");
//추천코드인지 확인
$SQL  = " SELECT seq, discount FROM tbl_board_recommend_code WHERE recommendation_code = '".$_GET["recommend_name"]."' ";
$SQL .= " AND start_date <= '".$today."' AND end_date >= '".$today."' ";
$log->log_write("sql:".$SQL);
$RS = $dbcon->query($SQL);
$ROW = $dbcon->fetch_array($RS);

if(!$ROW["seq"]) {
	echo(json_encode(array("success"=>"-1","msg"=>"추천코드와 일치하는 정보가 없습니다.")));
	exit;
}

$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];
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
	
	$gender			= $_POST["gender"];			// 가입자 성별
	$birth			= $_POST["birth"];			// 가입자 생일
	$user_name		= $_POST["user_name"];		// 가입자 이름
	$user_rnumber	= $_POST["user_rnumber"];	// 가입자 주민등록번호
	$user_hp		= $_POST["user_hp"];		// 가입자 연락처
	$email			= $_POST["email"];			// 가입자 이메일 앞
	$email2			= $_POST["email2"];			// 가입자 이메일 뒤
	$ins_gubun		= $_POST["ins_gubun"];		// 여행타입
	$c_name			= $_POST["c_name"];			// 여행국 이름
	$c_code			= $_POST["c_code"];			// 여행국 코드
	$en_secur		= $_POST["en_secur"];		// 영문여권 여부
	$en_name		= $_POST["en_name"];		// 영문여권 이름
	$cp_cd			= $_POST["cp_cd"];			// 쿠폰코드

	//if ($chk_p=="Y"){$period_gubun = "일";}else{$period_gubun = "달";}
	//$period_txt = fn_tr_period($s_date." ".$s_date_time,$e_date." ".$e_date_time,$chk_p);		// 여행 기간
	
	$select_add_people = $_POST["select_add_people"];		// 동반인명수
	if (!$select_add_people){$select_add_people = 0;}
	$t_select_add_people = $select_add_people + 1;
	if ($select_add_people >0){
		$arr_add_gender = array();
		$arr_add_birth = array();
		$arr_add_name = array();
		for($k=0;$k<5;$k++){
			if ($_POST["add_gender"][$k] && $_POST["add_birth"][$k]){
			$arr_add_gender[] = $_POST["add_gender"][$k];									// 동반인 성별
			$arr_add_birth[] = $_POST["add_birth"][$k];											// 동반인 생일
			$arr_add_name[] = $_POST["add_user_name"][$k];			// 동반인 이름
			$arr_add_rnumber[] = $_POST["add_rnumber"][$k];			// 동반인 주민등록번호
			$arr_add_en_secur[] = $_POST["add_en_secur"][$k];							// 영문여권 여부
			$arr_add_en_name[] = $_POST["add_en_name"][$k];		// 영문여권 이름
			}
		}
	}

}else{
//	echo "<script>alert('일정이 없습니다.');</script>";
	exit;
}

// 플랜검색
$plan_view  = selPlanView($plan_seq, $t_s_date, $t_e_date, $chk_p);

// 여행비용 검색
$user_age = fn_ins_age($birth);																		// 가입자 나이
$user_amt = fn_sel_ins_amt($period,$chk_p,$plan_seq,$user_age,$gender);		// 가입자 여행비용
$t_amt = 0;
$t_add_user_amt = 0;
$t_add_user_service_amt = 0;
for($k=0;$k<5;$k++){
	if ($arr_add_birth[$k]!=""){
	$add_user_age = fn_ins_age($arr_add_birth[$k]);
	$add_user_amt[$k] = fn_sel_ins_amt($period,$chk_p,$plan_seq,$add_user_age,$arr_add_gender[$k]);		// 동행자 여행비용
	$add_user_service_amt[$k] = fn_ins_service_amt($plan_seq, $plan_view["ext3"], $period_month, $period_day, $period, $chk_p, $add_user_age, $arr_add_gender[$k]);
	$t_add_user_service_amt = $t_add_user_service_amt + $add_user_service_amt[$k];
	$t_amt = $t_amt + $add_user_amt[$k];		// 동반자 여행보험비용
	$t_add_user_amt = $t_add_user_amt + $add_user_amt[$k];
	}
}
$t_amt = $t_amt + $user_amt;							// 가입자 여행보험비용

// 여행 서비스 비용
$service_amt = fn_ins_service_amt($plan_seq, $plan_view["ext3"], $period_month, $period_day, $period, $chk_p, $user_age, $gender);
//echo $service_amt." 서비스금액<br>";
// $t_service_amt = ($service_amt * (1+count($add_user_amt)));
$t_service_amt = $t_add_user_service_amt + $service_amt;
//echo $t_service_amt." 서비스비용 총액<br>";
// 보험비용
$t_ins_amt = $user_amt + $t_add_user_amt;

//최종합한 상품가격 필요
$total_amount = $t_amt+$t_service_amt;

$s_amt_temp = $total_amount/100*$ROW["discount"];
$s_amt_temp = floor($s_amt_temp); //할인금액 소수점 버린다.


//할인폭이 3만원 이상인경우 3만원까지만 할인되도록
if ($s_amt_temp<=30000){
	$s_amount = $s_amt_temp;
	$s_amt_per = $ROW["discount"];
}else{
	$s_amount = 30000;
	$s_amt_per = 30000*100/$total_amount; //재계산 들어감
}


// 할인금액 처리
//echo $s_amt_temp." 임시할인금액<br>";
//echo $s_amount." 할인금액<br>";
//echo $t_select_add_people." 사람수<br>";
$sale_amt = $s_amount;														// 총 할인금액
$t_service_temp_amt = $t_service_amt - $s_amount;						// 결제금액의 할인금액처리

/*
if($t_service_amt < 0) { //서비스료가 할인금액보다 작은 경우 상품가격에서 뺀다
	$t_amt = $t_amt+$t_service_amt; 
	$t_service_amt = 0;
}
*/
/*
$usr_s_amount = $s_amount / ($t_select_add_people);			// 개별 할인금액
//echo $usr_s_amount." 각할인금액<br>";
$usr_vat_amount = $usr_s_amount * 0.1;								// 개별 VAT
$usr_t_amount =	$user_amt + $service_amt - $usr_s_amount;	// 가입자 결제금액
//VAT
$amt_vat = $t_service_amt * 0.1;
*/

//총괄 서비스 비용
// $t_amt = $t_amt + ($service_amt * (1+count($add_user_amt))) - $s_amount;
$t_amt = $t_amt + $t_service_amt - $s_amount;


echo(json_encode(array("success"=>"1","recommend_cd"=>$ROW["seq"]
		,"s_amt_per_txt" => "할인쿠폰이 적용되었습니다."
		, "s_amt_per" => $s_amt_per
		, "sale_amt" => $sale_amt
		, "t_amount" => $t_amt)));
?>
