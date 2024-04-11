<?


function getInsuProductInfo($seq) { //상품정보 추출
	global $dbcon;
	//ext3 알릴사항 Y,N
	$TempSQL = " SELECT seq, subject, content, ext1, ext2, ext4, ext3, ext5, ext6, ext7, ext8, ext10, imgfile, is_notification_visible FROM tbl_board_product WHERE seq='".$seq."' ";
	
	$result = $dbcon -> query($TempSQL);
	$row = $dbcon->fetch_array($result);
	$arr_img_info = setFileName($row["imgfile"])[0];
	$row["imgfile"] = "/_data/board/product/".$arr_img_info[1];
	return $row;
}


function getGuaranteeOfPlan_cd($pr_cd='',$plan_cd='',$plan_seq='') {//플랜코드에 대한 보장내역 가져오기
	global $dbcon;
	
	if(!$plan_seq) { //플랜고유번호가 없으면 플랜코드에 플랜고유번호를 추출한다.
		
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
	}
	
	$TempSQL  = " SELECT p.g_amount, g.service_name, g.service_content ";
	$TempSQL .= " FROM tbl_board_plan_guarantee p INNER JOIN tbl_board_guarantee_opt g ON p.g_seq = g.idx ";
	$TempSQL .= " WHERE plan_cd = '".$plan_seq."' ";
	$TempSQL .= " ORDER BY idx ASC ";
	
	$RS = $dbcon->query($TempSQL);

	$data = array();
	while($ROW_G = $dbcon->fetch_array($RS)) {
		$data[] = $ROW_G;
	}
	return $data;
}

//보험비교 리스트
function getPlanListOfPr_cd($pr_cd, $param) {
	global $dbcon;
	
	$row_pr = getInsuProductInfo($pr_cd); //단기, 장기 여부 추출
	
	$where = "";
	$order_by = " ORDER BY field(chk_service,'C','D','E','N') ASC, common_amount ASC ";
	if($param["insurplus_check"] == "insuplus") {
		$where .= " AND chk_service in ('C') ";
	} else if($param["insurplus_check"] == "flying") {
		$where .= " AND chk_service in ('D', 'E') ";
	}
	
	if($param["sort1"]) {
		$order_by = " ORDER BY ".$param["sort1"];
	}
	
	if($param["sort2"]) {
		$where .= " AND ins_cd = '".$param["sort2"]."' ";
	}
	
	$TempSQL =  " SELECT * FROM tbl_board_plan WHERE pr_cd = ".$pr_cd." ";
	$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
	if($row_pr["ext1"] == "Y") {
		$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".SHORT_DATE."' ";
		$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".SHORT_DATE."' ";
	} else if($row_pr["ext1"] == "N") {
		$TempSQL .= " AND s_date <= '".LONG_DATE."' ";
		$TempSQL .= " AND e_date >= '".LONG_DATE."' ";
	}
	$TempSQL .= $where;
	$TempSQL .= $order_by;
	
	$RS = $dbcon->query($TempSQL);
	
	$data = array();
	while($ROW = $dbcon->fetch_array($RS)) {
		$data[] = $ROW;
	}
	return $data;

}

