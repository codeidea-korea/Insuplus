<?


/*
$SQL_OP1_del = "delete from tbl_board_guarantee_opt where list_seq = '".$seq."' ";
$RS_OP1_del = $dbcon -> query($SQL_OP1_del);
*/

for ($k=0;$k<=$_POST["arr_num"];$k++){
	if ($_POST["service_name"][$k]){
		
		
		
		$SQL_Csel = "select cate_name from tbl_category where bc_id='".$bc_id."' and idx='".$_POST["group_mn"][$k]."' ";

		$RS_Csel = $dbcon -> query($SQL_Csel);
		$row_Csel = $dbcon -> fetch_array($RS_Csel);
		$group_mn_kr = $row_Csel["cate_name"];

		if($_POST["idx"][$k]) { //있으면 수정한다
			
			if($_POST["del_idx"][$k]) {
				$SQL_del1 = " DELETE FROM tbl_board_guarantee_opt WHERE idx = '".$_POST["idx"][$k]."' ";
				$RS_del1 = $dbcon -> query($SQL_del1);
					
			} else {
				if ($_POST["check_service"][$k]!="Y"){
					$chk_service = "N";
				}else{
					$chk_service = $_POST["check_service"][$k];
				}
				$SQL_up1  = " UPDATE tbl_board_guarantee_opt SET ";
				$SQL_up1 .= " chk_service = '".$chk_service."' ";
				$SQL_up1 .= " ,service_name = '".$_POST["service_name"][$k]."' ";
				$SQL_up1 .= " ,service_name_en = '".$_POST["service_name_en"][$k]."' ";
				$SQL_up1 .= " ,service_content = '".$_POST["service_content"][$k]."' ";
				$SQL_up1 .= " ,group_mn = '".$group_mn_kr."' ";
				$SQL_up1 .= " ,group_mn_en = '".$_POST["group_mn_en"][$k]."' ";
				$SQL_up1 .= " WHERE idx = '".$_POST["idx"][$k]."' ";
				$RS_up1 = $dbcon -> query($SQL_up1);
			}
			
		} else {
			if ($_POST["check_service"][$k]!="Y"){
				$chk_service = "N";
			}else{
				$chk_service = $_POST["check_service"][$k];
			}
			$SQL_in1 = "insert into tbl_board_guarantee_opt set";
			$SQL_in1 .= " list_seq = '".$seq."' ";
			$SQL_in1 .= " , chk_service = '".$chk_service."' ";
			$SQL_in1 .= " , service_name = '".$_POST["service_name"][$k]."' ";
			$SQL_in1 .= " , service_name_en = '".$_POST["service_name_en"][$k]."' ";
			$SQL_in1 .= " , service_content = '".$_POST["service_content"][$k]."' ";
			$SQL_in1 .= " , group_mn = '".$group_mn_kr."' ";
			$SQL_in1 .= " , group_mn_en = '".$_POST["group_mn_en"][$k]."' ";
	
			$RS_In1 = $dbcon -> query($SQL_in1);
		}
	}
}

?>