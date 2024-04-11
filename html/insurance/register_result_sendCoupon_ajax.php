<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";


$data["result"] = "-1";
$data["msg"] = "올바른 경로를 이용해 주세요.";

if($_POST["mode"]=="send") {
	$orderno = $_SESSION["orderno"];
	$mobile = $_POST["mobile"]; //친구번호
	$name = $_POST["name"]; //선물한 친구 이름
	$ori_mobile = $_POST["ori_mobile"]; //획득한 휴대폰번호
	$data = updSendCoupon($orderno, $mobile, $ori_mobile);
	
	if($data["result"]) {
		$coupon_data = selCounponOfOrderno($orderno);
		
		$param = array();
		$param["name"] = $name;
		$param["discount"] = floor($coupon_data["temp_discount"]);
		$param["coupon_period"] = $coupon_data["start_date"]." ~ ". $coupon_data["end_date"];
		$param["discount_txt"] = $param["discount"]."% (할인 최대한도 30,000원)";
		$param["coupon_name"] = $coupon_data["coupon_name"];
		
		kakaoSendFriend($param,$mobile);
		
	}
}
?>
{"result":"<?=$data["result"]?>","msg":"<?=$data["msg"];?>"}