<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "intra";
	$lm = "";
	include $path_admin."inc/header.php";
?>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">상상에센스(사내제안) 게시판</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

				<table width="800" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
						<?php
							$bc_id = "intra5";
							include_once $path_board."board.php";
							$dbcon -> dbcon_close();
						?>
						</td>
					</tr>
				</table>


<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>
