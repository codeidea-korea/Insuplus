<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$join_seq = REQSTR($join_seq, "");
$apply_date = REQSTR($apply_date, "");
$occur_date = REQSTR($occur_date, "");
$place = REQSTR($place, "");
$country = REQSTR($country, "");
$visit_date = REQSTR($visit_date, "");
$hospital = REQSTR($hospital, "");
$mobile = all_seed_enc(REQSTR($mobile, ""));
$network = REQSTR($network, "");
$gop_status = REQSTR($gop_status, "");

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " join_seq='".$join_seq."' ";
$sql .= " , apply_date='".$apply_date."' ";
$sql .= " , occur_date='".$occur_date."' ";
$sql .= " , place='".$place."' ";
$sql .= " , country='".$country."' ";
$sql .= " , visit_date='".$visit_date."' ";
$sql .= " , hospital='".$hospital."' ";
$sql .= " , mobile='".$mobile."' ";
$sql .= " , network='".$network."' ";
$sql .= " , gop_status='".$gop_status."' ";
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