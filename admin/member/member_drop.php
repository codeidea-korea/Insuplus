<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk("7", $url_admin_login_out);


	$u_id = REQSTR($_GET[u_id], "");
	$type = REQSTR($_GET[type], "");

	isnull($u_id);
	//isnull($type);

	MemberDeleteProcess($u_id, $type);
	alert_page('처리되었습니다.' , "member_list.php" );

	$dbcon -> dbcon_close();
?>
