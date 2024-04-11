<?
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	
	// 상품 검색
	$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보

	$today = "";
	if($PR_INFO["ext1"] == "Y") { //단기
		$today = SHORT_DATE;
	} else if($PR_INFO["ext1"] == "N") { //장기
		$addTimestamp = strtotime("+1 days");
		$today = LONG_DATE;
	}
	
	// 상품 보험사 서비스 검색	
	$arr_pr_service = getInsListOfPr_cd($PR_INFO["seq"]);
	
	//플랜 GROUP
	$SQL_PLAN_GROUP = " SELECT distinct(plan_cd) as plan_cd FROM tbl_board_plan WHERE  pr_cd='".$PR_SEQ."' AND plan_status='Y' AND secret='Y' ";
	if($PR_INFO["ext1"] == "Y") {
		$SQL_PLAN_GROUP .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$today."' ";
		$SQL_PLAN_GROUP .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$today."' ";
	} else if($PR_INFO["ext1"] == "N") {
		$SQL_PLAN_GROUP .= " AND s_date <= '".$today."' ";
		$SQL_PLAN_GROUP .= " AND e_date >= '".$today."' ";
	}
	$SQL_PLAN_GROUP .= " ORDER BY field(plan_cd,2,3,4,1,5) ASC ";
	$rs_plan_group = $dbcon -> query($SQL_PLAN_GROUP);
	$arr_plan_group = array();
	while($row_plan_group = $dbcon -> fetch_array($rs_plan_group)){
		$arr_plan_group[] = $row_plan_group;
	}
	
	if($PR_INFO["ext4"]) {
		// 상품에 대한 보장내역 검색
		$SQL_GUARANTEE_OP = "SELECT idx, service_name FROM tbl_board_guarantee_opt WHERE list_seq = ".$PR_INFO["ext4"]." AND chk_service='Y' ORDER BY idx ASC ";
		$RS_GUARANTEE_OP = $dbcon -> query($SQL_GUARANTEE_OP);
		$arr_guarantee_op = array();
		while($ROW_GUARANTEE_OP = $dbcon -> fetch_array($RS_GUARANTEE_OP)) {
			$arr_guarantee_op[] = $ROW_GUARANTEE_OP;
		}
	}

	//서비스 보장내역 조회
	$SQL_PLS = "select tbp.seq as seq, tbp.chk_service as chk_service from tbl_board_plan tbp
				where tbp.pr_cd = ".$PR_SEQ." and tbp.plan_status = 'Y' and tbp.secret = 'Y'
				and tbp.chk_service in (select tbps.service_gubun from tbl_board_product tbpr
				left join tbl_board_product_service tbps on (tbpr.seq = tbps.pr_seq)
				where tbpr.seq=".$PR_SEQ.")";
	$RS_PLS = $dbcon -> query($SQL_PLS);
	$arr_plan_service = array();
	while($row_pls = $dbcon -> fetch_array($RS_PLS)) {
		$arr_plan_service[] = $row_pls;
	}
?>
<style>
	.font-wb{word-break: normal !important;}
	.plan_subtitle{margin:0 !important;}
