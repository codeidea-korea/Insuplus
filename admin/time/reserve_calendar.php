<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "board";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 onLoad="">
<table width=100% border=0 cellpadding=0 cellspacing=0>
<tr>

	<td valign=top>
		<!-- 오른쪽 테이블 START -->
		<table border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td bgcolor=#FFFFFF valign=top>

					<?
					include( "reserve_calendar.inc.php");
					?>
					<!-- #### CONTENT END #### -->
				</td>
			</tr>
		</table>
		<!-- 오른쪽 테이블 END -->
	</td>
</tr>

</table>
</BODY>
</HTML>
<? include $path_admin."inc/footer.php"; ?>
