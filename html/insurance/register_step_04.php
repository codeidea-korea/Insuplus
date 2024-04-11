<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';

	include $_SERVER["DOCUMENT_ROOT"]."/_config/Mobile_Detect.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가

	$detect = new Mobile_Detect;
?>
<?
//==========================================================
if (!$plan_seq && !$PR_SEQ){echo "<script>alert('잘못된 경로로 들어오셨습니다.');location.href='/html/main/index.php';</script>";exit;}

$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];

// 여행 변수 받음 처리
if ($_POST["s_date"] && $_POST["e_date"]){
	$s_date = $_POST["s_date"];					// 기간 시작
	$e_date = $_POST["e_date"];					// 기간 종료
	$s_date_time = $_POST["s_date_time"];		// 기간 시작 시간
	$e_date_time = $_POST["e_date_time"];		// 기간 종료 시간

	if($chk_p == "Y") { //단기
		$t_s_date = $s_date." ".$s_date_time;
		$t_e_date = $e_date." ".$e_date_time;

		$s_date_text = $t_s_date.":00";
		$e_date_text = $t_e_date.":00";

		$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
		$period_day = $arr_period["day"];
		$period = $arr_period["day"];
		$period_month = $arr_period["month"];
	} else if($chk_p == "N") { //장기
		$t_s_date = $s_date;
		$t_e_date = $e_date;

		$s_date_text = $t_s_date;
		$e_date_text = $t_e_date;

		$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
		$period_day = $arr_period["day"];
		$period = $arr_period["month"];
		$period_month = $arr_period["month"];
	}
	$gender			= $_POST["gender"];								// 가입자 성별
	$birth				= $_POST["birth"];									// 가입자 생일
	$user_name	= $_POST["user_name"];		// 가입자 이름
	$user_rnumber	= $_POST["user_rnumber"];	// 가입자 주민등록번호
	$user_hp		= $_POST["user_hp"];			// 가입자 연락처
	$email			= $_POST["email"];				// 가입자 이메일 앞
	$email2			= $_POST["email2"];				// 가입자 이메일 뒤
	$purpose		= $_POST["purpose"];							// 여행타입
	$c_name		= $_POST["c_name"];								// 여행국 이름
	$c_code			= $_POST["c_code"];								// 여행국 코드
	$en_secur		= $_POST["en_secur"];								// 영문여권 여부
	$en_name		= $_POST["en_name"];								// 영문여권 이름

	$select_add_people = $_POST["select_add_people"];		// 동반인명수
	if ($select_add_people >0){
		$arr_add_gender = array();
		$arr_add_birth = array();
		$arr_add_name = array();
		for($k=0;$k<5;$k++){
			if ($_POST["add_gender"][$k] && $_POST["add_birth"][$k]){
			$arr_add_gender[] = $_POST["add_gender"][$k];									// 동반인 성별
			$arr_add_birth[] = $_POST["add_birth"][$k];											// 동반인 생일
			$arr_add_name[] = $_POST["add_user_name"][$k];			// 동반인 이름
			$arr_add_rnumber[] = $_POST["add_rnumber"][$k];			// 동반인 주민등록번호
			$arr_add_en_secur[] = $_POST["add_en_secur"][$k];							// 영문여권 여부
			$arr_add_en_name[] = $_POST["add_en_name"][$k];		// 영문여권 이름
			}
		}
	}
	for ($k=0;$k<count($_POST["pr_notice"]);$k++){
		$arr_pr_notice[]		= $_POST["pr_notice"][$k];
		$arr_pr_notice_a[]	= $_POST["notice_a"][$k];
	}
} else {
	alert_page("올바른 경로로 이용해 주세요.","../main/index.php");
	exit;
}

if($chk_p == "Y") {
	if($arr_period["day"] > 90) {
		alert_page("가입기간은 90일까지 가입가능합니다.","./search_insur.php?PR_SEQ=".$PR_SEQ);
		exit;
	}
} else if($chk_p == "N") {
	if($arr_period["day"] > 365) {
		alert_page("가입기간은 365일까지 가입가능합니다.","./search_insur.php?PR_SEQ=".$PR_SEQ);
		exit;
	}
}

// 플랜검색
$plan_view  = selPlanView($plan_seq, $t_s_date, $t_e_date, $chk_p);

