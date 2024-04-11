<? if($client_mode=="Y"){ //사용자모드
	$toDay = date_create(date('Ymd')); //오늘
	$startDate=date_create(substr($start_date, 0, 10));
	$endDate=date_create(substr($end_date, 0, 10));
	$ss_partner_seq = $_SESSION["ss_partner_seq"];
	
	if($partner_event_yn == "Y") { //제휴이벤트 경우에만
		if($alliance_code){
			$alliance_code_dec = decrypt($alliance_code); //복호화
			$partner_seq = getPartnerSeq($alliance_code_dec);
	
			if($alliance_code_dec != $event_partnership_code) { ?>
				<script>
						alert('올바른 경로를 이용해 주세요.');
						window.location.href = '/html/main/index.php';
				</script>
			<? }
			
			
			if($toDay >= $startDate && $toDay <= $endDate){
				if(!$ss_partner_seq){?>
					<script>
						alert('올바른 경로를 이용해 주세요.');
						window.location.href = '/html/main/index.php';
					</script>
				<? 
				} else if($alliance_code_dec != $event_partnership_code){?>
					<script>
					alert('올바른 경로를 이용해 주세요.');
					window.location.href = '/html/main/index.php';
					</script>
				<?
				}
			} else {?>
				<script>
					alert('이벤트 기간이 종료로 되었습니다.');
					window.location.href = '/html/main/index.php?alliance_code=<?=$alliance_code?>';
				</script>
			<? } 
		} else {?>
		<script>
			alert('올바른 경로를 이용해 주세요.');
			window.location.href = '/html/main/index.php';
		</script>
		<? }
	}?>
	<div class="col-md-10 col-sm-9">
		<div class="sub-content cs-wrap">
			<div class='row-border' id='event_view'>
				<div class='detail-col-label hidden-xs'>제목</div>
				<div class='detail-col-input colspan-3 flex-100'>
				<?
				if($toDay >= $startDate && $toDay <= $endDate && $event_type == 'C' && $coupon_size > 0){?>
					<span class='label label-notice m-r-05'>진행중</span><?=$subject?>
					<a class='btn btn-sm btn-navy pull-right hidden-xs' data-toggle='pop-modal' data-size='xs' data-href='pop_coupon_download.php?bc_id=event&id=<?=$seq?>' data-title='쿠폰 다운로드' target='modal_iframe'>쿠폰 다운로드</a>
					<? if($partner_coupon_yn == 'Y'){?>
					<a class='btn btn-sm btn-navy hidden-xs' data-toggle='pop-modal' data-size='xs' data-href='pop_partner_coupon_regist.php?bc_id=event&id=<?=$seq?>' data-title='제휴사 쿠폰 등록' target='modal_iframe' style="margin-left: 10px;">제휴사 쿠폰 등록</a>
					<? } ?>
				<?} else if($event_type == 'C') {?>
					<span class='label label-notice m-r-05' style="background-color: #000; opacity: 0.5; -webkit-opacity: 0.5; -moz-opacity: 0.5; -ms-opacity: 0.5; filter: alpha(opacity=50); -ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=50)';">종료됨</span><?=$subject?>
					<a class='btn btn-sm btn-navy pull-right hidden-xs' href='javascript: alert("이벤트 기간이 지났습니다.");'>쿠폰 다운로드</a>
					<? if($partner_coupon_yn == 'Y'){?>
					<a class='btn btn-sm btn-navy pull-right hidden-xs' href='javascript: alert("이벤트 기간이 지났습니다.");'>제휴사 쿠폰 등록</a>
					<? } ?>
				<?} else {?>
					<?=$subject?>
				<? } ?>
				</div>
				<div class='detail-col-label valign-middle hidden-xs'>이벤트 내용</div>
				<div class='detail-col-input colspan-3 flex-column flex-100 p-y-2'>
					<?=$content?>
					<div class='m-t-2 text-center text-lg-center text-md-center text-sm-center' >
					<?if($toDay >= $startDate && $toDay <= $endDate && $event_type == 'C' && $coupon_size > 0){?>
						<a class='btn btn-lg btn-navy'data-toggle='pop-modal' data-size='sm' data-href='pop_coupon_download.php?bc_id=event&id=<?=$seq?>' data-title='쿠폰 다운로드' target='modal_iframe'><i class='ti ti-download'></i>&nbsp;쿠폰 다운로드</a>
						<? if($partner_coupon_yn == 'Y'){?>
						<a class='btn btn-lg btn-navy'data-toggle='pop-modal' data-size='sm' data-href='pop_partner_coupon_regist.php?bc_id=event&id=<?=$seq?>' data-title='제휴사 쿠폰 등록' target='modal_iframe'><i class='ti ti-download'></i>&nbsp;제휴사 쿠폰 등록</a>
						<? } ?>
					<?} else if($event_type == 'C') {?>
						<a class='btn btn-lg btn-navy' href='javascript: alert("이벤트 기간이 지났습니다.");'><i class='ti ti-download'></i>&nbsp;쿠폰 다운로드</a>
						<? if($partner_coupon_yn == 'Y'){?>
						<a class='btn btn-lg btn-navy' href='javascript: alert("이벤트 기간이 지났습니다.");'><i class='ti ti-download'></i>&nbsp;제휴사 쿠폰 등록</a>
						<? } ?>
					<? } ?>
					</div>
				</div>
			</div>
			<div class='row m-t-3'>
				<div class='col-md-2 col-sm-3 col-xs-4'>
					<a href="javascript:;" onClick="frontList()" class='btn btn-block btn-theme-dark light'>목록</a>
				</div>
			</div>
		</div>
	</div>
<? }else{ //관리자 모드?>
<script type="text/javascript">

$( document ).ready( function() {
mod_go('<?=$seq?>');
});
//-->
</script>
<?}?>