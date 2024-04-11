<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?

	$SQL = "
		delete from tbl_productregist
		where
			1=1
			and idx = '".$idx."'
	";
	$result = $dbcon -> query($SQL);
	if (!$result) {
		alert_back("삭제에 실패하였습니다.");
		exit;
	}


	alert_page("삭제되었습니다.", "bbs_product_list.php");


?>
