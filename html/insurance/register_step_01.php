<?php
	include '../_include/_header.html';
	
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가

?>
<?
//==========================================================


$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];
$compare_seq = "";

// if($_POST["joinType"] == "2") { //step1~5방식
	
// 	for($i=0; $i<count($_POST["compare_seq"]); $i++) {
// 		if($_POST["compare_seq"][$i]) {
// 			if($compare_seq) $compare_seq .= ",";
// 			$compare_seq .= $_POST["compare_seq"][$i];
// 		}
// 	}
// } else {
// 	$compare_seq = $_POST["compare_seq"];
// }


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
	
	$gender	= $_POST["gender"];					// 가입자 성별
	$birth		= $_POST["birth"];				// 가입자 생일
	$age = fn_ins_age(date("Y-m-d",strtotime($birth))); //보험나이
	$purpose= $_POST["purpose"];			// 여행타입
	$select_add_people = $_POST["select_add_people"]; // 동반인명수
	
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

	//선택 플랜 리스트
	$arr_plan_list = selPlanList($PR_SEQ, $compare_seq, $t_s_date, $chk_p);
	

	//상품에 대한 인슈플러스 항목 리스트
	if($PR_INFO["ext5"]) {
		$arr_insuplus = selInsuplusList($PR_INFO["ext5"]);
	}
	
	
	
	//상품에 대한 보장내역 검색
	if($PR_INFO["ext4"]){
		$arr_guarantee = selGuaranteeList($PR_INFO["ext4"]);
	}
	
	//디자인 UI
	$colspan = "1";
	//$tit_colspan = "3";
	$tit_colspan = count($arr_plan_list)+1;
	if(count($arr_plan_list) == "1")  $colspan = "2";
	//if(count($arr_plan_list) == "3")  $tit_colspan = "4";
	
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
			<?if ($_POST["s_date"] && $_POST["e_date"]){?>
			<div class="step_join">
				<? include './register_step_ui.php'; ?>
			</div>
			<div class="register-box clearfix">
				<div class='box'>
					<div class='box-row'>
						<div class='box-cell col-sm-4 col-xs-12 p-a-0'>
							<div class='box valign-middle'>
								<div class='box-row bg-navy text-left'>
									<div class='box-cell th col-sm-4 col-xs-12 p-l-2'><strong>기간</strong></div>
								</div>
								<div class='box-row valign-middle'>
									<div class='box-cell td col-sm-4 col-xs-12 p-x-2'>
										<h5 class='pull-right text-right text-black line-height-2'><?=$s_date_text?> ~ <?=$e_date_text?></h5>
										<h2 class='font-normal'><span class='text-black'><?=$arr_period["day"]?></span>일</h2>
										<!-- <h2 class='font-normal'><span class='text-black'><?=$arr_period["day"]?></span>일(<span class='text-black'><?=$arr_period["month"]?></span>개월)</h2> -->
									</div>
								</div>
							</div>
						</div>
						<div class='box-cell col-sm-8 col-xs-12 p-a-0'>
							<div class='box'>
								<div class='box-row bg-navy text-left'>
									<div class='box-cell th p-l-2'><strong>출국목적</strong></div>
									<div class='box-cell th p-l-2'><strong>상품가격</strong></div>
								</div>
								<div class='box-row text-right'>
									<div class='box-cell td p-r-2'><h5 class=''><?=$PR_INFO["ext7"]?></h5></div>
									<div class='box-cell td p-r-2'><h4 class='text-danger'><strong id="tot_amount_txt">-</strong><small class='text-dark'>원</small></h4></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='join_title clearfix' data-toggle='collapse' data-target='#join_info' aria-expanded="false">
					<a class='btn btn-sm btn-default pull-right'>자세히 보기</a>
					<h5>가입정보</h5>
				</div>
				<div class='collapse' id='join_info'>
					<div class='box'>
						<div class='box-row'>
							<div class='box-cell col-sm-4 col-xs-12'>
								<div class='join_info'>
									<div class='box'>
										<div class='box-row bg-navy text-left'>
											<div class='box-cell th p-l-2'><strong>가입자</strong></div>
										</div>
										<div class='box-row text-right'>
											<div class='box-cell td p-r-2'>
												<p><?=date("Y-m-d",strtotime($birth))?> (<?=$age?>세, <?=$Arr_u_sex[$gender]?>)</p>
												<h4><strong class='text-black' id="join_txt">-</strong><small class='text-dark'>원</small></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?for ($k=0;$k<count($arr_add_birth);$k++){
							if ($arr_add_birth[$k]!=""){
								$z=$k+1;
							?>
							<div class='box-cell col-sm-4 col-xs-12'>
								<div class='join_info'>
									<div class='box'>
										<div class='box-row bg-navy text-left'>
											<div class='box-cell th p-l-2'><strong>동반인(<?=$z;?>)</strong></div>
										</div>
										<div class='box-row text-right'>
											<div class='box-cell td p-r-2'>
												<p><?=date("Y-m-d",strtotime($arr_add_birth[$k]))?> (<?=fn_ins_age(date("Y-m-d",strtotime($arr_add_birth[$k])))?>세, <?=$Arr_u_sex[$arr_add_gender[$k]]?>)</p>
												<h4><strong class='text-black' id="partner_<?=$z?>_txt">-</strong><small class='text-dark'>원</small></h4>
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
			<?}?>

			<form name="frm1" method="post">
			<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>">
			<input type="hidden" name="s_date" value="<?=$s_date?>">
			<input type="hidden" name="s_date_time" value="<?=$s_date_time?>">
			<input type="hidden" name="e_date" value="<?=$e_date?>">
			<input type="hidden" name="e_date_time" value="<?=$e_date_time?>">
			<input type="hidden" name="gender" value="<?=$gender?>">
			<input type="hidden" name="birth" value="<?=$birth?>">
			<input type="hidden" name="purpose" value="<?=$purpose?>">
			<input type="hidden" name="select_add_people" value="<?=$select_add_people?>">
			<input type="hidden" name="joinType" value="<?=$joinType?>">
			<?if ($select_add_people>0){
			for($k=0;$k<count($arr_add_gender);$k++){?>
			<input type="hidden" name="add_gender[]" value="<?=$arr_add_gender[$k]?>">
			<input type="hidden" name="add_birth[]" value="<?=$arr_add_birth[$k]?>">
			<?}}?>
			<div class='m-t-2 m-b-1'>
				<table width="100%" class="table table-bordered">
					<thead>
						<tr class='text-center'>
							<th class='p-y-1 border-b-2 border-default bg-white darken text-black'>선택</th>
							<? 
							$k=1;
							$plan_chk = array();
							foreach($arr_plan_list as $row) {
								$z=$k+1;
								$tot_amount = 0;
								$reason_txt = ""; //가입불가 이유
								$partner_amount = array();
								$check_age = fn_check_ins_age($row["seq"],$age,$gender);
								$ins_amount = fn_sel_ins_amt($period,$chk_p,$row["seq"],$age,$gender);
								
								if(!$check_age) {
									$ins_age = fn_sel_ins_age($row["seq"]);
									$reason_txt = $ins_age[0]."세~".$ins_age[1]."세까지 가입가능";
									$plan_chk[$row["seq"]] = "N";
								}
								
								if(!$reason_txt) {
									if ($select_add_people >0){
										$arr_add_gender = array();
										$arr_add_birth = array();
										for($j=0;$j<5;$j++){
											if(!$reason_txt) {
												if ($_POST["add_gender"][$j]){
													$partner_age = fn_ins_age(date("Y-m-d",strtotime($_POST["add_birth"][$j])));
													$check_partner_age = fn_check_ins_age($row["seq"],$partner_age,$_POST["add_gender"][$j]);
													$ins_amount_partner = fn_sel_ins_amt($period,$chk_p,$row["seq"],$partner_age,$_POST["add_gender"][$j]);
													if(!$check_partner_age) {
														$ins_age = fn_sel_ins_age($row["seq"]);
														$reason_txt = $ins_age[0]."세~".$ins_age[1]."세까지 가입가능";
														$plan_chk[$row["seq"]] = "N";
													}				
												}
											}
										}
									}
								}
							?>
								<th class='p-t-05 p-b-1 border-b-2 border-default' colspan="<?=$colspan?>">
									<span class='checkbox styled'>
										<? if(!$reason_txt) {?>
										<input type='radio' name='plan_seq' id='red_plan<?=$k?>' value='<?=$row["seq"]?>' onClick="fnCheck('<?=$row["seq"]?>')" />
										<label for='red_plan<?=$k?>'></label>
										<? } else { ?>
										<?=$reason_txt;?>
										<? } ?>
									</span>
								</th>
							<?$k++;
							}?>
						</tr>
					</thead>
					<tbody class='text-center'>
						<tr>
							<td class='p-y-1 bg-white darken text-black'>플랜명 </td>
							<? foreach($arr_plan_list as $row) {?>
							<td class='p-y-1' colspan="<?=$colspan?>">
							<strong class='text-<?=$Arr_plan_cd_css[$row["plan_cd"]]?>'><?=$Arr_plan_cd[$row["plan_cd"]]?></strong></td>
							<? } ?>
						</tr>
						<tr>
							<td class='p-y-1 bg-white darken text-black'>상품가격</td>
							<? 								
								$reason_txt = "";
								foreach($arr_plan_list as $row) {
									if($plan_chk[$row["seq"]] != "N") {
										$tot_amount = 0;
										$partner_amount = array();
										$ins_amount = fn_sel_ins_amt($period,$chk_p,$row["seq"],$age,$gender);
										if($ins_amount) {
											$reason_txt = $age."이상 가입 불가";
										}
										$servie_amount = fn_ins_service_amt($row["seq"],$row["chk_service"],$period_month, $period_day);
										$join_amount = $ins_amount+$servie_amount; //가입자
										
										if ($select_add_people >0){
											$arr_add_gender = array();
											$arr_add_birth = array();
											for($k=0;$k<5;$k++){
												
												if ($_POST["add_gender"][$k]){
													
													$partner_age = fn_ins_age(date("Y-m-d",strtotime($_POST["add_birth"][$k])));
													$ins_amount_partner = fn_sel_ins_amt($period,$chk_p,$row["seq"],$partner_age,$_POST["add_gender"][$k]);
													$servie_amount_partner = fn_ins_service_amt($row["seq"],$row["chk_service"],$period_month, $period_day);
													$partner_amount[] = $ins_amount_partner+$servie_amount_partner;
													
													$tot_amount += $partner_amount[$k];
													
												}
											}
										}
		
										$tot_amount += $join_amount; //전체상품가격
									}
							?>
							<td class='p-y-1 text-black' id="amount_<?=$row["seq"]?>" data-amount="<?=number_format($tot_amount)?>" data-join="<?=number_format($join_amount)?>"
							data-partner_1="<?=number_format($partner_amount[0])?>" data-partner_2="<?=number_format($partner_amount[1])?>" data-partner_3="<?=number_format($partner_amount[2])?>" data-partner_4="<?=number_format($partner_amount[3])?>" data-partner_5="<?=number_format($partner_amount[4])?>"
							colspan="<?=$colspan?>">
								<? if($plan_chk[$row["seq"]] != "N") { ?>
									<?=number_format($tot_amount)?>원
								<? } else { ?>
									-
								<? } ?>
							</td>
							<? } ?>

						</tr>
					</tbody>
					<? if(count($arr_insuplus) > 0 ) {?>
					<tbody class='text-center'>
						<tr>
							<td class="p-y-05 bg-darken-red text-black text-left p-l-2 border-default" colspan="<?=$tit_colspan?>">인슈플러스</td>
						</tr>
						<? $k = 0;
						foreach($arr_insuplus as $row_ins) { ?>
						<tr>
							<td class='p-y-05 bg-light-red text-black'><?=$row_ins["service_name"]?></td>
							<? foreach($arr_plan_list as $row) {
								$sql = "select k_amount from tbl_board_plan_insuplus where 1=1 and plan_seq = '".$row["seq"]."' ";
								$RS_PLAN_SEQ = $dbcon->query($sql);
								$ARR_PLAN_INS = array();
	
								while($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
									$ARR_PLAN_INS[] = $row_s;
								}
								$column = strtolower($row["chk_service"]);?>
								<td class='p-y-1 text-black' colspan="<?=$colspan?>"><?=$ARR_PLAN_INS[$k]["k_amount"] ? $ARR_PLAN_INS[$k]["k_amount"] : "-";?></td>
							<? } ?>
						</tr>
						<?$k++;}?>
					</tbody>
					<? } ?>
					<? if($arr_guarantee){ ?>
					<tbody class='text-center'>
						<tr>
							<td class='p-y-05 bg-darken-navy text-black text-left p-l-2 border-default' colspan="<?=$tit_colspan?>">보장내역</td>
						</tr>
							<? foreach($arr_guarantee as $row_guarantee){ ?>
							<tr>
								<td class='p-y-05 bg-light-navy text-black'><?=$row_guarantee["service_name"]?></td>
								<? foreach($arr_plan_list as $row) {
									$arr_guaranteeOfPlan = selGuaranteeOfPlan($row["seq"],$row_guarantee["idx"]);
								?>
								<td class='p-y-1 text-black' colspan="<?=$colspan?>"><?=$arr_guaranteeOfPlan["g_amount"]?></td>
								<? } ?>
							</tr>
						    <? }?>
					</tbody>
					<? } ?>
				</table>
				<ul class='pagination arrow-only'>
					<? if($joinType == "2") {?>
						<li class='prev'><a href="javascript:history.back(-1);">이전</a></li>
					<? } ?>
					<li class='next'><a href='javascript: chk_submit1();'>다음</a></li>
				</ul>
			</div>
			</form>
			
        </div>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>

<script>

function chk_submit1(){
	var ff = document.frm1;

	if(!$("input:radio[name='plan_seq']:checked").val()) {
		alert("플랜을 선택해 주세요.");
		$("input:radio[name='plan_seq']").focus();
		return;
	}
	
	ff.action="./register_step_02.php";
	ff.submit();
}

function fnCheck(plan_seq) {
	$("#join_txt").html($("#amount_"+plan_seq).data("join"));

	<? for($k=1; $k<=$select_add_people; $k++) {?>
	$("#partner_<?=$k?>_txt").html($("#amount_"+plan_seq).data("partner_<?=$k?>"));
	<? } ?>
	
	$("#tot_amount_txt").html($("#amount_"+plan_seq).data("amount"));
}


<?if ($plan_seq){?>
	var ff = document.frm1;
	ff.action="./register_step_02.php";
	ff.submit();
<?}?>

jQuery(document).ready(function () {
	$("input:radio[name='plan_seq']")[0].checked = true;
	fnCheck($("input:radio[name='plan_seq']")[0].value);
	<? if($_POST["joinType"] != "2") {?>
		/*jQuery('#modal_iframe').attr('src','./pop_register_noti.php');
		jQuery('#pop_modal').find('#modal_document').addClass('modal-sm');
		jQuery('#pop_modal .modal-header h3').html('실손의료보험<br>중복가입 유의사항');
		jQuery('#pop_modal').modal('show');
		jQuery('[data-toggle="pop-modal"]').click(function(){
			var link_href = jQuery(this).data("href");
		});*/
	<? } ?>
		
});	
</script>
