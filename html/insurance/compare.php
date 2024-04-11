<?php
include '../_include/_header.html';
include '../_include/_top.html';
include '../_include/_sidebar.html';

include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.insurance.php";

// 상품 검색
$PR_INFO = getInsuProductInfo($PR_SEQ);

// 상품 보험사 서비스 검색	
$arr_ins = getNoteInsOfPr_cd($PR_SEQ);

?>
<div class="breadcrumb-image" style="background-image:url('<?= $PR_INFO["imgfile"] ?>')">
	<div class="container">
		<h2><?= $PR_INFO["subject"] ?></h2>
		<h4><?= $PR_INFO["content"] ?></h4>
	</div>
</div>
<div class="breadcrumb-wrap">
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="../main/index.php">InsuPlus HOME</a></li>
			<li>보험 비교</li>
		</ol>
	</div>
</div>
<div class="container">
	<div class="sub-content compare-wrap">
		<div class='text-center text-md-center text-sm-left text-xs-left title'>
			<h1 class='font-light text-black'><span class='point-rotate'>고민</span>하지 말고! 골라서 <span class='point-rotate'>선택</span>!</h1>
			<h3 class='font-light text-black'>인슈플러스에서 플랜 <strong>3개</strong>를 골라서 <br class='hidden visible-xs'><strong>비교</strong> 후 <strong>선택</strong>하세요!</h3>
		</div>
		<ul class='nav nav-tabs nav-select bg-navy radius'>
			<li class='title'>플랜선택</li>
			<li class='select_plan'><a href='javascript:;'>플랜을 선택해 주세요</a></li>
			<li class='select_plan'><a href='javascript:;'>플랜을 선택해 주세요</a></li>
			<li class='select_plan'><a href='javascript:;'>플랜을 선택해 주세요</a></li>
		</ul>
		<section class="main_plan" id='search_plan'>
			<div class="container">
				<form name="frm_search" id="frm_search">
					<input type="hidden" name="PR_SEQ" value="<?= $PR_SEQ ?>" />
					<input type="hidden" name="compare_seq[]" id="compare_seq1" value="" />
					<input type="hidden" name="compare_seq[]" id="compare_seq2" value="" />
					<input type="hidden" name="compare_seq[]" id="compare_seq3" value="" />
					<div class='row form-inline m-y-15'>
						<div class='col-md-3 col-sm-6 col-md-offset-9 col-sm-offset-6'>
							<div class='row'>
								<div class='col-xs-6'>
									<select name="insurplus_check" class='form-control selectpicker' data-style="form-control" onchange="plan_list();">
										<option value="">전체 서비스</option>
										<option value="insuplus">인슈플러스</option>
										<option value="flying">플라잉닥터스</option>
									</select>
								</div>
								<div class='col-xs-6'>
									<select name="sort1" class='form-control selectpicker' data-style="form-control" onchange="plan_list();">
										<!-- <option value="추천순" selected>추천순</option>
											<option value="인기순">인기순</option> -->
										<option value="common_amount desc">보장높은순</option>
										<option value="common_amount asc">보장낮은순</option>
									</select>
								</div>
								<!-- <div class='col-xs-4'>
									<select name="sort2" class='form-control selectpicker' data-style="form-control" onchange="plan_list();">
										<option value="" selected>보험사</option>
										<? if ($arr_ins) {
											foreach ($arr_ins as $row) {
										?>
												<option value="<?= $row["ins_seq"] ?>"><?= $row["ins_name"] ?></option>
										<?	}
										} ?>
									</select>
								</div> -->
								<p class='clearfix text-right text-danger m-r-1'>산출 상품가 기준 : <?= $PR_INFO["ext8"] ?></p>
							</div>
						</div>
					</div>
				</form>
				<!-- (s) 데이터 AJAX  -->
				<div class="row" id="plan_list"></div>
				<!-- (e) 데이터 AJAX  -->
			</div>
		</section>
		<div class='row'>
			<div class='col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0'>
				<div class='row'>
					<a class='btn btn-lg btn-block btn-theme-bg' onClick="fnSelectPlan()">플랜비교 가입하기</a>
					<!-- <div class='col-xs-6'>
								<a class='btn btn-lg btn-block btn-theme-dark' href='./pr_plans.php?PR_SEQ=<?= $PR_SEQ ?>'>상품안내 이동하기</a>
							</div>
							<div class='col-xs-6'>
								<a class='btn btn-lg btn-block btn-theme-bg' onClick="fnSelectPlan()">플랜비교 가입하기</a>
							</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