//보험약관 파일 확인
$arr_ins_agree_file = selInsAgreeFile($plan_view["agree_cd"]);

//인슈플러스 서비스 약관 파일 확인
if($plan_view["chk_service"] != "N" && $plan_view["chk_service"] != "") {
	$arr_service_file = selServiceFile($plan_view["service_cd"]);
}

// 여행비용 검색
$user_age = fn_ins_age($birth);																		// 가입자 나이
$user_amt = fn_sel_ins_amt($period,$chk_p,$plan_seq,$user_age,$gender);		// 가입자 여행비용
$t_amt = 0;
for($k=0;$k<5;$k++){
	if ($arr_add_birth[$k]!=""){
	$add_user_age = fn_ins_age($arr_add_birth[$k]);
	$add_user_amt[$k] = fn_sel_ins_amt($period,$chk_p,$plan_seq,$add_user_age,$arr_add_gender[$k]);		// 동행자 여행비용
	$t_amt = $t_amt + $add_user_amt[$k];		// 동반자 여행보험비용
	}
}
$t_amt = $t_amt + $user_amt;							// 가입자 여행보험비용

// 여행 서비스 비용
$service_amt = fn_ins_service_amt($plan_seq,$plan_view["chk_service"],$period_month, $period_day);

//총괄 서비스 비용
$t_amt = $t_amt + ($service_amt * (1+count($add_user_amt)));
//==========================================================
?>
<? if($_SESSION["ss_partner_seq"] == 7){?> 
	<script type="text/javascript">
		var popupX = (window.screen.width / 2) - (500 / 2);
		var popupY= (window.screen.height / 2) - (285 / 2);

		function popupCheck(){
			var win = window.open('', 'win', 'width=1, height=1, scrollbars=yes, resizable=yes, left='+ popupX + ', top='+ popupY);

			if (win == null || typeof(win) == "undefined" || (win == null && win.outerWidth == 0) || (win != null && win.outerHeight == 0) || win.test == "undefined"){
				alert("팝업 차단 기능이 설정 되어있습니다.\n\n차단 기능을 해제(팝업허용) 한 후 다시 이용해 주십시오.");
				if(win){
					win.close();
				}
				return;
			}
			if(win){
				win.close();
			}
		}

		window.onload = function(){
			popupCheck();
			var openPopup = window.open('https://www.isic.co.kr/home/insuPlus_verify.jsp', 'popup', 'status=no, width=500, height=285, left='+ popupX + ', top='+ popupY);
		}

				
	</script>
<? } ?>
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
		<div class="container sub-content sub-register">
			<div class="step_join">
				<? include './register_step_ui.php'; ?>
			</div>
			<div class='m-t-2 m-b-1'>
				<form class='form-inline' method="post" name="frm4" id="frm4">
				<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>">
				<input type="hidden" name="plan_seq" value="<?=$plan_seq?>">
				<input type="hidden" name="s_date" value="<?=$s_date?>">
				<input type="hidden" name="s_date_time" value="<?=$s_date_time?>">
				<input type="hidden" name="e_date" value="<?=$e_date?>">
				<input type="hidden" name="e_date_time" value="<?=$e_date_time?>">
				<input type="hidden" name="gender" value="<?=$gender?>">
				<input type="hidden" name="birth" value="<?=$birth?>">
				<input type="hidden" name="user_name" value="<?=$user_name?>">
				<input type="hidden" name="user_rnumber" value="<?=$user_rnumber?>">
				<input type="hidden" name="user_hp" value="<?=$user_hp?>">
				<input type="hidden" name="email" value="<?=$email?>">
				<input type="hidden" name="email2" value="<?=$email2?>">
				<input type="hidden" name="c_name" value="<?=$c_name?>">
				<input type="hidden" name="c_code" value="<?=$c_code?>">
				<input type="hidden" name="en_secur" value="<?=$en_secur?>">
				<input type="hidden" name="en_name" value="<?=$en_name?>">
				<input type="hidden" name="purpose" value="<?=$purpose?>">
				<input type="hidden" name="t_amt" value="<?=$t_amt?>"><!-- 총금액 -->
				<input type="hidden" name="service_amt" value="<?=$service_amt?>"><!-- 개별 서비스 금액 -->
				<input type="hidden" name="select_add_people" value="<?=$select_add_people?>">
				<input type="hidden" name="recommend_cd" value="0"> <!-- c:쿠폰,r:추천코드 -->
				<input type="hidden" name="cp_cd" value="0">
				<input type="hidden" name="joinType" value="<?=$joinType?>">

				<input type="hidden" name="rule_site_cd" value="<?=$rule_site_cd?>"><!-- 사이트 이용약관  -->
				<input type="hidden" name="rule_group_cd" value="<?=$rule_group_cd?>"><!-- 단체보험 규약 이용약관  -->
				<input type="hidden" name="rule_privacy_cd" value="<?=$rule_privacy_cd?>"><!-- 개인정보 수집 및 이용약관  -->
				<input type="hidden" name="ins_file_cd" value="<?=$plan_view["agree_cd"]?>"><!-- 개인정보 수집 및 이용약관  -->
				<input type="hidden" name="service_file_cd" value="<?=$plan_view["service_cd"]?>"><!-- 개인정보 수집 및 이용약관  -->
				<input type="hidden" name="is_abroad_resident" value="<?=$_POST["is_abroad_resident"]?>"><!-- 해외거주 여부  -->

				<?if ($select_add_people >0){
				for($k=0;$k<count($_POST["add_birth"]);$k++){?>
				<input type="hidden" name="add_gender[]" value="<?=$_POST["add_gender"][$k]?>">
				<input type="hidden" name="add_birth[]" value="<?=$_POST["add_birth"][$k]?>">
				<input type="hidden" name="add_user_name[]" value="<?=$arr_add_name[$k]?>">
				<input type="hidden" name="add_rnumber[]" value="<?=$arr_add_rnumber[$k]?>">
				<input type="hidden" name="add_en_secur[]" value="<?=$arr_add_en_secur[$k]?>">
				<input type="hidden" name="add_en_name[]" value="<?=$arr_add_en_name[$k]?>">
				<?}}?>
				<?for ($k=1;$k<=count($_POST["pr_notice"]);$k++){?>
				<input type="hidden" name="pr_notice[<?=$k?>]" value="<?=$_POST["pr_notice"][$k]?>">
				<input type="hidden" name="pr_notice_a[<?=$k?>]" value="<?=$_POST["notice_a"][$k]?>">
				<?}?>
					<h4 class='text-darken m-b-1'><i class='fa fa-file-text-o'></i>&nbsp;계약정보</h4>
					<h5 class='text-black m-b-1'>가입 상품</h5>
					<div class='row-border'>
						<div class='detail-col-label'><strong class='text-black'>플랜명</strong></div>
						<div class='detail-col-input'><strong class='text-black'><span><?=$Arr_plan_cd[$plan_view["plan_cd"]]?></span>&nbsp;<?=$PR_INFO["subject"]?></strong></div>
					</div>
					<div class='clearfix m-t-3 m-b-1'><h4 class='text-black'>가입자 정보</h4></div>
					<div class='clearfix'>
						<div class='row-border'>
							<div class='detail-col-label'>이름</div>
							<div class='detail-col-input'><?=all_seed_dec($user_name)?></div>
							<div class='detail-col-label'>주민등록번호</div>
							<div class='detail-col-input'><?=substr($birth,2,6)?>-<?=fn_user_isdn_1($birth,$gender)?>******</div>
							<div class='detail-col-label'>휴대폰번호</div>
							<div class='detail-col-input'><?=all_seed_dec($user_hp)?></div>
							<div class='detail-col-label'>이메일</div>
							<div class='detail-col-input'><?=all_seed_dec($email)?>@<?=all_seed_dec($email2)?></div>
							<? if($en_secur == "Y") {?>
							<div class='detail-col-label'>영문명</div>
							<div class='detail-col-input colspan-3'><?=all_seed_dec($en_name)?></div>
							<? } ?>
							<div class='detail-col-label'>상품 가격</div>
							<div class='detail-col-input colspan-3 text-right'><h4><span class='text-black'><?=number_format($user_amt+$service_amt)?></span> <small>원</small></h4></div>
						</div>
					</div>
					<?for ($k=0;$k<count($arr_add_birth);$k++){
					if ($arr_add_birth[$k]!=""){
					?>
					<div class='clearfix m-t-3 m-b-1'><h4 class='text-black'>동반인 정보(<?=$k+1?>)</h4></div>
					<div class='clearfix'>
						<div class='row-border'>
							<div class='detail-col-label'>이름</div>
							<div class='detail-col-input'><?=all_seed_dec($arr_add_name[$k])?></div>
							<div class='detail-col-label'>주민등록번호</div>
							<div class='detail-col-input'><?=substr($arr_add_birth[$k],2,6)?>-<?=fn_user_isdn_1($arr_add_birth[$k],$arr_add_gender[$k])?>******</div>
							<? if($arr_add_en_secur[$k] == "Y") {?>
							<div class='detail-col-label'>영문명</div>
							<div class='detail-col-input colspan-3'><?=all_seed_dec($arr_add_en_name[$k])?></div>
							<? } ?>
							<div class='detail-col-label'>상품 가격</div>
							<div class='detail-col-input colspan-3 text-right'><h4 class='pull-right'><span class='text-black'><?=number_format($add_user_amt[$k]+$service_amt)?></span> <small>원</small></h4></div>
						</div>
					</div>
					<?}}?>
					
					<div class='row m-y-2'>
					<div class='col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0'>
						<div class='btn-group btn-group-justified btn-group-noborder'>
							<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' data-toggle='pop-modal' data-size='lg' data-href='./pop_warranty.php?seq=<?=$plan_view["seq"]?>' data-title='보장내역' target='modal_iframe'>보장내역<i class='ti ti-search pull-right hidden-xs'></i></a></span>
							<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' onClick="fnDown('<?=all_seed_enc("/_data/board/ins_agree/".$arr_ins_agree_file["file_realname"])?>','<?=$arr_ins_agree_file["file_name"]?>','<?=$arr_ins_agree_file["file_size"]?>')">보험약관<i class='ti ti-download pull-right hidden-xs'></i></a></span>
							<? if($arr_service_file["file_realname"]) {?>
							<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' onClick="fnDown('<?=all_seed_enc("/_data/board/service_agree/".$arr_service_file["file_realname"])?>','<?=$arr_service_file["file_name"]?>','<?=$arr_service_file["file_size"]?>')">서비스 이용약관<i class='ti ti-download pull-right hidden-xs'></i></a></span>
							<? } ?>
						</div>
					</div>
					</div>
					
					<h4 class='text-darken m-b-1'><img src="../images/ic-discount.svg" align="absmiddle" height='20' />&nbsp;할인정보 </h4>
					<div class='clearfix'>
						<div class='row-border'>
							<div class='detail-col-label'>할인정보</div>
							<div class='detail-col-input colspan-3'>
								<div class='radio radio-inline'>
									<input type="radio" name='radio_discount' id='radio_discount_coupon' value="C" onClick="fnSaleGubun('C')" checked />
									<label for='radio_discount_coupon'>쿠폰사용</label>
								</div>
								<div class='radio radio-inline m-l-3'>
									<input type="radio" name='radio_discount' id='radio_discount_code' value="R" onClick="fnSaleGubun('R')"/>
									<label for='radio_discount_code'>추천코드</label>
								</div>
							</div>
							<div class='detail-col-label' id="sale_gubun_txt">쿠폰</div>
							<div class='detail-col-input colspan-3'>
								<div class='text-danger clearfix'>
									<div class='input-group' id="coupon_ui">
										<input type="text" name='cp_name' id="cp_name" placeholder="쿠폰명" class="form-control" size='30' readonly="readonly" />
										<span class='input-group-btn'><button id="btnCoupon" type='button' class='btn btn-default' data-toggle='pop-modal' data-size='md' data-href="" data-title='쿠폰' target='modal_iframe'>쿠폰확인</button>
									</div>
									<div class='input-group' id="reommend_ui" style="display:none;">
										<input type="text" name='recommend_name' id="recommend_name" placeholder="추천코드" class="form-control" size='30' />
										<span class='input-group-btn'><button onClick="fnRecommend_code()" type='button' class='btn btn-default'>확인</button>
									</div>
									<p class='m-y-05' id="sale_txt"></p>
								</div>
							</div>
						</div>
						<p class='m-y-05 text-black'>※  다인 가입시 합산해서 할인 적용되며 최대 <span class='point'> 3만원 </span>까지 할인받으실 수 있습니다.</p>
					</div>
					<h4 class='text-darken m-t-3 m-b-1'><i class='ti ti-credit-card'></i>&nbsp;결제정보</h4>
					<div class='clearfix'>
						<div class='row-border'>
							<div class='detail-col-label'>상품가격</div>
							<div class='detail-col-input colspan-3 text-right'>
								<div class='clearfix text-right'><h3><span class='text-danger'><?=number_format($t_amt)?></span> <small>원</small></h3></div>
							</div>
							<div class='detail-col-label'>할인금액</div>
							<div class='detail-col-input colspan-3 text-right'>
								<div class='clearfix text-right'><h4><span class='text-black' id="sale_amt"><?=number_format($sale_amt)?></span> <small>원</small></h4></div>
							</div>
							<div class='detail-col-label'>총 결제금액</div>
							<div class='detail-col-input colspan-3 text-right'>
								<div class='clearfix text-right'><h3><span class='text-danger' id="t_amount"><?=number_format($t_amt-$sale_amt)?></span> <small>원</small></h3></div>
							</div>
						</div>
					</div>
					<h4 class='text-darken m-t-2 m-b-1'><i class='ti ti-view-grid'></i>&nbsp;결제방식 선택 </h4>
					<div class='row' id='card_select'>
						<div class='col-sm-3 col-xs-6 m-t-1'><a href="javascript: chg_method_pay('Card');" class='btn btn-lg btn-block btn-navy btn-outline'>신용카드</a></div>
						<div class='col-sm-3 col-xs-6 m-t-1'><a href="javascript: chg_method_pay('HPP');;" class='btn btn-lg btn-block btn-mint btn-outline'>휴대폰결제</a></div>
						<div class='col-sm-3 col-xs-6 m-t-1'><a href="javascript: chg_method_pay('Vbank');;" class='btn btn-lg btn-block btn-purple btn-outline'>가상계좌</a></div>
						<!-- <div class='col-sm-3 col-xs-6 m-t-1'><a href="javascript: chg_method_pay('DirectBank');;" class='btn btn-lg btn-block btn-purple btn-outline'>계좌이체</a></div> -->
						<!-- <div class='col-sm-3 col-xs-6 m-t-1'><a href="javascript:;" class='btn btn-lg btn-block btn-kakao btn-outline'>카카오페이</a></div> -->
						<input type="hidden" name="gopaymethod" value="" >
					</div>
					<ul class='pagination arrow-only'>
						<li class='prev'><a href='javascript: history.back();'>이전</a></li>
						<li class='next'><a href='javascript: chk_submit4();'>결제하기</a></li>
					</ul>
				</form>
			</div>
        </div>
        <form name="downForm" id="downForm" method="post">
        	<input type="hidden" name="mode" value="down" />
        	<input type="hidden" name="file" value="" />
        	<input type="hidden" name="filename" value="" />
        	<input type="hidden" name="filesize" value="" />
        </form>
		<div id="act_div"></div>
