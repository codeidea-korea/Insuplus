<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
include $_SERVER["DOCUMENT_ROOT"]."/_config/Mobile_Detect.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.coupon.php";

$log = new log();

$detect = new Mobile_Detect;

$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];

//현시점 약관 번호 검색
$rule_site_cd = selRuleSeq("사이트 이용약관");
$rule_group_cd = selRuleSeq("단체보험 규약");
$rule_privacy_cd = selRuleSeq("개인정보 수집 및 이용 동의");

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

	$gender			= $_POST["gender"];								// 가입자 성별
	$birth			= $_POST["birth"];								// 가입자 생일
	$user_name		= all_seed_enc(trim($_POST["user_name"]));		// 가입자 이름
	$user_rnumber	= all_seed_enc(trim($_POST["user_rnumber"]));	// 가입자 주민등록번호
	$user_hp		= all_seed_enc(trim($_POST["user_hp"]));		// 가입자 연락처
	$email			= all_seed_enc(trim($_POST["email"]));			// 가입자 이메일 앞
	$email2			= all_seed_enc(trim($_POST["email2"]));			// 가입자 이메일 뒤
	$purpose		= $_POST["purpose"];							// 여행타입
	$c_name			= $_POST["c_name"];								// 여행국 이름
	$c_code			= $_POST["c_code"];								// 여행국 코드
	$en_secur		= $_POST["en_secur"];							// 영문여권 여부
	$en_name		= all_seed_enc(trim($_POST["en_name"]));		// 영문여권 이름
	$cp_cd			= $_POST["cp_cd"];								// 쿠폰코드
	$chk_p 			= print_pr_info($PR_SEQ,"ext1");							// 여행타입코드
	$is_abroad_resident = $_POST["is_abroad_resident"];	//해외거주 여부
	$join_ch 		= $_POST["join_ch"];	//제휴사 코드

	if ($chk_p=="Y"){$period_gubun = "일";}else{$period_gubun = "달";}

	// 플랜검색
	$plan_view  = selPlanView($plan_seq, $t_s_date, $t_e_date, $chk_p);
	$ins_file_cd = $plan_view["ins_term1_seq"];
	$service_file_cd = is_null($plan_view["service_cd"]) ? 0 : $plan_view["service_cd"];

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
			$arr_add_name[] = all_seed_enc(trim($_POST["add_user_name"][$k]));			// 동반인 이름
			$arr_add_rnumber[] = all_seed_enc(trim($_POST["add_rnumber"][$k]));			// 동반인 주민등록번호
			$arr_add_en_secur[] = $_POST["add_en_secur"][$k];							// 영문여권 여부
			$arr_add_en_name[] = all_seed_enc(trim($_POST["add_en_name"][$k]));		// 영문여권 이름
			}
		}
	}
}else{
//	echo "<script>alert('일정이 없습니다.');</script>";
	exit;
}

//상품검색
$SQL_ins = "select seq,ext4,ext5 from tbl_board_product where seq='".$PR_SEQ."' ";
$RS_ins = $dbcon -> query($SQL_ins);
$PR_row = $dbcon -> fetch_array($RS_ins);

// 플랜검색
$SQL_PLAN = "select * from tbl_board_plan where seq=".$plan_seq." and s_date<='".$s_date."' and e_date>='".$s_date."' and plan_status='Y' AND secret='Y' ";
//echo $SQL_PLAN;
$result_plan = $dbcon -> query($SQL_PLAN);
$row_plan = $dbcon -> fetch_array($result_plan);

