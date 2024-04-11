<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "intra";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<?
	if ($ss_u_level<6){
		echo "<script>alert('팀장회원 이상만 사용이 가능합니다.');history.back();</script>";
		exit;
	};
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">수술 스케쥴</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

				<table width="698" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
						<?
						include ("caleandar_inc.php");
						?>
						</td>
					</tr>
				</table>


<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>
