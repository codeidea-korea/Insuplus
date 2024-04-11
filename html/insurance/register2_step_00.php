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
	if ($_POST["o_name"]){
		$name = $_POST["o_name"];
		$phone = $_POST["o_phone"];
		$email = $_POST["o_email"];
		$file_path = $_POST["file_path"];
	
		if($_POST["group_join_type"] == "B2C"){
			$group_join_type = "B2C";
			$birthdate = $_POST["birthdate"];
		} else {
			$group_join_type = "B2B";
			$biz_num = $_POST["biz_num"];
			$issue_num = $_POST["issue_num"];
		}
	
		$gender	= $_POST["gender"];					// 가입자 성별
		$birth		= $_POST["birth"];				// 가입자 생일
		$purpose= $_POST["purpose"];			// 여행타입
		$compare_seq  = $_POST["compare_seq"];
		//상품에 대한 인슈플러스 항목 리스트
		//$arr_insuplus = selInsuplusList($PR_INFO["ext5"]);
		//상품에 대한 보장내역 검색
		//$arr_guarantee = selGuaranteeList($PR_INFO["ext4"]);
		//디자인 UI
		$colspan = "1";
		$tit_colspan = "3";
		if(count($arr_plan_list) == "1")  $colspan = "2";
		if(count($arr_plan_list) == "3")  $tit_colspan = "4";
	
	} else {
		alert_page("올바른 경로로 이용해 주세요.","../main/index.php");
		exit;
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
			<div class="compare-wrap">
				<div class='m-t-2 m-b-1'>
					<h4 class='text-black'>가입 상품 비교</h4>
					<p class='font-light text-black line-height-3 m-t-05'>인슈플러스에서 가입 상품 최대 <strong>3개</strong>를 골라서 <strong>비교</strong> 후 <br class='hidden visible-xs' /><strong>선택</strong>하세요!</p>
				</div>
				<ul class='nav nav-tabs nav-select bg-navy radius'>
					<li class='title'>플랜선택</li>
					<li class='select_plan'><a href='javascript:;'>플랜을 선택해 주세요</a></li>
					<li class='select_plan'><a href='javascript:;'>플랜을 선택해 주세요</a></li>
					<li class='select_plan'><a href='javascript:;'>플랜을 선택해 주세요</a></li>
				</ul>
				<section class="main_plan" id='search_plan'>
					<div class="container">
						<form name="frm_search" id="frm_search" method="post">
						<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>" />
						<input type="hidden" name="o_name" value="<?=$name?>" />
						<input type="hidden" name="group_join_type" value="<?=$group_join_type?>">
						<input type="hidden" name="file_path" value="<?=$file_path?>">
						<input type="hidden" name="purpose" value="<?=$purpose?>">
						<input type="hidden" name="o_email" value="<?=$email?>">
						<input type="hidden" name="birthdate" value="<?=$birthdate?>">
						<input type="hidden" name="o_phone" value="<?=$phone?>">
						<input type="hidden" name="biz_num" value="<?=$biz_num?>">
						<input type="hidden" name="chk_p" value="<?=$chk_p?>">
						<input type="hidden" name="compare_seq[]" id="compare_seq1" value="" />
						<input type="hidden" name="compare_seq[]" id="compare_seq2" value="" />
						<input type="hidden" name="compare_seq[]" id="compare_seq3" value="" />
						<input type="hidden" name="plan_amount[]" id="plan_amount1" value="" />
						<input type="hidden" name="plan_amount[]" id="plan_amount2" value="" />
						<input type="hidden" name="plan_amount[]" id="plan_amount3" value="" />
						<input type="hidden" name="joinType" value="<?=$joinType?>" />
						<div class='row form-inline m-y-15'>
							<div class='col-md-3 col-sm-6 col-md-offset-9 col-sm-offset-6'>
								<div class='row'>
									<div class='col-xs-6 text-right m-t-1'>
										<div class='checkbox checkbox-inline'>
											<input type='checkbox' name='insurplus_check' id='insurplus_check' value='insuplus' onclick="plan_list()"/>
											<label for='insurplus_check'>인슈플러스</label>
										</div>
									</div>
									<div class='col-xs-6'>
										<select name="sort2" class='form-control selectpicker' data-style="form-control" onchange="plan_list();">
											<option value="" selected>보험사</option>
											<?if($arr_ins) { 
												foreach($arr_ins as $row) { ?>
												<option value="<?=$row["ins_seq"]?>"><?=$row["ins_name"]?></option>
											<?	}
											}?>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<!-- (s) 데이터 AJAX  -->
						<div class="row" id="plan_list"></div>
						<!-- (e) 데이터 AJAX  -->
						</form>
						
					</div>
				</section>
				<p class='clearfix text-right text-danger m-b-2'>
					산출 상품가 기준 <?=$PR_INFO["ext8"];?>
				</p>
				<ul class='pagination arrow-only'>
					<!--<li class='prev'><a href="./javascript:history.go(-1)" class='disabled'>이전</a></li>-->
					<li class='next'><a href='javascript: fnSelectPlan()'>다음</a></li>
				</ul>
			</div>
        </div>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>

<script>
//보험사 한화손해보험(hanhwa.svg), 메리츠(meritz.svg), 현대해상(hyundai.svg), MG손해보험(mg.svg) (4개)
chkPlan = function(t,plan_seq,ins_name,plan_name,service_name,ins_logo_img,plan_amount) { //플랜선택

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
				$("#plan_amount"+k).val(plan_amount);
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
	var formData = $("#frm_search").serialize();
	
	$.ajax({
		type : "POST",
		url : "./register2_step_reg_group.php",
		cache : false,
        data:formData,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'html',
		success: function (result) {
			if (result.trim() == "true"){
				ff.action = "./register2_step_01.php";
				ff.submit();
			}
		},
		error : function(xhr, status, error) {
			alert("단체가입 등록을 실패하였습니다. 관리자에게 문의하십시오.");
			return false;
		}
	});

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
		url : "./register2_step_00_ajax_plan.php",
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
		jQuery('#modal_iframe').attr('src','./pop_register_noti.php');
		jQuery('#pop_modal').find('#modal_document').addClass('modal-sm');
		jQuery('#pop_modal .modal-header h3').html('실손의료보험<br>중복가입 유의사항');
		jQuery('#pop_modal').modal('show');
		jQuery('[data-toggle="pop-modal"]').click(function(){
			var link_href = jQuery(this).data("href");
		});
	});
})
</script>
