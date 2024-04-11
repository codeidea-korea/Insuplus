<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$pc_idx = REQSTR($pc_idx, "");
	$search_pc_num = REQSTR($search_pc_num, "");

	$SQL = "
		delete from tbl_product_category
		where
			pc_idx = '".$pc_idx."'
	";
	$dbcon -> query($SQL);
?>
<?
	$dbcon -> dbcon_close();
	alert_page("삭제되었습니다.","product_category.php?search_pc_num=".$search_pc_num);
?>