//검색한 보험 비교 (step1~5)
function getSearchPlanListOfPr_cd($pr_cd, $param) {
	global $dbcon;
	
	$where = "";
	$order_by = " ORDER BY field(chk_service,'C','D','E','N') ASC, common_amount ASC ";
	if($param["insurplus_check"] == "insuplus") {
		$where .= " AND chk_service = 'C' ";
	} else if($param["insurplus_check"] == "flying") {
		$where .= " AND chk_service in ('D', 'E') ";
	}
	
	if($param["sort2"]) {
		$where .= " AND ins_cd = '".$param["sort2"]."' ";
	}
	
	$TempSQL =  " SELECT seq, chk_service, ins_cd, plan_cd, content, service_txt, plan_isdn FROM tbl_board_plan WHERE pr_cd = ".$pr_cd." ";
	$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
	if($param["chk_p"] == "Y") {
		$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$param["s_date"]."' ";
		$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$param["s_date"]."' ";
	} else if($param["chk_p"] == "N") {
		$TempSQL .= " AND s_date <= '".$param["s_date"]."' ";
		$TempSQL .= " AND e_date >= '".$param["s_date"]."' ";
	}
	$TempSQL .= $where;
	$TempSQL .= $order_by;
	
	$RS = $dbcon->query($TempSQL);
	
	$data = array();
	$arr_amount = array();
	while($ROW = $dbcon->fetch_array($RS)) {
		
		$ins_amount = 0;
		$service_amount = 0;
		$tot_amount = 0;
		
		if($param["chk_p"] == "Y") { //기간을 구간으로 변경
			$period= fnShortTermSection($param["period"]);
		} else {
			$period = $param["period_month"];
		}
		
		//가입자
		$sql_ins_amount  = " SELECT period".$period." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$ROW["seq"]."' ";
		$sql_ins_amount .= " AND age = '".$param["age"]."' AND gender = '".$param["gender"]."' ";
		$rs_ins_amount = $dbcon->query($sql_ins_amount);
		$row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
		 
		$ins_amount = $row_ins_amount["period".$period]; //상품가격
		$ROW["reason_txt"] = ""; //가입불가 이유
		$ROW["joinChk"] = "Y"; //가입여부

		// if(!$ins_amount) {
		// 	$ROW["reason_txt"] = "가입자 ".$param["age"]."세 가입 불가";
		// 	$ROW["joinChk"] = "N";
		// }
		
		if($ROW["chk_service"] != "N" && $ROW["chk_service"] != "") {
			$SQL_SPD = "select service_amount_per_day from tbl_board_plan where seq = ". $ROW["seq"];
			$RS_SPD = $dbcon->query($SQL_SPD);
			$spd = $dbcon->fetch_row($RS_SPD);
			if(!is_null($spd[0])) {
				$service_amount = $spd[0] * $param["period_day"];
			} else {
				$sql_service_amount  = " SELECT mon".$param["period_month"]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$ROW["seq"]."' ";
				$sql_service_amount .= " AND stype = '".$ROW["chk_service"]."' ";
				$rs_service_amount = $dbcon->query($sql_service_amount);
				$row_service_amount = $dbcon->fetch_array($rs_service_amount);
				
				$service_amount = $row_service_amount["mon".$param["period_month"]]; //상품가격
			}
		}
		
		//동반자
		if($param["select_add_people"] > 0) {
			$user_ins_amount = 0;
			for($i=0; $i<$param["select_add_people"]; $i++) {
				$add_user_age = fn_ins_age(date("Y-m-d",strtotime($param["add_birth"][$i])));
				$add_gender = $param["add_gender"][$i]=="M" ? "남자":"여자";
				
				$sql_ins_amount  = " SELECT period".$period." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$ROW["seq"]."' ";
				$sql_ins_amount .= " AND age = '".$add_user_age."' AND gender = '".$add_gender."' ";
				$rs_ins_amount = $dbcon->query($sql_ins_amount);
				$row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
					
				$user_ins_amount += $row_ins_amount["period".$period]; //상품가격
				
				if(!$row_ins_amount["period".$period]) {
					$ROW["reason_txt"] = "동반인 (".($i+1).") ".$add_user_age."세 가입 불가";
					$ROW["joinChk"] = "N";
				}
			}			
		} else {
			$param["select_add_people"] = 0;
		}
		
		$tot_amount = $ins_amount+($service_amount*(1+$param["select_add_people"]))+$user_ins_amount;
		$ROW["amount_".$ROW["seq"]] = $tot_amount;
		
		$data[] = $ROW;

	}
	
	/*
	$TempSQL =  " SELECT seq, chk_service, ".$arr_amount[]." as amount FROM tbl_board_plan WHERE pr_cd = ".$pr_cd." ";
	$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
	//if($row_pr["ext1"] == "Y") {
	//$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$param["s_date"]."' ";
	//$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$param["s_date"]."' ";
	//} else if($row_pr["ext1"] == "N") {
	$TempSQL .= " AND s_date <= '".$param["s_date"]."' ";
	$TempSQL .= " AND e_date >= '".$param["s_date"]."' ";
	//}
	 * 
	 */
	
	
	
	return $data;

}

