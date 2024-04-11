<?
	$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_01.gif\">";

		if ( $reply_ok == "Y" && $reply) {
			$print_reply_image = "답변완료";
			$reply_css = "class=\"end\"";
		}else {
			$print_reply_image = "답변대기";
			$reply_css = "";
		}

?>

<?
//사용자 모드입니다
	if ($client_mode=="Y"){

				if (!$Arr_cost_detail_list[$ext1]) {
					$Arr_cost_detail_list[$ext1] = "기타";
				}
?>


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
				<td class="l"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>
				<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
				<td><?=$secret=='Y' ? "비공개" : "공개"?></td>
				<td><?=$PrintRegDate;?></td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
								<tr><td colspan="7">등록 된 데이터가 없습니다.</td></tr>
		<? }else{ ?>
		.
		<? } ?>

<?
}else{
##############################################
## 일반페이지
##############################################
?>
		<? if ($list_type == "list") { ?>
			<tr>
				<td><?=$no?></td>
				<td><?=$print_cate_name?></td>
				<td class="l"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>
				<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
				<td><?=$secret=='Y' ? "비공개" : "공개"?></td>
				<td><?=$PrintRegDate;?></td>
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
				<td><?=$img_notice?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td><?//=$print_cate_name?></td>
				<? } ?>
				<td class="l"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td><?=$print_file?></td>
				<? } ?>

				<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
				<td><?=$PrintRegDate?></td>
				<td><?=$view_cnt?></td>
				<td><?=$print_reply_image?></td>
			</tr>

		<? }elseif ($list_type == "list") { ?>
			<tr>
				<td><?=$no?></td>
				<td><?=$print_cate_name?></td>
				<td class="l"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>
				<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
				<td><?=$secret=='Y' ? "비공개" : "공개"?></td>
				<td><?=$PrintRegDate;?></td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="7">등록 된 데이터가 없습니다.</td>
			</tr>

		<? }else{ ?>
		
		<? } ?>

<?}?>