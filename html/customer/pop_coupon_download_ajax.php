<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";

$SQL = "";
if($_POST["mode"] == "insert") {
	$SQL = "SELECT COUNT(*) FROM tbl_board_coupon_history WHERE event_seq = '".$_POST["seq"]."'";
	$used_coupon_size = $dbcon -> getCount($SQL);//사용된 쿠폰수

	$SQL = "SELECT coupon_size, expire_date_s, expire_date_e FROM tbl_board_event WHERE seq = '".$_POST["seq"]."'";
	$rs_event = $dbcon -> query($SQL);
	$row_event = $dbcon->fetch_array($rs_event);
	
	$coupon_size = $row_event["coupon_size"];
	$expire_date_s = substr($row_event["expire_date_s"],0,10);
	$expire_date_e = substr($row_event["expire_date_e"],0,10);

	$SQL = "SELECT COUNT(*) FROM tbl_board_coupon_history WHERE mobile = '".all_seed_enc($_POST["hp"])."' AND event_seq = '".$_POST["seq"]."' AND partner_coupon IS NULL";	
	$cnt = $dbcon -> getCount($SQL);

	$toDay = date_create(date('Ymd')); //오늘
	$startDate=date_create(substr($_POST["start_date"], 0, 10));
	$endDate=date_create(substr($_POST["end_date"], 0, 10));

	if($coupon_size <= $used_coupon_size){
		$result = '3';//쿠폰이 모두 소진되었습니다.
	} else if($toDay >= $startDate && $toDay <= $endDate){
		if($cnt == 0) { // 휴대폰 번호 중복여부 확인
			$SQL = "";
			$SQL .=  " INSERT INTO tbl_board_coupon_history SET ";
			$SQL .= " event_seq = '".$_POST["seq"]."' ";
			$SQL .= " ,start_date = '".$expire_date_s."' ";
			$SQL .= " ,end_date = '".$expire_date_e."' ";
			$SQL .= " ,mobile = '".all_seed_enc($_POST["hp"])."' ";
			$SQL .= " ,ori_mobile = '".all_seed_enc($_POST["hp"])."' ";
			$SQL .= " ,temp_discount = '".$_POST["discount"]."' ";
			$SQL .= " ,use_yn = 'N' ";
			$SQL .= " ,writedate = now() ";
	
			if ($dbcon -> query($SQL) ) {
				$result = '1';//쿠폰등록을 완료 하였습니다.
			}
	
			$SQL = "SELECT coupon_name FROM tbl_board_event WHERE seq = '".$_POST["seq"]."'";
			$result = $dbcon -> query($SQL);//쿠폰이름
			$row= $dbcon -> fetch_array($result);

			$param = array();
			$param["discount"] = $_POST["discount"];
			$param["discount_txt"] = $_POST["discount"]."%";
			$param["period"] = $expire_date_s. " ~ ".$expire_date_e;
			$param["coupon_name"] = $row["coupon_name"];
			kakaoCouponDown($param, $_POST["hp"]);//알림톡 쿠폰등록완료

			$result = '1';
		} else {
			//$result = '4';//이벤트기간이 아닙니다.
			$result = '2'; //이미 등록된 휴대폰
		}

	} else {
		$result = '2';//이미 등록된 휴대폰번호입니다.
	}
	
	$dbcon -> dbcon_close();
}
?>
{"result":"<?=$result;?>"}