function getInsListOfPr_cd($pr_cd) { //상품보험사 서비스 검색
	global $dbcon;
	$TempSQL  = " SELECT ins_seq, service_gubun, (SELECT subject FROM tbl_board_ins_list WHERE seq = ins_seq) as ins_name ";
	$TempSQL .= " FROM tbl_board_product_service WHERE ";
	$TempSQL .= " pr_seq = ".$pr_cd." ORDER BY service_gubun ASC, idx DESC ";
	
	$RS = $dbcon -> query($TempSQL);
	
	$data = array();
	while($ROW = $dbcon->fetch_array($RS)) {
		$data[] = $ROW;
	}
	
	return $data;
}
//유의사항 보험 리스트
function getNoteInsOfPr_cd($pr_cd) {
	global $dbcon;
	$TempSQL  = " SELECT distinct(ins_seq) as ins_seq ";
	$TempSQL .= ", (SELECT subject FROM tbl_board_ins_list WHERE seq = ins_seq) as ins_name ";
	$TempSQL .= " FROM tbl_board_product_service WHERE PR_SEQ = '".$pr_cd."' ORDER BY service_gubun ASC, idx DESC ";
	
	$RS = $dbcon -> query($TempSQL);
	
	$data = array();
	while($ROW = $dbcon->fetch_array($RS)) {
		$data[] = $ROW;
	}
	
	return $data;
}

//유의사항 약관 상품에 해당하는 보험 플랜 정보 1건 가져오기  $chk_p Y:장기,N:단기
function getNoteInsAgreeOfPlan($pr_cd, $ins_cd, $chk_p) { 
	global $dbcon;
	
	$TempSQL = " SELECT agree_cd FROM tbl_board_plan WHERE pr_cd = '".$pr_cd."' AND ins_cd = '".$ins_cd."' AND plan_status='Y' AND secret='Y'  ";
	if($chk_p == "Y") {
		$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".SHORT_DATE."' ";
		$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".SHORT_DATE."' ";
	} else {
		$TempSQL .= " AND s_date <= '".LONG_DATE."' ";
		$TempSQL .= " AND e_date >= '".LONG_DATE."' ";
	}
	$TempSQL .= " limit 0, 1";
	
	$RS = $dbcon -> query($TempSQL);
	
	
	return $ROW = $dbcon->fetch_array($RS);	
}

//유의사항 약관 상품에 해당하는 인슈플래스 플랜 정보 1건 가져오기 
function getNoteServiceAgreeOfPlan($pr_cd, $chk_p) {
	global $dbcon;
	
	$TempSQL  = " SELECT service_cd FROM tbl_board_plan WHERE pr_cd = '".$pr_cd."' ";
	$TempSQL .= " AND chk_service in ('A','B') AND plan_status='Y' AND secret='Y'  ";
	if($chk_p == "Y") {
		$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".SHORT_DATE."' ";
		$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".SHORT_DATE."' ";
	} else {
		$TempSQL .= " AND s_date <= '".LONG_DATE."' ";
		$TempSQL .= " AND e_date >= '".LONG_DATE."' ";
	}
	$TempSQL .= " limit 0, 1";
	
	$RS = $dbcon -> query($TempSQL);
	
	
	return $ROW = $dbcon->fetch_array($RS);
}

//친구에게 쿠폰전송
function updSendCoupon($orderno,$mobile,$ori_mobile) {
	global $dbcon;
	$TempSQL  = " SELECT seq, use_yn, mobile FROM tbl_board_coupon_history WHERE receive_orderno = '".$orderno."' AND ori_mobile = '".all_seed_enc($ori_mobile)."' ";
	$RS = $dbcon -> query($TempSQL);
	$ROW = $dbcon->fetch_array($RS);
	
	if($ROW["seq"]) {
		if(all_seed_dec($ROW["mobile"]) != $ori_mobile) {
			$data["result"] = "-1";
			$data["msg"] = "이미 친구에게 전송했습니다.";	
		} else if($ROW["use_yn"] == "Y") {
			$data["result"] = "-1";
			$data["msg"] = "쿠폰을 사용하셨습니다.";
		} else {
			$TempInsSQL  = " UPDATE tbl_board_coupon_history SET ";
			$TempInsSQL .= " mobile = '".all_seed_enc($mobile)."' ";
			$TempInsSQL .= " WHERE receive_orderno = '".$orderno."' AND ori_mobile = '".all_seed_enc($ori_mobile)."' ";
			$dbcon -> query($TempInsSQL);
			
			$data["result"] = "1";
			$data["msg"] = "친구에게 전송했습니다.";
		}
	} else {
		$data["result"] = "-1";
		$data["msg"] = "일치하는 정보가 없습니다.";
	}
	
	return $data;	
}

function selCounponOfOrderno($orderno) {
	global $dbcon;
	$TempSQL  = " SELECT start_date, end_date, temp_discount ";
	$TempSQL .= " ,(select coupon_name from tbl_board_event where seq=h.event_seq) as coupon_name "; 
	$TempSQL .= " FROM tbl_board_coupon_history h WHERE receive_orderno = '".$orderno."' AND use_yn='N' ";
	$RS = $dbcon -> query($TempSQL);
	return $ROW = $dbcon->fetch_array($RS);
}


