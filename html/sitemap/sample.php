<?
$mn1="sitemap";
$mn2="1";
?>
<?include_once("$_SERVER[DOCUMENT_ROOT]/html/_inc/header.php");?>
		<tr>
			<td id="submain">
				<!--L_MAIN_IMAGE[[[--><!--메인-->

<!--//메인--><!--L_MAIN_IMAGE]]]-->
			</td>
		</tr>
		<tr>
			<td valign="top">
				<?php
					$bc_id = "notice";									// 생성된 게시판 ID 실제로 테이블은 tbl_board_notice
					$client_mode = "Y";								// 게시판스킨에서 Client_mode = Y 에 해당되는 스킨을 가져다 쓰게 된다.
					include_once $path_board."board.php";	// 게시판사용
					$dbcon -> dbcon_close();					// 게시판 DB종료
				?>
			</td>
		</tr>
    </table>



<?include_once("$_SERVER[DOCUMENT_ROOT]/html/_inc/footer.php");?>