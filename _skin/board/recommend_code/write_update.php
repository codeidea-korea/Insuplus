<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$recommendation_code = REQSTR($recommendation_code, "");
$recom_partnership_code = REQSTR($recom_partnership_code, "");
$start_date = REQSTR($start_date, "");
$end_date = REQSTR($end_date, "");
$discount = REQSTR($discount, "") ? REQSTR($discount, "") : "0";

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " recommendation_code='".$recommendation_code."' ";
$sql .= ", recom_partnership_code='".$recom_partnership_code."' ";
$sql .= ", start_date='".$start_date."' ";
$sql .= ", end_date='".$end_date."' ";
$sql .= ", discount='".$discount."' ";
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