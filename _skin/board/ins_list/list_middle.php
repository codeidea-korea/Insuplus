<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

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
		<td><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>
	
		<? if ( $bc_upfile_cnt > 0 ) { ?>
		<td><?=$print_file?></td>
		<? } ?>
		<td><?=$PrintRegDate?></td>
	</tr>
<? } elseif ($list_type == "list") { ?>
	<tr>
		<? if ($ss_u_level >= $auth_admin) { ?>
		<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
		<? } ?>
		<td><?=$no?></td>
		<td><?=$print_list_image?></td>
		<? if ($bc_category_use == "Y") { ?>
		<td><?=$print_cate_name?></td>
		<? } ?>
		<td class="l"><a href="javascript: mod_go('<?=$seq?>')"><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>
		<td><?=$ext1?><br/><?=$ext2?></td>
		<td><?=$ext3?><br/><?=$ext4?></td>
		<? if ( $bc_upfile_cnt > 0 ) { ?>
		<td><?=$print_file?></td>
		<? } ?>

		<td><?=$PrintRegDate?></td>
	</tr>
<? }elseif ($list_type == "null") { ?>
	<tr>
		<td colspan="6">등록 된 데이터가 없습니다.</td>
	</tr>
<? }else{ ?>
.
<? } ?>

<?}?>