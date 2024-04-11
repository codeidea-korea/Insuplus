<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	
	// 상품 검색
	$PR_INFO = getInsuProductInfo($PR_SEQ);
	$chk_p = $PR_INFO["ext1"];
	$joinType = "2";
	
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
			$period = $arr_period["day"];
			$period_month = $arr_period["month"];
		} else if($chk_p == "N") { //장기
			$t_s_date = $s_date;
			$t_e_date = $e_date;
	
			$s_date_text = $t_s_date;
			$e_date_text = $t_e_date;
	
			$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
			$period = $arr_period["month"];
			$period_month = $arr_period["month"];
		}
	
		$gender	= $_POST["gender"];					// 가입자 성별
		$birth		= $_POST["birth"];				// 가입자 생일
		$age = fn_ins_age(date("Y-m-d",strtotime($birth))); //보험나이
		$purpose= $_POST["purpose"];			// 여행타입
		$select_add_people = $_POST["select_add_people"]; // 동반인명수
		$compare_seq  = $_POST["compare_seq"];
	
		if ($select_add_people >0){
			$arr_add_gender = array();
			$arr_add_birth = array();
			for($k=0;$k<5;$k++){
				if ($_POST["add_gender"][$k]){
					$arr_add_gender[] = $_POST["add_gender"][$k];
					$arr_add_birth[] = $_POST["add_birth"][$k];
				}
			}
		}

		//상품에 대한 인슈플러스 항목 리스트
		$arr_insuplus = selInsuplusList($PR_INFO["ext5"]);
	
		//상품에 대한 보장내역 검색
		$arr_guarantee = selGuaranteeList($PR_INFO["ext4"]);
	
		//디자인 UI
		$colspan = "1";
		$tit_colspan = "3";
		if(count($arr_plan_list) == "1")  $colspan = "2";
		if(count($arr_plan_list) == "3")  $tit_colspan = "4";
	
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
	
	// 상품 보험사 서비스 검색
	$arr_ins = getNoteInsOfPr_cd($PR_SEQ);

