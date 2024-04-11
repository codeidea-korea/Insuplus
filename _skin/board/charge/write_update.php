<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$join_seq = REQSTR($join_seq, "");
$apply_date = REQSTR($apply_date, "");
$occur_date = REQSTR($occur_date, "");
$place = REQSTR($place, "");
$hospital = REQSTR($hospital, "");
$money = REQSTR($money,0);
$charge_type = REQSTR($charge_type, "");
$charge_status = REQSTR($charge_status, "");
$mobile = all_seed_enc(REQSTR($mobile, ""));

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " join_seq='".$join_seq."' ";
$sql .= " , apply_date='".$apply_date."' ";
$sql .= " , occur_date='".$occur_date."' ";
$sql .= " , place='".$place."' ";
$sql .= " , hospital='".$hospital."' ";
$sql .= " , money='".$money."' ";
$sql .= " , charge_type='".$charge_type."' ";
$sql .= " , charge_status='".$charge_status."' ";
$sql .= " , mobile='".$mobile."' ";
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