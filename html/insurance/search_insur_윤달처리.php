<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	include $_SERVER["DOCUMENT_ROOT"]."/_config/Mobile_Detect.php";
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	
	$detect = new Mobile_Detect;
	
	$today = date("Y-m-d");
	$tomorrow = date("Y-m-d" ,strtotime("+1 day"));
	$s_date_hour = date("H",strtotime("+1 hour"));


	$SQL_PR = "SELECT seq,ext7 FROM tbl_board_product WHERE secret = 'Y' ORDER BY sort_order ASC "; //출국목적
	$RS_PR = $dbcon -> query($SQL_PR);
	
	if($PR_SEQ) { //상품번호가 있는 경우
		$PR_INFO = getInsuProductInfo($PR_SEQ);
		if($_SESSION["ss_partner_seq"] != null && $PR_INFO["ext2"] == 'N'){
			$SQL_PR = "SELECT seq,ext7 FROM tbl_board_product WHERE seq = '".$PR_SEQ."' ORDER BY sort_order ASC "; //출국목적
			$RS_PR = $dbcon -> query($SQL_PR);
		}
	} else {
		$SQL = "SELECT seq FROM tbl_board_product WHERE  secret = 'Y' ORDER BY sort_order ASC LIMIT 0,1 ";
		$RS = $dbcon -> query($SQL);
		$ROW =  $dbcon -> fetch_array($RS);
		$PR_SEQ = $ROW["seq"];
		$PR_INFO = getInsuProductInfo($PR_SEQ);
	}
	
	//선택한 플랜
	if(count($_GET["compare_seq"]) > 1) {
		$compare_seq = "";
		for($i=0; $i<count($_GET["compare_seq"]); $i++) {
			if($_GET["compare_seq"][$i]) {
				if($compare_seq) $compare_seq .= ",";
				$compare_seq .= $_GET["compare_seq"][$i];
			}
		}
	} else {
		$compare_seq = $_GET["compare_seq"];
	}
