<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>
		<?if($secret != 'Y') {
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
									<tr>
										<td class='text-left flex-100'><span class='label label-notice'>공지</span>&nbsp;<?=$view_link?><?=$re?><?=$subject?></a><?=$print_file?><?=$print_secret?> <?=$print_hidden?></td>
										<td class='td-inline' data-title='조회수'><?=$view_cnt?></td>
										<td class='td-inline' data-title='등록일'><?=$PrintRegDate?></td>
									</tr>
		<? }elseif ($list_type == "list") { ?>
									<tr>
										<td class='text-left flex-100'><?=$view_link?><?=$re?><?=$subject?></a><?=$print_file?><?=$print_secret?> <?=$print_hidden?></td>
										<td class='td-inline' data-title='조회수'><?=$view_cnt?></td>
										<td class='td-inline' data-title='등록일'><?=$PrintRegDate?></td>
									</tr>
		<? }elseif ($list_type == "null") { ?>
									<tr>
										<td colspan="3">등록 된 데이터가 없습니다.</td>
									</tr>
		<? }else{ ?>
		.
		<? } 
		}?>

<?}else{?>

		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
			<tr>
				<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
				<? } ?>
				<td><?=$img_notice?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td><?//=$print_cate_name?></td>
				<? } ?>
				<td class="l"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center"><?=$print_file?></td>
				<? } ?>

				<td><?=$view_cnt?></td>
				<td><?=$secret=='Y' ? "비공개" : "공개"?></td>
				<td><?=$PrintRegDate?></td>
			</tr>

		<? }elseif ($list_type == "list") { ?>
			<tr>
				<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
				<? } ?>
				<td><?=$no?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td><?=$print_cate_name?></td>
				<? } ?>
				<td class="l"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td><?=$print_file?></td>
				<? } ?>

				<td><?=$view_cnt?></td>
				<td><?=$secret=='N' ? "공개" : "비공개" ?></td>
				<td><?=$PrintRegDate?></td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="7">등록 된 데이터가 없습니다.</td>
			</tr>
		<? }else{ ?>
		.
		<? } ?>

<?}?>