// 여행비용 검색
$user_age = fn_ins_age($birth);																		// 가입자 나이
$user_amt = fn_sel_ins_amt($period,$chk_p,$plan_seq,$user_age,$gender);		// 가입자 여행비용
$user_ins_plan_cd = fn_sel_ins_plan_cd($period,$chk_p,$plan_seq,$user_age,$gender);		// 가입자 실제 보험사 플랜코드
$t_amt = 0;
$t_add_user_amt = 0;
$t_add_user_service_amt = 0;
for($k=0;$k<5;$k++){
	if ($arr_add_birth[$k]!=""){
	$add_user_age = fn_ins_age($arr_add_birth[$k]);
	$add_user_amt[$k] = fn_sel_ins_amt($period,$chk_p,$plan_seq,$add_user_age,$arr_add_gender[$k]);		// 동행자 여행비용
	$add_user_ins_plan_cd[$k] = fn_sel_ins_plan_cd($period,$chk_p,$plan_seq,$add_user_age,$arr_add_gender[$k]);		// 동행자 실제 보험사 플랜코드
	$add_user_service_amt[$k] = fn_ins_service_amt($plan_seq, $plan_view["ext3"], $period_month, $period_day, $period, $chk_p, $add_user_age, $arr_add_gender[$k]);
	$t_add_user_service_amt = $t_add_user_service_amt + $add_user_service_amt[$k];
	$t_amt = $t_amt + $add_user_amt[$k];		// 동반자 여행보험비용
	$t_add_user_amt = $t_add_user_amt + $add_user_amt[$k];
	}
}
$t_amt = $t_amt + $user_amt;							// 가입자 여행보험비용



// 여행 서비스 비용
//$service_amt = fn_ins_service_amt($plan_seq, $row_plan["chk_service"], $period_month, $period_day);
$service_amt = fn_ins_service_amt($plan_seq, $row_plan["ext3"], $period_month, $period_day, $period, $chk_p, $user_age, $gender);
$service_amt = $service_amt > 0 ? $service_amt : 0;
//echo $service_amt." 서비스금액<br>";
// $t_service_amt = ($service_amt * (1+count($add_user_amt)));
$t_service_amt = $t_add_user_service_amt + $service_amt;
//echo $t_service_amt." 서비스비용 총액<br>";
// 보험비용
$t_ins_amt = $user_amt + $t_add_user_amt;

//총 상품가에서 할인을 적용한다.
$total_amount = $t_amt+$t_service_amt;

//쿠폰 검색
$sale_gubun = ""; //할인방법
$s_amt_per = 0;
$s_amount = 0;
if ($cp_cd){ //쿠폰
	$sale_gubun = "C";
	// $SQL_CP = " SELECT A.subject,B.temp_discount FROM tbl_board_event A INNER JOIN tbl_board_coupon_history B ";
	// $SQL_CP .= " ON A.seq=B.event_seq where B.seq='".$cp_cd."' ";
	// $result_cp = $dbcon -> query($SQL_CP);
	// $row_cp = $dbcon -> fetch_array($result_cp);

	$discount = $row_cp["temp_discount"];

	$cp_discount_info = fn_calculate_coupon_discount($cp_cd, $t_ins_amt, $t_service_amt, $total_amount);

	$s_amount = $cp_discount_info["totalDiscount"];				// 총 할인금액
	$s_amt_per = $cp_discount_info["s_amt_per"];								// 총 할인율

}else if($recommend_cd) { //추천코드
	$sale_gubun = "R";
	$SQL_RECOMMEND  = " SELECT seq, discount FROM tbl_board_recommend_code WHERE seq = '".$recommend_cd."' ";
	$result_recommend = $dbcon -> query($SQL_RECOMMEND);
	$row_recommend = $dbcon -> fetch_array($result_recommend);

	$discount = $row_recommend["discount"];
	$s_amt_temp = $total_amount/100*$discount;
	$s_amt_temp = floor($s_amt_temp);

	if ($s_amt_temp<=30000){ 	//할인폭이 3만원 이상인경우 3만원까지만 할인되도록
		$s_amount = $s_amt_temp;
		$s_amt_per = $discount;
	}else{
		$s_amount = 30000;
		$s_amt_per = (float)30000*100/$total_amount; //재계산 들어감
	}
}

// 할인금액 처리
//echo $s_amt_temp." 임시할인금액<br>";
//echo $s_amount." 할인금액<br>";
//echo $t_select_add_people." 사람수<br>";
$cp_amt = $s_amount;	// 총 할인금액
$t_service_temp_amt = $t_service_amt - $s_amount;		// 과세대상 결제총액 = 서비스료 - 할인금액
$value_of_supply = round($t_service_temp_amt / 1.1);	//공급가액 (서비스 과세금액)  과세대상 결제총액 / 1.1
//VAT
$amt_vat = $t_service_temp_amt - $value_of_supply; 		//부가세 = 과세대상 결제총액 - 공급가액

