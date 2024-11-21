<?php
include "../_include/_header.html";
include "../_include/_top.html";
include "../_include/_sidebar.html";
?>
<?
// 전화번호와 주민등록번호에 대한 부분처리
$hp = $_POST["hp"];
$rnumber = $_POST["rnumber"];

if ($_POST["hp"]) {
	$_SESSION["enc_hp"] = all_seed_enc($hp);
}
if ($_POST["rnumber"]) {
	$_SESSION["enc_rnumber"] = all_seed_enc($rnumber);
}

if (!$_SESSION["enc_hp"] || !$_SESSION["enc_rnumber"]) {
	echo "<script>alert('잘못된 경로로 입장하셨습니다.');</script>";
	exit;
}

// 가입자에서 동반인이 아닌 본인에 대한 정보만 검색
$SQL_J_list  = "select * ";
$SQL_J_list .= " , (SELECT plan_cd FROM tbl_board_plan WHERE seq = o.plan_cd ) as plan ";
$SQL_J_list .= " , (SELECT guarantee1_ins_seq FROM tbl_board_plan WHERE seq = o.plan_cd ) as guarantee1_ins_seq ";
$SQL_J_list .= " from tbl_order_list o where orderno in (select orderno from tbl_order_list_join where o_phone = '" . $_SESSION["enc_hp"] . "' and o_isdn2='" . $_SESSION["enc_rnumber"] . "' and chk_join='N') order by seq desc ";
//echo $SQL_J_list;
$RS_J_list = $dbcon->query($SQL_J_list);
$join_data = array(); //가입데이터

while ($row = $dbcon->fetch_array($RS_J_list)) {
	$join_data[] = $row;
}


if (count($join_data) <= 0) {
	echo "<script>alert('일치하는 정보가 없습니다.');";
	echo "location.href='./join_confirm.php';</script>";
	exit;
}

$name = all_seed_dec($join_data[0]["o_name"]);

// 가입자 쿠폰리스트 검색
$SQL_Q = " 
	SELECT 
		A.*,
		B.coupon_name,
		B.duplicate_status_yn,
		B.subscription_start_day,
		B.subscription_end_day,
		B.event_category_master_seq,
		B.min_companion,
		B.max_companion,
		B.insurance_discount_applied,
		B.service_fee_discount_applied,
		B.insurance_max_discount_amount,
		B.service_fee_max_discount_amount,
		C.partnership_name 
	FROM 
";
$SQL_Q .= " 
tbl_board_coupon_history A 
inner join tbl_board_event B on A.event_seq=B.seq 
inner join tbl_board_partner C on B.event_partnership_code = C.partnership_code 
where A.mobile='" . $_SESSION["enc_hp"] . "' and A.use_yn='N' AND A.end_date>='" . date("Y-m-d") . "' ";
$SQL_Q .= " ORDER BY A.seq DESC ";
$RS_Q = $dbcon->query($SQL_Q);

$coupon_data = array(); //쿠폰데이터
while ($row = $dbcon->fetch_array($RS_Q)) {
	$coupon_data[] = $row;
}

?>
<div class="breadcrumb-image">
	<div class="container">
		<h2>가입확인</h2>
		<h4>가입내역 및 쿠폰내역을 확인해 주세요</h4>
	</div>
</div>
<div class="breadcrumb-wrap">
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="../main/index.php">InsuPlus HOME</a></li>
			<li>가입확인</li>
		</ol>
	</div>
