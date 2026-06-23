<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

function getHeader() {
global $pageVal, $subVal, $thVal, $fhVal, $login, $bc_LoginChk, $url_root, $path_root;
global $dbcon;
global $ss_u_idx, $ss_u_id, $ss_u_name, $url_member, $url_Now;
global $sc_site_url, $sc_site_title;
global $OnlyEng;
$login = $bc_LoginChk;//=> 로그인 여부
//    	echo "login : ".$login."<BR>";
//    	echo "menuName : ".$menuName."<BR>";
getLib();
}

require_once $_SERVER["DOCUMENT_ROOT"]."/html/_inc/Mobile_Detect.php";
$detect = new Mobile_Detect;


if ($_REQUEST[pc_mode]=="Y" || $_SESSION[pc_mode]=="Y"){
	$_SESSION[pc_mode] = "Y";
}else{
	// 모바일 장비 확인 (폰과 테블릿 모두)
	if ( $detect->isMobile() ) {
		echo "<script>document.location.href='/m/';</script>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="US">
<head>
<title>홈페이지 타이틀</title>
<link rel="shortcut icon" href="" type="image/x-icon" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8">

<link type='text/css' href='/html/_css/style.css?v=<?=time();?>' rel='stylesheet' />
<link type='text/css' href='/html/_css/sub.css?v=<?=time();?>' rel='stylesheet' />
<link type='text/css' href='/html/_js/jquery.theme.css?v=<?=time();?>' rel='stylesheet' />
<!-- <script type='text/javascript' src="/html/_js/jquery-1.11.1.min.js"></script> -->
<script type='text/javascript' src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type='text/javascript' src='/html/_js/sky.rama.js?v=<?=time();?>'></script>
<script type='text/javascript' src='/html/_js/jquery.custom.js?v=<?=time();?>'></script>
<script type='text/javascript' src='/html/_js/jquery.i18n.properties-min-1.0.9.js?v=<?=time();?>'></script>
<script type='text/javascript' src='/html/_js/durian.common.js?v=<?=time();?>'></script>
<script type='text/javascript' src='/html/_js/jquery-ui.custom.min.js?v=<?=time();?>'></script>
<!-- /DEFAULT HEAD AREA -->
<script type="text/javascript">
<!--
// 즐겨찾기 추가
function bookmarksite(title,url) {
	if (window.sidebar && window.sidebar.addPanel) {
		// FF ver 23 이하.
		window.sidebar.addPanel(title, url, '');
	}
	else {
		if (window.external && ('AddFavorite' in window.external)) {
			// IE
			window.external.AddFavorite(url, title);
		}
		else {
			// Others
			alert('확인을 클릭하신 후 주소창에서 <Ctrl+D>를 누르시면 즐겨찾기에 등록됩니다.');
		}
	}
}
//-->
</script>
<!-- ADN3.0 Tracker[공통] start -->
<script src="//fin.rainbownine.net/js/across_adn_3.0.1.js" type="text/javascript"></script>
<!-- ADN3.0 Tracker[공통] end -->
</head>
<body id="wrap" style="background-color:#FFFFFF; ">

<div id="header">

		<table width="1000" cellpadding="0" cellspacing="0" style="background:#ffffff;" align="center">
		<tr>
			<td id="logo">
				<!--L_SITE_LOGO[[[-->
				<div id="L_SITE_LOGO">
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td align="center" height="70">
							<a href="../"><img src="../_images/common/logo_top.png" border="0" ></a>
						</td>
					</tr>
					</table>
				</div>
				<!--L_SITE_LOGO]]]-->
			</td>
			<td>
				<?include "../_inc/header_top.php";?>
			</td>
			<td id="login">
				<!--L_TOP_GNB[[[-->
				<a href="../">Home</a>
				<a href="../sitemap/sitemap.php">SiteMap</a>
				<!--L_TOP_GNB]]]-->
			</td>
		</tr>
		</table>

		<div class="sub_area"></div>
</div>

<table cellpadding="0" cellspacing="0" width="1000" align="center">
<!--[[AREA_TOP_START]]-->

<!--[[AREA_TOP_END]]-->