$log->log_write("amt1=".$amt_vat);

if($t_service_temp_amt < 0) { //서비스 금액보다 할인금액이 큰 경우
	$amt_vat = 0;
}

$log->log_write("amt2=".$amt_vat);
$log->log_write("t_service_temp_amt=".$t_service_temp_amt);


//$usr_s_amount = floor($s_amount/($t_select_add_people));			// 가입자 할인금액
$usr_s_amount = floor(($user_amt+$service_amt)/100*$s_amt_per);			// 가입자 할인금액
$usr_vat_amount = floor($amt_vat/($t_select_add_people));			// 개별 VAT, 소수점 버림
$usr_t_amount =	$user_amt + $service_amt - $usr_s_amount;	// 가입자 결제금액


//총괄 서비스 비용
// $t_amt = $t_amt + ($service_amt * (1+count($add_user_amt))) - $s_amount;
$t_amt = $t_amt + $t_service_amt - $s_amount;

?>
<?php
//======================================================================================================
// 이니시스 기본 세팅 시작
//======================================================================================================
require_once('./libs/INIStdPayUtil.php');
$SignatureUtil = new INIStdPayUtil();
/*
  //*** 위변조 방지체크를 signature 생성 ***

  oid, price, timestamp 3개의 키와 값을

  key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값

  ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004


 * key기준 알파벳 정렬

 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그대로 사용하여야함
 */

//############################################
// 1.전문 필드 값 설정(***가맹점 개발수정***)
//############################################
// 여기에 설정된 값은 Form 필드에 동일한 값으로 설정
//인증
$mid = MID;  // 가맹점 ID
$signKey = SIGNKEY; // 가맹점에 제공된 웹 표준 사인키


$timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성

$orderNumber = "P_" .date("YmdHis").$SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)
$price = $t_amt;        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)
$log->log_write("price=".$price);
$log->log_write("user_rnumber=" . $user_rnumber);

$cardNoInterestQuota = "11-2:3:,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4";  // 카드 무이자 여부 설정(가맹점에서 직접 설정)
$cardQuotaBase = "2:3:4:5:6:11:12:24:36";  // 가맹점에서 사용할 할부 개월수 설정
//###################################
// 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
//###################################
$mKey = $SignatureUtil->makeHash($signKey, "sha256");

$params = array(
    "oid" => $orderNumber,
    "price" => $price,
    "timestamp" => $timestamp
);
$sign = $SignatureUtil->makeSignature($params, "sha256");

/* 기타 */
//$siteDomain = "http://localhost:200/admin/cardTest/INIStdPaySample"; //로컬 테스트  가맹점 도메인 입력\
$domain = getDomain();
$siteDomain = $domain.$add_port."/html/insurance/libs"; //로컬 테스트  가맹점 도메인 입력
// 페이지 URL에서 고정된 부분을 적는다.
// Ex) returnURL이 http://localhost:8082/demo/INIpayStdSample/INIStdPayReturn.jsp 라면
//                 http://localhost:8082/demo/INIpayStdSample 까지만 기입한다.

//======================================================================================================
// 이니시스 기본세팅 종료
//======================================================================================================
?>




