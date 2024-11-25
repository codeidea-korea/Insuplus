<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	if($_GET["id"] != null) {
		$seq = $_GET["id"];
		$SQL =  " SELECT 
				seq
				,category
				,subject
				,content
				,coupon_name
				,start_date
				,end_date
				,expire_date_s
				,expire_date_e
				,discount
				,coupon_size
				,event_type
				,event_url
				,event_partnership_code
				,duplicate_status_yn
				,insurance_discount_applied
				,service_fee_discount_applied
				,insurance_discount_rate
				,insurance_max_discount_amount
				,service_fee_discount_rate
				,service_fee_max_discount_amount
				,subscription_start_day
				,subscription_end_day
				,event_category_master_seq
				,min_companion
				,max_companion
		FROM tbl_board_event WHERE seq = '".$seq."' ";
		$result = $dbcon -> query($SQL);
	}
	include '../_include/_header.html';
?>
<form name="WriteForm" id="WriteForm" onsubmit="return false">
<? while($ListRs = $dbcon -> fetch_array($result) ) {
	extract($ListRs);
	if($event_type == 'C' && is_null($discount)){
		$discount = 0;
		$symbol = '';
		$discountName = '';
		$discountValue = '';
		if($service_fee_discount_applied == 'F') {
			$symbol = '원';
			$discount = number_format($service_fee_max_discount_amount);
			$discountName = '서비스료 할인';
		} else {
			if($insurance_discount_applied == 'P' && $service_fee_discount_applied == 'P') {
				$symbol = '%';
				$discount = max($insurance_discount_rate, $service_fee_discount_rate);
				$discountName = '인슈플러스 할인';
			} else if($insurance_discount_applied == 'P') {
				$symbol = '%';
				$discount = $insurance_discount_rate;
				$discountName = '인슈플러스 할인';
			} else if($service_fee_discount_applied == 'P'){
				$symbol = '%';
				$discount = $service_fee_discount_rate;
				$discountName = '서비스료 할인';
			}
		}
		$discountValue = $discount.$symbol;
	}
?> 
<input type="hidden" name="mode" />
<input type="hidden" name="seq" value="<?=$seq?>" />
<input type="hidden" name="category" value="<?=$category?>" />
<input type="hidden" name="coupon_name" value="<?=$coupon_name?>" />
<input type="hidden" name="start_date" value="<?=substr($start_date, 0, 10)?>" />
<input type="hidden" name="end_date" value="<?=substr($end_date, 0, 10)?>" />
<input type="hidden" name="expire_date_s" value="<?=substr($expire_date_s, 0, 10)?>" />
<input type="hidden" name="expire_date_e" value="<?=substr($expire_date_e, 0, 10)?>" />
<input type="hidden" name="discount" value="<?=$discount?>" />
<input type="hidden" name="coupon_size" value="<?=$coupon_size?>" />
<input type="hidden" name="event_type" value="<?=$event_type?>" />
<input type="hidden" name="event_url" value="<?=$event_url?>" />
<input type="hidden" name="event_partnership_code" value="<?=$event_partnership_code?>" />
<input type="hidden" name="symbol" value="<?=$symbol?>" />
	<div class='row-border form-inline'>
		<div class='detail-col-label'>쿠폰명</div>
		<div class='detail-col-input'><?=$coupon_name?></div>
		<div class='detail-col-label'>쿠폰 사용기간</div>
		<div class='detail-col-input'><?=substr($expire_date_s, 0, 10)?> ~ <?=substr($expire_date_e, 0, 10)?></div>
		<div class='detail-col-label'><?= $discountName?></div>
		<div class='detail-col-input'><?=$discountValue?></div>
		<div class='detail-col-label'>휴대폰번호</div>
		<div class='detail-col-input'><input type="number" id="hp" name='hp' placeholder="휴대폰번호를 입력해 주세요." class="form-control" size='20' <?=$OnlyNumber?>/></div>
		<?}?>
	</div>
</form>
<div class='clearfix text-center m-t-1'>
	<p class='text-danger'>* 고객님의 정보는  쿠폰받기 외 다른 용도로 사용하지 않습니다. </p>
</div>
<div class='row m-t-3'>
	<div class='col-xs-8 col-xs-offset-2'>
		<a href='javascript: submit_coupon();' class='btn btn-block btn-theme-dark'><i class='ti ti-download'></i> 쿠폰받기</a>
	</div>
</div>
<script>
	// 숫자만 입력
	function OnlyNumber() {
		var lkeycode = window.event.keyCode;
		var sOrg = String.fromCharCode(lkeycode);

		if(!sOrg.match(/^[\d|\.]/)) {
			window.event.keyCode = 0;
		}
		// if  (lkeycode < 48 || lkeycode > 57)
		// window.event.keyCode = 0;
	}

	function submit_coupon() {
		var ff = document.WriteForm;
		if(!ff.hp.value) {
			alert('휴대폰번호를 입력해 주세요.');
			ff.hp.focus();
			return;
		} else if(ff.hp.value.length != 11) {
			alert('휴대폰번호를 확인해 주세요.');
			ff.hp.focus();
			return;
		}

		ff.mode.value = "insert";

		var params = jQuery("#WriteForm").serialize();
		var request = $.ajax({
			type : "POST",
			url : "./pop_coupon_download_ajax.php",
			cache : false,
			data:params,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			dataType: 'json',
			success: function (d) {
				if(d.result=="1") {
					alert('쿠폰을 받았습니다.');
					parent.closeModal();
				} else if(d.result=="2") {
					alert("이미 쿠폰을 받으셨습니다.");
					return false;
				} else if(d.result=="3") {
					alert("쿠폰이 모두 소진되었습니다.");
					return false;
				} else if(d.result=="4") {
					alert("이벤트 기간이 아닙니다..");
					return false;
				}
			},
			error : function(xhr, status, error) {
				alert("실패했습니다. 관리자에게 문의하십시오.");
				return false;
			}
		});
		request.done(function(result) {

		});
	}
</script>
<?php
	$dbcon -> dbcon_close();
	include '../_include/_footer.html';
?>

