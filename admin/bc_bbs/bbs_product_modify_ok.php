<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?

	$SQL = "
		update tbl_productregist
		set
			pc_num = '".$pc_num."'
			, pr_name = '".$pr_name."'
			, pr_code = '".$pr_code."'

		where
			u_id = '".$u_id."'
			and idx = '".$idx."'
	";
	$result = $dbcon -> query($SQL);
	if (!$result) {
		alert_back("수정에 실패하였습니다.");
		exit;
	}

	$go_page = "?idx=".$idx;

	alert_page("처리되었습니다.", "bbs_product_modify.php".$go_page);


?>
