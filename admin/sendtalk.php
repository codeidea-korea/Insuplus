<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";

  admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$param = array();
  $mobile = $_POST["phone"];
  $param["name"] = "테스트";
  $param["pr_name"] = "테스트_상품";
  $param["period"] = "2024-01-02 ~ 2024-02-01";
  $param["t_amount"] = 1000;
  $param["promotion"] = "IP3DJOUJ89DA";
  $param["coupon_name"] = "쿠폰테스트";
  $param["discount"] = "10%";

  // kakaoPromotionSend($param,$mobile);
  // kakaoJoin($param,$mobile);
  kakaoInsuplusJoin($param,$mobile);
?>
{resulte: 1}