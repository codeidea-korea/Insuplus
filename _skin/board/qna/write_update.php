<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$customer_content = REQSTR($customer_content, "");
$name = all_seed_enc(REQSTR($name, ""));
$mobile = all_seed_enc(REQSTR($mobile, ""));
$customer_content = REQSTR($customer_content, "");
$customer_password = REQSTR($customer_password, "");
if($customer_password != ""){
	$customer_password = all_seed_enc(REQSTR($customer_password, ""));
}
$status = REQSTR($status, "");
$pr_cd = REQSTR($pr_cd, "");
$alrim_talk = REQSTR($alrim_talk, "");

if($email1) $email1 = all_seed_enc($email1);
if($email2) $email2 = all_seed_enc($email2);

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " customer_content='".$customer_content."' ";
$sql .= " , name='".$name."' ";
$sql .= " , mobile='".$mobile."' ";
$sql .= " , customer_password='".$customer_password."' ";
$sql .= " , status='".$status."' ";
$sql .= " , pr_cd='".$pr_cd."' ";
if($email1) $sql .= ", email1='".$email1."' ";
if($email2) $sql .= ", email2='".$email2."' ";
$sql .= " where seq= ".$seq."" ;
$result = $dbcon -> query($sql);

if (!$result) {
	$dbcon -> dbcon_close();
	//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
	//echo "에러";
	alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
	exit;
}

//알림톡 전송
if($alrim_talk == "Y" && $status == "A") {
	$param = array();
	$param["name"] = all_seed_dec($name);
	kakaoQnaAnswer($param, all_seed_dec($mobile));
}
?>