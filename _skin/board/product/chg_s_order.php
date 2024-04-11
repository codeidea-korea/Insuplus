<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

for ($k=0;$k<count($_POST["seq"]);$k++){
	if ($_POST["seq"][$k]){
	$SQL_in1 = "update tbl_board_product set";
	$SQL_in1 .= " sort_order = '".$_POST["sort_order"][$k]."' ";
	$SQL_in1 .= " where seq='".$_POST["seq"][$k]."' ";
//	echo $SQL_in1."<br>";
	$RS_In1 = $dbcon -> query($SQL_in1);
	}
}
?>
<script type="text/javascript">
<!--
alert("변경되었습니다.");
top.document.location.reload();	
//-->
</script>