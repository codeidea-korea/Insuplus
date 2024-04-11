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

    // 상품검색
    $SQL_PR_top = " select seq, subject from tbl_board_product WHERE secret = 'Y' AND ext2 = 'Y' order by sort_order asc ";
    $RS_PR_top = $dbcon -> query($SQL_PR_top);
	
	if($PR_SEQ) { //상품번호가 있는 경우
		$PR_INFO = getInsuProductInfo($PR_SEQ);
		if($_SESSION["ss_partner_seq"] != null && $PR_INFO["ext2"] == 'N'){
			$SQL_PR = "SELECT seq,ext7 FROM tbl_board_product WHERE seq = '".$PR_SEQ."' ORDER BY sort_order ASC "; //출국목적
			$RS_PR = $dbcon -> query($SQL_PR);
		}
	} else {
		// $SQL = "SELECT seq FROM tbl_board_product WHERE  secret = 'Y' ORDER BY sort_order ASC LIMIT 0,1 ";
		// $RS = $dbcon -> query($SQL);
		// $ROW =  $dbcon -> fetch_array($RS);
		//$PR_SEQ = $ROW["seq"];
		$PR_SEQ = '23';
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
						<input type="hidden" name="group_join_type" value="B2C">
						<input type="hidden" name="file_path" value="">
						<input type="hidden" name="purpose" value="<?=$PR_INFO["ext7"]?>">
						<input type="hidden" name='o_email' value=""/>
							<div class='panel'>
								<div class='panel-body text-md-center text-sm-left text-xs-left'>
								
									<h3 class='text-black'><span class='point'>여행하실 고객님의 정보를 <br class='hidden visible-xs' />입력해 주세요.</span></h3>
									<div class='row-border m-t-3' id='search_insur'>
                                        <div class='detail-col-label'>가입상품</div>
										<div class='detail-col-input colspan-3 flex-wrap'>
                                            <div class='col-md-2 col-sm-4 col-xs-7 p-x-0'>
                                                <div class='btn-group btn-group-justified btn-group-multi'>
                                                    <select name="product" id="product" onchange="selectProduct()" class="form-control hidden-xs">
                                                    <?while($row_pr_rop = $dbcon -> fetch_array($RS_PR_top)){		//상품 검색?>
                                                        <option value="<?=$row_pr_rop["seq"]?>" <?if($PR_SEQ ===$row_pr_rop["seq"]){echo "selected";}?>><?=$row_pr_rop["subject"]?></option>
                                                    <?}?>
                                                    </select>
                                                </div>
                                            </div>
										</div>
										<div class='detail-col-label'>출국목적</div>
										<div class='detail-col-input colspan-3 flex-wrap'>
											<div class='btn-group btn-group-justified btn-group-multi'>
												<? while($row_pr = $dbcon -> fetch_array($RS_PR)){ //상품 검색 ?>
													<div class='btn-group'><a class='btn <?=$row_pr["seq"]==$PR_SEQ ? "btn-theme-bg":"btn-default"?>' ata-toggle='alert-modal' data-size='xs' data-title='알림' data-btn-text='<?=$row_pr["subject"]?>' data-content='<?=$row_pr["ext7"]?>' data-href='./search_group_join.php?PR_SEQ=<?=$row_pr['seq']?>' <? if($row_pr['seq'] != $PR_SEQ) { ?>onClick='fn_chg_propose(this,"<?=$row_pr["ext7"]?>")' <? } ?>><?=$row_pr["ext7"]?></a></div>
												<? } ?>
												<!-- <a class='btn btn-default' data-toggle='alert-modal' data-size='sm' data-title='알림' data-content='유학' target='modal_iframe' onclick="fn_chg_ins('2');">유학</a>
												<a class='btn btn-default' data-toggle='alert-modal' data-size='sm' data-title='알림' data-content='워킹 홀리데이' target='modal_iframe' onclick="fn_chg_ins('3');">워킹 홀리데이</a>
												<a class='btn btn-default' data-toggle='alert-modal' data-size='sm'  data-title='알림' data-content='장기체류' target='modal_iframe' onclick="fn_chg_ins('4');">장기체류</a> -->
											</div>
										</div>
										<div class='detail-col-label'>가입구분</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-5 col-sm-4 col-xs-7 p-x-0'>
												<div class='btn-group btn-group-justified'>
													<a class='btn btn-theme-bg btn-gender' id="btn_type_B2C" onclick="fn_chg_type(0,'B2C')">개인</a>
													<a class='btn btn-default btn-gender' id="btn_type_B2B" onclick="fn_chg_type(0,'B2B')">기업</a>
												</div>
											</div>
										</div>
										<div class='detail-col-label'>대표자명</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-5 col-sm-4 col-xs-7 p-x-0'>
												<input type="text" name='o_name' placeholder="이름" class="form-control" size='5' maxlength="20" />
											</div>
										</div>
										<div class='detail-col-label'  id="b2cdiv1">생년월일</div>
										<div class='detail-col-input flex-wrap'  id="b2cdiv2">
											<div class='col-md-5 col-sm-4 col-xs-7 p-x-0'>
												<input type="text" name='birthdate' placeholder="예)20210101" class="form-control" size='5' maxlength="8" />												
											</div>
										</div>
										<div class='detail-col-label'>연락처</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-5 col-sm-4 col-xs-7 p-x-0'>
												<input type="text" name='o_phone' placeholder="예)01012345678" class="form-control" size='5' maxlength="14" />
											</div>
										</div>
										<div class='detail-col-label' id="b2bdiv1" style="display: none;">사업자번호</div>
										<div class='detail-col-input flex-wrap' id="b2bdiv2" style="display: none;">
											<div class='col-md-5 col-sm-4 col-xs-7 p-x-0'>
												<input type="text" name='biz_num' placeholder="예)11012312345" class="form-control" size='5' maxlength="10" />												
											</div>
										</div>
										<div class='detail-col-label'>이메일</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-10 col-sm-4 col-xs-7 p-x-0'>
												<div class='input-group'>
													<input type="text" name='email' placeholder="" class="form-control radius" size='12' />
													<span class='input-group-addon'>@</span>
													<input type="text" name='email2' placeholder="" class="form-control" size='12' />
												</div>
												<select name="email_list" id="email_list" onchange="$('input[name=email2]').val(this.value)" class="form-control hidden-xs">
													<option value="" selected="">직접입력</option>
													<option value="naver.com">naver.com</option>
													<option value="chol.com">chol.com</option>
													<option value="dreamwiz.com">dreamwiz.com</option>
													<option value="empal.com">empal.com</option>
													<option value="freechal.com">freechal.com</option>
													<option value="gmail.com">gmail.com</option>
													<option value="hanafos.com">hanafos.com</option>
													<option value="hanmail.net">hanmail.net</option>
													<option value="hanmir.com">hanmir.com</option>
													<option value="hitel.net">hitel.net</option>
													<option value="hotmail.com">hotmail.com</option>
													<option value="korea.com">korea.com</option>
													<option value="lycos.co.kr">lycos.co.kr</option>
													<option value="nate.com">nate.com</option>
													<option value="netian.com">netian.com</option>
													<option value="paran.com">paran.com</option>
													<option value="yahoo.com">yahoo.com</option>
													<option value="yahoo.co.kr">yahoo.co.kr</option>
												</select>
											</div>
										</div>
										<div class='detail-col-label'>가입자 등록</div>
										<div class='detail-col-input flex-wrap'>
											<div class='col-md-10 col-sm-4 col-xs-7 p-x-0' style="align-items: center;">
												<input type="file" id="file1" name="file1" style="justify-content: flex-start !important;">
												<a href="./사용자_등록.xlsx"><span style="justify-content: flex-end !important;"><u>등록양식 다운로드</u></span></a>
											</div>
										</div>
									</div>
									<p class='clearfix text-left text-black m-t-2'>
										<strong>* 보험가입안내</strong><br>
										<?=nl2br($PR_INFO["ext6"]);?>
									</p>
								</div>
								<div class='panel-footer'>
									<a class='btn btn-lg btn-block btn-theme-bg' href="javascript: fregist_submit();">비교 플랜 선택</a>
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

function selectProduct() {
    var seq = $('#product').val();
    location.href = "../insurance/search_group_join.php?PR_SEQ="+seq;
}

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
function fn_chg_type(idx,val){
	var ff = document.fregist;
	ff.group_join_type.value = val;
	$(".btn-gender").removeClass('btn-theme-bg').addClass('btn-default');
	$("#btn_type_"+val).removeClass('btn-default').addClass('btn-theme-bg');

	if(val == 'B2C'){
		$("#b2bdiv1").css("display","none");
		$("#b2bdiv2").css("display","none");
		$("#b2bdiv3").css("display","none");
		$("#b2bdiv4").css("display","none");
		$("#b2cdiv1").css("display","");
		$("#b2cdiv2").css("display","");
	} else {
		$("#b2bdiv1").css("display","");
		$("#b2bdiv2").css("display","");
		$("#b2bdiv3").css("display","");
		$("#b2bdiv4").css("display","");
		$("#b2cdiv1").css("display","none");
		$("#b2cdiv2").css("display","none");
	}
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
	var date_pattern = /^(19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[0-1])$/; 
	var email_check = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

	if(ff.group_join_type.value == "B2C"){
		if(ff.birthdate.value == "") {
			alert("생년월일을 입력해주세요.");
			ff.birthdate.focus();
			return false;
		} else if(!date_pattern .test(ff.birthdate.value)){
			alert("올바른 생년월일을 입력해 주세요.");
			ff.birthdate.focus();
			return;
		}
	} else {
		if(ff.biz_num.value == "") {
			alert("사업자번호를 입력해주세요.");
			ff.biz_num.focus();
			return false;
		}
	}

	if(ff.o_name.value == "") {
		alert("대표자명을 입력해주세요.");
		ff.o_name.focus();
		return false;
	} else if(ff.o_phone.value == "") {
		alert("연락처를 입력해주세요.");
		ff.o_phone.focus();
		return false;
	} else if(ff.email.value == "") {
		alert("이메일을 입력해주세요.");
		ff.email.focus();
		return false;
	} else if(ff.email2.value == "") {
		alert("이메일을 입력해주세요.");
		ff.email2.focus();
		return false;
	}

	var o_email = ff.email.value + '@' + ff.email2.value;

	if(!email_check.test(o_email)){
		alert("잘못된 이메일 형식입니다.");
		return false;
	} else {
		ff.o_email.value = o_email;
	}

	if($('#file1').val() == "") {
		alert("엑셀 파일을 등록해주세요.");
		return false;
	}

	var str = $('#file1').prop('files')[0]['name'];
	var fileFormat = str.split(".").pop().toLowerCase();
	if($.inArray(fileFormat, ['xlsx','xls']) == -1) {
		alert('xlsx,xls 파일만 업로드 할수 있습니다.');
		return;
	}

	var file_data = $('#file1').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file1', file_data);
	$.ajax({
		type : "POST",
		url : "./group_join_list_check.php",
		data: form_data,
		dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,    
		success: function (data) {
			ff.file_path.value = data.trim();
			ff.action = './register2_step_00.php';
			ff.submit();
		},
        error : function (jqXHR, textStatus, errorThrown) {
			alert('ERRORS: ' + textStatus);
		},
		cache: false,
		contentType: false,
		processData: false
	});
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
		minDate:minDate,
	    onClose: function( selectedDate ) {
		    if(selectedDate) {
			    var minDataAdd = $(this).datepicker('getDate');
			    var maxDataAdd = $(this).datepicker('getDate');
				minDataAdd.setDate(minDataAdd.getDate() + minDate);
				maxDataAdd.setDate(maxDataAdd.getDate() + period);

				$("#e_date").datepicker("option", "minDate", minDataAdd );
				$('#e_date').datepicker("option", "maxDate", maxDataAdd);
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