<!-- 이니시스 표준결제 js -->
<? if(SERVER_CHECK == "DEV") {?>
<script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } else if(SERVER_CHECK == "REAL") {?>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } ?>
<script type="text/javascript">

function fnSaleGubun(val) {//할인방법 선택
	if(val == "C") {
		$("#coupon_ui").show();
		$("#reommend_ui").hide();
		$("#sale_gubun_txt").html("쿠폰");
	} else if(val == "R") {
		$("#reommend_ui").show();
		$("#coupon_ui").hide();
		$("#sale_gubun_txt").html("추천코드");
	}

}

function fnRecommend_code() {
	if(!$("input[name='recommend_name']").val()) {
		alert("추천코드를 입력해 주세요.");
		$("input[name='recommend_name']").focus();
		return;
	}

	var params = jQuery("#frm4").serialize();
	$.ajax({
		type : "POST",
		url : "./register_step_04_recommend.php",
		cache : false,
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (result) {
			if (result.success=="1"){
				$("input[name='cp_cd']").val("0"); //쿠폰번호 초기화
				$("input[name='recommend_cd']").val(result.recommend_cd);
				$("#sale_txt").text(result.s_amt_per_txt);
				$("#sale_amt").text(comma(result.sale_amt));
				$("#t_amount").text(comma(result.t_amount));
			} else {
				alert(result.msg)
			}
		},
		error : function(xhr, status, error) {
			alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
			console.log(xhr.responseText);
			return false;
		}
	});
}


