<?
include_once $_SERVER['DOCUMENT_ROOT']."/_config/lib.php";
$bc_id = REQSTR($bc_id, "");
$seq = REQSTR($seq, "");
$exposure_order = REQSTR($exposure_order, "");

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " exposure_order='".$exposure_order."' ";
$sql .= " where seq= ".$seq."" ;
$result = $dbcon -> query($sql);
echo $result;
if (!$result) {
	$dbcon -> dbcon_close();
	//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
	//echo "에러";
	alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
	exit;
}
?>