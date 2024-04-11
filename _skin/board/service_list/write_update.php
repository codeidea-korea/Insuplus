<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/



//echo "<script>alert('안녕하세요');</script>";

// 기존 정보 삭제
$SQL_OP1_del = "delete from tbl_board_service_list_opt where list_seq = '".$seq."' ";
$RS_OP1_del = $dbcon -> query($SQL_OP1_del);

// 신규정보 입력
for ($k=0;$k<$_POST["arr_num"];$k++){
	if ($_POST["service_name"][$k]){
	echo $_POST["service_name"][$k]."<br>";
	$SQL_in1 = "insert into tbl_board_service_list_opt set";
	$SQL_in1 .= " list_seq = '".$seq."' ";
	$SQL_in1 .= " , service_name = '".$_POST["service_name"][$k]."' ";
	$SQL_in1 .= " , service_name_en = '".$_POST["service_name_en"][$k]."' ";
	$SQL_in1 .= " , basic_care = '".$_POST["basic_care"][$k]."' ";
	$SQL_in1 .= " , primium_care = '".$_POST["primium_care"][$k]."' ";
	echo $SQL_in1."<br>";
	$RS_In1 = $dbcon -> query($SQL_in1);
	}
}

//$sql = "update tbl_board_".$bc_id." set where seq= ".$seq.""
//	$result = $dbcon -> query($SQL);
//	if (!$result) {
//		$dbcon -> dbcon_close();
//		//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
//		//echo "에러";
//		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
//		exit;
//	}
?>