function comma(num){
    var len, point, str;

    num = num + "";
    point = num.length % 3 ;
    len = num.length;

    str = num.substring(0, point);
    while (point < len) {
        if (str != "") str += ",";
        str += num.substring(point, point + 3);
        point += 3;
    }

    return str;

}

function inipay() { //pc결제

	INIStdPay.pay('SendPayForm_id');
}

window.name = "BTPG_CLIENT";

var width = 330;
var height = 480;
var xpos = (screen.width - width) / 2;
var ypos = (screen.width - height) / 2;
var position = "top=" + ypos + ",left=" + xpos;
var features = position + ", width=320, height=440";

function on_web() { //모바일결제

	var order_form = document.SendPayForm_id;
	var paymethod = order_form.paymethod.value;

//	var wallet = window.open("", "BTPG_WALLET", features);
//
//	if (wallet == null)
//	{
//
//		if ((webbrowser.indexOf("Windows NT 5.1")!=-1) && (webbrowser.indexOf("SV1")!=-1))
//		{    // Windows XP Service Pack 2
//			alert("팝업이 차단되었습니다. 브라우저의 상단 노란색 [알림 표시줄]을 클릭하신 후 팝업창 허용을 선택하여 주세요.");
//		}
//		else
//		{
//			alert("팝업이 차단되었습니다.");
//		}
//		return false;
//	}



//	order_form.target = "BTPG_WALLET";

	order_form.action = "https://mobile.inicis.com/smart/" + paymethod + "/";
	order_form.submit();
}

