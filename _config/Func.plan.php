<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가
$log = new log();
// 여행기간 무조건 개월수계산
function fn_priod_month($sdate,$edate){
	$datetime1 = date_create($sdate);
	$datetime2 = date_create($edate);
	$interval= date_diff($datetime1, $datetime2);
	$m = $interval->format('%m');
	$d = $interval->format('%d');
	if ($d>0){
		$period_dt = $m + 1;
	}else{
		$period_dt = $m;
	}
	return $period_dt;
}

// 여행기간 계산 함수 //배열로 수정
function fn_tr_period($sdate,$edate,$chk_p,$differenceFormat = '%a'){
	//단기여행
	if ($chk_p=="Y"){
		$result = (strtotime($edate.":00") - strtotime($sdate.":00")) / 3600;

		// 결과 값은 소숫점으로 출력되는 경우가 있으므로 정수형(int)으로 캐스팅(형변환)
		// 소숫점 이하 자리 제거는 floor나 number_format($result, false)를 이용하는 것도 가능
		$period_dt = (int) $result;
		$period_dt = ceil($period_dt/24);
	}
	//장기여행 총 12개월
	if ($chk_p=="N"){
		//$datetime1 = date("Y-m-d", strtotime($sdate." -1days"));
		$datetime1 = $sdate;
		$datetime1 = date_create($datetime1);
		$datetime2 = date_create($edate);
		$interval= date_diff($datetime1, $datetime2);
		$m = $interval->format('%m');
		$d = $interval->format('%d');
		if ($d>0){
			$period_dt = $m + 1;
		}else{
			$period_dt = $m;
		}
	}
	return $period_dt;
}

// 여행기간 계산 함수
function getArrPeriod($sdate,$edate,$chk_p,$differenceFormat = '%a'){
	//단기여행
	if ($chk_p=="Y"){
		$result = (strtotime(trim($edate).":00") - strtotime(trim($sdate).":00")) / 3600;
		$period_dt = (int) $result;
		$day = ceil($period_dt/24);
		
		$s_day = substr($sdate,0,10);
		$e_day = substr($edate,0,10);
		$s_hour = substr($sdate,11,2);
		$e_hour = substr($edate,11,2);
		
		if($s_hour < $e_hour) {
			$datetime1 = date("Y-m-d", strtotime($s_day." -1days"));
			$datetime1 = date_create($datetime1);
		} else {
			$datetime1 = date_create($s_day);
		}
		$datetime2 = date_create($e_day);
		
		$interval= date_diff($datetime1, $datetime2);
		$m = $interval->format('%m');
		$d = $interval->format('%d');
		
		if ($d>0){
			$month = $m + 1;
		}else{
			$month = $m;
		}
	}
	//장기여행 총 12개월
	if ($chk_p=="N"){
		//$datetime1 = date("Y-m-d", strtotime($sdate." -1days"));
		//$datetime2 = $edate;
		//$day = (strtotime(date($datetime2,time())) - strtotime(date($datetime1,time()))) / 86400;
		$datetime1 = new DateTime($sdate);
		$datetime2 = new DateTime($edate);
		$interval= date_diff($datetime1, $datetime2);
		$day = $interval->days;

		$y = $interval->format('%y');
		$m = $interval->format('%m');
		$d = $interval->format('%d');

		
		$sDate1 = new DateTime($sdate);
		$eDate1 = new DateTime($edate);

		$month = (($eDate1->format('y') - $sDate1->format('y')) * 12) +
				($eDate1->format('m') - $sDate1->format('m')) +
				(($eDate1->format('d') - $sDate1->format('d') >= 0 ? 1: 0 ));
	}
	
	$data["day"] = $day;
	$data["month"] = $month;
	return $data;
}

// 보험나이 계산
function fn_ins_age($birthday){
	$datetime1 = date_create($birthday);
	$datetime2 = date_create(date("Y-m-d"));


	//$interval= date_diff($datetime1, $datetime2);
	$interval = $datetime1->diff($datetime2);
	$period_dt_y = $interval->format('%y');
	$period_dt_m = $interval->format('%m');
	$period_dt_d = $interval->format('%d');

	
	//if ($period_dt_m>=6 && $period_dt_d>0 ){ //TODO 확인필요
	if ($period_dt_m>=6 ){ 
		$plus_age = 1;
	}else{
		$plus_age = 0;
	}
	
	$ins_age = $period_dt_y + $plus_age;
	return $ins_age;
}

