<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

<?}else{?>

<?if ($list_type == "list") { ?>
	<tr>
		<td><?=$no?></td>
		<td><?=$print_list_image?></td>
		<td><?=$exposure_order?></td>
		<td class="l"><?=$view_link?><?=$subject?> <?=$print_secret?></a></td>
		<td><?=$secret=='Y' ? "비공개" : "공개" ?></td>
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