function chg_cp(chk){

}
function chg_fund(chk,seq){
	$("input[name='cp_cd']").val(seq);
	var params = jQuery("#frm4").serialize();
	$.ajax({
		type : "POST",
		url : "./register_step_04_coupon.php",
		cache : false,
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (result) {
			if (result){
				$("input[name='recommend_cd']").val("0"); //추천고유번호 초기화
				$("#cp_name").val(result.cp_name);
				$("#sale_txt").text(result.s_amt_per_txt);
				$("#sale_amt").text(comma(result.sale_amt));
				$("#t_amount").text(comma(result.t_amount));
//				$("#ajax_fund").html("");
//				$("#ajax_fund").html(result);
				$(".modal-header .close").click();
			} else {
				$("input[name='cp_cd']").val("");
			}
		},
		error : function(xhr, status, error) {
			$("input[name='cp_cd']").val("");
			alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
			console.log(xhr.responseText);
			return false;
		}
	});
}
function chg_method_pay(pay_type){
	<? if ( $detect->isMobile() ) { ?>
	if(pay_type == "Card") {
		pay_type = "wcard";
	} else  if(pay_type == "HPP") {
		pay_type = "mobile";
	} else  if(pay_type == "Vbank") {
		pay_type = "vbank";
	}
	<? } ?>
	document.frm4.gopaymethod.value = pay_type;
	chk_submit4();
}
function chk_submit4(){

	if(!document.frm4.gopaymethod.value) {
		alert("결제방식을 선택해 주세요.");
		return;
	}

	var params = jQuery("#frm4").serialize();
	var request = $.ajax({
		type : "POST",
		url : "./register_step_04_ajax.php",
		cache : false,
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'html',
		success: function (result) {
			if (result){
				$("#act_div").html(result);
				setTimeout(function() {
					<? if ( $detect->isMobile() ) { ?>
						on_web();
					<? } else { ?>
					 	inipay();
					<? } ?>


				}, 1000);
			}
		},
		error : function(xhr, status, error) {
			alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
			return false;
		}
	});
	request.done(function(result) {

	});

}

function fnDown(file, filename, file_size) {
	$("#downForm input[name='file']").val(file);
	$("#downForm input[name='filename']").val(filename);
	$("#downForm input[name='filesize']").val(file_size);
	$("#downForm").attr("action","fileDown.php").submit();
}

function replace(inum) { // 인코딩 함수 이용 
	var s_date = '<?= $s_date?>';
	var e_date = '<?= $e_date?>';
	var result = './pop_coupon.php?usr_cd='+encodeURIComponent(inum)+'&s_date='+s_date+'&e_date='+e_date;
	$("#btnCoupon").attr('data-href', result);
	return true;
}

//-->
</script>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>
<script>
$(document).ready(function(){
    replace('<?=$user_hp?>');
});
</script>

