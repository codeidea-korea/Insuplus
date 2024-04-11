<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>
</tbody>
		</table>
		<div class='clearfix text-center'>
			<ul class='pagination'>
				<? list_page_ljh($page, $total_page, $page_per_block); ?>
			</ul>
		</div>
	</div>
</div>

<?}else{?>

			</table>
			<input type=hidden name=f_delete>
			</form>
			<!-- ### 게시판 끝 ###  -->


			<!-- ### 페이지 시작 ###  -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="70" valign="top" style="padding:11px 0 0 0"><?=$btn_list?></td>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?></td>
					</td>
				</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->

			<!-- ### 버튼 시작 ###  -->
			<? if ($ss_u_level >= $auth_admin) { ?>
			<!-- <table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td colspan="2" height="4"></td>
				</tr>
				<tr>
					<td width="116"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_title_admin.gif"></td>
					<td width="70"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_delete_admin.gif" onClick="checkView('del')" style="cursor:hand"></td>
					<td><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_notice_admin.gif"></td>
				</tr>
			</table> -->
			<? } ?>
			<!-- ### 버튼 끝 ###  -->

<?}?>