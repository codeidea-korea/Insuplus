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
//쿠폰 검색
$SQL_CP =  " select (case WHEN B.partner_coupon IS NULL then A.subject else A.partner_coupon_name end) as subject, B.seq,B.temp_discount,B.start_date,B.end_date, B.event_seq from ";
$SQL_CP .= " tbl_board_event A inner join tbl_board_coupon_history B on A.seq=B.event_seq  "; 
//결제일 기준 쿠폰조회 추후 쿠폰 전체 조회 후 사용가능한 쿠폰만 선택되도록 변경 예정
$SQL_CP .= " where B.mobile = '".$user_hp."' and B.use_yn='N' and B.start_date <='".$today."' and B.end_date >='".$today."'";

if($_SESSION["ss_partner_seq"]){
	// $SQL_CP .= "and A.event_partnership_code = (SELECT partnership_code FROM tbl_board_partner WHERE seq = '".$_SESSION["ss_partner_seq"]."' 
	// 			AND start_Partner_period <= '".$today."' AND end_Partner_period >= '".$today."' ORDER BY seq ASC limit 0,1)";
	$SQL_CP .= "and A.event_partnership_code in (SELECT partnership_code FROM tbl_board_partner WHERE seq in ('01', '".$_SESSION["ss_partner_seq"]."')
				AND start_Partner_period <= '".$today."' AND end_Partner_period >= '".$today."' ORDER BY seq ASC)";
}

$result_cp = $dbcon -> query($SQL_CP);
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