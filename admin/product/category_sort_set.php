<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	//$dbcon -> setDebug(1);

	$SQL = "
		select count(*)
		from tbl_product
	";
	$tcnt = $dbcon -> getCount($SQL);

	//echo "tcnt : ".$tcnt."<BR>";

	$SQL = "
		select pr_idx
		from tbl_product
		order by
			pr_idx desc
	";
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon->fetch_array($rs)) {
		extract($rows);
		unset($rows);
		//echo "pr_idx : ".$pr_idx."<BR>";
		$SQL2 = "
			update tbl_product
			set
				pr_sort = '".$tcnt."'
			where
				pr_idx = '".$pr_idx."'
		";
		$dbcon -> query($SQL2);

		$tcnt --;

	}
?>
<?
	$dbcon -> dbcon_close();
	alert_page("처리되었습니다.", "product_list.php?search_pc_num=".$search_pc_num);
?>
