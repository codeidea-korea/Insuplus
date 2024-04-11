<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
?>
<?
//==========================================================
if (!$plan_seq){echo "<script>alert('잘못된 경로로 들어오셨습니다.');location.href='/html/main/index.php';</script>";exit;}

$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];
$is_noti = $PR_INFO["is_notification_visible"];

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
	
	$gender				= $_POST["gender"];								// 가입자 성별
	$birth				= $_POST["birth"];								// 가입자 생일
	$user_name			= all_seed_enc(trim($_POST["user_name"]));			// 가입자 이름
	$user_rnumber		= all_seed_enc(trim($_POST["user_rnumber"]));			// 가입자 주민등록번호
	$user_hp			= all_seed_enc(trim($_POST["user_hp"]));				// 가입자 연락처
	$email				= all_seed_enc(trim($_POST["email"]));				// 가입자 이메일 앞
	$email2				= all_seed_enc(trim($_POST["email2"]));				// 가입자 이메일 뒤
	$purpose			= $_POST["purpose"];							// 여행타입
	$c_name				= $_POST["c_name"];								// 여행국 이름
	$c_code				= $_POST["c_code"];								// 여행국 코드
	$en_secur			= $_POST["en_secur"];							// 영문여권 여부
	$en_name			= all_seed_enc(trim($_POST["en_name"]));				// 영문여권 이름
	
	$select_add_people = $_POST["select_add_people"];					// 동반인명수
	if ($select_add_people >0){
		$arr_add_gender = array();
		$arr_add_birth = array();
		$arr_add_name = array();
		for($k=0;$k<5;$k++){
			if ($_POST["add_gender"][$k] && $_POST["add_birth"][$k]){
			$arr_add_gender[] = $_POST["add_gender"][$k];									// 동반인 성별
			$arr_add_birth[] = $_POST["add_birth"][$k];											// 동반인 생일
			$arr_add_name[] = all_seed_enc(trim($_POST["add_user_name"][$k]));			// 동반인 이름
			$arr_add_rnumber[] = all_seed_enc(trim($_POST["add_rnumber"][$k]));			// 동반인 주민등록번호
			$arr_add_en_secur[] = $_POST["add_en_secur"][$k];							// 영문여권 여부
			$arr_add_en_name[] = all_seed_enc(trim($_POST["add_en_name"][$k]));		// 영문여권 이름
			}
		}
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

// 알릴사항 검색
if($PR_INFO["ext3"] == "Y") {
	$SQL_N = "select * from tbl_board_product_notice where pr_seq = ".$PR_SEQ." ";
	$RS_N = $dbcon -> query($SQL_N);
}

//현시점 약관 번호 검색
$rule_site_cd = selRuleSeq("사이트 이용약관");
$rule_group_cd = selRuleSeq("단체보험 규약");
$rule_privacy_cd = selRuleSeq("개인정보 수집 및 이용 동의");

//보험약관 파일 확인
$arr_ins_agree_file = selInsAgreeFile($plan_view["agree_cd"]);

//인슈플러스 서비스 약관 파일 확인
if($plan_view["chk_service"] == "A" ||  $plan_view["chk_service"] == "B") {
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
			<!-- <div class="register-box clearfix">
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
										<h5 class='pull-right text-right text-black line-height-2'><?=$s_date_text?><br class='hidden-xs'>~ <?=$e_date_text?>:00</h5>
										<h2 class='font-normal'><span class='text-black'><?=$arr_period["day"]?></span>일(<span class='text-black'><?=$arr_period["month"]?></span>개월)</h2>
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
									<div class='box-cell td p-r-2'><h5 class=''><?=print_pr_info($PR_SEQ,"subject");?></h5></div>
									<div class='box-cell td p-r-2'><h4 class='text-danger'><strong><?=number_format($t_amt);?></strong><small class='text-dark'>원</small></h4></div>
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
												<p><?=date("Y-m-d",strtotime($birth))?> (<?=fn_ins_age(date("Y-m-d",strtotime($birth)))?>세, <?=$Arr_u_sex[$gender]?>)</p>
												<h4><strong class='text-black'><?=number_format($user_amt+$service_amt)?></strong><small class='text-dark'>원</small></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?for ($k=0;$k<count($arr_add_birth);$k++){
							if ($arr_add_birth[$k]!=""){
							?>
							<div class='box-cell col-sm-4 col-xs-12'>
								<div class='join_info'>
									<div class='box'>
										<div class='box-row bg-navy text-left'>
											<div class='box-cell th p-l-2'><strong>동반인</strong></div>
										</div>
										<div class='box-row text-right'>
											<div class='box-cell td p-r-2'>
												<p><?=date("Y-m-d",strtotime($arr_add_birth[$k]))?> (<?=fn_ins_age(date("Y-m-d",strtotime($arr_add_birth[$k])))?>세, <?=$Arr_u_sex[$arr_add_gender[$k]]?>)</p>
												<h4><strong class='text-black'><?=number_format($add_user_amt[$k]+$service_amt)?></strong><small class='text-dark'>원</small></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?}}?>
						</div>
					</div>
				</div>
			</div> -->
			<form class='form-inline' name="frm3">
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
			<input type="hidden" name="en_secur" value="<?=$en_secur?>">
			<input type="hidden" name="en_name" value="<?=$en_name?>">
			<input type="hidden" name="c_name" value="<?=$c_name?>">
			<input type="hidden" name="c_code" value="<?=$c_code?>">
			<input type="hidden" name="purpose" value="<?=$purpose?>">
			<input type="hidden" name="t_amt" value="<?=$t_amt?>"><!-- 총금액 -->
			<input type="hidden" name="service_amt" value="<?=$service_amt?>"><!-- 개별 서비스 금액 -->
			<input type="hidden" name="select_add_people" value="<?=$select_add_people?>">
			<input type="hidden" name="joinType" value="<?=$joinType?>">
			
			<input type="hidden" name="rule_site_cd" value="<?=$rule_site_cd?>"><!-- 사이트 이용약관  -->
			<input type="hidden" name="rule_group_cd" value="<?=$rule_group_cd?>"><!-- 단체보험 규약 이용약관  -->
			<input type="hidden" name="rule_privacy_cd" value="<?=$rule_privacy_cd?>"><!-- 개인정보 수집 및 이용약관  -->
			<input type="hidden" name="is_abroad_resident" value="N"><!-- 해외거주 여부  -->
			
			<? if($chk_p == "N" && $is_noti == "Y") { //해외거주 여부 장기플랜일 경우 결제자 정보에만 기입함 2023.04.27 ?>
				<div class='clearfix m-t-3 m-b-1'> 
					<h4 class='pull-left text-black'>(필수) 해외거주 유무</h4>
					<div class='pull-right'>
					</div>
				</div>
				<div class='clearfix' id='form_abroad_resident'>
					<div class='row-border'>
						<div class='detail-col-all text-black' style="padding: 12px 10px;">
							<span>현재 외국에 거주중이거나 가입하는 장소가 외국이십니까? <br>
							해외체류 중 가입은 유학, 법인소속 해외근무자(주재원, 공무원, 교환교수 등)만 가입하실 수 있습니다.</span>
							<div class='pull-right radiobox'>
								<div class='radio radio-inline'>
									<input type="radio" name='is_abroad_resident' id='is_abroad_resident_y' value="Y" />
									<label for='is_abroad_resident_y'>예</label>
								</div>
								<div class='radio radio-inline'>
									<input type="radio" name='is_abroad_resident' id='is_abroad_resident_n' value="N" checked/>
									<label for='is_abroad_resident_n'>아니오</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? } ?>
			<?if ($select_add_people >0){
			for($k=0;$k<count($_POST["add_birth"]);$k++){?>
			<input type="hidden" name="add_gender[]" value="<?=$_POST["add_gender"][$k]?>">
			<input type="hidden" name="add_birth[]" value="<?=$_POST["add_birth"][$k]?>">
			<input type="hidden" name="add_user_name[]" value="<?=$arr_add_name[$k]?>">
			<input type="hidden" name="add_rnumber[]" value="<?=$arr_add_rnumber[$k]?>">
			<input type="hidden" name="add_en_secur[]" value="<?=$arr_add_en_secur[$k]?>">
			<input type="hidden" name="add_en_name[]" value="<?=$arr_add_en_name[$k]?>">
			<?}}?>
				<? if($PR_INFO["ext3"] == "Y") { ?>
				<div class='clearfix m-t-3 m-b-1' id='form_agree1_all'> 
					<h4 class='pull-left text-black'>(필수) 알릴사항1</h4>
					<div class='pull-right'>
						<div class='radio radio-inline'>
							<input type="radio" name='radio_1_all' id='radio_1_all' />
							<label for='radio_1_all'>전체 아니오</label>
						</div>
					</div>
				</div>
				<div class='clearfix' id='form_agree1'>
					<div class='row-border'>
						<?
						//=================================================
						// 알릴사항
						//=================================================
						
							$k = 1;
							while($row_n = $dbcon -> fetch_array($RS_N)){?>
							<div class='detail-col-all text-black' style="padding: 12px 10px;">
								<input type="hidden" name="pr_notice[<?=$k?>]" value="<?=$row_n["pr_notice"]?>">
								<?=$k?>. <?=$row_n["pr_notice"]?>
								<div class='pull-right radiobox'>
									<div class='radio radio-inline'>
										<input type="radio" name='notice_<?=$k?>' id='radio_<?=$k?>_y' value="Y" checked/>
										<label for='radio_<?=$k?>_y'>예</label>
									</div>
									<div class='radio radio-inline'>
										<input type="radio" name='notice_<?=$k?>' id='radio_<?=$k?>_n' value="N"  />
										<label for='radio_<?=$k?>_n'>아니오</label>
									</div>
								</div>
							</div>
							<?$k++;
							}?>
					</div>
				</div>
				<? } ?>				
				<div class='clearfix m-t-3 m-b-1' id='form_agree2_all'>
					<h4 class='pull-left text-black'>(필수) 가입을 위해 아래사항을 확인해 주세요.</h4>
					<div class='pull-right'>
						<div class='radio radio-inline'>
							<input type="radio" name='radio_2_all' id='radio_2_all' />
							<label for='radio_2_all'>전체 동의</label>
						</div>
					</div>
				</div>
				<div class='clearfix' id='form_agree2'>
					<div class='row-border'>
						<div class='detail-col-all flex-wrap text-right text-black'>
							<div class='row text-left'>
								<div class='col-lg-3 col-md-4 col-sm-5 col-xs-12'>
									<a data-toggle='pop-modal' data-size='lg' data-href="./pop_rule_site.php?policy_name=사이트 이용약관" data-title='사이트 이용약관' target='modal_iframe' class='btn btn-sm btn-default pull-right btn-line'><i class='ti ti-search'></i> 자세히 보기</a>
									사이트 이용약관 동의
								</div>
								<div class='col-lg-9 col-md-8 col-sm-7 col-xs-12 text-right'>
									<div class='radiobox'>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_1' id='radio2_1_y' value="Y" />
											<label for='radio2_1_y'>동의</label>
										</div>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_1' id='radio2_1_n' value="N" checked />
											<label for='radio2_1_n'>미동의</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='detail-col-all flex-wrap text-right text-black'>
							<div class='row text-left'>
								<div class='col-lg-3 col-md-4 col-sm-5 col-xs-12'>
									<a href="/_data/board/ins_agree/<?=$arr_ins_agree_file["file_realname"]?>" target="_blank" class='btn btn-sm btn-default pull-right  btn-line'><i class='ti ti-search'></i> 자세히 보기</a>
									상품 가입약관 동의
								</div>
								<div class='col-lg-9 col-md-8 col-sm-7 col-xs-12 text-right'>
									<div class='radiobox'>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_2' id='radio2_2_y' value="Y" />
											<label for='radio2_2_y'>동의</label>
										</div>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_2' id='radio2_2_n' value="N" checked />
											<label for='radio2_2_n'>미동의</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<? if($plan_view["chk_service"] == "A" || $plan_view["chk_service"] == "B") { //서비스 상품?>
						<div class='detail-col-all flex-wrap text-right text-black'>
							<div class='row text-left'>
								<div class='col-lg-3 col-md-4 col-sm-5 col-xs-12'>
									<a href="/_data/board/service_agree/<?=$arr_service_file["file_realname"]?>" target="_blank" class='btn btn-sm btn-default pull-right btn-line'><i class='ti ti-search'></i> 자세히 보기</a>
									서비스 이용약관 동의
								</div>
								<div class='col-lg-9 col-md-8 col-sm-7 col-xs-12 text-right'>
									<div class='radiobox'>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_3' id='radio2_3_y' value="Y"/>
											<label for='radio2_3_y'>동의</label>
										</div>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_3' id='radio2_3_n' value="N" checked />
											<label for='radio2_3_n'>미동의</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<? } ?>
						<div class='detail-col-all flex-wrap text-right text-black'>
							<div class='row text-left'>
								<div class='col-lg-3 col-md-4 col-sm-5 col-xs-12'>
									<a data-toggle='pop-modal' data-size='lg' data-href="./pop_rule_site.php?policy_name=단체보험 규약" data-title='단체 보험규약' target='modal_iframe' class='btn btn-sm btn-default pull-right btn-line'><i class='ti ti-search'></i> 자세히 보기</a>
									단체 보험규약 동의
								</div>
								<div class='col-lg-9 col-md-8 col-sm-7 col-xs-12 text-right'>
									<div class='radiobox'>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_4' id='radio2_4_y' value="Y"/>
											<label for='radio2_4_y'>동의</label>
										</div>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_4' id='radio2_4_n' value="N" checked />
											<label for='radio2_4_n'>미동의</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class='detail-col-all flex-wrap text-right text-black'>
							<div class='row text-left'>
								<div class='col-lg-3 col-md-4 col-sm-5 col-xs-12'>
									<a data-toggle='pop-modal' data-size='lg' data-href="./pop_rule_site.php?policy_name=개인정보 수집 및 이용 동의" data-title='개인정보 수집 및 이용' target='modal_iframe' class='btn btn-sm btn-default pull-right btn-line'><i class='ti ti-search'></i> 자세히 보기</a>
									개인정보 수집 및 이용 동의
								</div>
								<div class='col-lg-9 col-md-7 col-sm-7 col-xs-12 text-right'>
									<div class='radiobox'>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_5' id='radio2_5_y' value="Y"/>
											<label for='radio2_5_y'>동의</label>
										</div>
										<div class='radio radio-inline'>
											<input type="radio" name='radio2_5' id='radio2_5_n' value="N" checked />
											<label for='radio2_5_n'>미동의</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				<ul class='pagination arrow-only'>
					<li class='prev'><a href='javascript: history.back(-1);'>이전</a></li>
					<li class='next'><a href='javascript: chk_submit3();'>다음</a></li>
				</ul>
			</form>
        </div>
<script type="text/javascript">

function chk_submit3(){
	var ff = document.frm3;
	<? if($PR_INFO["ext3"] == "Y") { ?>
	var notice_chk = true;
	$("#form_agree1 input[type='radio']").each(function(i){
		if($(this).is(":checked")) {
			if($(this).val() == "Y") {
				alert("알릴사항을 다시 확인해 주세요.");
				$(this).focus();
				notice_chk = false;
				return false;
			}
		}
	})
	
	if(!notice_chk) return;
	<? } ?>
	
	if ($("input:radio[name=radio2_1]:checked").val()=="N"){
		alert("사이트 이용약관 동의에 동의체크 해주셔야 합니다.");
		return;
	}
	if ($(":input:radio[name=radio2_2]:checked").val()=="N"){
		alert("상품 가입약관 동의에 동의체크 해주셔야 합니다.");
		return;
	}
	<? if($plan_view["chk_service"] == "A" || $plan_view["chk_service"] == "B") { //서비스 상품?>
	if ($(":input:radio[name=radio2_3]:checked").val()=="N"){
		alert("서비스 이용약관 동의에 동의체크 해주셔야 합니다.");
		return;
	}
	<? } ?>
	if ($(":input:radio[name=radio2_4]:checked").val()=="N"){
		alert("단체 보험규약 동의에 동의체크 해주셔야 합니다.");
		return;
	}
	if ($(":input:radio[name=radio2_5]:checked").val()=="N"){
		alert("개인정보 수집 및 이용동의에 동의체크 해주셔야 합니다.");
		return;
	}
	ff.action="./register_step_04.php";
	ff.method="post";
	ff.submit();
}
//-->
</script>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>

