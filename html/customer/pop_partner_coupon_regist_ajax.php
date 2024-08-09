<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";

$SQL = "";
if($_POST["mode"] == "insert") {

	$SQL = "SELECT expire_date_s, expire_date_e FROM tbl_board_event WHERE seq = '".$_POST["seq"]."'";
	$rs_event = $dbcon -> query($SQL);
	$row_event = $dbcon->fetch_array($rs_event);
	
	$expire_date_s = substr($row_event["expire_date_s"],0,10);
	$expire_date_e = substr($row_event["expire_date_e"],0,10);
 
	$toDay = date_create(date('Ymd')); //오늘
	$startDate=date_create(substr($_POST["start_date"], 0, 10));
	$endDate=date_create(substr($_POST["end_date"], 0, 10));

	$SQL_C = "SELECT COUNT(*) FROM tbl_partner_coupon WHERE event_seq = '".$_POST["seq"]."' AND coupon_cd = '".$_POST["coupon"]."' AND use_yn = 'N' ";
	$isCoupon = $dbcon -> getCount($SQL_C);//사용 가능한 쿠폰 확인

	$SQL_U = "SELECT COUNT(*) FROM tbl_board_coupon_history WHERE event_seq = '".$_POST["seq"]."' AND mobile = '".all_seed_enc($_POST["hp"])."' ";
	$isUsed = $dbcon -> getCount($SQL_U);//발급여부 확인

	if($toDay >= $startDate && $toDay <= $endDate){	//이벤트 기간 확인
		if($isUsed == 0) {
			if($isCoupon == 1) { 	//사용 가능한 쿠폰 확인
				$SQL = "";
				$SQL .=  " INSERT INTO tbl_board_coupon_history SET ";
				$SQL .= " event_seq = '".$_POST["seq"]."' ";
				$SQL .= " ,start_date = '".$expire_date_s."' ";
				$SQL .= " ,end_date = '".$expire_date_e."' ";
				$SQL .= " ,mobile = '".all_seed_enc($_POST["hp"])."' ";
				$SQL .= " ,ori_mobile = '".all_seed_enc($_POST["hp"])."' ";
				$SQL .= " ,temp_discount = '".$_POST["discount"]."' ";
				$SQL .= " ,partner_coupon = '".$_POST["coupon"]."' ";
				$SQL .= " ,use_yn = 'N' ";
				$SQL .= " ,writedate = now() ";
				$dbcon -> query($SQL);

				$SQL_P = "";
				$SQL_P = "UPDATE tbl_partner_coupon set mobile ='".all_seed_enc($_POST["hp"])."', use_yn = 'Y' 
				WHERE event_seq = '".$_POST["seq"]."' AND coupon_cd = '".$_POST["coupon"]."' ";
				$dbcon -> query($SQL_P);
				
		
				$SQL = "SELECT partner_coupon_name FROM tbl_board_event WHERE seq = '".$_POST["seq"]."'";
				$result = $dbcon -> query($SQL);//쿠폰이름
				$row= $dbcon -> fetch_array($result);

				$param = array();
				$param["discount"] = $_POST["discount"];
				$param["discount_txt"] = $_POST["discount"]."%";
				$param["period"] = $expire_date_s. " ~ ".$expire_date_e;
				$param["coupon_name"] = $row["partner_coupon_name"];
				kakaoCouponDown($param, $_POST["hp"]);//알림톡 쿠폰등록완료
				$result = '1';	//쿠폰등록을 완료 하였습니다.
			} else {
				$result = '2'; //이미 등록된 휴대폰
			}
		} else {
			$result = '3';//이벤트 기간내 1회만 등록할 수 있습니다.
		}
	} else {
		$result = '4';//이벤트기간이 아닙니다.
	}
	
	$dbcon -> dbcon_close();
}
?>
{"result":"<?=$result;?>"}