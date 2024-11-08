<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";

  admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$param = array();
  $mobile = $_POST["phone"];
  $param["name"] = "테스트";
  $param["pr_name"] = "테스트_상품";
  $param["period"] = "2024-00-00 ~ 2024-00-00";
  $param["t_amount"] = 1000;
  $param["promotion"] = "IP3DJOUJ89DA";
  $param["coupon_name"] = "쿠폰테스트";
  $param["discount"] = "10%";
  $param["bank"] = "국민은행";
  $param["account"] = "7894-05-12345567";
  $param["cancle_date"] = date("Y-m-d");
  $param["cancle_amount"] = 1000;

  kakaoJoinCancel($param, $mobile);       // 가입취소
  // kakaoInsuplusJoin($param,$mobile);   // 가입안내
  // kakaoCouponDown($param,$mobile);     // 쿠폰 다운로드
  // kakaoJoinBankInfo($param,$mobile);   // 가입 입금안내
  // kakaoPromotionSend($param,$mobile);  // 프로모션 발송
  // kakaoJoin($param,$mobile);           // 가입
?>
{resulte: 1}