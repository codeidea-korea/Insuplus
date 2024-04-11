<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

<?}else{?>

			</table>
			<input type=hidden name=f_delete>
			</form>
			<!-- ### 게시판 끝 ###  -->

			<script type="text/javascript">
			<!--
				function chg_s_order(){
					var ff = document.frmCheckDel;
					ff.target="board_iframe";
					ff.action="/_skin/board/<?=$bc_skin?>/chg_s_order.php";
					ff.submit();
				}
			//-->
			</script>
			<!-- ### 페이지 시작 ###  -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="btnWrap">
				<tr>
					<td width="70" valign="top" style="padding:11px 0 0 0"><span class="inp_green1"><input type="button" value="순서변경" onclick="chg_s_order();"></span></td>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?></td>
					</td>
					<td width="70" align="right" valign="top" style="padding:11px 0 0 0">
					</td>
				</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->
<?}?>