function getSearchGroupPlanListOfPr_cd($pr_cd, $ins_chk, $sort2, $param) {
	global $dbcon;
	
	$where = "";
	$order_by = " ORDER BY field(chk_service,'C','D','E','N') ASC, common_amount ASC ";
	if($ins_chk == "insuplus") {
		$where .= " AND chk_service = 'C' ";
	}
	
	if($sort2) {
		$where .= " AND ins_cd = '".$sort2."' ";
	}
	
	$data = array();
	$arr_plan = array();
	for($i = 0; $i < count($param["s_date"]); $i++){
		$TempSQL =  " SELECT seq, chk_service, ins_cd, plan_cd, content, service_txt, plan_isdn FROM tbl_board_plan WHERE pr_cd = ".$pr_cd." ";
		$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
		if($param["chk_p"] == "Y") {
			$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$param["s_date"][$i]."' ";
			$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$param["s_date"][$i]."' ";
		} else if($param["chk_p"] == "N") {
			$TempSQL .= " AND s_date <= '".$param["s_date"][$i]."' ";
			$TempSQL .= " AND e_date >= '".$param["s_date"][$i]."' ";
		}
		$TempSQL .= $where;
		$TempSQL .= $order_by;
		
		$RS = $dbcon->query($TempSQL);
		
		while($ROW = $dbcon->fetch_array($RS)) {
			
			$ins_amount = 0;
			$service_amount = 0;
			$tot_amount = 0;
			
			
			if($param["chk_p"] == "Y") { //기간을 구간으로 변경
				$period= fnShortTermSection($param["period"][$i]);
			} else {
				$period = $param["period_month"][$i];
			}
			
			//가입자
			$sql_ins_amount  = " SELECT period".$period." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$ROW["seq"]."' ";
			$sql_ins_amount .= " AND age = '".$param["age"][$i]."' AND gender = '".$param["gender"][$i]."' ";
			$rs_ins_amount = $dbcon->query($sql_ins_amount);
			$row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
			$ins_amount = $row_ins_amount["period".$period]; //상품가격
			$ROW["reason_txt"] = ""; //가입불가 이유
			$ROW["joinChk"] = "Y"; //가입여부

			if($ROW["chk_service"] != "N") {
				$sql_service_amount  = " SELECT mon".$param["period_month"][$i]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$ROW["seq"]."' ";
				$sql_service_amount .= " AND stype = '".$ROW["chk_service"]."' ";
				$rs_service_amount = $dbcon->query($sql_service_amount);
				$row_service_amount = $dbcon->fetch_array($rs_service_amount);

				$service_amount = $row_service_amount["mon".$param["period_month"][$i]]; //상품가격
			}
			
			$tot_amount = $ins_amount+$service_amount;
			$ROW["amount_".$ROW["seq"]] = $tot_amount;
			
			$arr_plan[$ROW["seq"]][] = $ROW;
		}
	}

	foreach($arr_plan as $plan){
		$temp_amount = 0;
		for($i=0;$i < count($plan);$i++){
			$temp_amount = $temp_amount + $plan[$i]["amount_".$plan[$i]["seq"]];
		}
		$plan[0]["amount_".$plan[0]["seq"]] = $temp_amount;
		$data[] = $plan[0];
	}

	return $data;
}

