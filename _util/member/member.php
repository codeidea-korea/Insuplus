<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$mode = REQSTR($mode,"");

	#### mode 설정
	if ( !getLen($mode) ) {
//		if ($auth_level >= $auth_admin) $mode = "list";
//		else $mode = $bc_url_start;
		alert_back("잘못된 접근입니다.");
	}

	//echo $path_member."<BR>";

	switch ($mode) {
		case "login" :
			login_chk(0, "");
			include_once $path_member."login.php";
			break;
		case "agree" :
			login_chk(0, "?mode=login");
			include_once $path_member."agree.php";
			break;
	}

	$dbcon -> dbcon_close();
?>
