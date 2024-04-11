<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	if ( $ss_u_idx ) {
		alert_page("현재 로그인 상태입니다.", $url_index);
	}

	$act				= REQSTR($_POST[act],"");
	$url				= REQSTR($url,"");
	if ( strlen($url) == 0  ) {
		$url = $url_index;
	}
	else {
	}

//	if ( $_SERVER[HTTP_REFERER] ) {
//		$url = $_SERVER[HTTP_REFERER];
//	}
//	else {
//		$url = $url_index;
//	}

//	echo $HTTP_REFERER."<BR>";
//	echo $_SERVER[HTTP_REFERER]."<BR>";
//	echo "url : ".$url."<BR>";

//	echo "url : ".$url."<BR>";

	if ( $act == "ok" ) {
		$user				= REQSTR($_POST[user],"");
		$pass				= REQSTR($_POST[pass],"");


		LoginProcess($user, $pass, $join_user_level);

		alert_page($msg_login_ok,$cf_site_url.$url);
	}
	else {
		//LogoutProcess();
		//alert_page("", $url_index);
	}

	include_once $path_skin_member."login.php";
	$dbcon -> dbcon_close();
?>

<script language=javascript>
<!--

	function checkForm() {
		var ff = document.form1;

		if (!ff.user.value)
		{
			alert("[아이디]를 입력해주세요.");
			ff.user.focus();
			return false;
		}

		if (!ff.pass.value) {
			alert("[비밀번호]를 입력해주세요.");
			ff.pass.focus();
			return false;
		}

		ff.action = "<?=$cf_site_url?>/member/login.php";
		//ff.action = "<?=$cf_site_url_ssl?>/member/login.php";

		return true;

	}

//-->
</script>