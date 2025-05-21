<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	$act					= REQSTR($act,"");

    $_SESSION['auth_code'] = '';
	$_SESSION['otpValid'] = 0; //2차인증 시도횟수

	if ( $act == "ok" ) {
		$user				= REQSTR($_POST[user],"");
		$pass			= REQSTR($_POST[pass],"");

		LoginProcess($user, $pass, 2);

		// echo $_SESSION['ss_u_email'];
        if($_SESSION['ss_u_email'] != '@' ){
            // 인증 성공
            $authCode = random_int(100000, 999999); // 6자리 난수 생성
            $_SESSION['auth_code'] = $authCode; // 세션에 저장 
            $_SESSION['is_authenticated'] = false; // 2차 인증 완료 전 상태
			$emailAddr = $_SESSION['ss_u_email'];
            //$emailAddr = "rbswsky@naver.com";
        	include_once $_SERVER["DOCUMENT_ROOT"]."/admin/mailer.php";
        	header('Location: verify.php');
        } else {
            if($_SESSION["ss_u_level"] == "7") {
                alert_page($msg_login_ok,"/admin/mn1/join_partner_list.php");
            } else if($_SESSION["ss_u_level"] == "6") { 
                alert_page($msg_login_ok,"/admin/mn1/join_ins_list.php");
            } else {
                // alert_page($msg_login_ok,$url_admin_login_ok);
                alert_page("",$url_admin_login_ok);
            }
		} 
	} 

	$dbcon -> dbcon_close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=(defined("DEVELOPMENT_MODE") ? '[DEV] ' : '')?><?=$sc_site_title?></title>
<meta content="IE=edge" http-equiv="X-UA-Compatible" />
<script src="js/placeholders.min.js"></script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
.loginWrap{width:300px; height:500px; margin:200px auto;}
.loginWrap h1{text-align:center;}
.loginWrap .txt1{font-size:20px; color:#666; text-align:left;}
.loginWrap input[type=text],.loginWrap input[type=password]{width:100%; height:40px; padding:10px; box-sizing:border-box;}
.loginWrap input[type=submit]{width:100%;height:40px; color:#fff; background:#333; border:none;}
.loginWrap .copyright{text-align:center; font-size:11px; color:#666; margin:40px 0 0 0;}
</style>

<script language=javascript>


	function SetFocus()
	{
		if(document.form1!=null && document.form1.user != null) {
			document.form1.user.focus();
		}
	}

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

		return true;
	}
	onload = SetFocus;

    
</script>
</head>

<body style="background-color:#FFFFFF;">

<div class="loginWrap">
<h1><img src="<?=$url_admin?>/images/logo_login.png" alt="insuplus" /></h1>
<p class="txt1">Administrator</p>
<form name="form1" action="" method="post" onSubmit="return checkForm();">
<input type="hidden" name="act" value="ok">
	<p><input type="text" name="user" value="" autocomplete="off" maxlength="20" tabindex="1" placeholder="아이디"></p>
	<p><input type="password" name="pass" value="" maxlength="20" tabindex="2" placeholder="비밀번호" autocomplete="new-password"></p>
	<p><input type="submit" value="로그인" /></p>
	<p class="copyright">Copyrights © KoreaAssistance, All rights reserved.</p>
</form>
</div>
</body>
<script>
  document.addEventListener('DOMContentLoaded', function() {
  const textAndPasswordInputs = document.querySelectorAll("input[type='text'], input[type='password']");
  
  textAndPasswordInputs.forEach(function(input) {
    input.value = "";
  });
});
</script>
</html>

