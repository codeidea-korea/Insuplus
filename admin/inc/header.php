<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
	$html_version = time();	
	if($ss_u_level == "7") { //제휴관리자
		if(!strpos($_SERVER['REQUEST_URI'],"join_partner")) {
			//alert_page("권한이 없습니다.","/admin/mn1/pay_partner_list.php");
			alert_page("권한이 없습니다.","/admin/mn1/join_partner_list.php");
		}
	} else if($ss_u_level == "6") { //보험관리자
		if(!strpos($_SERVER['REQUEST_URI'],"join_ins")) {
			alert_page("권한이 없습니다.","/admin/mn1/join_ins_list.php");
		}
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/_css/admin.css?v=<?=$html_version;?>" rel="stylesheet" type="text/css">
<meta content="IE=9" http-equiv="X-UA-Compatible" />
<?php getLib();?>
<script src="/admin/js/jquery.min.js"></script>

<!-- <script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript" src="/js/ecaso.js"></script> -->
<script src="/admin/js/admin.js"></script>
<script src="/admin/js/jquery-ui.js"></script>
<link href="/_css/jquery-ui.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?= $url_admin ?>js/block.js"></script>
</head>
<body style="background-color:#FFFFFF;">
<!-- Header Start --> 

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background:#29354c;">
				<tr>
					<td height="30">
						<table border="0" cellpadding="0" cellspacing="0" width="100%" >
							<tr>
								<td align="right" valign="top" class="a_global" style="padding-top:3px">
									<a href="javascript: passwdChange();" ><font color="#fff;">비밀번호 변경</font></a>  &nbsp;|&nbsp;
									<a href="<?=$url_root?>" target="_blank"><font color="#fff;">SITE HOME</font></a>  &nbsp;|&nbsp;
									<a href="<?=$url_admin?>"><font color="#339933">ADMIN HOME</font></a>  &nbsp;|&nbsp;
									<a href="<?=$url_admin_login_out?>">LOGOUT</a>
								</td>
							</tr>
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<script>
				function passwdChange(){
					var left = (screen.width - 500) / 2;
					var top = (screen.height - 335) / 2;
					window.open("<?=$url_admin?>mn1/passwd_change.php","passwdChange","width=500,height=335,left=" + left + ",top=" + top + ",scrollbars=yes");
				}
			</script>

			<!--  TomMenu Start -->
			<?
				include $path_admin."inc/top_menu.php";
			?>

			<!--  //TomMenu End -->
			<!-- //Header End -->


			<table border="0" cellpadding="0" cellspacing="0" width="100%" height="500" style="padding-right:20px; box-sizing:borer-box;">
				<tr>
					<td width="198" valign="top" style="padding:20px 10px 20px">

						<!--  LeftMenu Start -->
						<? include $path_admin."inc/left_menu.php"; ?>
						<!--  //LeftMenu End -->

					</td>
					<td width="15">&nbsp;</td>
					<td align="left" valign="top" style="padding:20px 0px 20px 0px;">
						<!-- Content Start -->

