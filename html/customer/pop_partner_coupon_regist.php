<?php
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

	if($_GET["id"] != null) {
		$seq = $_GET["id"];
		$SQL =  " SELECT 
						e.seq
						,e.category
						,e.subject
						,e.content
						,e.partner_coupon_name as coupon_name
						,e.start_date
						,e.end_date
						,e.expire_date_s
						,e.expire_date_e
						,e.partner_coupon_discount as discount
						,(SELECT COUNT(*) FROM tbl_partner_coupon where event_seq = e.seq) as coupon_size
						,e.event_type
						,e.event_url
						,e.event_partnership_code
						,e.partner_coupon_yn
				FROM tbl_board_event e
				WHERE  seq = '".$seq."' ";
		
		$result = $dbcon -> query($SQL);
			
	}

	include '../_include/_header.html';

?>
<form name="WriteForm" id="WriteForm">
<? while($ListRs = $dbcon -> fetch_array($result) ) {
	extract($ListRs);
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
	<div class='row-border form-inline'>
		<div class='detail-col-label'>휴대폰번호</div>
		<div class='detail-col-input'><input type="number" id="hp" name='hp' placeholder="휴대폰번호를 입력해 주세요." class="form-control" size='20' <?=$OnlyNumber?>/></div>
		<div class='detail-col-label'>쿠폰 번호</div>
		<div class='detail-col-input'><input type="text" id="coupon" name='coupon' placeholder="쿠폰 번호를 입력해 주세요." class="form-control" size='200'/></div>
		<?}?>
	</div>
</form>
<div class='clearfix text-center m-t-1'>
	<p class='text-danger'>※ 휴대폰번호 입력 후 ‘확인‘ 버튼을 클릭하시면 할인 쿠폰이 등록되며 같은 휴대폰번호로 가입시 할인을 적용 받으실 수 있습니다.  </p>
</div>
<div class='row m-t-3'>
	<div class='col-xs-8 col-xs-offset-2'>
		<a href='javascript: submit_coupon();' class='btn btn-block btn-theme-dark'><i class='ti ti-download'></i>확인</a>
	</div>
</div>
<script>
	function submit_coupon() {
		var ff = document.WriteForm;
		if(!ff.hp.value) {
			alert('휴대폰번호를 입력해 주세요.');
			ff.hp.focus();
			return;
		} else if(ff.hp.value.length > 13 || ff.hp.value.length < 9) {
			alert('휴대폰번호를 확인해 주세요.');
			ff.hp.focus();
			return;
		} else if(!ff.coupon.value) {
			alert('쿠폰 번호를 입력해 주세요.');
			ff.coupon.focus();
			return;
		}
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

		ff.mode.value = "insert";

		var params = jQuery("#WriteForm").serialize();
		var request = $.ajax({
			type : "POST",
			url : "./pop_partner_coupon_regist_ajax.php",
			cache : false,
			data:params,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			dataType: 'json',
			success: function (d) {
				if(d.result=="1") {
					alert('쿠폰을 받았습니다.');
					parent.closeModal();
					window.close();
				} else if(d.result=="2") {
					alert("이미 등록 되었거나 사용할 수 없는 쿠폰입니다.");
					return false;
				} else if(d.result=="3") {
					alert("이벤트 기간내 1회만 등록할 수 있습니다.");
					return false;
				} else if(d.result=="4") {
					alert("이벤트 기간이 아닙니다.");
					parent.closeModal();
					window.close();
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

