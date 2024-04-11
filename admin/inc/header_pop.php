<?
	if ($bc_login_check == "Y") {
		if ( getLen($ss_u_idx) == 0 ) {
			$dbcon -> dbcon_close();
			alert_page("로그인 후 이용해 주십시오.",$url_login);
			exit;
		}
	}

	#### 게시판 권한설정
	if ( getLen($ss_u_level) > 0 ) {
		$auth_level			= $ss_u_level;
	} else {
		$auth_level			= 0;
	}
	
	// 관리자 설정
	if ( $auth_level >= $auth_admin ) {
		$auth_view			= 1;
		$auth_write			= 1;
		$auth_modify		= 1;
		$auth_delete		= 1;
		$auth_notice		= 1;
		$auth_reply	 		= 1;
		$auth_comment	= 1;
		$auth_hidden		= 1;
		$auth_upload		= 1;
		$auth_download	= 1;
		$auth_secret		= 1;
	}
	else {
		if ( $auth_level >= $bc_auth_view ) $auth_view				= 1;
		if ( $auth_level >= $bc_auth_write ) $auth_write				= 1;
		if ( $auth_level >= $bc_auth_modify ) $auth_modify			= 1;
		if ( $auth_level >= $bc_auth_delete ) $auth_delete			= 1;
		if ( $auth_level >= $bc_auth_notice ) $auth_notice			= 1;
		if ( $auth_level >= $bc_auth_reply ) $auth_reply	 			= 1;
		if ( $auth_level >= $bc_auth_comment ) $auth_comment	= 1;
		if ( $auth_level >= $bc_auth_hidden ) $auth_hidden			= 1;
		if ( $auth_level >= $bc_auth_upload ) $auth_upload			= 1;
		if ( $auth_level >= $bc_auth_download ) $auth_download	= 1;
		if ( $auth_level >= $bc_auth_secret ) $auth_secret		= 1;
	}

	if ($auth_level > 0) {
		$MEMRS = getMemberInfo("u_id", $ss_u_id);
		$now_writer			= $MEMRS[u_id];
		$now_nick_name		= $MEMRS[u_name];
		$now_passwd			= $MEMRS[u_pw];
		$now_email1			= $MEMRS[u_email1];
		$now_email2			= $MEMRS[u_email2];
		$now_homepage		= $MEMRS[u_homepage];
		unset($MEMRS);
	} else {
		$now_writer				= "guest";
		$now_nick_name		= "";
		$now_passwd			= "";
		$now_email1			= "";
		$now_email2			= "";
		$now_homepage		= "";
	}
	
	$writer = $now_writer;
	$nick_name = $now_nick_name;
	$passwd = $now_passwd;
	$email1 = $now_email1;
	$email2 = $now_email2;
	$homepage = $now_homepage;

	$html_version = time();
?>
<html>
<head>
<link href="/_css/admin.css?v=<?=$html_version;?>" rel="stylesheet" type="text/css">
<meta content="IE=9" http-equiv="X-UA-Compatible" />
<?php getLib();?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="/admin/js/jquery-ui.js"></script>
<link href="/_css/jquery-ui.css" rel="stylesheet" type="text/css">
<!-- <script src="//code.jquery.com/jquery-3.3.1.min.js"></script> -->
</head>
<body>
<!-- Header Start -->
