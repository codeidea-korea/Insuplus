<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>
		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
									<tr>
										<td><span class="iconSt01">공지</span></td>
										<td><?=$print_cate_name?></td>
										<td class="title">
											<?=$sNew?><?=$view_link?><?=$re?><?=$subject?></a><?=$print_file?><!-- <img src="/images/common/icon/icon_lock.gif" alt="lock" /> -->
										</td>
										<td><?=$nick_name?></td>
										<td><?=$PrintRegDate?></td>
										<td><?=$view_cnt?></td>
									</tr>
		<? }elseif ($list_type == "list") { ?>
									<tr>
										<td><span><?=$no?></span></td>
										<td><?=$print_cate_name?></td>
										<td class="title">
											<?=$view_link?><?=$re?><?=$subject?></a>
										</td>
										<td><?=$nick_name?></td>
										<td><?=$PrintRegDate?></td>
										<td><?=$view_cnt?></td>
									</tr>
		<? }elseif ($list_type == "null") { ?>
									<tr>
										<td height="25" align="center" colspan="6" class="b_name">등록 된 데이터가 없습니다.</td>
									</tr>
		<? }else{ ?>
		.
		<? } ?>

<?}else{?>

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
				<td class="b_subtitle"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center" class="b_name"><?=$print_file?></td>
				<? } ?>

				<td align="center" class="b_name"><?=$nick_name?></td>
				<td align="center" class="b_num"><?=$PrintRegDate?></td>
				<td align="center" class="b_num"><?=$view_cnt?></td>
			</tr>
			<tr>
				<td colspan="7" class="m_line_1px">&nbsp;</td>
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
				<td class="b_subtitle"><?=$view_link?><?=$re?>[<?=$Arr_cost_list[$ext1]?>]<?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center" class="b_name"><?=$print_file?></td>
				<? } ?>

				<td align="center" class="b_name"><?=$nick_name?></td>
				<td align="center" class="b_num"><?=$PrintRegDate?></td>
				<td align="center" class="b_num"><?=$view_cnt?></td>
			</tr>
			<tr>
				<td colspan="7" class="m_line_1px">&nbsp;</td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td height="25" align="center" colspan="30" class="b_name">등록 된 데이터가 없습니다.</td>
			</tr>
			<tr>
				<td colspan="7" class="m_line_1px">&nbsp;</td>
			</tr>
		<? }else{ ?>
		.
		<? } ?>

<?}?>