</div>
<div class="container">
	<div class="sub-content info-wrap">
		<ul class="nav nav-tabs nav-justified">
			<li class="active"><a data-toggle="tab" href="#tab_insur">가입내역</a></li>
			<li class=""><a data-toggle="tab" href="#tab_coupon">쿠폰내역</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="tab_insur">
				<table width="100%" class="table table-bordered border-b-2 border-navy table-break table-form join_confirm_result">
					<colgroup>
        				<col width="10%"> <!--20231103 상품명 추가-->
						<col width="20%"> 
						<col width="15%"> 
						<col width="12%">
						<col width="*">
						<col width="10%">
						<col width="15%">

					</colgroup>
					<thead>
						<tr>
							<th class="bg-navy border-navy">상품명</th> 
							<th class="bg-navy border-navy">보험사</th>
							<th class="bg-navy border-navy">플랜명</th>
							<th class="bg-navy border-navy">결제일</th>
							<th class="bg-navy border-navy">가입기간</th>
							<th class="bg-navy border-navy">결제상태</th>
							<th class="bg-navy border-navy">결제금액</th>

						</tr>
					</thead>
					<tbody class="text-center">
						<?
						if (count($join_data) > 0) {
							foreach ($join_data as $row_J) { ?>
								<tr onClick="fnView('<?= $row_J["orderno"] ?>','<?= all_seed_enc($hp) ?>','<?= all_seed_enc($rnumber) ?>')" style="cursor:pointer;">
									<td data-title="상품명"><?= $row_J["pr_name"] ?></a></td>
									<td data-title="보험사"><?=$row_J["ins_cd"] > 0 ? print_ins($row_J["ins_cd"]) : print_ins($row_J["guarantee1_ins_seq"]);?><!-- <img src="../images/logos/mg.svg" align="absmiddle" /> --></td>
									<td data-title="플랜명" class="td-all"><strong class="text-<?= $Arr_plan_cd_css[$row_J["plan"]] ?>"><?= $row_J["plan_name"] ?></strong></td>
									<td data-title="가입일" class="td-all"><?= substr($row_J["writedate"], 0, 10) ?></td>
									<td data-title="가입기간" class="td-all"><?= $row_J["s_date"] ?> <? if ($row_J["chk_p"] == "Y") { ?><?= $row_J["s_date_time"] ?>:00<? } ?> ~ <?= $row_J["e_date"] ?><? if ($row_J["chk_p"] == "Y") { ?> <?= $row_J["e_date_time"] ?>:00<? } ?> (<?= $row_J["ins_period"] ?><?= $arr_chk_p_gubun[$row_J["chk_p"]] ?>)</td>
									<td data-title="결제상태">
										<? if ($row_J["order_step"] == "N" || $row_J["order_step"] == "P") { ?>
											<span class="text-danger"><?= $arr_ord_step[$row_J["order_step"]] ?></span>
										<? } else { ?>
											<?= $arr_ord_step[$row_J["order_step"]] ?>
										<? } ?>
									</td>
									<td data-title="결제금액" class="text-right p-r-2 icon_detail"><?= number_format($row_J["t_amount"]) ?>원</td>
								</tr>
						<? }
						} ?>
					</tbody>
				</table>
				<ul class="icons list-unstyled line-height-8 m-t-2">
					<li><i class="ti ti-check"></i>개시일 전에는 결제취소가 가능합니다.</li>
					<li><i class="ti ti-check"></i>상세내역에서 가입증명서(국문,영문) 재발급 받을 수 있습니다.</li>
				</ul>
			</div>
			<div class="tab-pane fade" id="tab_coupon">
				<table width="100%" class="table table-bordered border-b-2 border-navy table-break table-form table-all" summary="">
					<thead>
						<tr>
							<th class="bg-navy border-navy">쿠폰명</th>
							<th class="bg-navy border-navy">사용기간</th>
							<th class="bg-navy border-navy">할인</th>
							<th class="bg-navy border-navy">최대할인금액</th>
							<th class="bg-navy border-navy">쿠폰조건</th>
							<th class="bg-navy border-navy">친구에게 쿠폰전송</th>
						</tr>
					</thead>
					<tbody class="text-center">
						<?
						if (count($coupon_data) > 0) {
							foreach ($coupon_data as $row_Q) { 
								// 카테고리 조건이 없는 경우 : ‘전체상품’
								// 카테고리 조건 있는 경우 : ‘제휴사명’
								// 동반가입 조건 있는 경우 : ‘1~5명 가입시 사용 가능’
								// 위 인원수는 ‘동반인 조건 +1명’으로 표기 (가입자 포함)
								// 가입기간 조건 있는 경우 : ‘1~90일 가입시 사용 가능’
								// 중복가능한 경우 : ‘타 쿠폰과 중복사용 가능’
								$coupon_conditions = "";
								$isDup = $row_Q["duplicate_status_yn"] == "Y" ? true : false;
								$categorySeq = $row_Q["event_category_master_seq"];
								$isCompanion = (isset($row_Q["min_companion"]) && isset($row_Q["max_companion"])) ? true : false;
								$isPeriod = (isset($row_Q["subscription_start_day"]) && isset($row_Q["subscription_end_day"])) ? true : false;
								$isDup = $row_Q["duplicate_status_yn"] == "Y" ? true : false;
								$insurance_discount_applied = $row_Q["insurance_discount_applied"];
								$service_fee_discount_applied = $row_Q["service_fee_discount_applied"];
								$insurance_max_discount_amount = $row_Q["insurance_max_discount_amount"];
								$service_fee_max_discount_amount = $row_Q["service_fee_max_discount_amount"];
								$max_dixcount = 0;
								if($row_Q["temp_discount"] && $insurance_discount_applied === "N" && $service_fee_discount_applied === "N"){
									$max_dixcount = 30000;
								} else if($service_fee_discount_applied === "F"){
									$max_dixcount = $row_Q["service_fee_max_discount_amount"];
								} else {
									$max_dixcount = ($insurance_max_discount_amount+$service_fee_max_discount_amount)*10000;
								}
								
								if ($categorySeq) {
									$coupon_conditions .= $row_Q["partnership_name"];
								} else {
									$coupon_conditions .= "전체상품";
								}
								if ($isCompanion) {
									$coupon_conditions .= "<br>" . ($row_Q["min_companion"] + 1) ."~".($row_Q["max_companion"] + 1) . "명 가입시 사용 가능";
								}
								if ($isPeriod) {
									$coupon_conditions .= "<br>" . $row_Q["subscription_start_day"] . "~" . $row_Q["subscription_end_day"] . "일 가입시 사용 가능";
								}
								if ($isDup) {
									$coupon_conditions .= "<br>타 쿠폰과 중복사용 가능";
								}
						
						?>
								<tr>
									<td data-title="쿠폰명"><?= $row_Q["coupon_name"] ?></td>
									<td data-title="사용기간"><?= $row_Q["start_date"] ?> ~ <?= $row_Q["end_date"] ?></td>
									<td data-title="할인"><?= number_format($row_Q["temp_discount"]) ?><?= (strlen($row_Q["temp_discount"]) > 3) ? "원" : "%"?></td>
									<td data-title="최대할인금액"><?= number_format($max_dixcount) ?>원</td>
									<td data-title="쿠폰조건"><span style="padding: 5px;text-align: left;"><?= $coupon_conditions?></span></td>
									<td class="p-y-05 p-x-1">
										<div class="input-group">
											<input type="tel" name="fr_hp<?= $row_Q["seq"] ?>" id="fr_hp<?= $row_Q["seq"] ?>" placeholder="친구 휴대폰번호를 입력해 주세요." class="form-control numberonly" maxlength="12" size="12" />
											<input type="hidden" name="fr_name<?= $row_Q["seq"] ?>" id="fr_name<?= $row_Q["seq"] ?>" value="<?= $name ?>" />
											<span class="input-group-btn"><button class="btn btn-default" onclick="send_cp(<?= $row_Q["seq"] ?>);">쿠폰전송</button></span>
										</div>
									</td>
								</tr>
							<? }
						} else { ?>
							<tr>
								<td colspan="4" class="empty text-center">쿠폰내역이 없습니다.</td>
							</tr>
						<? } ?>
					</tbody>
				</table>
				<ul class="icons list-unstyled line-height-8 m-t-2">
					<li><i class="ti ti-check"></i>할인 적용 시 쿠폰의 최대 금액을 초과하여 할인 받을 수 없습니다. 단, 보험료는 최대 3만원 이상 할인 받으실 수 없습니다.</li>
					<li><i class="ti ti-check"></i>친구에게 가지고 있는 쿠폰을 전송할 수 있습니다.</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<form name="frm_cp_send" method="post">
	<input type="hidden" name="s_idx" value="">
	<input type="hidden" name="s_pnum" value="">
	<input type="hidden" name="s_name" value="">
</form>
<div style="display:none;"><iframe src="" width="0" height="0" frameborder="0" id="ifr_act" name="ifr_act"></iframe></div>
<script type="text/javascript">
	<!--
	function fnView(orderno, chk1, chk2) {
		location.href = "./join_confirm_view.php?orderno=" + orderno + "&chk1=" + chk1 + "&chk2=" + chk2;
	}

	function send_cp(idx) {
		var t_idx = "#fr_hp" + idx;
		var name_idx = "#fr_name" + idx;
		var tt = $(t_idx).val();
		var name = $(name_idx).val();

		if (tt == "") {
			alert("보내시려는 연락처를 입력해주세요");
			return;

		}

		if (confirm("입력된 휴대폰번호로 쿠폰을 전송합니다")) {
			var ff = document.frm_cp_send;
			ff.s_idx.value = idx;
			ff.s_pnum.value = tt;
			ff.s_name.value = name;
			ff.action = "join_confirm_result_coupon_send.php";
			ff.target = "ifr_act";
			ff.submit();
		}
	}
	//
	-->
</script>
<?php
include "../_include/_tail.html";
include "../_include/_footer.html";
?>