<?
// 데이터 입력
//데이터 입력 - 주문
$select_add_people = $select_add_people +1;		//가입자 본인 추가
$SQL1 = "insert into tbl_order_listTemp set";
$SQL1 .= " orderno = '".$orderNumber."' ";
$SQL1 .= " , pr_name = '".print_pr_name($PR_SEQ)."' ";
$SQL1 .= " , pr_cd = '".$PR_SEQ."' ";
//$SQL1 .= " , ins_name = '".print_ins($row_plan["ins_cd"])."' ";
//$SQL1 .= " , ins_cd = '".$row_plan["ins_cd"]."' ";
$SQL1 .= " , ins_name = '신규가입' ";
$SQL1 .= " , ins_cd = 0 "; //개편이후 신규 가입자 구분용으로 0을 입력 20230926
//$SQL1 .= " , plan_name = '".$Arr_plan_cd[$row_plan["plan_cd"]]."' ";
$SQL1 .= " , plan_name = '".$row_plan["ins_plan_name"]." - ".$Arr_plan_cd[$row_plan["plan_cd"]]."' ";
$SQL1 .= " , plan_cd = '".($plan_seq > 0 ? $plan_seq : 0)."' ";
$SQL1 .= " , agree_cd = '".($PR_row["ext4"] > 0 ? $PR_row["ext4"] : 0)."' ";
$SQL1 .= " , service_name = '".print_insu_service($PR_row["ext5"])."' ";
$SQL1 .= " , service_cd = '".($PR_row["ext5"] > 0 ? $PR_row["ext5"] : 0)."' ";
$SQL1 .= " , rule_site_cd = '".$rule_site_cd."' ";
$SQL1 .= " , rule_group_cd = '".$rule_group_cd."' ";
$SQL1 .= " , rule_privacy_cd = '".$rule_privacy_cd."' ";
$SQL1 .= " , ins_file_cd = '".$ins_file_cd."' ";
$SQL1 .= " , service_file_cd = '".$service_file_cd."' ";
$SQL1 .= " , s_date = '".$s_date."' ";
$SQL1 .= " , s_date_time = '".$s_date_time."' ";
$SQL1 .= " , e_date = '".$e_date."' ";
$SQL1 .= " , e_date_time = '".$e_date_time."' ";
$SQL1 .= " , ins_period = '".$period."' ";
$SQL1 .= " , chk_p = '".$chk_p."' ";
$SQL1 .= " , chk_service = '".$row_plan["chk_service"]."' ";
$SQL1 .= " , o_name = '".$user_name."' ";
$SQL1 .= " , o_email1 = '".$email."' ";
$SQL1 .= " , o_email2 = '".$email2."' ";
$SQL1 .= " , join_cnt = '".$select_add_people."' ";
$join_ch_val = strlen($_SESSION["ss_partner_seq"]) > 0 ? $_SESSION["ss_partner_seq"] : $join_ch;
$SQL1 .= " , join_ch = '".$join_ch_val."' ";
$SQL1 .= " , purpose = '".$purpose."' ";
$SQL1 .= " , join_nation_cd = '".$c_code."' ";
$SQL1 .= " , join_nation_name = '".$c_name."' ";
$SQL1 .= " , sale_gubun = '".$sale_gubun."' ";
$SQL1 .= " , sale_discount = '".$s_amt_per."' ";
$SQL1 .= " , new_cp_cd = '".$cp_cd."' ";
$SQL1 .= " , recommend_cd = '".($recommend_cd > 0 ? $recommend_cd : 0)."' ";
$SQL1 .= " , ins_amount = '".($t_ins_amt > 0 ? $t_ins_amt : 0)."' ";
$SQL1 .= " , service_amount = '".($t_service_amt> 0 ? $t_service_amt : 0)."' ";
$SQL1 .= " , s_amount = '".($s_amount > 0 ? $s_amount : 0)."' ";
$SQL1 .= " , vat_amount = '".($amt_vat > 0 ? $amt_vat : 0)."' ";
$SQL1 .= " , t_amount = '".($t_amt > 0 ? $t_amt : 0)."' ";
$SQL1 .= " , cancle_amount = '0' ";
$SQL1 .= " , writedate = now() ";
// echo $SQL1."<br>";
$RS1 = $dbcon -> query($SQL1);

