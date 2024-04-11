<?
//사용자 모드입니다
	if ($client_mode=="Y"){

				if (!$Arr_cost_detail_list[$ext1]) {
					$Arr_cost_detail_list[$ext1] = "기타";
				}
?>
<? if ($list_type == "list") { 
	if($partner_event_yn == "N"){?>
		<?
		$toDay = date_create(date('Ymd')); //오늘
		$startDate=date_create(substr($start_date, 0, 10));
		$endDate=date_create(substr($end_date, 0, 10));
		//if($toDay >= $startDate && $toDay <= $endDate && $event_type == 'C' && $coupon_size > 0){
		$end_event = false; //이벤트 종료
		if($toDay >= $startDate && $toDay <= $endDate){
			$end_event = true;
		}
		?>
		<div id="" class='event_list' style="cursor: pointer;" onclick="view_go('<?=$seq?>');">
			<div class='row <?=!$end_event ? "disabled":"";?>'>
				<div class='col-md-5 col-sm-5 col-xs-12'>
					<span class='label'><?=!$end_event ? "종료":"진행중";?></span>
					<div class='image'>
						<img src="<?=$FC_file?>" class="thumb" />
					</div>
				</div>
				<div class='col-md-7 col-sm-7 col-xs-12 content'>					
					<h4><?=$subject?></h4>
					<!-- <h5><?=strip_tags($content)?></h5> -->
					<p><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></p>
				</div>
			</div>
		</div>
	<?}?>
<? }elseif ($list_type == "null") { ?>
	<div id=""class='event_list' style="cursor: pointer;" onclick="view_go('<?=$seq?>');">
		<span>등록 된 데이터가 없습니다.</span>
	</div>

<? }else{ ?>
		
<? } ?>
<?
#############################################################
## 관리자 모드
#############################################################
}else{?>

		<? if ($list_type == "list") { ?>
			<tr>
				<td><?=$no?></td>
				<td><?=$event_type=="N" ? "일반" : "쿠폰" ?></td>
				<td><? if($partner_event_yn=="Y") {?><?=$ArrCateList[$event_partnership_code]?><? } ?></td>
				<td><?=$print_list_image?></td>
				<td class="l"><a href="javascript:;" onClick="mod_go('<?=$seq;?>')"><?=$subject?></a></td>
				<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
				<td><?=$view_cnt?></td>
				<td><?=$secret=='Y' ? "비공개" : "공개"?></td>
				<td><?=$PrintRegDate;?></td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td colspan="9">등록 된 데이터가 없습니다.</td>
			</tr>

		<? }else{ ?>
		
		<? } ?>

<?}?>