// 보험나이 계산
function fn_ins_age_from_reg_date($birthday, $regDate){
	$datetime1 = date_create($birthday);
	$datetime2 = date_create($regDate);


	//$interval= date_diff($datetime1, $datetime2);
	$interval = $datetime1->diff($datetime2);
	$period_dt_y = $interval->format('%y');
	$period_dt_m = $interval->format('%m');
	$period_dt_d = $interval->format('%d');

	
	//if ($period_dt_m>=6 && $period_dt_d>0 ){ //TODO 확인필요
	if ($period_dt_m>=6 ){ 
		$plus_age = 1;
	}else{
		$plus_age = 0;
	}
	
	$ins_age = $period_dt_y + $plus_age;
	return $ins_age;
}

//단기 가격 구간 설정
function fnShortTermSection($period) {
	if ($period>0 && $period<=2){
		$period_sel = 1;
	}else if ($period>2 && $period<=3){
		$period_sel = 2;
	}else if ($period>3 && $period<=4){
		$period_sel = 3;
	}else if ($period>4 && $period<=5){
		$period_sel = 4;
	}else if ($period>5 && $period<=6){
		$period_sel = 5;
	}else if ($period>6 && $period<=7){
		$period_sel = 6;
	}else if ($period>7 && $period<=10){
		$period_sel = 7;
	}else if ($period>10 && $period<=14){
		$period_sel = 8;
	}else if ($period>14 && $period<=17){
		$period_sel = 9;
	}else if ($period>17 && $period<=21){
		$period_sel = 10;
	}else if ($period>21 && $period<=24){
		$period_sel = 11;
	}else if ($period>24 && $period<=27){
		$period_sel = 12;
	}else if ($period>27 && $period<=30){
		$period_sel = 13;
	}else if ($period>30 && $period<=45){
		$period_sel = 14;
	}else if ($period>45 && $period<=60){
		$period_sel = 15;
	}else if ($period>60 && $period<=90){
		$period_sel = 16;
	}
	
	return $period_sel;
}

//장 단기 기간 보험사 별 사용하는 플랜코드 가져오기
function fn_sel_ins_plan_cd($period,$chk_p,$plan_cd,$age,$gender){
	global $dbcon,$Arr_u_sex;

	//단기여행
	if ($chk_p=="Y"){

		$period_sel = fnShortTermSection($period);

		$SQL_SEL = "select plan_txt from tbl_board_plan_amount1 where plan_cd=".$plan_cd." and gender = '".$Arr_u_sex[$gender]."' and age=".$age." ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_plan_cd = $sel_row[0];
	}

	//장기여행
	if ($chk_p=="N"){
		$SQL_SEL = "select plan_txt from tbl_board_plan_amount1 where plan_cd=".$plan_cd." and gender = '".$Arr_u_sex[$gender]."' and age=".$age." ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_plan_cd = $sel_row[0];
	}
	return $ins_plan_cd;
}

//보험 플랜 가입 가능한 나이 확인
function fn_check_ins_age($plan_cd,$age,$gender){
	global $dbcon,$Arr_u_sex;

	$SQL_SEL = "select age from tbl_board_plan_amount1 where plan_cd=".$plan_cd." and gender = '".$Arr_u_sex[$gender]."' and age=".$age." ";
	$result_sel = $dbcon -> query($SQL_SEL);
	$sel_row = $dbcon -> fetch_row($result_sel);
	$ins_amt = $sel_row[0];
	return $ins_amt;
}

//보험 플랜 가입 가능한 연령대 조회
function fn_sel_ins_age($plan_cd) {
global $dbcon;

	$SQL_SEL = "select min(age) as firstAge, max(age) as lastAge from tbl_board_plan_amount1 where plan_cd=".$plan_cd;
	$result_sel = $dbcon -> query($SQL_SEL);
	$sel_row = $dbcon -> fetch_row($result_sel);
	return $sel_row;
}