//데이터 입력 - 가입자
$SQL2 = "insert into tbl_order_list_joinTemp set";
$SQL2 .= " orderno = '".$orderNumber."' ";
$SQL2 .= " , gender = '".$gender."' ";
$SQL2 .= " , chk_join = 'N' ";
$SQL2 .= " , chk_eng_passport = '".$en_secur."' ";
$SQL2 .= " , o_name = '".$user_name."' ";
$SQL2 .= " , o_phone = '".$user_hp."' ";
$SQL2 .= " , o_isdn1 = '".all_seed_enc($birth)."' ";
$SQL2 .= " , o_isdn2 = '".$user_rnumber."' ";
$SQL2 .= " , o_name_en = '".$en_name."' "; 
$SQL2 .= " , ins_plan_cd = '".$user_ins_plan_cd."' ";
$SQL2 .= " , join_amount = '".$user_amt."' ";
$SQL2 .= " , join_service = '".$service_amt."' ";
$SQL2 .= " , vat_amount = '".$usr_vat_amount."' ";
$SQL2 .= " , s_amount = '".$usr_s_amount."' ";
$SQL2 .= " , t_amount = '".$usr_t_amount."' ";
$SQL2 .= " , is_abroad_resident = '".$is_abroad_resident."' "; //해외거주 여부 동반인은 사용안함
$SQL2 .= " , regdate = now() ";
//echo $SQL2."<br>";
$RS2 = $dbcon -> query($SQL2);

//데이터 입력 - 동반자
if ($select_add_people >0){
	for($k=0;$k<5;$k++){
		if ($_POST["add_gender"][$k] && $_POST["add_birth"][$k]){
			$usr_s_amount = ($add_user_amt[$k] + $add_user_service_amt[$k])/100*$s_amt_per;
			$usr_t_amount =	$add_user_amt[$k] + $add_user_service_amt[$k] - $usr_s_amount;	// 가입자 결제금액
			$SQL2 = "insert into tbl_order_list_joinTemp set";
			$SQL2 .= " orderno = '".$orderNumber."' ";
			$SQL2 .= " , gender = '".$arr_add_gender[$k]."' ";
			$SQL2 .= " , chk_join = 'Y' ";
			$SQL2 .= " , chk_eng_passport = '".$arr_add_en_secur[$k]."' ";
			$SQL2 .= " , o_name = '".$arr_add_name[$k]."' ";
			$SQL2 .= " , o_phone = '' ";
			$SQL2 .= " , o_isdn1 = '".all_seed_enc($arr_add_birth[$k])."' ";
			$SQL2 .= " , o_isdn2 = '".$arr_add_rnumber[$k]."' ";
			$SQL2 .= " , o_name_en = '".$arr_add_en_name[$k]."' ";
			$SQL2 .= " , ins_plan_cd = '".$add_user_ins_plan_cd[$k]."' ";
			$SQL2 .= " , join_amount = '".$add_user_amt[$k]."' ";
			$SQL2 .= " , join_service = '".$add_user_service_amt[$k]."' ";
			$SQL2 .= " , vat_amount = '".$usr_vat_amount."' ";
			$SQL2 .= " , s_amount = '".$usr_s_amount."' ";
			$SQL2 .= " , t_amount = '".$usr_t_amount."' ";
			$SQL2 .= " , regdate = now() ";
//			echo $SQL2."<br>";
			$RS2 = $dbcon -> query($SQL2);

		}
	}
}

// 동의사항에서 정보 입력
for ($k=0;$k<count($_POST["pr_notice"]);$k++){
	$SQL_N = "insert into tbl_order_list_noticeTemp set ";
	$SQL_N .= " orderno = '".$orderNumber."' ";
	$SQL_N .= " ,pr_notice = '".$_POST["pr_notice"][$k]."' ";
	$SQL_N .= " ,pr_notice_a = '' ";
//	echo $SQL_N."<br>";
	$RS_N = $dbcon -> query($SQL_N);
}

//쿠폰등록
// tbl_order_list_coupon 테이블 결제 구간 말고 사용하는 곳이 없음
if ($cp_cd){
	$SQL_cp = "insert into tbl_order_list_couponTemp set ";
	$SQL_cp .= " orderno = '".$orderNumber."' ";
	$SQL_cp .= " ,coupon_name = '신규쿠폰' ";
	// $SQL_cp .= " ,coupon_seq = '0' ";
	$SQL_cp .= " ,new_cp_cd = '".$cp_cd."' ";
	$SQL_cp .= " ,cp_sale_per = '".$s_amt_per."' ";
	$SQL_cp .= " ,coupon_amount = '".$s_amount."' ";
	$SQL_cp .= " ,writedate = now() ";
//	echo $SQL_cp."<br>";
	$RS_N = $dbcon -> query($SQL_cp);
}