?>
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
				<? include "./register_step_ui.php"; ?>
			</div>
			<div class="register-box clearfix">
				<div class="box">
					<div class="box-row">
						<div class="box-cell col-sm-4 col-xs-12 p-a-0">
							<div class="box valign-middle">
								<div class="box-row bg-navy text-left">
									<div class="box-cell th col-sm-4 col-xs-12 p-l-2"><strong>기간</strong></div>
								</div>
								<div class="box-row valign-middle">
									<div class="box-cell td col-sm-4 col-xs-12 p-x-2">
										<h5 class="pull-right text-right text-black line-height-2"><?=$s_date_text?> ~ <?=$e_date_text?></h5>
										<h2 class="font-normal"><span class="text-black"><?=$arr_period["day"]?></span>일</h2>
									</div>
								</div>
							</div>
						</div>
						<div class="box-cell col-sm-8 col-xs-12 p-a-0">
							<div class="box">
								<div class="box-row bg-navy text-left">
									<div class="box-cell th p-l-2"><strong>출국목적</strong></div>
								</div>
								<div class="box-row text-right">
									<div class="box-cell td p-r-2"><h5 class=""><?=$PR_INFO["ext7"]?></h5></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="join_title clearfix" data-toggle="collapse" data-target="#join_info" aria-expanded="false">
					<a class="btn btn-sm btn-default pull-right">상세 보기</a>
					<h5>가입정보</h5>
				</div>
				<div class="collapse" id="join_info">
					<div class="box">
						<div class="box-row">
							<div class="box-cell">
								<div class="join_info">
									<div class="box">
										<div class="box-row bg-navy text-left">
											<div class="box-cell th p-l-2"><strong>가입자</strong></div>
										</div>
										<div class="box-row">
											<div class="box-cell td p-l-2">
												<p><?=date("Y-m-d",strtotime($birth))?> (<?=$age?>세, <?=$Arr_u_sex[$gender]?>)</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?for ($k=0;$k<count($arr_add_birth);$k++){
								if ($arr_add_birth[$k]!=""){
								$z=$k+1;
							?>
							<div class="box-cell col-sm-4 col-xs-12">
								<div class="join_info">
									<div class="box">
										<div class="box-row bg-navy text-left">
											<div class="box-cell th p-l-2"><strong>동반자(<?=$z;?>)</strong></div>
										</div>
										<div class="box-row text-right">
											<div class="box-cell td p-r-2">
												<p><?=date("Y-m-d",strtotime($arr_add_birth[$k]))?> (<?=fn_ins_age(date("Y-m-d",strtotime($arr_add_birth[$k])))?>세, <?=$Arr_u_sex[$arr_add_gender[$k]]?>)</p>
												<h4><strong class="text-black" id="partner_<?=$z?>_txt">-</strong><small class="text-dark">원</small></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?}}?>
						</div>
					</div>
				</div>
			</div>
			<div class="compare-wrap">
				<div class="m-t-2 m-b-1">
					<h4 class="text-black">가입 상품 비교</h4>
					<p class="font-light text-black line-height-3 m-t-05">인슈플러스에서 가입 상품 <strong>3개</strong>를 골라서 <strong>비교</strong> 후 <br class="hidden visible-xs" /><strong>선택</strong>하세요!</p>
				</div>
				<ul class="nav nav-tabs nav-select bg-navy radius">
					<li class="title">플랜선택</li>
					<li class="select_plan"><a href="javascript:;">플랜을 선택해 주세요</a></li>
					<li class="select_plan"><a href="javascript:;">플랜을 선택해 주세요</a></li>
					<li class="select_plan"><a href="javascript:;">플랜을 선택해 주세요</a></li>
				</ul>
				<section class="main_plan" id="search_plan">
					<div class="container">
						<form name="frm_search" id="frm_search" method="post">
						<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>" />
						<input type="hidden" name="compare_seq[]" id="compare_seq1" value="" />
						<input type="hidden" name="compare_seq[]" id="compare_seq2" value="" />
						<input type="hidden" name="compare_seq[]" id="compare_seq3" value="" />
						<input type="hidden" name="chk_p" value="<?=$chk_p?>">
						<input type="hidden" name="s_date" value="<?=$s_date?>">
						<input type="hidden" name="s_date_time" value="<?=$s_date_time?>">
						<input type="hidden" name="e_date" value="<?=$e_date?>">
						<input type="hidden" name="e_date_time" value="<?=$e_date_time?>">
						<input type="hidden" name="gender" value="<?=$gender?>">
						<input type="hidden" name="birth" value="<?=$birth?>">
						<input type="hidden" name="purpose" value="<?=$purpose?>">
						<input type="hidden" name="select_add_people" value="<?=$select_add_people?>">
						<input type="hidden" name="joinType" value="<?=$joinType;?>" />
						<?if ($select_add_people>0){
						for($k=0;$k<count($arr_add_gender);$k++){?>
						<input type="hidden" name="add_gender[]" value="<?=$arr_add_gender[$k]?>">
						<input type="hidden" name="add_birth[]" value="<?=$arr_add_birth[$k]?>">
						<?}}?>
						<div class="row form-inline m-y-15">
							<div class="col-md-3 col-sm-6 col-md-offset-9 col-sm-offset-6">
								<div class="row">
									<div class="col-xs-6">
										<div class="dropdown bootstrap-select form-control">
											<select name="insurplus_check" class="form-control selectpicker" data-style="form-control" onchange="plan_list();" tabindex="-98">
												<option value="">전체 서비스</option>
												<option value="insuplus">인슈플러스</option>
												<option value="flying">플라잉닥터스</option>
											</select>
										</div>
									</div>
									<div class="col-xs-6">
										<div class="dropdown bootstrap-select form-control">
											<select name="sort1" class="form-control selectpicker" data-style="form-control" onchange="plan_list();" tabindex="-98">
												<option value="common_amount desc">보장높은순</option>
												<option value="common_amount asc">보장낮은순</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						</form>
						
						<!-- (s) 데이터 AJAX  -->
						<div class="row" id="plan_list"></div>
						<!-- (e) 데이터 AJAX  -->
						
					</div>
				</section>
				<!-- <p class="clearfix text-right text-danger m-b-2">
					산출 상품가 기준 <?=$PR_INFO["ext8"];?>
				</p> -->
				<ul class="pagination arrow-only">
					<!--<li class="prev"><a href="./javascript:history.go(-1)" class="disabled">이전</a></li>-->
					<li class="next"><a href="javascript: fnSelectPlan()">다음</a></li>
				</ul>
			</div>
        </div>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>

