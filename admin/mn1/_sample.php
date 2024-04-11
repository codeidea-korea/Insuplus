<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "ins";
	$lm = "";
	include $path_admin."inc/header.php";
?>





<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>