<script>
	//보험사 한화손해보험(hanhwa.svg), 메리츠(meritz.svg), 현대해상(hyundai.svg), MG손해보험(mg.svg) (4개)
	chkPlan = function(t, plan_seq, ins_name, plan_name, service_name, ins_logo_img) { //플랜선택

		if (!$(t).parent().hasClass("active")) {

			if ($(".nav-select .active").length > 2) {
				alert("플랜은 최대3개까지 비교 선택 가능합니다. ");
				return;
			}

			var ins_img = ins_logo_img; //선택한 보험사에 따라 이미지 변경 필요
			var select_html = "";
			select_html += "<img src='" + ins_img + "' class='logo-partners' />";
			select_html += "<span class='m-l-1 p-l-1 border-l-1'>" + plan_name + "</span> ";
			if (service_name == "A") {
				select_html += "<img src='../images/ic-small-logo.svg'>";
			} else if (service_name == "B") {
				select_html += "<img src='../images/ic-small-logo.svg'>";
				select_html += "<img src='../images/ic-small-logo.svg'>";
			}
			select_html += "<span class='close' onClick='cancelPlan(" + plan_seq + ")'><i class='ti ti-close'></i></span>";

			$(t).attr("id", "list_" + plan_seq);
			$(".nav-select .select_plan").each(function(i) {
				if (!$(this).hasClass("active")) {
					var k = i + 1;
					$("#compare_seq" + k).val(plan_seq);
					$(this).addClass("active");
					$(this).attr("id", 'nav_' + plan_seq);

					$(this).children("a").addClass("selected");
					$(this).children("a").html(select_html);
					return false;
				}
			})

		} else {
			var remove_html = "플랜을 선택해 주세요";
			$("input[name='compare_seq[]'").each(function() {
				if ($(this).val() == plan_seq) {
					$(this).val("");
				}
			})
			//console.log($("#nav_"+plan_seq).indexOf("ul.nav .select_plan "));
			$("#nav_" + plan_seq).removeClass("active");
			$("#nav_" + plan_seq + " a ").removeClass("selected");
			$("#nav_" + plan_seq + " a ").html(remove_html);

			$("#nav_" + plan_seq).attr("id", "");
			$("#list_" + plan_seq).attr("id", "");
		}
		$(t).parent().toggleClass('active');
		$(t).parent().find('.btn-group .btn').toggleClass('disabled');

	}

	cancelPlan = function(plan_seq) { //플랜 취소
		var remove_html = "플랜을 선택해 주세요";
		$("input[name='compare_seq[]'").each(function() {
			if ($(this).val() == plan_seq) {
				$(this).val("");
			}
		})

		$("#nav_" + plan_seq).removeClass("active");
		$("#nav_" + plan_seq + " a ").removeClass("selected");
		$("#nav_" + plan_seq + " a ").html(remove_html);

		$("#list_" + plan_seq).parent().toggleClass('active');
		$("#list_" + plan_seq).parent().find('.btn-group .btn').toggleClass('disabled');
		$("#nav_" + plan_seq).attr("id", "");
		$("#list_" + plan_seq).attr("id", "");
	}

	openPop = function(t) {
		var link_href = $(t).data("href");
		jQuery('#modal_iframe').attr('src', link_href);
		jQuery('#pop_modal .modal-header h3').text($(t).data('title'));
		jQuery('#pop_modal').find('#modal_document').removeClass('modal-lg').removeClass('modal-md').removeClass('modal-sm').addClass('modal-' + $(t).data('size'));
		$('#pop_modal').modal('show');
	}

	function fnSelectPlan() { //플랜비교하기

		var val_chk = "";
		$("input[name='compare_seq[]']").each(function() {
			val_chk += $(this).val();
		})

		if (!val_chk) {
			alert("플랜을 선택해 주세요.");
			return;
		}

		var ff = document.frm_search;
		ff.action = "./search_insur.php";
		ff.submit();
	}

	function plan_list() {
		var ff = document.frm_search;
		var com1 = "";
		var com2 = "";
		var com3 = "";

		var formData = $("#frm_search").serialize();

		if ($('input:checkbox[id="insurplus_check"]').is(":checked") == true) {
			com1 = $('input:checkbox[id="insurplus_check"]').val();
		}



		$.ajax({
			type: "POST",
			url: "./compare_ajax_plan.php",
			cache: false,
			data: formData,
			contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
			dataType: 'html',
			success: function(result) {
				if (result) {
					$("#plan_list").html(result);
				}
			},
			error: function(xhr, status, error) {
				alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
				return false;
			}
		});
	}

	plan_list();
	closedPage();

</script>