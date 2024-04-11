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
?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{
?>
		<? if ($list_type == "list") { ?>
			<tr>
				<td><?=$no?></td>
				<td><?=$partnership_name?></td>
				<td><?=$view_link?><?=$re?><b><?=$partnership_code?></b> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$print_hidden?></a></td>
				<!-- 코드로 출력후 스크립트로 이름으로 변경 -->
				<td class="product-category"><?=$ext1?></td>
				<td class="l"><?=$tracking_url?></td>
				<td><?=number_format($partnership_charge)?>%</td>
				<td><?=substr($start_Partner_period, 0, 10)?> ~ <?=substr($end_Partner_period, 0, 10)?></td>
				<!-- <td><?=$PrintRegDate;?></td> -->
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="7">등록 된 데이터가 없습니다.</td>
			</tr>

		<? } ?>

<?}?>