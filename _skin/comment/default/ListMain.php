<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

							<li><strong><?=$cmt_u_id?></strong><span><?=$regdate?></span>
								<p><?=nl2br($cmt_content)?></p>
								<? if ( ($cmt_u_id == $ss_u_id && strlen($ss_u_id) > 0 ) || ( strlen($ss_u_id) == 0 && strlen($cmt_u_id) == 0 ) || ( $ss_u_level >= $auth_admin && strlen($ss_u_id) > 0) ) { ?>
								<a href="javascript: CommentDeleteView('<?=$idx?>');"><img src="/images/common/icon/icon_delete.gif" alt="삭제" /></a>
								<?}?>
							</li>

<?}else{
//관리자 모드입니다.
?>
	<tr>
		<td width="27" class="b_memo_num">&nbsp;<?=$cmt_num?></td>
		<td width="70" class="b_memo_name"><b><?=$cmt_u_name?></b>(<?=$cmt_u_id?>)</td>
		<td class="b_memo_name"><?=nl2br($cmt_content)?></td>
		<td width="110" align="right" class="b_memo_num"><?=$regdate?></td>
		<td width="22" align="right" class="b_memo_num">
			<? if ( ($cmt_u_id == $ss_u_id && strlen($ss_u_id) > 0 ) || ( strlen($ss_u_id) == 0 && strlen($cmt_u_id) == 0 ) || ( $ss_u_level >= $auth_admin && strlen($ss_u_id) > 0) ) { ?>
			<img src="<?=$url_skin_comment?>default/images/btn_coment_del.gif" align="absmiddle" vspace="0" onclick="CommentDeleteView('<?=$idx?>')" style="cursor:hand;">
			<!--  | <a href="javascript:CommentModifyView('<?=$idx?>')">MODIFY</a> -->
			<? } ?>&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan=5 class="m_line_1px"></td>
	</tr>
<?}?>