//장 단기 기간 가격선택 부분
function fn_sel_ins_amt($period,$chk_p,$plan_cd,$age,$gender){
	global $dbcon,$Arr_u_sex;

	//단기여행
	if ($chk_p=="Y"){
		
		$period_sel = fnShortTermSection($period);
		
		//$SQL_SEL = "select period".$period_sel." from tbl_board_plan_amount1 where plan_cd=".$plan_cd." and gender = '".$Arr_u_sex[$gender]."' and age=".$age." ";
		$SQL_SEL = "select A.period".$period_sel." from tbl_board_plan_amount1 A left join tbl_board_plan B ON (A.plan_cd = B.seq) where A.plan_cd=".$plan_cd." and A.gender = '".$Arr_u_sex[$gender]."' and age=".$age." and B.ext1 != 'N' ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_amt = is_null($sel_row[0]) ? 0 : $sel_row[0];
	}

	//장기여행
	if ($chk_p=="N"){
		$SQL_SEL = "select A.period".$period." from tbl_board_plan_amount1 A left join tbl_board_plan B ON (A.plan_cd = B.seq) where A.plan_cd=".$plan_cd." and A.gender = '".$Arr_u_sex[$gender]."' and age=".$age." and B.ext1 != 'N' ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_amt = is_null($sel_row[0]) ? 0 : $sel_row[0];
	}
	return $ins_amt;
}

// 플랜별 인슈플러스 서비스 금액
function fn_ins_service_amt($plan_cd, $type, $period_month, $period_day, $period, $chk_p, $age, $gender){
	if($type != "N") {
		if($type == "Y" || $type == "") {
			return fn_new_ins_service_amt($period,$chk_p,$plan_cd,$age,$gender,$period_day);
		} else {
			return fn_old_ins_service_amt($plan_cd,$type,$period_month,$period_day);
		}
	} else {
		return 0;
	}
}

// 플랜별 인슈플러스 서비스 금액
function fn_old_ins_service_amt($plan_cd,$type,$period_month, $period_day){
	global $dbcon;
	$ins_amt = 0;
	if($type != "N" && $type != "") {
		$SQL_SPD = "select service_amount_per_day from tbl_board_plan where seq = ". $plan_cd;
		$RS_SPD = $dbcon->query($SQL_SPD);
		$spd = $dbcon->fetch_row($RS_SPD);
		if(!is_null($spd[0])) {
			return $spd[0] * $period_day;
		}

		$SQL_SEL = "select mon". $period_month." from tbl_board_plan_amount2 where stype='".$type."' and plan_cd=".$plan_cd." ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_amt = $sel_row[0];
	} else {
		$ins_amt = 0;
	}
	return $ins_amt;
}

//장 단기 기간 가격선택 부분
function fn_new_ins_service_amt($period,$chk_p,$plan_cd,$age,$gender,$period_day){
	global $dbcon,$Arr_u_sex;

	$SQL_SPD = "select service_amount_per_day from tbl_board_plan where seq = ". $plan_cd;
	$RS_SPD = $dbcon->query($SQL_SPD);
	$spd = $dbcon->fetch_row($RS_SPD);
	if(!is_null($spd[0])) {
		return $spd[0] * $period_day;
	}

	//단기여행
	if ($chk_p=="Y"){
		
		$period_sel = fnShortTermSection($period);
		
		$SQL_SEL = "select period".$period_sel." from tbl_board_plan_amount1 where plan_cd=".$plan_cd." and gender = '".$Arr_u_sex[$gender]."' and age=".$age." and plan_type='S' ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_amt = $sel_row[0];
	}

	//장기여행
	if ($chk_p=="N"){
		$SQL_SEL = "select period".$period." from tbl_board_plan_amount1 where plan_cd=".$plan_cd." and gender = '".$Arr_u_sex[$gender]."' and age=".$age." and plan_type='S' ";
		$result_sel = $dbcon -> query($SQL_SEL);
		$sel_row = $dbcon -> fetch_row($result_sel);
		$ins_amt = $sel_row[0];
	}
	return is_null($ins_amt) ? 0 : $ins_amt;
}

// 플랜 개별 보장금액 검색
function fn_plan_gua($plan_cd,$g_seq){
	global $dbcon;
	$SQL_SEL = "select g_amount from tbl_board_plan_guarantee where plan_cd='".$plan_cd."' and g_seq=".$g_seq." ";
	$result_sel = $dbcon -> query($SQL_SEL);
	$sel_row = $dbcon -> fetch_row($result_sel);
	return $sel_row[0];
}

// 주민등록번호 앞번호 처리
function fn_user_isdn_1($birthday,$gender){
	$A = substr($birthday,0,4);
	if ($A>=2000){
		if ($gender=="M"){$result = "3";}
		if ($gender=="F"){$result = "4";}
	}else{
		if ($gender=="M"){$result = "1";}
		if ($gender=="F"){$result = "2";}
	}
	return $result;
}

