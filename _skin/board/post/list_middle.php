<?
	$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_01.gif\">";
	if ($reply_ok == "Y") {
		if ( $reply) {
			$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_02.gif\">";
		}
		else {
			$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_03.gif\">";
		}
	}
?>

<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>


<?
if ($mymode == "mypost"){
###############################################
## 마이페이지
###############################################
?>
		<? if ($list_type == "list") { ?>
									<tr>
										<td><?=$no?></td>
										<td><?=$print_cate_name?></td>
										<td><?=$Arr_cost_detail_list[$ext1]?></td>
										<td class="title">
											<?=$view_link?><?=$re?><?=$subject?></a><?=$print_file?>
										</td>
										<td><?=$PrintRegDate?></td>
									</tr>
		<? }elseif ($list_type == "null") { ?>
								<tr><td colspan="5">등록 된 데이터가 없습니다.</td></tr>
		<? }else{ ?>
		.
		<? } ?>

<?
}else{
##############################################
## 일반페이지
##############################################
?>
		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
									<tr>
										<td><span class="iconSt01">공지</span></td>
										<td><?=$print_cate_name?></td>
										<td><?=$Arr_cost_detail_list[$ext1]?></td>
										<td class="title">
											<?=$sNew?><?=$view_link?><?=$re?><?=$subject?></a><?=$print_file?>
										</td>
										<td><?=$nick_name?></td>
										<td><?=$PrintRegDate?></td>
										<td><?=$view_cnt?></td>
									</tr>
		<? }elseif ($list_type == "list") { ?>
									<tr>
										<td><?=$no?></td>
										<td><?=$print_cate_name?></td>
										<td><?=$Arr_cost_detail_list[$ext1]?></td>
										<td class="title"><?=str_replace("width=\"193\" height=\"143\"","width=\"50\" height=\"50\"",$print_list_image)?>
										<?
										if ($writer==$ss_u_id || $secret=='N'){
											$view_link = "<a href=\"javascript:view_go('".$seq."');\">";
											$print_secret = "";
										}	else{
											$view_link = "<a href=\"javascript:view_go_secret('".$seq."');\">";
										}
										?>
											<?=$sNew?><?=$view_link?><?=$re?><?=$subject?></a><?=$print_file?>
											<?=$print_secret?> <?=$print_hidden?>
										</td>
										<td><?=$nick_name?></td>
										<td><?=$PrintRegDate?></td>
										<td><?=$view_cnt?></td>
									</tr>
		<? }elseif ($list_type == "null") { ?>
								<tr><td colspan="7">등록 된 데이터가 없습니다.</td></tr>
		<? }else{ ?>
		.
		<? } ?>

<?}?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{?>

		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
			<tr bgcolor="#F3F3F3">
				<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
				<? } ?>
				<td align="center" height="25" class="b_num"><?=$img_notice?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td align="center" width="70"  class="b_subtitle"><?//=$print_cate_name?></td>
				<? } ?>
				<td class="b_subtitle">[<?=$Arr_cost_detail_list[$ext1]?>]<?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?> </a>
					<?if ($ext3=="Y"){echo "[BEST]";}?>
				</td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center" class="b_name"><?=$print_file?></td>
				<? } ?>

				<td align="center" class="b_name"><?=$nick_name?></td>
				<td align="center" class="b_num"><?=$PrintRegDate?></td>
				<td align="center" class="b_num"><?=$view_cnt?></td>
				<td align="center" class="b_num"><?=$print_reply_image?></td>
			</tr>
			<tr>
				<td colspan="8" class="m_line_1px">&nbsp;</td>
			</tr>

		<? }elseif ($list_type == "list") { ?>
			<tr>
				<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
				<? } ?>
				<td align="center" height="25" class="b_num"><?=$no?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td align="center" width="70"  class="b_subtitle"><?=$print_cate_name?></td>
				<? } ?>
				<td class="b_subtitle">[<?=$Arr_cost_detail_list[$ext1]?>]<?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a><?if ($ext3=="Y"){echo "[BEST]";}?></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center" class="b_name"><?=$print_file?></td>
				<? } ?>

				<td align="center" class="b_name"><?=$nick_name?></td>
				<td align="center" class="b_num"><?=$PrintRegDate?></td>
				<td align="center" class="b_num"><?=$view_cnt?></td>
				<td align="center" class="b_num"><?=$print_reply_image?></td>
			</tr>
			<tr>
				<td colspan="8" class="m_line_1px">&nbsp;</td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td height="25" align="center" colspan="30" class="b_name">등록 된 데이터가 없습니다.</td>
			</tr>
			<tr>
				<td colspan="8" class="m_line_1px">&nbsp;</td>
			</tr>
		<? }else{ ?>
		.
		<? } ?>

<?}?>