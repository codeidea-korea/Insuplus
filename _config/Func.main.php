<?

function getMainBanner() { //메인 배너 이미지 추출
	global $dbcon;
	$TempSQL = " SELECT seq, pc_url, imgfile, imgfile2 FROM tbl_board_main_banner WHERE secret = 'N' ORDER BY exposure_order asc, seq DESC ";
	
	$result = $dbcon -> query($TempSQL);
	
	$data = array();
	while($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

function getRecommendPlan() { //추천플랜관리
	global $dbcon;
	$today = date("Y-m-d");
	$TempSQL  = " SELECT r.subject, r.ext1,  r.plan_seq_1,r.plan_seq_2, r.plan_seq_3, e.imgfile, e.pc_url ";
	$TempSQL .= " FROM tbl_board_main_recommend r LEFT JOIN tbl_board_main_event e ";
	$TempSQL .= " ON r.event_seq = e.seq ";
	$TempSQL .= " WHERE r.secret = 'N' ";
	$TempSQL .= " AND r.main_recommand_date_s <= '".$today."' AND r.main_recommand_date_e >= '".$today."' ";
	$TempSQL .= " order by r.exposure_order asc, r.seq desc ";
	
	$result = $dbcon -> query($TempSQL);
	
	$data = array();
	while($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

function getPlanInfo($seq) { //플랜 상세정보
	global $dbcon;

	$TempSQL   =  " SELECT seq, pr_cd, ins_cd, plan_cd, chk_service, common_amount, service_txt ,content, plan_isdn ";
	$TempSQL  .=  " ,plan_status, chk_period, secret ";
	$TempSQL  .=  " , ( CASE WHEN chk_period = 'Y' then if(date_format(concat(s_date,' ',s_date_time),'%Y-%m-%d %H') <= '".SHORT_DATE."' ";
	$TempSQL  .=  "   AND date_format(concat(e_date,' ',e_date_time),'%Y-%m-%d %H') >= '".SHORT_DATE."','Y','N') ";
	$TempSQL  .=  "   WHEN chk_period = 'N' then if(s_date <= '".LONG_DATE."' AND e_date >= '".LONG_DATE."','Y','N') END ) as period_sell_yn ";
	$TempSQL  .=  " , (SELECT imgfile FROM tbl_board_ins_list WHERE seq = p.ins_cd) ins_logo ";
	$TempSQL  .=  "	FROM tbl_board_plan p ";
	$TempSQL  .=  " WHERE seq = '".$seq."'  ";
	
	$result = $dbcon -> query($TempSQL);
	$row = $dbcon->fetch_array($result);
	$data = array();
	
	$row["sell_yn"] = "Y";
	if($row["plan_status"] == "N") {
		$row["sell_yn"] = "N";
	} else if($row["secret"] == "N") { 
		$row["sell_yn"] = "N";
	} else if($row["period_sell_yn"] == "N") { 
		$row["sell_yn"] = "N";
	}
		
	$data[] = $row;
	
	return $data;
}

function getNurseCounsel() { //간호사 상담
	global $dbcon;
	$TempSQL =  " SELECT seq, subject, nation, imgfile";
	$TempSQL .= " FROM tbl_board_counsel_case WHERE secret = 'N' ORDER BY seq DESC limit 0,4 ";
	
	$result = $dbcon -> query($TempSQL);
	
	$data = array();
	while($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

function getRewardService() { //보상서비스
	global $dbcon;
	$TempSQL =  " SELECT seq, subject, date_format(regdate,'%Y-%m-%d') as regdate ";
	$TempSQL .= " FROM tbl_board_compensation_case WHERE secret = 'N' ORDER BY seq DESC limit 0,5 ";
	
	$result = $dbcon -> query($TempSQL);
	
	$data = array();
	while($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

//문의사항
function getQnaService() {
	global $dbcon;
	$TempSQL =  " SELECT seq, subject, date_format(regdate,'%Y-%m-%d') as regdate ";
	$TempSQL .= " FROM tbl_board_qna WHERE secret = 'N' ORDER BY seq DESC limit 0,5 ";

	$result = $dbcon -> query($TempSQL);

	$data = array();
	while($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

function getJoinList() { //가입현황
	global $dbcon;
	$TempSQL =  " SELECT 
					j.o_name
					, j.join_amount
					, date_format(regdate,'%Y-%m-%d') as regdate
					, o.ins_period
					, o.chk_p
					, o.pr_name
					, o.plan_name
					, o.chk_service
					, o.purpose
					, o.chk_service
				FROM tbl_order_list_join j 
				INNER JOIN tbl_order_list o ON j.orderno = o.orderno
				WHERE j.join_status = 'Y' 
				AND o.order_step = '2' 
				ORDER BY j.seq DESC limit 0,20";

	$result = $dbcon -> query($TempSQL);

	$data = array();
	while($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

function getNotiList() {
	global $dbcon;
	$TempSQL =  "SELECT seq, subject, view_cnt, date_format(regdate,'%Y-%m-%d') as regdate 
				from tbl_board_notice
				WHERE secret = 'N'
				ORDER BY seq DESC limit 0,5";

	$result = $dbcon->query($TempSQL);

	$data = array();
	while ($row = $dbcon->fetch_array($result)) {
		$data[] = $row;
	}
	return $data;
}

?>