//인슈플러스 검색
function print_insu_service($seq){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select subject from tbl_board_insuplus where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_array($RS_ins_subject);
	return $rows["subject"];
	}
}

//상품 정보 검색
function print_pr_info($seq,$sel_code){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select ".$sel_code." from tbl_board_product where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_row($RS_ins_subject);
	return $rows[0];
	}
}

//플랜 정보 검색
function print_plan_info($seq,$sel_code){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select ".$sel_code." from tbl_board_plan where seq='".$seq."' ";
//	echo $SQL_ins_subject;
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_row($RS_ins_subject);
	return $rows[0];
	}
}
//보험사 이미지 검색
function print_ins_img($seq){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select imgfile from tbl_board_ins_list where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_row($RS_ins_subject);
//	echo $rows[0]."<br>";
	$arr_img = explode(",",$rows[0]);
	$img = "/_data/board/ins_list/".$arr_img[1];
	return $img;
	}
}

//선택한 플랜리스트
function selPlanList($pr_cd, $compare_seq, $s_date ,$chk_p) {
	global $dbcon;
	$SQL = " SELECT seq, ins_cd , plan_cd, chk_service FROM tbl_board_plan WHERE pr_cd = '".$pr_cd."' AND plan_status='Y' AND secret='Y'  ";
	if($chk_p == "Y") {
		$SQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$s_date."' ";
		$SQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$s_date."' ";
	} else {
		$SQL .= " AND s_date <= '".$s_date."' ";
		$SQL .= " AND e_date >= '".$s_date."' ";
	}	

	$SQL .= " ORDER BY seq ASC ";
	
	$RS = $dbcon -> query($SQL);
	$data = array();
	while($row = $dbcon->fetch_array($RS)) {
		$data[] = $row;
	}
	
	return $data;
}

//선택한 플랜정보
function selPlanView($plan_seq, $s_date, $e_date, $chk_p) {
	global $dbcon;
	$SQL  = " SELECT * FROM tbl_board_plan WHERE seq=".$plan_seq." and plan_status='Y' AND secret='Y' ";
	if($chk_p == "Y") {
		$SQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$s_date."' ";
		$SQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$s_date."' ";
	} else {
		$SQL .= " AND s_date <= '".$s_date."' ";
		$SQL .= " AND e_date >= '".$s_date."' ";
	}	
	
	$RS = $dbcon -> query($SQL);
	$data = array();
	$data = $dbcon->fetch_array($RS);
	
	return $data;
}

//상품에 대한 인슈플러스 항목 리스트
function selInsuplusList($list_seq) {
	global $dbcon;
	$SQL = " SELECT * FROM tbl_board_insuplus_opt WHERE list_seq = ".$list_seq." ";
	$RS = $dbcon -> query($SQL);
	
	$data = array();
	while($row = $dbcon->fetch_array($RS)) {
		$data[] = $row;
	}
	
	return $data;
}

//상품에 대한 보장내역 검색
function selGuaranteeList($list_seq) {
	global $dbcon;
	$SQL= " SELECT idx, list_seq, service_name FROM tbl_board_guarantee_opt WHERE list_seq = ".$list_seq." ";
	$RS = $dbcon -> query($SQL);
	
	$data = array();
	while($row = $dbcon->fetch_array($RS)) {
		$data[] = $row;
	}
	
	return $data;
}

//플랜 보장내역 검색
function selGuaranteeOfPlan($plan_seq, $guarantee_idx) {
	global $dbcon;
	$SQL = " SELECT g_amount FROM tbl_board_plan_guarantee WHERE plan_cd = '".$plan_seq."' AND  g_seq = '".$guarantee_idx."' ";
	
	$RS = $dbcon -> query($SQL);
	$data = $dbcon->fetch_array($RS);
	return $data;
}

//약관번호 검색
function selRuleSeq($policy_name) {
	global $dbcon;

	$today = date("Y-m-d");
	
	$SQL  = " SELECT seq FROM tbl_board_policy where secret = 'N' ";
	$SQL .=	" AND category = (SELECT idx FROM tbl_category WHERE bc_id = 'policy' AND cate_name = '".$policy_name."') ";
	$SQL .= " AND start_date <= '".$today."' AND end_date >= '".$today."' ";
	$SQL .=	" ORDER BY seq DESC  limit 0,1 ";
	
	$RS = $dbcon -> query($SQL);
	$data = $dbcon->fetch_array($RS);
	$seq = $data["seq"];
	return $seq;
}

