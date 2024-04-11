<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$nation = REQSTR($nation, "");
$pr_cd = REQSTR($pr_cd, "");

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " nation='".$nation."' ";
if($pr_cd) {
	$sql .= " ,pr_cd='" . $pr_cd . "' ";
}
if($counsel_date != null && $counsel_date != ''){
	$sql .= ", counsel_date='".$counsel_date."' ";
}else{
	$sql .= " , counsel_date=DATE_FORMAT('".$counsel_date."', '%Y-%m-%d') ";
}
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