?>
<script src="../_js/jquery.min.js"></script>


		<div class="breadcrumb-image" style="background-image:url('<?=$PR_INFO["imgfile"]?>')">
			<div class="container">
				<h2><?=$PR_INFO["subject"]?></h2>
				<h4><?=$PR_INFO["content"]?></h4>
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li><?=$PR_INFO["subject"]?></li>
				</ol>
            </div>
        </div>
		<div class="container">
			<div class="sub-content search-wrap">
				<div class='row'>
					<div class='col-sm-10 col-sm-offset-1'>
						<form name="fregist" id="fregist" method="post" enctype="multipart/form-data" autocomplete="off">
						<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>">
						<input type="hidden" name="gender" value="M">
						<input type="hidden" name="purpose" value="<?=$PR_INFO["ext7"]?>">
						<input type="hidden" name="compare_seq" value="<?=$compare_seq?>">
						<input type="hidden" name="add_gender[]" value="">
						<input type="hidden" name="add_gender[]" value="">
						<input type="hidden" name="add_gender[]" value="">
						<input type="hidden" name="add_gender[]" value="">
						<input type="hidden" name="add_gender[]" value="">
							<div class='panel'>
								<div class='panel-body text-md-center text-sm-left text-xs-left'>
								
									<h3 class='text-black'><span class='point'>여행하실 고객님의 정보를 <br class='hidden visible-xs' />입력해 주세요.</span></h3>
									<div class='row-border m-t-3' id='search_insur'>
										<div class='detail-col-label'>출국목적</div>
										<div class='detail-col-input colspan-3 flex-wrap'>
											<div class='btn-group btn-group-justified btn-group-multi'>
												<? while($row_pr = $dbcon -> fetch_array($RS_PR)){ //상품 검색 ?>
													<div class='btn-group'><a class='btn <?=$row_pr["seq"]==$PR_SEQ ? "btn-theme-bg":"btn-default"?>' ata-toggle='alert-modal' data-size='xs' data-title='알림' data-btn-text='<?=$row_pr["subject"]?>' data-content='<?=$row_pr["ext7"]?>' data-href='./search_insur.php?PR_SEQ=<?=$row_pr['seq']?>' <? if($row_pr['seq'] != $PR_SEQ) { ?>onClick='fn_chg_propose(this,"<?=$row_pr["ext7"]?>")' <? } ?>><?=$row_pr["ext7"]?></a></div>
												<? } ?>
												<!-- <a class='btn btn-default' data-toggle='alert-modal' data-size='sm' data-title='알림' data-content='유학' target='modal_iframe' onclick="fn_chg_ins('2');">유학</a>
												<a class='btn btn-default' data-toggle='alert-modal' data-size='sm' data-title='알림' data-content='워킹 홀리데이' target='modal_iframe' onclick="fn_chg_ins('3');">워킹 홀리데이</a>
												<a class='btn btn-default' data-toggle='alert-modal' data-size='sm'  data-title='알림' data-content='장기체류' target='modal_iframe' onclick="fn_chg_ins('4');">장기체류</a> -->
											</div>
										</div>
										<div class='detail-col-label'>계약자 생년월일</div>
										<div class='detail-col-input colspan-3 flex-wrap'>
											<div class='col-md-3 col-sm-4 col-xs-7 p-x-0'>
												<input type="text" name='birth' placeholder="예)20190101" class="form-control" size='10' maxlength="8" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"/>
											</div>
											<div class='col-md-3 col-sm-4 col-xs-5 p-r-0'>
												<div class='btn-group btn-group-justified'>
													<a class='btn btn-theme-bg btn-gender' id="btn_gender_M" onclick="fn_chg_gender(0,'M')">남성</a>
													<a class='btn btn-default btn-gender' id="btn_gender_F" onclick="fn_chg_gender(0,'F')">여성</a>
												</div>
											</div>
										</div>

										<div class='detail-col-label'>출국일</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-5 col-sm-7 col-xs-7 p-x-0' id="act_date1">
												<input type="text" name='s_date' id="s_date" placeholder="<?=$today;?>" class="form-control " size='10'/><!-- datepicker -->
											</div>
											<div class='col-md-4 col-sm-5 col-xs-5 p-r-0'>
												<? if($PR_INFO["ext1"] == "Y") {
													//$s_date_hour
												?>
												<select class='form-control' name="s_date_time">
													<? for($z=0; $z<24; $z++) { ?>
													<option value='<?=sprintf("%02d", $z)?>' <?=sprintf("%02d", $z) == "00" ? "selected":""?>><?=sprintf("%02d", $z)?>시</option>
													<? } ?>
												</select>
												<? } ?>
											</div>
										</div>
										<div class='detail-col-label'>귀국일</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-5 col-sm-7 col-xs-7 p-x-0' id="act_date2">
												<input type="text" name='e_date' id="e_date" placeholder="<?=$tomorrow;?>" class="form-control  edate" size='10'/><!-- datepicker -->
											</div>
											<div class='col-md-4 col-sm-5 col-xs-5 p-r-0'>
												<? if($PR_INFO["ext1"] == "Y") {?>
												<select class='form-control' name="e_date_time">
													<? for($z=0; $z<24; $z++) {?>
													<option value='<?=sprintf("%02d", $z)?>' <?=sprintf("%02d", $z) == "00" ? "selected":"";?>><?=sprintf("%02d", $z)?>시</option>
													<? } ?>
												</select>
												<? } ?>
											</div>
										</div>
										<? if($PR_INFO["ext1"] == "Y") {?>
										<div class='detail-col-label'>동반인 선택</div>
										<div class='detail-col-input colspan-3 flex-wrap form-inline'>
											<select class='form-control' name='select_add_people' id='select_add_people'>
												<option value="">선택</option>
												<option value='1'>1명</option>
												<option value='2'>2명</option>
												<option value='3'>3명</option>
												<option value='4'>4명</option>
												<option value='5'>5명</option>
											</select>
										</div>
										<? } ?>
									</div>
									<? if($PR_INFO["ext1"] == "Y") {?>
									<div id="variablePeople">
										<div class="row">
											<div id="add_people_1" class='col-md-4 col-sm-6 col-xs-12' data-number="1"></div>
											<div id="add_people_2" class='col-md-4 col-sm-6 col-xs-12' data-number="2"></div>
											<div id="add_people_3" class='col-md-4 col-sm-6 col-xs-12' data-number="3"></div>
											<div id="add_people_4" class='col-md-4 col-sm-6 col-xs-12' data-number="4"></div>
											<div id="add_people_5" class='col-md-4 col-sm-6 col-xs-12' data-number="5"></div>
										</div>
										<div class='agree-check text-left m-t-1'>
											동반인 가입을 동의합니다.
											<a data-toggle='pop-modal' data-size='sm' data-href='./pop_rule_group.html' data-title='동반인 가입 동의' target='modal_iframe' class='btn btn-md btn-default m-x-1'>자세히 보기</a>
												<div class='checkbox checkbox-inline'>
													<input type='checkbox' name='agree_group' id='agree_group' />
													<label for='agree_group'>동의</label>
											</div>
									</div>
									</div>
									<? } ?>
									<p class='clearfix text-left text-black m-t-2'>
										<strong>* 보험가입안내</strong><br>
										<?=nl2br($PR_INFO["ext6"]);?>
									</p>
								</div>
								<div class='panel-footer'>
									<a class='btn btn-lg btn-block btn-theme-bg' href="javascript: fregist_submit();">상품가격 확인 </a>
								</div>
							</div>
							<div class='row text-center'>
								<div class='col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 p-x-0'>
									<div class='row btn-icon-group p-n'>
										<div class='col-xs-6'>
											<a class='btn btn-block btn-default btn-icon btn-cscenter' href='../customer/cs_center.php'>
												<p><small class='text-gray'>24시간 알람 센터</small></p>
												<h3 class='text-danger'><?=$insuplus_phone;?></h3>
											</a>
										</div>
										<div class='col-xs-6'>
											<a class='btn btn-block btn-default btn-icon btn-charity' href='./pr_plans.php?PR_SEQ=<?=$PR_SEQ?>'>
												<p class='text-black'>인슈플러스</p>
												<h3 class='text-black'>보험 상품안내</h3>
											</a>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
        </div>

