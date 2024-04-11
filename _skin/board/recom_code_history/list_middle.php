<?
//사용자 모드입니다
	if ($client_mode=="Y"){
?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{?>
		<? if ($list_type == "list") { ?>
			<?while ($CateListRs = $dbcon -> fetch_array($ArrPartnerListRs[1]) ) {
				extract($CateListRs);?>
				<? if ($recom_partnership_code == $partnership_code ) {?>
			<tr>
				<td><?=$product_name?></td>
				<td><?=$partnership_name?></td>
				<td><?=$recommendation_code?></td>
				<td><?=$contractor?></td>
				<td class="r"><?=number_format($total_charge)?></td>
				<td><?=$PrintRegDate?></td>
			</tr>
				<?}
			}?>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="8">등록 된 데이터가 없습니다.</td>
			</tr>

		<? } ?>

<?}?>