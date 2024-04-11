<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 10);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	$search_orderby = REQSTR($search_orderby, "");

	$parameter = "&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page;



	$idx = REQSTR($_GET["idx"], "");
	isnull($idx);


	$table_name = "tbl_messenger";

	// 카운트 실행
	$SQL = "
		delete from ".$table_name."
		where
			idx = '".$idx."'
	";

	$rs = $dbcon->query($SQL);

	if ( $rs ) alert_page("처리되었습니다.", "messenger.php?page=".$page.$parameber);
?>


<? $dbcon -> dbcon_close();?>
