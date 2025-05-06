<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

if($_SESSION['is_authenticated'] == true){ //이미 인증되었는데 페이지 접근하는 경우
    if($_SESSION["ss_u_level"] == "7") {
        alert_page($msg_login_ok,"/admin/mn1/join_partner_list.php");
    } else if($_SESSION["ss_u_level"] == "6") {
        alert_page($msg_login_ok,"/admin/mn1/join_ins_list.php");
    } else {
        // alert_page($msg_login_ok,$url_admin_login_ok);
        alert_page("",$url_admin_login_ok);
    }
}

// 인증 코드 입력 후 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputCode = $_POST['auth_code'];
    if ($inputCode == $_SESSION['auth_code']) {
        $_SESSION['is_authenticated'] = true; // 2차 인증 완료
        unset($_SESSION['auth_code']); // 세션에서 인증 코드 제거


      $session_token=  generateLoginTokenInfo();


      $_SESSION['session_token'] = $session_token;
   

        if($_SESSION["ss_u_level"] == "7") {
            alert_page($msg_login_ok,"/admin/mn1/join_partner_list.php");
        } else if($_SESSION["ss_u_level"] == "6") {
            alert_page($msg_login_ok,"/admin/mn1/join_ins_list.php");
        } else {
            // alert_page($msg_login_ok,$url_admin_login_ok);
            alert_page("",$url_admin_login_ok);
        }
        exit;
    } else {
        $error = '인증 코드가 잘못되었습니다.';
        $_SESSION['otpValid']++;

        if($_SESSION['otpValid'] > 5) {
            $_SESSION['is_authenticated'] = false;
            $_SESSION['auth_code'] = '';
            alert_page("이메일 인증을 다시 시도해 주세요.", "/admin/login.php");
        }
    }
}
?>
<!-- HTML 인증 코드 입력 폼 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$sc_site_title?></title>
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
</script>
</head>

<body style="background-color:#FFFFFF;">

<div class="loginWrap">
<h1><img src="<?=$url_admin?>/images/logo_login.png" alt="insuplus" /></h1>
<p class="txt1">2차 인증 코드를 입력해주세요.</p>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
<form action="verify.php" method="post">
<input type="hidden" name="act" value="ok">
	<p><input type="text" name="auth_code" value="<?=($_SERVER['REMOTE_ADDR'] == '59.15.184.2' ? $_SESSION['auth_code'] : '')?>" autocomplete="off" maxlength="6" placeholder="6자리 인증 코드" required></p>
	<p><input type="submit" value="입력" /></p>
	<p class="copyright">Copyrights © KoreaAssistance, All rights reserved.</p>

    <?php if($_SERVER['REMOTE_ADDR'] == '59.15.184.2') { ?>
    	<p class="copyright">이 메시지는 인슈플러스 개발 서버에 코드아이디어 사무실에서 접속했을 때만 보이며, 2차 인증번호가 자동으로 입력되어 바로 로그인할 수 있습니다. 운영 서버에는 적용되지 않습니다. 작업자 - 이인한</p>
    <?php } ?>
        
</form>
</div>

</body>
</html>
