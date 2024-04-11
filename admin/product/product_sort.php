<?
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.Array.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.MSG.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.SQL.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.session.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.DB.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.File.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.global.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.page.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.product.php";

	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$act = REQSTR($_GET[act], "");
	$pr_idx = REQSTR($_GET[pr_idx], "");
	$pr_sort = REQSTR($_GET[pr_sort], "");

	isnull($act);
	isnull($pr_idx);
	isnull($pr_sort);

//	echo "act : ".$act."<BR>";
//	echo "pr_idx : ".$pr_idx."<BR>";
//	echo "pr_sort : ".$pr_sort."<BR>";


	$set_pr_sort = "";
	if ( $act == "up" ) {
		$set_pr_sort = $pr_sort+1;
	}
	elseif ( $act == "down" ) {
		$set_pr_sort = $pr_sort-1;
	}
	else {
		alert_back("잘못된 접근");
	}

//	echo "set_pr_sort : ".$set_pr_sort."<BR>";

	$dbcon = new dbcon;
	$dbcon -> dbcon_open(0);
	$SC_Rows = getSiteConfig();
	extract($SC_Rows);
	unset($SC_Rows);

	#### 스킨정보
	$sc_path_skin_member			= $path_skin_member.$sc_skin_member."/";
	$sc_path_skin_product			= $path_skin_product.$sc_skin_product."/";
	$sc_url_skin_member				= $url_skin_member.$sc_skin_member."/";
	$sc_url_skin_product				= $url_skin_product.$sc_skin_product."/";
	$sc_url_skin_mail					= $url_skin_mail."default/";

	$SQL = "
		update tbl_product
		set
			pr_sort = '".$pr_sort."'
		where
			pr_sort = '".$set_pr_sort."'
		limit 1
	";
	$dbcon -> query($SQL);


	$SQL = "
		update tbl_product
		set
			pr_sort = '".$set_pr_sort."'
		where
			pr_idx = '".$pr_idx."'
		limit 1
	";
	$dbcon -> query($SQL);

	$dbcon -> dbcon_close();
?>
<script>
	parent.document.location.reload();
</script>