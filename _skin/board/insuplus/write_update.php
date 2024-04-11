<?
/*=========================================================

���⿡�� �߰��� �ۼ��Ǵ� �÷��̳� �� �Խ����� Ư���� ��Ÿ���� ����� ������ �Ѵ�.

�Ʒ��� ���� ������ �̴� ���� �ϵ���... by JHW

=========================================================*/

// 기존 삭제 후 인서트 로직에서 on duplicate key update 로 변경
// $SQL_OP1_del = "delete from tbl_board_insuplus_opt where list_seq = '".$seq."' ";
// $RS_OP1_del = $dbcon -> query($SQL_OP1_del);

// 메뉴명 인슈플러스(서비스) 인서트/업데이트
for ($k=0;$k<=$_POST["arr_num"];$k++){

	if ($_POST["service_name"][$k]){
		if ($_POST["check_service"][$k]!="Y"){
			$chk_service = "N";
		}else{
			$chk_service = $_POST["check_service"][$k];
		}
		
		$SQL_in1 = "insert into tbl_board_insuplus_opt values (";
		$SQL_in1 .= " ".$_POST["idx"][$k]." ";
		$SQL_in1 .= ", ".$_POST["list_seq"][$k]." ";
		$SQL_in1 .= ", '".$_POST["service_name"][$k]."' ";
		$SQL_in1 .= ", '".$_POST["service_name_en"][$k]."' ";
		$SQL_in1 .= ", '".$_POST["type_a"][$k]."' ";
		$SQL_in1 .= ", '".$_POST["type_b"][$k]."' ";
		$SQL_in1 .= ", '".$chk_service."' ";
		$SQL_in1 .= ", '".$_POST["service_group_name"][$k]."' ";
		$SQL_in1 .= ") on duplicate key update ";
		$SQL_in1 .= " chk_service = '".$chk_service."' ";
		$SQL_in1 .= " , service_name = '".$_POST["service_name"][$k]."' ";
		$SQL_in1 .= " , service_name_en = '".$_POST["service_name_en"][$k]."' ";
		$SQL_in1 .= " , service_group_name = '".$_POST["service_group_name"][$k]."' ";

		$RS_In1 = $dbcon -> query($SQL_in1);
	}
}
?>