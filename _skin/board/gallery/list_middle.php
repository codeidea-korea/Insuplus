<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>


		<? }elseif ($list_type == "list") { ?>
		<?
			$list_type_gallery = true;
		?>
				<li style="width:33.3%;float:left;padding-top:20px;text-align:center;">
					<a href="javascript: photo_view('<?=$FC_file_img?>.thumb');"><?=$print_list_image?></a>
					<div class="f_center mt12"><?=$subject?></div>
					<div class="f_center mt12"><span class="inp_black1"><input type="button" value="View" onclick="photo_view('<?=$FC_file_img?>.thumb');"></span></div>
				</li>
		<? }elseif ($list_type == "null") { ?>
				<li style="text-align:center;width:100%;">
					<?=$GLOBALS[msg_list_notdata]?>
				</li>
		<? } elseif ( $list_type == "blank" ) { ?>
		<? } elseif ( $list_type == "line" ) { ?>

		<? }else{ ?>
		.

		<? } ?>


<?
//사용자 모드2입니다
}else if ($client_mode2=="Y"){?>


<?
//사용자 모바일 모드입니다
}else if ($client_mobile_mode=="Y"){
?>




<?
// 관리자 모드
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

				<?
				$filek = $imgfile;
				$ar_filename = setFileName($filek);
				?>
				<td class="b_subtitle">
				<?=$view_link?>
				<img src="/_data/board/<?=$bc_id?>/<?=$ar_filename[0][1]?>.thumb2">
				<?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

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