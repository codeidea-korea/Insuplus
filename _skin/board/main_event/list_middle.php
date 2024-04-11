<? if ($client_mode=="Y"){ //사용자?>

<? }else{ //관리자 ?>
	<? if ($list_type == "list") { ?>
	<tr>
		<td><?=$print_list_image?></td>
		<td class="l"><?=$view_link?><?=$subject?> <?=$print_secret?></a></td>
		<td><input type="button" value="선택" onClick="g_select('<?=$seq?>','<?=$imgfile?>','<?=$subject?>')"/></td>
		<td><?=$PrintRegDate;?></td>
	</tr>
	<? }elseif ($list_type == "null") { ?>
	<tr>
		<td colspan="4"><?=$GLOBALS[msg_list_notdata]?></td>
	</tr>
	<? } ?>
<?}?>