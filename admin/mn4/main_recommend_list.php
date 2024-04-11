<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN4";
	$lm = "";
	include $path_admin."inc/header.php";
	
?>
<script>
	function popup_main_roll() {
		var popup_member = window.open('popup_main_roll.php','popup_main_roll','width=700,height=500, top=0, left=0');
	}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">메인 추천 관리</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<!-- 190725 수정 800px->100% -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
		<?php
			$bc_id = "main_recommend";
			include_once $path_board."board.php";
			$dbcon -> dbcon_close();
		?>
		</td>
	</tr>
</table>


<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>