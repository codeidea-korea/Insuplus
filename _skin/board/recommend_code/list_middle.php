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
	function checkHistory(seq) {
		// window.name = "부모창 이름"; 
		window.name = "parentForm";
		window.open('/_skin/board/recommend_code/popup_recommend_code_history.php?recommend_seq='+seq,'_p','width=1045,height=530');
	}
</script>
<? if ($list_type == "list") { 
	$SQL = " SELECT count(*) FROM tbl_board_recommend_code_history WHERE recommend_seq = '".$seq."' "; 
	$count = $dbcon -> getCount($SQL);
?>
	<tr>
		<td><?=$no?></td>
		<td><?=$ArrPartnerList[$recom_partnership_code]?></td>
		<td class="l"><?=$view_link?><b><?=$subject?></b></a></td>
		<td><?=$recommendation_code?></td>
		<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
		<td><?=$discount;?>%</td>
		<td><?=number_format($count);?>건</td>
		<td><input type="button" value="내역확인" onClick="checkHistory('<?=$seq?>');"></td>
		<td><?=$PrintRegDate;?></td>
	</tr>
<? }elseif ($list_type == "null") { ?>
	<tr>
		<td colspan="9">등록 된 데이터가 없습니다.</td>
	</tr>

<? } ?>

<?}?>