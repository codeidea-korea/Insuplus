<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

$tm = "main";
$lm = "";
include $path_admin."inc/header.php";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top" width="650">


			<table width="600">
				<tr>
					<td><b>질문과 답변</b></td>
				</tr>
				<tr>
					<td>
						<?php
//						$bc_id = "qna";
//						include_once $path_board."board.php";
//						$dbcon -> dbcon_close();
						?>
					</td>
				</tr>
				<tr>
					<td><b>최근예약자 리스트</b></td>
				</tr>
				<tr>
					<td>
						<!-- 예약자 리스트 -->
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>



<?
include $path_admin."inc/footer.php";
?>

<?
$dbcon -> dbcon_close();
?>