$product_name = $row_plan["ins_plan_name"];
if($row_plan["chk_service"] == "A" || $row_plan["chk_service"] == "B") {
	$product_name .= " ".$Arr_txt_plus[$row_plan["chk_service"]];
}
?>
<? if ( $detect->isMobile() ) { ?>
<form id="SendPayForm_id" name="SendPayForm_id" method="POST" accept-charset="EUC-KR">
<input type="hidden" name="inipaymobile_type" value="web">
<input type="hidden" name="P_MID" 							value="<?=$mid ?>">
<input type="hidden" name="P_OID" 		id="textfield2" 	value="<?=$orderNumber?>"/><!-- 주문번호 -->
<input type="hidden" name="P_GOODS"   	id="textfield3"     value="<?=$product_name;?>"  /><!-- 상품명 -->
<input type="hidden" name="P_AMT"     	id="textfield4"     value="<?=$price?>"  /><!-- 가격 -->
<input type="hidden" name="P_TAX"     	id="textfield4"     value="<?=$amt_vat?>"  /><!-- 부가세 -->
<input type="hidden" name="P_TAXFREE"   id="textfield4"     value="<?=$user_amt?>"  /><!-- 면세금액 -->
<input type="hidden" name="P_UNAME"   	id="textfield5"     value="<?=all_seed_dec($user_name)?>"  /><!-- 구매자명 -->
<input type="hidden" name="P_MNAME"   	id="textfield6"     value="insuplus"  /><!-- 상점이름 -->
<input type="hidden" name="P_MOBILE"  	id="textfield7"     value="<?=all_seed_dec($user_hp)?>"  /><!-- 휴대폰번호 -->
<input type="hidden" name="P_EMAIL"   	id="textfield8" 	value="<?=all_seed_dec($email)?>@<?=all_seed_dec($email2)?>"  /><!-- 이메일 -->
<input type="hidden" name="paymethod" 	id="textfield8" 	value="<?=$gopaymethod?>"  /><!-- 결제방법 -->
<input type="hidden" name="P_RESERVED" value="twotrs_isp=Y&block_isp=Y&twotrs_isp_noti=N&merc_noint=N&bank_receipt=N&vbank_receipt=N">
<input type="hidden" name="P_NEXT_URL" 						value="<?=$siteDomain?>/mobileINIStdPayReturn.php">
<input type="hidden" name="P_NOTI_URL" 						value="<?=$siteDomain?>/mx_rnoti.php">
<input type="hidden" name="P_RETURN_URL" 					value="<?=$domain.$add_port?>/html/insurance/renewal_step05.php" />
<input type="hidden" name="P_HPP_METHOD" 				value="2">
<input type="hidden" name="P_VBANK_DT" value="<?= date("Ymd")?>">
<input type="hidden" name="P_VBANK_TM" value="2359">
<!-- <input type="hidden" name="P_VBANK_DT" value="<?//date("Ymd", strtotime("+1 day"))?>"> -->
<input type="hidden" name="P_CHARSET" value="utf8">
</form>
<? } else {?>
<!-- 이니시스 표준결제 js -->
<? if(SERVER_CHECK == "DEV") {?>
<script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } else if(SERVER_CHECK == "REAL") {?>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } ?>

<!-- <button onclick="inipay()" style="padding:10px" id="inipay_btn">결제요청</button> -->

<form id="SendPayForm_id" name="" method="POST">
<input type="hidden" name="version" value="1.0" >
<input type="hidden" name="mid" value="<?php echo $mid ?>" >
<input type="hidden" name="goodname" value="<?=$product_name?>" >
<input type="hidden" name="oid" value="<?php echo $orderNumber ?>" >
<input type="hidden" name="price" value="<?php echo $price ?>" >
<input type="hidden" name="tax" value="<?php echo $amt_vat ?>" >
<input type="hidden" name="taxfree" value="<?php echo $user_amt ?>" >
<input type="hidden" name="currency" value="WON" >
<input type="hidden" name="buyername" value="<?=all_seed_dec($user_name)?>" >
<input type="hidden" name="buyertel" value="<?=all_seed_dec($user_hp)?>" >
<input type="hidden" name="buyeremail" value="<?=all_seed_dec($email)?>@<?=all_seed_dec($email2)?>" >
<input type="hidden" name="timestamp" value="<?php echo $timestamp ?>" >
<input type="hidden" name="signature" value="<?php echo $sign ?>" >
<input type="hidden" name="returnUrl" value="<?php echo $siteDomain ?>/INIStdPayReturn.php" >
<input type="hidden" name="mKey" value="<?php echo $mKey ?>" >
<input type="hidden" name="gopaymethod" value="<?=$gopaymethod?>" >
<!-- <b>offerPeriod</b> : 제공기간
<br/>ex)20150101-20150331, [Y2:년단위결제, M2:월단위결제, yyyyMMdd-yyyyMMdd : 시작일-종료일] -->
<input type="hidden" name="offerPeriod" value="2015010120150331" >
<!-- <br/><b>acceptmethod</b> : acceptmethod
<br/>acceptmethod  ex) CARDPOINT:SLIMQUOTA(코드-개월:개월):no_receipt:va_receipt:vbanknoreg(0):vbank(20150425):va_ckprice:vbanknoreg:
<br/>KWPY_TYPE(0):KWPY_VAT(10|0) 기타 옵션 정보 및 설명은 연동정의보 참조 구분자 ":" -->
<input type="hidden" name="acceptmethod" value="HPP(2):no_receipt:va_receipt:vbanknoreg(0):vbank(<?=date("Ymd")?>):below1000" >
<!-- <br/><b>languageView</b> : 초기 표시 언어
<br/>[ko|en] (default:ko) -->
<input type="hidden" name="languageView" value="" >
<!-- <br/><b>charset</b> : 리턴 인코딩
<br/>[UTF-8|EUC-KR] (default:UTF-8) -->
<input type="hidden" name="charset" value="" >
<!-- <br/><b>payViewType</b> : 결제창 표시방법
<br/>[overlay] (default:overlay) -->
<input type="hidden" name="payViewType" value="" >
<!-- <br/><b>closeUrl</b> : payViewType='overlay','popup'시 취소버튼 클릭시 창닥기 처리 URL(가맹점에 맞게 설정)
<br/>close.jsp 샘플사용(생략가능, 미설정시 사용자에 의해 취소 버튼 클릭시 인증결과 페이지로 취소 결과를 보냅니다.) -->
<input type="hidden" name="closeUrl" value="<?php echo $siteDomain ?>/close.php" >
<!-- <br/><b>popupUrl</b> : payViewType='popup'시 팝업을 띄울수 있도록 처리해주는 URL(가맹점에 맞게 설정)
<br/>popup.jsp 샘플사용(생략가능,payViewType='popup'으로 사용시에는 반드시 설정) -->
<input type="hidden" name="popupUrl" value="<?php echo $siteDomain ?>/popup.php" >
<!-- <br/><b>nointerest</b> : 무이자 할부 개월
<br/>ex) 11-2:3:4,04-2:3:4 -->
<input type="hidden" name="nointerest" value="<?php echo $cardNoInterestQuota ?>" >
<!-- <br/><b>quotabase</b> : 할부 개월
<br/>ex) 2:3:4 -->
<input type="hidden" name="quotabase" value="<?php echo $cardQuotaBase ?>" >
<!-- <b>-- 가상계좌 --</b> -->
<!-- <br/><b>INIregno</b> : 주민번호 설정 기능
<br/>13자리(주민번호),10자리(사업자번호),미입력시(화면에서입력가능) -->
<input type="hidden" name="INIregno" value="" >
<!-- <b>***** 추가 옵션 *****</b>
<br/><b>merchantData</b> : 가맹점 관리데이터(1000byte)
<br/>인증결과 리턴시 함께 전달됨 -->
<input type="hidden" name="merchantData" value="" >
</form>
<? } ?>

