<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$main_recommand_date_s = REQSTR($main_recommand_date_s, "");
$main_recommand_date_e = REQSTR($main_recommand_date_e, "");
$exposure_order = REQSTR($exposure_order, "");
$event_seq = REQSTR($event_seq, "");
$plan_seq_1 = REQSTR($plan_seq_1, "");
$plan_seq_2 = REQSTR($plan_seq_2, "");
$plan_seq_3 = REQSTR($plan_seq_3, "");
$plan_seq_1 = $plan_seq_1 == "" ? 0 : $plan_seq_1;
$plan_seq_2 = $plan_seq_2 == "" ? 0 : $plan_seq_2;
$plan_seq_3 = $plan_seq_3 == "" ? 0 : $plan_seq_3;

$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " main_recommand_date_s='".$main_recommand_date_s."' ";
$sql .= ", main_recommand_date_e='".$main_recommand_date_e."' ";
$sql .= ", exposure_order='".$exposure_order."' ";
$sql .= ", event_seq='".$event_seq."' ";
$sql .= ", plan_seq_1='".$plan_seq_1."' ";
$sql .= ", plan_seq_2='".$plan_seq_2."' ";
$sql .= ", plan_seq_3='".$plan_seq_3."' ";
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