<script>
//보험사 한화손해보험(hanhwa.svg), 메리츠(meritz.svg), 현대해상(hyundai.svg), MG손해보험(mg.svg) (4개)
chkPlan = function(t,plan_seq,ins_name,plan_name,service_name,ins_logo_img) { //플랜선택

	if(!$(t).parent().hasClass("active")) {

		if($(".nav-select .active").length > 2) {
			alert("플랜은 최대3개까지 비교 선택 가능합니다. ");
			return;
		}

		var ins_img = ins_logo_img; //선택한 보험사에 따라 이미지 변경 필요
		var select_html = "";
		select_html+= "<img src='"+ins_img+"' class='logo-partners' />";
		select_html+= "<span class='m-l-1 p-l-1 border-l-1'>"+plan_name+"</span> ";
		if(service_name=="A") {
			select_html+= "<img src='../images/ic-small-logo.svg'>";
		} else if(service_name=="B") {
			select_html+= "<img src='../images/ic-small-logo.svg'>";
			select_html+= "<img src='../images/ic-small-logo.svg'>";
		}
		select_html+= "<span class='close' onClick='cancelPlan("+plan_seq+")'><i class='ti ti-close'></i></span>";

		$(t).attr("id","list_"+plan_seq);
		$(".nav-select .select_plan").each(function(i){
			if(!$(this).hasClass("active")){
				var k=i+1;
				$("#compare_seq"+k).val(plan_seq);
				$(this).addClass("active");
				$(this).attr("id",'nav_'+plan_seq);

				$(this).children("a").addClass("selected");
				$(this).children("a").html(select_html);
				return false;
			}
		})

	} else {
		var remove_html = "플랜을 선택해 주세요";
		$("input[name='compare_seq[]'").each(function(){
			if($(this).val() == plan_seq) {
				$(this).val("");
			}
		})
		//console.log($("#nav_"+plan_seq).indexOf("ul.nav .select_plan "));
		$("#nav_"+plan_seq).removeClass("active");
		$("#nav_"+plan_seq +" a ").removeClass("selected");
		$("#nav_"+plan_seq +" a ").html(remove_html);

		$("#nav_"+plan_seq).attr("id","");
		$("#list_"+plan_seq).attr("id","");
	}
	$(t).parent().toggleClass('active');
	$(t).parent().find('.btn-group .btn').toggleClass('disabled');

}

cancelPlan = function(plan_seq) { //플랜 취소
	var remove_html = "플랜을 선택해 주세요";
	$("input[name='compare_seq[]'").each(function(){
		if($(this).val() == plan_seq) {
			$(this).val("");
		}
	})
	
	$("#nav_"+plan_seq).removeClass("active");
	$("#nav_"+plan_seq +" a ").removeClass("selected");
	$("#nav_"+plan_seq +" a ").html(remove_html);

	$("#list_"+plan_seq).parent().toggleClass('active');
	$("#list_"+plan_seq).parent().find('.btn-group .btn').toggleClass('disabled');
	$("#nav_"+plan_seq).attr("id","");
	$("#list_"+plan_seq).attr("id","");
}

openPop = function(t) {
	var link_href = $(t).data("href");
	jQuery('#modal_iframe').attr('src',link_href);
	jQuery('#pop_modal .modal-header h3').text($(t).data('title'));
	jQuery('#pop_modal').find('#modal_document').removeClass('modal-lg').removeClass('modal-md').removeClass('modal-sm').addClass('modal-'+$(t).data('size'));
	$('#pop_modal').modal('show');
}

function fnSelectPlan() { //플랜비교하기

	var val_chk = "";
	$("input[name='compare_seq[]']").each(function(){
		val_chk += $(this).val();
	})
	
	if(!val_chk) {
		alert("플랜을 선택해 주세요.");
		return;
	}
	
	var ff = document.frm_search;
	ff.action = "./register_step_01.php";
	ff.submit();
}

function plan_list(){
	var ff = document.frm_search;
	var com1 = "";
	var com2 = "";
	var com3 = "";

	var formData = $("#frm_search").serialize();



	if ($('input:checkbox[id="insurplus_check"]').is(":checked") == true){
		com1 = $('input:checkbox[id="insurplus_check"]').val();
	}

	$.ajax({
		type : "POST",
		url : "./register_step_00_ajax_plan.php",
		cache : false,
        data:formData,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'html',
		success: function (result) {
			if (result){
				$("#plan_list").html(result);
			}
		},
		error : function(xhr, status, error) {
			alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
			return false;
		}
	});
}

plan_list();



$(function(){
	jQuery(document).ready(function () {
		// jQuery('#modal_iframe').attr('src','./pop_register_noti.php');
		// jQuery('#pop_modal').find('#modal_document').addClass('modal-sm');
		// jQuery('#pop_modal .modal-header h3').html('실손의료보험<br>중복가입 유의사항');
		// jQuery('#pop_modal').modal('show');
		// jQuery('[data-toggle="pop-modal"]').click(function(){
		// 	var link_href = jQuery(this).data("href");
		// });
	});
})
</script>
