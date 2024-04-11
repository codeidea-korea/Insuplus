<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

<?}else{?>

<?if ($list_type == "list") {
	$secret_txt = $secret == "Y" ? "공개":"비공개";
	$plan_status_txt = $plan_status == "Y" ? "판매":"판매중지";
?>
	<tr>
		<td><?=$no?></td>
		<td>
			<a href="javascript: mod_go('<?=$seq?>')"><?=$re?><?=print_pr_name($pr_cd)?><?=$print_cmt_cnt?> <?=$sNew?></a>
			<span style="background-color: brown; color: white; padding: 1px 4px; border-radius: 5px; font-size: 0.5em;"><?= $seq ?></span>
		</td>
		<td><?=print_ins($guarantee1_ins_seq)?></td>
		<td><?= $ins_plan_name." - ".$Arr_plan_cd[$plan_cd]?></td>
		<td><?=$s_date?> ~ <?=$e_date?></td>
		<td><?=$plan_status_txt?></td>
		<td class="r"><?=number_format($common_amount)?>원</td>
		<td><?=$secret_txt?></td>
		<td><?=$PrintRegDate?></td>
	</tr>
<? }elseif ($list_type == "null") { ?>
	<tr>
		<td colspan="9">등록 된 데이터가 없습니다.</td>
	</tr>
<? }else{ ?>
.
<? } ?>

<?}?>