function getSearchGroupjoinListOfPr_cd($pr_cd, $ins_chk, $sort2, $param) {
	global $dbcon;
	
	$where = "";
	$order_by = " ORDER BY field(chk_service,'C','D','E','N') ASC, common_amount ASC ";
	if($ins_chk == "insuplus") {
		$where .= " AND chk_service = 'C' ";
	}
	
	if($sort2) {
		$where .= " AND ins_cd = '".$sort2."' ";
	}
	
	$data = array();
	$arr_plan = array();
	for($i = 0; $i < count($param["s_date"]); $i++){
		$TempSQL =  " SELECT seq, chk_service, ins_cd, plan_cd, content, service_txt, plan_isdn FROM tbl_board_plan WHERE pr_cd = ".$pr_cd." ";
		$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
		if($param["chk_p"] == "Y") {
			$TempSQL .= " AND date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".$param["s_date"][$i]."' ";
			$TempSQL .= " AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".$param["s_date"][$i]."' ";
		} else if($param["chk_p"] == "N") {
			$TempSQL .= " AND s_date <= '".$param["s_date"][$i]."' ";
			$TempSQL .= " AND e_date >= '".$param["s_date"][$i]."' ";
		}
		$TempSQL .= $where;
		$TempSQL .= $order_by;
		
		$RS = $dbcon->query($TempSQL);
		
		while($ROW = $dbcon->fetch_array($RS)) {
			
			$ins_amount = 0;
			$service_amount = 0;
			$tot_amount = 0;
			
			
			
			if($param["chk_p"] == "Y") { //기간을 구간으로 변경
				$period= fnShortTermSection($param["period"][$i]);
			} else {
				$period = $param["period_month"][$i];
			}
			
			
			//가입자
			$sql_ins_amount  = " SELECT period".$period." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$ROW["seq"]."' ";
			$sql_ins_amount .= " AND age = '".$param["age"][$i]."' AND gender = '".$param["gender"][$i]."' ";
			$rs_ins_amount = $dbcon->query($sql_ins_amount);
			$row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
			$ins_amount = $row_ins_amount["period".$period]; //상품가격
			$ROW["reason_txt"] = ""; //가입불가 이유
			$ROW["joinChk"] = "Y"; //가입여부

			if($ROW["chk_service"] != "N") {
				$sql_service_amount  = " SELECT mon".$param["period_month"][$i]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$ROW["seq"]."' ";
				$sql_service_amount .= " AND stype = '".$ROW["chk_service"]."' ";
				$rs_service_amount = $dbcon->query($sql_service_amount);
				$row_service_amount = $dbcon->fetch_array($rs_service_amount);

				$service_amount = $row_service_amount["mon".$param["period_month"][$i]]; //상품가격
			}
			
			$tot_amount = $ins_amount+$service_amount;
			$ROW["amount_".$ROW["seq"]] = $tot_amount;
			
			$arr_plan[$ROW["seq"]][] = $ROW;
		}
	}

	foreach($arr_plan as $plan){
		$temp_amount = 0;
		for($i=0;$i < count($plan);$i++){
			$temp_amount = $temp_amount + $plan[$i]["amount_".$plan[$i]["seq"]];
		}
		$plan[0]["amount_".$plan[0]["seq"]] = $temp_amount;
		$data[] = $plan[0];
	}

	return $data;
}

function getGroupAmountList($compare_seq, $param){
	global $dbcon;
	
	for($i = 0; $i < count($compare_seq); $i++){
		if($compare_seq[$i]){
			$order_by = " ORDER BY field(chk_service,'C','D','E','N') ASC, common_amount ASC ";
			$TempSQL =  " SELECT seq, chk_service, ins_cd, plan_cd, content, service_txt, plan_isdn FROM tbl_board_plan WHERE seq = ".$compare_seq[$i]." ";
			$TempSQL .= " AND plan_status='Y' AND secret='Y' ";
			
			$RS = $dbcon->query($TempSQL);
			$ROW = $dbcon->fetch_array($RS);
			for($k = 0; $k < count($param["age"]); $k++){
				$ins_amount = 0;
				$service_amount = 0;
				$tot_amount = 0;
				
				if($param["chk_p"] == "Y") { //기간을 구간으로 변경
					$period= fnShortTermSection($param["period"][$k]);
				} else {
					$period = $param["period_month"][$k];
				}
				
				//가입자
				$sql_ins_amount  = " SELECT period".$period." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$compare_seq[$i]."' ";
				$sql_ins_amount .= " AND age = '".$param["age"][$k]."' AND gender = '".$param["gender"][$k]."' ";
				$rs_ins_amount = $dbcon->query($sql_ins_amount);
				$row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
				
				$ins_amount = $row_ins_amount["period".$period]; //상품가격
				$ROW["reason_txt"] = ""; //가입불가 이유
				$ROW["joinChk"] = "Y"; //가입여부

				if($ROW["chk_service"] != "N") {
					$sql_service_amount  = " SELECT mon".$param["period_month"][$k]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$compare_seq[$i]."' ";
					$sql_service_amount .= " AND stype = '".$ROW["chk_service"]."' ";
					$rs_service_amount = $dbcon->query($sql_service_amount);
					$row_service_amount = $dbcon->fetch_array($rs_service_amount);
					
					$service_amount = $row_service_amount["mon".$param["period_month"][$k]]; //상품가격
				}
				$tot_amount = $ins_amount+$service_amount;
				$data[$k][$compare_seq[$i]] = $tot_amount;
			}
		}
	}
	return $data;
}

?>