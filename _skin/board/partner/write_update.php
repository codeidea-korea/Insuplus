<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$start_Partner_period = REQSTR($start_Partner_period, "");
$end_Partner_period = REQSTR($end_Partner_period, "");
$partnership_code = REQSTR($partnership_code, "");
$partnership_name = REQSTR($partnership_name, "");
$partnership_charge = REQSTR($partnership_charge, 0);

$domain = "";
$domain = getDomain();

$tracking_url = $domain."/html/main/index.php?alliance_code=".encrypt($partnership_code);

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " start_Partner_period='".$start_Partner_period."' ";
$sql .= ", end_Partner_period='".$end_Partner_period."' ";
if($partnership_code) {
	$sql .= ", partnership_code='".$partnership_code."' ";
	$sql .= ", tracking_url='".$tracking_url."' ";
}
$sql .= ", partnership_name='".$partnership_name."' ";
$sql .= ", partnership_charge='".$partnership_charge."' ";
$sql .= " where seq= ".$seq."" ;
$result = $dbcon -> query($sql);
if (!$result) {
	$dbcon -> dbcon_close();
	//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
	//echo "에러";
	alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
	exit;
}
?>