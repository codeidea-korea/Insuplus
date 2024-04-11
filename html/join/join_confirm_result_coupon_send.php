<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";

$s_idx = $_POST["s_idx"];
$s_pnum = $_POST["s_pnum"];
$s_name = $_POST["s_name"];

if ($s_idx && $s_pnum){
	$SQL  = " select temp_discount, start_date, end_date, discount ";
	$SQL .= " ,(select coupon_name from tbl_board_event where seq=h.event_seq) as coupon_name ";
	$SQL .= " from tbl_board_coupon_history h where seq='".$s_idx."' and use_yn='N' ";

	$RS = $dbcon -> query($SQL);
	if (!$RS){
		echo "잘못된 경로로 입력되었습니다.";
		exit;
	}
	$SQL_U = "update tbl_board_coupon_history set mobile = '".all_seed_enc($s_pnum)."' where seq='".$s_idx."' ";
	$RS_U = $dbcon -> query($SQL_U);
	if ($RS_U){
		//알림톡 전송 타인에게 보내는건 알림톡 전송이 안되고 문자로 전송됨
		$row = $dbcon->fetch_array($RS);
	
		$param = array();
		$param["name"] = $s_name;
		$param["discount"] = floor($row["temp_discount"]);
		$param["coupon_period"] = $row["start_date"]." ~ ". $row["end_date"];
		$param["discount_txt"] = $param["discount"]."% (할인 최대한도 30,000원)";
		$param["coupon_name"] = $row["coupon_name"];
		
		kakaoSendFriend($param,$s_pnum);
	
		echo "<script>alert('친구에게 쿠폰이 전송되었습니다.');parent.document.location.reload();</script>";
	}
}else{
	echo "잘못된 경로로 입력되었습니다.";
	exit;
}

?>