</style>
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
		<div class="container sub-content info-wrap">
			<div class="text-center plan">
				<div class="plan_title half-highlight">나에게 맞는 플랜 찾는 방법!</div>
				<div class="plan_subtitle">
					<div class="num">1</div>
					<div class="tcopy">의료비 보장 한도를 확인하세요</div>
				</div>
				<div class="plan_subtitle">
					<div class="num">2</div>
					<div class="tcopy">지원되는 여행/의료 서비스를 확인하세요</div>
					<!-- <div class="timg">( <img src="../images/logo04.png" class="logo01">/<img src="../images/logo_flyingdoctors.svg" class="logo02"> )</div> -->
				</div>
			</div>
			<ul class="nav nav-tabs nav-justified">
				<li class="active"><a href="javascript:;">상품안내</a></li>
				<li class=""><a href="insuplus_note.php?PR_SEQ=<?=$PR_SEQ?>">유의사항</a></li>
				<li class=""><a href="insuplus_case.php?PR_SEQ=<?=$PR_SEQ?>">고객후기</a></li>
			</ul>
			<div class="tab-pane">
				<section id="p_info" class="tab-content fade in active">					
					<div class="info-box" style="display: none;">
							화면을 좌우로 움직여 주세요.<br>
							<small>인슈플러스 상품정보를<br>확인할 수 있습니다. </small>
					</div>
					<script>
						function fnSelectPlan(pr_cd, plan_seq) {
							location.href = "./search_insur.php?PR_SEQ="+pr_cd+"&compare_seq="+plan_seq;
						}
					</script>
					<p class="text-right">
						<strong class="text-danger">산출 상품가 기준 <?=$PR_INFO["ext8"]?></strong>
					</p>
					<!-- 버튼 테이블 시작 -->
					<div class="row">
						<div class="col-xs-12 scroll-wrap">
							<table class="table table-bordered">
								<colgroup>
									<col width="30%"/>
									<? 	$col_count = count($arr_plan_group); 
										for($i = 0; $i < $col_count; $i++){?>
										<col width="<?= 60/$col_count?>%"/>
									<? } ?>
								</colgroup>

								<!--20230528 헤더수정 start-->
								<thead>
									<tr class="text-center">
										<th class="border-default border-b-6">플랜</th>
										<?if($arr_plan_group) {
											foreach($arr_plan_group as $plan_group){ ?>
											<th class="border-<?=$Arr_plan_cd_css[$plan_group["plan_cd"]]?> border-b-6">
												<a class="btn btn-block btn-<?=$Arr_plan_cd_css[$plan_group["plan_cd"]]?>" data-toggle="pop-modal" data-size="lg" data-href="./pop_warranty.php?pr_cd=<?=$PR_INFO["seq"]?>&plan_cd=<?=$plan_group["plan_cd"];?>" data-title="보장내역" target="modal_iframe" style="padding:5px 0px"><?=$Arr_plan_cd[$plan_group["plan_cd"]]?>
												<img src="/html/images/help.png" style="width: 20px;"/>
												</a>
											</th>
											<?}
										}?>
									</tr>
								</thead>
								<tbody class="text-center">
									<? 
									if($arr_pr_service) {
										foreach($arr_pr_service as $row_pr_service) {
											$cnt = 0;
											if($arr_plan_group) {
												foreach($arr_plan_group as $plan_group){ 
													$SQL_PLAN_INFO =  " SELECT seq,common_amount FROM tbl_board_plan ";
													$SQL_PLAN_INFO .= " WHERE pr_cd='".$PR_SEQ."' AND plan_status='Y' AND secret='Y' ";
													$SQL_PLAN_INFO .= " AND ins_cd = '".$row_pr_service["ins_seq"]."' AND chk_service = '".$row_pr_service["service_gubun"]."' ";
													$SQL_PLAN_INFO .= " AND plan_cd = '".$plan_group["plan_cd"]."'  ";
													if($PR_INFO["ext1"] == "Y") {
														$SQL_PLAN_INFO .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$today."' ";
														$SQL_PLAN_INFO .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$today."' ";
													} else if($PR_INFO["ext1"] == "N") {
														$SQL_PLAN_INFO .= " AND s_date <= '".$today."' ";
														$SQL_PLAN_INFO .= " AND e_date >= '".$today."' ";
													}
													$RS_PLAN_INFO = $dbcon->query($SQL_PLAN_INFO);
													$ARR_PLAN_LIST[$cnt] = $dbcon->fetch_array($RS_PLAN_INFO);
												?>
													<? $cnt++;
												}
											}
										?>
										<tr>
											<td class="text-black">상품가격</td>
											<? 
											if($arr_plan_group) {
												foreach($arr_plan_group as $plan_group){ 
													$SQL_PLAN_INFO =  " SELECT seq,common_amount FROM tbl_board_plan ";
													$SQL_PLAN_INFO .= " WHERE pr_cd='".$PR_SEQ."' AND plan_status='Y' AND secret='Y' ";
													$SQL_PLAN_INFO .= " AND ins_cd = '".$row_pr_service["ins_seq"]."' AND chk_service = '".$row_pr_service["service_gubun"]."' ";
													$SQL_PLAN_INFO .= " AND plan_cd = '".$plan_group["plan_cd"]."'  ";
													if($PR_INFO["ext1"] == "Y") {
														$SQL_PLAN_INFO .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$today."' ";
														$SQL_PLAN_INFO .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$today."' ";
													} else if($PR_INFO["ext1"] == "N") {
														$SQL_PLAN_INFO .= " AND s_date <= '".$today."' ";
														$SQL_PLAN_INFO .= " AND e_date >= '".$today."' ";
													}
													$RS_PLAN_INFO = $dbcon->query($SQL_PLAN_INFO);
													$ROW_PLAN_INFO = $dbcon->fetch_array($RS_PLAN_INFO);
												?>
												<td>
												<? if($ROW_PLAN_INFO["common_amount"] > 0) { ?>
													<?=number_format($ROW_PLAN_INFO["common_amount"])?>원
													<div class="m-t-05"><a class="btn btn-md btn-default" href="javascript: alert('리뉴얼 작업중입니다.');">가입</a></div>
												<? } ?>
												</td>
											<? 
												}
											} ?>
										</tr>
									<?	
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- 버튼 테이블 종료 -->
					<!-- 보험 테이블 시작 -->
					<div class="row">
						<div class="col-xs-12 scroll-wrap">
							<table class="table table-bordered">
								<colgroup>
									<col width="30%"/>
									<? 	$col_count = count($arr_plan_group); 
										for($i = 0; $i < $col_count; $i++){?>
										<col width="<?= 60/$col_count?>%"/>
									<? } ?>
								</colgroup>

								<!--20230528 헤더수정 start-->
								<thead>
									<tr class="text-center">
										<th class="border-default border-b-6" colspan="<?= $col_count + 1?>">보험 주요 보장 한도</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<? 
									if($arr_guarantee_op) {
										foreach($arr_guarantee_op as $row_guarantee_op) {?>
										<tr>
											<td><?=$row_guarantee_op["service_name"]?></td>
											<? foreach($arr_plan_group as $plan_group){ 
													//판매중인 플랜그룹 1개 노출한다. 플랜그룹은 보장내역 및 금액이 동일
													$SQL_PLAN_SEQ =  " SELECT seq FROM tbl_board_plan ";
													$SQL_PLAN_SEQ .= " WHERE pr_cd='".$PR_SEQ."' AND plan_status='Y' AND secret='Y' ";
													$SQL_PLAN_SEQ .= " AND plan_cd = '".$plan_group["plan_cd"]."'  ";
													if($PR_INFO["ext1"] == "Y") {
														$SQL_PLAN_SEQ .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$today."' ";
														$SQL_PLAN_SEQ .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') > '".$today."' ";
													} else if($PR_INFO["ext1"] == "N") {
														$SQL_PLAN_SEQ .= " AND s_date <= '".$today."' ";
														$SQL_PLAN_SEQ .= " AND e_date > '".$today."' ";
													}
													$SQL_PLAN_SEQ .= " limit 0, 1 ";   
													$RS_PLAN_SEQ = $dbcon->query($SQL_PLAN_SEQ);
													$ROW_PLAN_SEQ = $dbcon->fetch_array($RS_PLAN_SEQ);
													
													$SQL_PLAN_GUARANTEE = " SELECT g_amount FROM tbl_board_plan_guarantee WHERE plan_cd = '".$ROW_PLAN_SEQ["seq"]."' ";
													$SQL_PLAN_GUARANTEE .= " AND  g_seq = '".$row_guarantee_op["idx"]."' ";
													$RS_PLAN_GUARANTEE = $dbcon->query($SQL_PLAN_GUARANTEE);
													$ROW_PLAN_GUARANTEE = $dbcon->fetch_array($RS_PLAN_GUARANTEE);
												?> 
													<td><?=$ROW_PLAN_GUARANTEE["g_amount"] ? $ROW_PLAN_GUARANTEE["g_amount"]:"-";?></td>
												<? } ?>
										</tr>
									<?	}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- 보험 테이블 종료 -->
					<!-- 서비스 테이블 시작 -->
                    <div class="row">
						<div class="col-xs-12 scroll-wrap">
							<? 
							$sql = "";
							for($i = 0; $i < count($arr_plan_service); $i++) {
								if($arr_plan_service[$i]["chk_service"] != 'N' && $arr_plan_service[$i]["chk_service"] != ''){
									$sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '".$arr_plan_service[$i]["seq"]."' ";
									break;
								}
							}
							
							$RS_PLAN_SEQ = $dbcon->query($sql);
							$ARR_PLAN_title = array();

							while($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
								$ARR_PLAN_title[] = $row_s;
							} 

							$ARR_PLAN_INS = array();
							for($i = 0; $i < count($arr_plan_service); $i++) { 
								if($arr_plan_service[$i]["chk_service"] != 'N' && $arr_plan_service[$i]["chk_service"] != '' ){
									$sql = "select k_amount from tbl_board_plan_insuplus where 1=1 and plan_seq = '".$arr_plan_service[$i]["seq"]."' ";
									$RS_PLAN_SEQ = $dbcon->query($sql);

									while($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
										$ARR_PLAN_INS[$i][] = $row_s;
									}
								}
							}
							if($arr_plan_service) {
							?>
							<table class="table table-bordered">
								<colgroup>
									<col width="30%"/>
									<? 	for($i = 0; $i < $col_count; $i++){?>
										<col width="<?= 60/$col_count?>%"/>
									<? } ?>
								</colgroup>
								<thead>
									<tr class="text-center">
										<th class="border-default border-b-6" colspan="<?= count($arr_plan_service) + 1?>">해외 여행/의료지원서비스 </th>
									</tr>
								</thead>
								<tbody class="text-center">
									<? for($i = 0; $i < count($ARR_PLAN_title); $i++) { ?>
									<tr>
										<td class="text-black"><?=$ARR_PLAN_title[$i]["k_name"]?></td>
											<? if(count($ARR_PLAN_INS) > 0) {
													foreach($ARR_PLAN_INS as $PLAN_INS) { ?>
														<td class="text-black"><?=$PLAN_INS[$i]["k_amount"]?></td>
												<? }
											} ?>
									</tr>
									<? } ?>
								</tbody>
							</table>
							<? } ?>
						</div>
					</div>                    
					<!-- 서비스 테이블 종료 -->
				</section>
			</div>
        </div>
		<!--20230519 상품가조회하기 버튼 추가-->
		<div class="button_joinbox">
			<button class="button_join" onclick="move_page('/html/insurance/search_insur.php?PR_SEQ=<?=$PR_SEQ?>');">
				<img src="../images/icon_search_bar.svg" />
				<span class="tshadow">간편 가격조회하기</span> <!--20230528 버튼명변경-->
			</button>
		</div>
		<script>
			function move_page(url) {
				location.href = url;
			}
			$(function() {
				closedPage();
        const param = location.search.match(/alliance_code=[^&]*/);
				// location.href = `./renewal_step00.php?${param[0]}`;
				location.href = `/html/main/index.php?${param[0]}`;
			})
		</script>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>