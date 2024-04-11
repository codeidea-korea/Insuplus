<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

<?}else{?>

<?if ($list_type == "list") { ?>
<tr>
	<td><?=$no?></td>
	<td><?=$exposure_order?></td>
	<td class="l"><a href="javascript:;" onClick="mod_go('<?=$seq?>')"><?=$subject?> <?=$print_secret?></a></td>
	<td><?=substr($main_recommand_date_s,0,10)?> ~ <?=substr($main_recommand_date_e,0,10)?></td>
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