<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>
<script type="text/javascript">
<!--
function fn_chg_propose(t) { //출국목적 선택

	//jQuery(t).parent().parent().find('.btn').removeClass('btn-theme-bg').addClass('btn-default');
	//jQuery(t).toggleClass('btn-default').toggleClass('btn-theme-bg');
	jQuery('#alert_modal .modal-header h3').text($(t).data('title'));
	jQuery('#alert_modal').find('.select').text($(t).data('content'));
	jQuery('#alert_modal .btn-theme-dark').find('.select').text($(t).data('btn-text'));
	jQuery('#alert_modal').find('.modal-dialog').addClass('modal-'+$(this).data('size'));
	jQuery('#alert_modal .btn-theme-dark').attr("href",$(t).data('href'));
	$('#alert_modal').modal('show');
	e.preventDefault();
}
function fn_chg_gender(idx,val){
	var ff = document.fregist;
	ff.gender.value = val;
	$(".btn-gender").removeClass('btn-theme-bg').addClass('btn-default');
	$("#btn_gender_"+val).removeClass('btn-default').addClass('btn-theme-bg');
//	ff["gender[]"][idx].value = val;
}
function fn_chg_add_gender(t,idx,val){
	var ff = document.fregist;
//	ff.gender.value = val;
	ff["add_gender[]"][idx].value = val;

	if(val=="M") {
		$(t).removeClass("btn-default");
		$(t).addClass("btn-theme-bg");
		$(t).next().removeClass("btn-theme-bg");
		$(t).next().addClass("btn-default");
	} else {
		$(t).removeClass("btn-default");
		$(t).addClass("btn-theme-bg");
		$(t).prev().removeClass("btn-theme-bg");
		$(t).prev().addClass("btn-default");
	}
}
function fregist_submit(){
	var ff = document.fregist;

	<? if($PR_INFO["ext1"] == "Y") {?>
	var period = 90;
	var s_date_time = ff.s_date_time.value;
	var e_date_time = ff.e_date_time.value;
	<? } else if($PR_INFO["ext1"] == "N") {?>
	var period = 365;
	var s_date_time = "00";
	var e_date_time = "00";
	<? } ?>
	
	var today = "<?=$today?>";
	today = today.replace(/-/gi,"");
	
	var sel_s_date = ff.s_date.value.replace(/-/gi,"");
	var sel_e_date = ff.e_date.value.replace(/-/gi,"");

	var date_pattern = /^(19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[0-1])$/; 



	var diffDate_1 = new Date(sel_s_date.substring(0,4),sel_s_date.substring(4,6),sel_s_date.substring(6,8),s_date_time);
    var diffDate_2 = new Date(sel_e_date.substring(0,4),sel_e_date.substring(4,6),sel_e_date.substring(6,8),e_date_time);
 
    //diffDate_1 =new Date(diffDate_1.getFullYear(), diffDate_1.getMonth()+1, diffDate_1.getDate());
    //diffDate_2 =new Date(diffDate_2.getFullYear(), diffDate_2.getMonth()+1, diffDate_2.getDate());
 
    var diff = Math.abs(diffDate_2.getTime() - diffDate_1.getTime());
    diff = Math.ceil(diff / (1000 * 3600 * 24))-1;

	
	if (ff.birth.value==""){
		alert("계약자 생년월일을 입력해주세요");
		return false;
	}

	if(!date_pattern .test(ff.birth.value)){
		alert("올바른 생년월일을 입력해 주세요.");
		ff.birth.focus();
		return;
	}


	if (ff.s_date.value==""){
		alert("출국일을 입력해주세요");
		return false;
	}
	if (ff.e_date.value==""){
		alert("귀국일을 입력해주세요");
		return false;
	}
	<? if($PR_INFO["ext1"] == "Y") {?>
	if(sel_s_date < today) {
		alert("개시일은 오늘날짜부터 가입 가능합니다.");
		ff.s_date.focus();
		return false;
	}
	<? } else if($PR_INFO["ext1"] == "N") {?>
	if(sel_s_date <= today) {
		alert("개시일은 다음날부터 가입 가능합니다.");
		ff.s_date.focus();
		return false;
	}
	<? } ?>

	if(sel_s_date == today) {
		if(ff.s_date_time){
			if(ff.s_date_time.value < "<?=$s_date_hour?>") {
				alert("개시일 시간은 <?=$s_date_hour?>시부터 가입 가능합니다.");
				return;
			}
		}
	}

	if(sel_e_date < today) {
		alert("보험종료일은 개시일 다음날부터 가입 가능합니다.");
		ff.e_date.focus();
		return false;
	}

	if(sel_s_date >= sel_e_date) {
		alert("보험종료일을 다시 확인해 주세요.");
		ff.e_date.focus();
		return false;
	}

	if(diff > period) {
		if(diff == 366 && sel_s_date.substring(4,8) < sel_e_date.substring(4,8)) {
			alert("보험가입기간은 최대 365일 입니다.");
			ff.s_date.focus();
			return false;
		} else if (diff != 366){
			alert("보험가입기간은 최대 "+period+"일 입니다.");
			ff.s_date.focus();
			return false;
		}
    }


	if($("#select_add_people").val() > 0) { //동반인이 있는 경우

		for(var i=1; i <= $("#select_add_people").val(); i++ ) {
			if(!$("#add_birth_"+i).val()) {
				alert("동반자("+i+") 생년월일을 입력해 주세요.");
				$("#add_birth_"+i).focus();
				return;
			}

			if(!date_pattern .test($("#add_birth_"+i).val())){
				alert("올바른 동반자("+i+") 생년월일을 입력해 주세요.");
				$("#add_birth_"+i).focus();
				return;
			}

			var k = i-1
			if(!$("input[name='add_gender[]'").eq(k).val()) {
				alert("동반자("+i+") 성별을 선택해 주세요.");
				return;
			}
		}

		if(!$("#agree_group").is(":checked")) {
			alert("동반인 가입을 동의해주세요.");
			$("#agree_group").focus();
			return false;
		}
	}
	
	<?if ($compare_seq){?>
		ff.action = "./register_step_01.php";
	<?}else{?>
		ff.action = "./register_step_00.php";
	<?}?>
	ff.submit();
}
function fn_chg_ins(val,chk_p){
	var date_period = "";
	var ff = document.fregist;
	if (chk_p=="Y"){date_period = 90;}
	if (chk_p=="N"){date_period = 365;}
	ff.PR_SEQ.value=val;

	<? if ( $detect->isMobile() ) { ?>
	    $("input[name='s_date']").attr("type","date");
	    $("input[name='s_date']").addClass("input-datepicker");
	    $("input[name='s_date']").val("<?=$today?>")
	    
	    $("input[name='e_date']").attr("type","date");
	    $("input[name='e_date']").addClass("input-datepicker");
	    $("input[name='e_date']").val("<?=$tomorrow?>");
	<? } else { ?>
	
	$.ajax({
		type : "POST",
		url : "./search_insur_ajax_date.php",
		cache : false,
        data:{
			date_period:date_period
			,chk : "1"
		},
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'html',
		success: function (result) {
			if (result){
				$("#act_date1").html(result);
			}
		},
		error : function(xhr, status, error) {
			alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
			return false;
		}
	});

	$.ajax({
		type : "POST",
		url : "./search_insur_ajax_date.php",
		cache : false,
        data:{
			date_period:date_period
			,chk : "2"
		},
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'html',
		success: function (result) {
			if (result){
				$("#act_date2").html(result);
			}
		},
		error : function(xhr, status, error) {
			alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
			return false;
		}
	});
	<? } ?>
}
//fn_chg_ins('<?=$PR_SEQ?>','<?=$PR_INFO["ext1"]?>')
$(document).ready(function(){
	<? if($PR_INFO["ext1"] == "Y") {?>
		var today = "<?=$today?>";
		var minDate = 0;
		var period = 90;
	<? } else if($PR_INFO["ext1"] == "N") {?>
		var today = "<?=$tomorrow?>";
		var minDate = 1;
		var period = 365-1;
	<? } ?>

	<? if ($detect->isMobile()) { ?>
	    $("input[name='s_date']").attr("type","date");
	    $("input[name='s_date']").addClass("input-datepicker");
	    //$("input[name='s_date']").attr("min",today);
	    //$("input[name='s_date']").val(today)
	    
	    $("input[name='e_date']").attr("type","date");
	    $("input[name='e_date']").addClass("input-datepicker");
	    //$("input[name='e_date']").attr("max","2019-12-30");
	    //$("input[name='e_date']").val("<?=$tomorrow?>");
	<? } else { ?>
	$("input[name='s_date']").attr("readonly",true);
	$("input[name='e_date']").attr("readonly",true);
	
	$('#s_date').datepicker({
		dateFormat: "yy-mm-dd",
		closeText: "닫기",
		autoclose : true,
		prevText: '<i class="ti ti-angle-left"></i>',
		nextText: '<i class="ti ti-angle-right"></i>',
		navigationAsDateFormat:true,
		currentText: "오늘",
		monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
		monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
		//dayNames: ["일","월","화","수","목","금","토"],
		//dayNamesShort: ["일","월","화","수","목","금","토"],
		dayNamesMin: ["일","월","화","수","목","금","토"],
		showOtherMonths:true,
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: "",
		changeMonth: true,
		changeYear: true,
		showOn: 'both',
		buttonText: "<i class='fa fa-calendar'></i>",
		buttonImageOnly: false,
		showButtonPanel: false,
		zIndex:"2",
		//minDate:minDate,
		minDate: 2,
		maxDate: 180,
	    onClose: function( selectedDate ) {
		    if(selectedDate) {
			    //var minDataAdd = $(this).datepicker('getDate');
			    //var maxDataAdd = $(this).datepicker('getDate');
				//minDataAdd.setDate(minDataAdd.getDate() + minDate);
				//maxDataAdd.setDate(minDataAdd.getDate() + period);
				//
				//$("#e_date").datepicker("option", "minDate", minDataAdd );
				//$('#e_date').datepicker("option", "maxDate", maxDataAdd);
				var stxt = selectedDate.split("-");
					stxt[1] = stxt[1] - 1;
				var sdate = new Date(stxt[0], stxt[1], stxt[2]);
				var edate = new Date(stxt[0], stxt[1], stxt[2]);
					//edate.setDate(sdate.getDate() + period+1);
					<? if($PR_INFO["ext1"] == "Y") {?>
						edate.setDate(sdate.getDate() + period);
					<? } else if($PR_INFO["ext1"] == "N") {?>
						edate.setDate(sdate.getDate() + period+1);
					<? } ?>
				
				$('#e_date').datepicker('option', {
					minDate: selectedDate,
					beforeShow : function () {
						$("#e_date").datepicker( "option", "maxDate", edate );
				}});
		    }
		    
		  
	   }
	});

	$('#e_date').datepicker({
		dateFormat: "yy-mm-dd",
		closeText: "닫기",
		autoclose : true,
		prevText: '<i class="ti ti-angle-left"></i>',
		nextText: '<i class="ti ti-angle-right"></i>',
		navigationAsDateFormat:true,
		currentText: "오늘",
		monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
		monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
		//dayNames: ["일","월","화","수","목","금","토"],
		//dayNamesShort: ["일","월","화","수","목","금","토"],
		dayNamesMin: ["일","월","화","수","목","금","토"],
		showOtherMonths:true,
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: "",
		changeMonth: true,
		changeYear: true,
		showOn: 'both',
		buttonText: "<i class='fa fa-calendar'></i>",
		buttonImageOnly: false,
		showButtonPanel: false,
		zIndex:"2",
		minDate: 1,
		maxDate: period,
	    onClose: function( selectedDate ) {
		    console.log(selectedDate);
		  
	   }
	});
	<? } ?>
})
//-->
</script>