//보험약관파일
function selInsAgreeFile($agree_cd) {
	global $dbcon;
	$SQL = " SELECT file_name, file_realname, file_size from tbl_file where bc_id='ins_agree' and seq ='".$agree_cd."' ORDER BY idx DESC limit 0,1 ";
	$RS = $dbcon -> query($SQL);
	$data = $dbcon->fetch_array($RS);
	return $data;
}

//서비스약관파일
function selServiceFile($service_cd) {
	global $dbcon;
	$SQL = " SELECT file_name, file_realname, file_size from tbl_file where bc_id='service_agree' and seq ='".$service_cd."' ORDER BY idx DESC limit 0,1 ";

	$RS = $dbcon -> query($SQL);
	$data = $dbcon->fetch_array($RS);
	return $data;
}

$pg_bank_gubun  = Array(
	"02" => "한국산업은행"
	,"03" => "기업은행"
	,"04" => "국민은행"
	,"05" => "하나은행  (구 외환)"
	,"06" => "국민은행  (구 주택)"
	,"07" => "수협중앙회"
	,"11" => "농협중앙회"
	,"12" => "단위농협"
	,"16" => "축협중앙회"
	,"20" => "우리은행"
	,"21" => "구)조흥은행"
	,"22" => "상업은행"
	,"23" => "SC제일은행"
	,"24" => "한일은행"
	,"25" => "서울은행"
	,"26" => "구)신한은행"
	,"27" => "한국씨티은행  (구 한미)"
	,"31" => "대구은행"
	,"32" => "부산은행"
	,"34" => "광주은행"
	,"35" => "제주은행"
	,"37" => "전북은행"
	,"38" => "강원은행"
	,"39" => "경남은행"
	,"41" => "비씨카드"
	,"45" => "새마을금고"
	,"48" => "신용협동조합중앙회"
	,"50" => "상호저축은행"
	,"53" => "한국씨티은행"
	,"54" => "홍콩상하이은행"
	,"55" => "도이치은행"
	,"56" => "ABN 암로"
	,"57" => "JP모건"
	,"59" => "미쓰비시도쿄은"
	,"60" => "BOA(Bank of America)"
	,"64" => "산림조합"
	,"70" => "신안상호저축은행"
	,"71" => "우체국"
	,"81" => "하나은행"
	,"83" => "평화은행"
	,"87" => "신세계"
	,"88" => "신한(통합)은행"
	,"89" => "케이뱅크"
	,"90" => "카카오뱅크"
	,"D1" => "유안타증권(구 동양증권)"
	,"D2" => "현대증권"
	,"D3" => "미래에셋증권"
	,"D4" => "한국투자증권"
	,"D5" => "우리투자증권"
	,"D6" => "하이투자증권"
	,"D7" => "HMC 투자증권"
	,"D8" => "SK 증권"
	,"D9" => "대신증권"
	,"DA" => "하나대투증권"
	,"DB" => "굿모닝신한증권"
	,"DC" => "동부증권"
	,"DD" => "유진투자증권"
	,"DE" => "메리츠증권"
	,"DF" => "신영증권"
	,"DG" => "대우증권"
	,"DH" => "삼성증권"
	,"DI" => "교보증권"
	,"DJ" => "키움증권"
	,"DK" => "이트레이드"
	,"DL" => "솔로몬증권"
	,"DM" => "한화증권"
	,"DN" => "NH증권"
	,"DO" => "부국증권"
	,"DP" => "LIG증권"
	);


	$pg_card_gubun = Array(
		"01" => "하나(외환)"
		,"03" => "롯데"
		,"04" => "현대"
		,"06" => "국민"
		,"11" => "BC"
		,"12" => "삼성"
		,"14" => "신한"
		,"21" => "해외 VISA"
		,"22" => "해외마스터"
		,"23" => "해외  JCB"
		,"26" => "중국은련"
		,"32" => "광주"
		,"33" => "전북"
		,"34" => "하나"
		,"35" => "산업카드"
		,"41" => "NH"
		,"43" => "씨티"
		,"44" => "우리"
		,"48" => "신협체크"
		,"51" => "수협"
		,"52" => "제주"
		,"54" => "MG새마을금고체크"
		,"55" => "케이뱅크"
		,"56" => "카카오뱅크"
		,"71" => "우체국체크"
		,"95" => "저축은행체크"
	);
	$pg_card_bank_gubun = Array(
		"02" => "한국산업은행"
		,"03" => "기업은행"
		,"04" => "국민은행"
		,"05" => "하나은행  (구외환)"
		,"06" => "국민은행  (구 주택)"
		,"07" => "수협중앙회"
		,"11" => "농협중앙회"
		,"12" => "단위농협"
		,"16" => "축협중앙회"
		,"20" => "우리은행"
		,"21" => "신한은행  (조흥은행)"
		,"23" => "제일은행"
		,"25" => "하나은행  (서울은행)"
		,"26" => "신한은행"
		,"27" => "한국씨티은행  (한미은행)"
		,"31" => "대구은행"
		,"32" => "부산은행"
		,"34" => "광주은행"
		,"35" => "제주은행"
		,"37" => "전북은행"
		,"38" => "강원은행"
		,"39" => "경남은행"
		,"41" => "비씨카드"
		,"53" => "씨티은행"
		,"54" => "홍콩상하이은행"
		,"71" => "우체국"
		,"81" => "하나은행"
		,"83" => "평화은행"
		,"87" => "신세계"
		,"88" => "신한은행(조흥 통합)"
		,"97" => "카카오 머니"
		,"98" => "페이코  (포인트  100% 사용)"
	);

	$arr_chk_p_gubun  = Array(
		"Y" => "일"
		,"N" => "달"
	);

