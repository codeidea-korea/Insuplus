<?
//사용자 모드입니다
	if ($client_mode=="Y"){
?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{?>
<script type="text/javascript">
	function checkHistory(code) {
		// window.name = "부모창 이름"; 
		window.name = "parentForm";
		window.open('/admin/board/popup.php?bc_id=recom_code_history&code='+code,'_p','width=1045,height=530');
	}
</script>
		<? if ($list_type == "list") { ?>
			<?while ($CateListRs = $dbcon -> fetch_array($ArrPartnerListRs[1]) ) {
				extract($CateListRs);?>
				<? if ($recom_partnership_code == $partnership_code ) {?>
			<tr>
				<td><?=$no?></td>
				<td><?=$partnership_name?></td>
				<td><?=$view_link?><b><?=$subject?></b></a></td>
				<td><?=$recommendation_code?></td>
				<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
				<td><?=$discount;?>%</td>
				<td><?=$discount;?>건&nbsp<input type="button" value="내역확인" onClick="checkHistory('<?=$recommendation_code?>');"></td>
				<td><?=$PrintRegDate;?></td>
			</tr>
				<?}
			}?>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="8">등록 된 데이터가 없습니다.</td>
			</tr>

		<? } ?>

<?}?>