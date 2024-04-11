<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "counter";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<?// include("$_SERVER["DOCUMENT_ROOT"]/_util/AceMTcounter/_AceMTcounter.php"); ?>
<table width="800" border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="4">
				<tr>
					<td>
						<span id="AceMTcounterView">오늘(0 Visit, 0명) | 어제(0 Visit, 0명) | 전체(0 Visit, 0명) | 현재(0 Visit, 0명)</span> | <a href="<?=$url_counter?>index.php" target="_blank">통계보기</a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="800" border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td>
			<iframe src="<?=$url_counter?>index.php" width="100%" height="500" frameborder=0></iframe>
		</td>
	</tr>
</table>

<!-- AceMTcounter 이 설치된곳의 디렉토리에 아래와 같은 파일이 들어갈 것입니다. -->
<iframe src="<?=$url_counter?>hiddenAceMTcounter.php" width=0 height=0 frameborder=0></iframe>


<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>
