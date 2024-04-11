<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/
	$homepage = REQSTR($homepage, "");
	$sql = "update tbl_board_".$bc_id." set homepage= '".$homepage."' where seq= ".$seq."";
//	echo $sql;
//	exit;
	$result = $dbcon -> query($sql);
	if (!$result) {
		$dbcon -> dbcon_close();
		//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
		//echo "에러";
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}
?>