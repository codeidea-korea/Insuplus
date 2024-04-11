<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$u_id = REQSTR($_GET[u_id], "");

	if ( getLen($ss_u_idx) > 0 && $ss_u_level < $auth_admin) {
		alert_close("<?=$msg_login_auth?>");
		exit;
	}

	$count = MemberCheckJoin($u_id);
	//echo $count."<BR>";


	include_once $path_skin_member."idcheck.php";

	$dbcon -> dbcon_close();
?>
