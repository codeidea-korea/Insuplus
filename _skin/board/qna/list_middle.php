<? if ($client_mode=="Y"){ //사용자 ?>
<? if ($list_type == "list") {
	$icon = "label-default";
	if($status == "W") {
		$icon = "label-default";
	} else if($status == "C") {
		$icon = "btn-yellow";
	} else if($status == "A") {
		$icon = "label-notice";
	}
?>

<tr>
	<td class='hidden-xs'><?=print_pr_name($pr_cd);?></td>
	<td class='flex-100 text-left'>
		<span class='m-r-1 hidden visible-xs'>
			[<?=print_pr_name($pr_cd);?>]
		</span>
		<? if($customer_password == '') { ?>
			<a onclick="frontViewGO('<?=$seq?>');" style="cursor: pointer;">
				<?=$subject?>
			</a>
		<? } else { ?>
			<a href='./pop_password.php?seq=<?=$seq?>' data-toggle='pop-modal' data-size='xs' data-href='./pop_password.html' data-title='비밀번호 확인' target='modal_iframe'>
				<?=$subject?>
			</a>
		<? } ?>
	</td>
	<td class='flex-100'><?=all_seed_dec($name)?><span class='m-l-1 p-l-1 border-l-1 hidden visible-xs'><?=$PrintRegDate;?></span></td>
	<td class='td-label p-y-05'><span class="label <?=$icon?> btn-block"><?=$status_arr[$status]?></span></td>
	<td class='hidden-xs'><?=$PrintRegDate;?></td>
</tr>
<? }elseif ($list_type == "null") { ?>
	<td colspan="5">등록 된 데이터가 없습니다.</td>
<? } ?>

<? } else { //관리자?>

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

				<td><?=$nick_name?></td>
				<td><?=$PrintRegDate?></td>
				<td><?=$view_cnt?></td>
				<td><?=$print_reply_image?></td>
			</tr>

		<? }elseif ($list_type == "list") { ?>
			<tr>
				<td><?=$no?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td><?=$print_cate_name?></td>
				<? } ?>
				<td class="l"><a href="javascript:;" onClick="mod_go('<?=$seq;?>')"><?=$subject?></a></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td><?=$print_file?></td>
				<? } ?>

				<td><?=all_seed_dec($name)?></td>
				<td><?=print_pr_name($pr_cd);?></td>
				<td>
					<?if($status=="W") {?>
						<span class="txt_red"><?=$status_arr[$status]?></span>
					<? } else { ?>
						<?=$status_arr[$status]?>
					<? } ?>
				</td>
				<td><?=$PrintRegDate;?></td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="6">등록 된 데이터가 없습니다.</td>
			</tr>

		<? }else{ ?>
		
		<? } ?>

<?}?>