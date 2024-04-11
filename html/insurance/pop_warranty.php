<?php
	include '../_include/_header.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	
	//플랜명은 2가지 타입 존재
	if($seq) { //플랜고유번호가 있는 경우
		$data1 = getFullPlanName($seq);

		//서비스 보장 내역 조회
		$sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '".$seq."' ";
		$RS_PLAN_SEQ = $dbcon->query($sql);
		$ARR_SERVICE_OPT = array();
		while($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
			$ARR_SERVICE_OPT[] = $row_s;
		}
		
		//보험 보장내역 조회
		$plan_name = $data1["pr_cd_name"]." ".$Arr_plan_cd[$data1["plan_cd"]];
		$data = getGuaranteeOfPlan_cd($pr_cd, $plan_cd, $seq);
	} else { //없는 경우
		$row_pr = getInsuProductInfo($pr_cd); //단기, 장기 여부 추출
		
		$TempSQL  = " SELECT seq FROM tbl_board_plan WHERE pr_cd='".$pr_cd."' AND plan_cd = '".$plan_cd."' ";
		$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
		if($row_pr["ext1"] == "Y") {
			$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".SHORT_DATE."' ";
			$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".SHORT_DATE."' ";
		} else if($row_pr["ext1"] == "N") {
			$TempSQL .= " AND s_date <= '".LONG_DATE."' ";
			$TempSQL .= " AND e_date >= '".LONG_DATE."' ";
		}
		$TempSQL .= " limit 0, 1 ";
		
		$RS_PLAN_SEQ = $dbcon->query($TempSQL);
		$ROW_PLAN_SEQ = $dbcon->fetch_array($RS_PLAN_SEQ);
		
		$plan_seq = $ROW_PLAN_SEQ["seq"];
		//서비스 보장 내역 조회
		$sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '".$plan_seq."' ";
		$RS_PLAN_SEQ = $dbcon->query($sql);
		$ARR_SERVICE_OPT = array();
		while($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
			$ARR_SERVICE_OPT[] = $row_s;
		}

		$plan_name = $Arr_plan_cd[$plan_cd];
		$data = getGuaranteeOfPlan_cd($pr_cd, $plan_cd,"");
	}


	
	
?>
	<h4 class='text-black m-b-1'>자세한 보장 내역을 확인해 보세요!</h4>
	<table width="100%" class="table table-bordered">
		<colgroup>
			<col class='col-sm-3 col-xs-4'></col>
			<col class='col-sm-9 col-xs-8'></col>
		</colgroup>
		<tbody>
			<tr>
				<td class='p-y-1 bg-white darken text-center'><strong class='text-black'>플랜명</strong></td>
				<td class='p-y-1'><h4 class='text-<?=$Arr_plan_cd_css[$plan_cd]?>'><?=$plan_name;?></h4></td>
			</tr>
		</tbody>
	</table>
	<h5 class='text-black m-t-2 m-b-1'>보장내역</h5>
	<table width="100%" class="table table-bordered">
		<colgroup>
			<col class='col-sm-2 col-xs-3'></col>
			<col class='col-sm-8 col-xs-6'></col>
			<col class='col-sm-2 col-xs-3'></col>
		</colgroup>
		<thead>
			<tr class='bg-blue text-center'>
				<th class='border-default b-y-1 bg-<?=$Arr_plan_cd_css[$plan_cd]?>'>보장내역</th>
				<th class='border-default b-y-1 bg-<?=$Arr_plan_cd_css[$plan_cd]?>'>보장내역 설명</th>
				<th class='border-default b-y-1 bg-<?=$Arr_plan_cd_css[$plan_cd]?>'>보장한도</th>
			</tr>
		</thead>
		<tbody>
			<? if($data) {
				foreach($data as $row) {
				?>
				<tr>
					<td class='text-center'><?=$row["service_name"]?></td>
					<td class='text-left'><?=$row["service_content"]?></td>
					<td class='text-center'><?=$row["g_amount"]?></td>
				</tr>
			<?	}
			}?>
		</tbody>
	</table>

	<h5 class='text-black m-t-2 m-b-1'>의료지원 서비스</h5>
	<table width="100%" class="table table-bordered">
		<colgroup>
			<col class='col-sm-5 col-xs-6'></col>
			<col class='col-sm-5 col-xs-4'></col>
		</colgroup>
		<thead>
			<tr class='bg-blue text-center'>
				<th class='border-default b-y-1 bg-<?=$Arr_plan_cd_css[$plan_cd]?>'>보장내역</th>
				<th class='border-default b-y-1 bg-<?=$Arr_plan_cd_css[$plan_cd]?>'>보장한도</th>
			</tr>
		</thead>
		<tbody>
			<? if($ARR_SERVICE_OPT) {
				foreach($ARR_SERVICE_OPT as $row) {
				?>
				<tr>
					<td class='text-center'><?=$row["k_name"]?></td>
					<td class='text-center'><?=$row["k_amount"]?></td>
				</tr>
			<?	}
			}?>
		</tbody>
	</table>
	<h5 class='text-black m-t-2 m-b-1'>해지환급금</h5>
	<p>가입 후 중도해지할 경우 미경과 상품가격를 해지환급금으로 지급해 드립니다.</p>

<?php
	include '../_include/_footer.html';
?>