// 주문상태
	$arr_ord_step = Array (
		"1" => "입금전"
		, "2" => "결제완료"
		, "N" => "결제취소"
		, "P" => "부분취소"
		, "R" => "환불"
	);
//가입상태
	$arr_join_step = Array (
		"Y" => "가입완료"
		, "W" => "입금대기"
		, "N" => "가입취소"
		, "R" => "중도해지"
	);

function inplus_mail_send($join_seq,$chk_eng){
	global $dbcon;
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	$subject = '[회원가입] [$NAME]님 환영합니다. ';   //필수입력
	$body = '[$NAME]님 환영합니다. 치환 문자 입니다. 수신 이메일 : [$EMAIL] 수신번호 : [$MOBILE] 메모 : [$NOTE]';                 //필수입력
	
    // $sender = "izm9870@nvaer.com";          //필수입력
	// $sender_name = "DirectSend";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력
 

    //20250318 추가 신규
    $sender = "izm9870@nvaer.com";          //필수입력
	$sender_name = "DirectSend";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력

	$receiver = '{"name":"정해원","email":"izm9870@gmail.com"}';

	$receiver = '['.$receiver.']';      //JSON 데이터

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터

	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
		"cache-control: no-cache",
		"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		print_R($response);
	}

	curl_close ($ch);

}

//선택한 단체가입 플랜리스트
function selGroupPlanList($pr_cd, $compare_seq, $chk_p) {
	global $dbcon;
	$SQL = " SELECT seq, ins_cd , plan_cd, chk_service FROM tbl_board_plan WHERE seq in (".$compare_seq.") AND pr_cd = '".$pr_cd."' AND plan_status='Y' AND secret='Y'  ";
	$SQL .= " ORDER BY field (seq, ".$compare_seq." ) ASC ";
	
	$RS = $dbcon -> query($SQL);
	$data = array();
	while($row = $dbcon->fetch_array($RS)) {
		$data[] = $row;
	}
	
	return $data;
}

//원격진료 코드 1일~365일까지 사용할 수 있는 쿠폰으로 변경 2023.06.09.
function getTelemedicCode($s_date, $e_date) {
	$today = new DateTime(date("Y-m-d"));
	$starttime1 = new DateTime($s_date);
	$endtime2 = new DateTime($e_date);
	$interval = "";
	if($today < $endtime2) {
		if($starttime1 <= $today) {
			$interval = date_diff($today, $endtime2);//보험기간	일수
		} else {
			$interval = date_diff($starttime1, $endtime2);//보험기간	일수
		}
		$day = $interval->days;
		$day_cd = "";
		for($i = 0; $i < 3-strlen($day); $i++) {
			$day_cd .= "0";
		}
		$day_cd .= $day; 
		return "ISPCDAY".$day_cd;
	} else {
		return "ISPCDAY000";
	}
}
?>