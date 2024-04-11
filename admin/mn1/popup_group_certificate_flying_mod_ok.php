<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_util/dompdf/dompdf_config.inc.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$result = 0;
	$arr_join_seq = array();
	$SQL_J = "SELECT seq, orderno FROM tbl_order_list_join where group_join_id = '".$group_join_id."' ";
	$RS_J = $dbcon->query($SQL_J);
	while($row = $dbcon->fetch_array($RS_J)) {
		$arr_join_seq[] = $row;
	}

	foreach($arr_join_seq as $join_row){
		$join_seq = $join_row["seq"];
		$orderno = $join_row["orderno"];

		$SQL  = " SELECT j.o_name, j.chk_eng_passport ";
		$SQL .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service";
		$SQL .= " , o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
		$SQL .= " , o.purpose, o.t_amount, o.ins_amount, o.service_amount, o.purpose ";
		$SQL .= " ,ins_file_cd, service_file_cd ";
		$SQL .= " FROM tbl_order_list_join j INNER JOIN tbl_order_list o ON j.orderno = o.orderno WHERE j.seq = '".$join_seq."' AND o.order_step = '2' ";
		$RS = $dbcon->query($SQL);
		
		$row = $dbcon->fetch_array($RS);
		
		$param = array();
		
		$param["pr_name"] = $row["pr_name"]." ".$row["ins_name"]." ".$row["plan_name"]." ".$Arr_txt_plus[$row["chk_service"]];
		// if($row["chk_service"] == "A" || $row["chk_service"]) {
		// 	$param["pr_name"] .= " ".$Arr_txt_plus[$row["chk_service"]];
		// }
		
		if($row["chk_p"] == "Y") { //단기
			$s_date = $row["s_date"]." ".$row["s_date_time"]."시";
			$e_date = $row["e_date"]." ".$row["e_date_time"]."시";
		} else if($row["chk_p"] == "N") { //장기
			$s_date = $row["s_date"];
			$e_date = $row["e_date"];
		}
		
		$param["period"] = $s_date." ~ ".$e_date;
		$param["purpose"] = $row["purpose"];
		$param["chk_service"] = $row["chk_service"];
		$param["domain"] = getDomain();
		$param["amount"] = $row["ins_amount"]+$row["service_amount"];
		$param["t_amount"] = $row["t_amount"];
		$param["ins_seq"] = $row["ins_file_cd"]; //약관파일 고유번호
		$param["service_seq"] = $row["service_file_cd"]; //약관파일 고유번호
		$param["service_name"] = $Arr_txt_plus[$row["chk_service"]]; //서비스명

		// 가입보험내역 검색
		$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' )";
		$RS_V = $dbcon -> query($SQL_V);
		if (!$RS_V){
			echo "<script>alert('해당 보험내역이 없습니다.');</script>";
			exit;
		}
		$row_r = $dbcon -> fetch_array($RS_V);
		
        //단체가입 여부 조회
        $SQL_G = "select * from tbl_order_group_join_list where group_join_id = '".$row_r["group_join_id"]."'";
        $RS_G = $dbcon -> query($SQL_G);
		if ($RS_G){
			$row_group = $dbcon -> fetch_array($RS_G);
		}

		//영문플랜명 상품에서 출력
		$SQL_PRODUCT =  " SELECT ext4, ext9 FROM tbl_board_product WHERE seq = '".$row_r["pr_cd"]."' ";
		$RS_PRODUCT = $dbcon -> query($SQL_PRODUCT);
		$row_product = $dbcon -> fetch_array($RS_PRODUCT);

		// 영문이름 업데이트 시 처리
		if ($o_name_en && $chk_lang == "E"){
			$SQL_U = "update tbl_order_list_join set o_name_en='".all_seed_enc($o_name_en)."', chk_eng_passport='Y' where seq=".$join_seq." and orderno='".$orderno."' ";
			$RS = $dbcon -> query($SQL_U);
		}

		// 가입자 본인내역 검색
		$SQL = "select * from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' ";
		$RS = $dbcon -> query($SQL);
		$row = $dbcon -> fetch_array($RS);

		//단체가입 상태 검색
		$SQL_CMN = "select * from safety_training.fd_cmn_cd where grp_cd ='CC09'";
		$RS_CMN = $dbcon -> query($SQL_CMN);
		$ARR_CMN = array();
		while($row_cmn = $dbcon->fetch_array($RS_CMN)) {
			$ARR_CMN[$row_cmn["cd"]] = $row_cmn["cd_nm"];
		}

		// 플랜검색
		$SQL_PLAN = "select * from tbl_board_plan where seq=".$row_r["plan_cd"]."";
		$RS_PLAN = $dbcon -> query($SQL_PLAN);
		$row_plan = $dbcon -> fetch_array($RS_PLAN);

		// 플랜관련
		$SQL_G = "select * from tbl_board_plan_guarantee where plan_cd=".$row_r["plan_cd"]."";
		$RS_G = $dbcon -> query($SQL_G);

		//보장내역
		if($row_product["ext4"]){
			$SQL_GO = "select * from tbl_board_guarantee_opt where list_seq=".$row_product["ext4"]."";
			$RS_GO = $dbcon -> query($SQL_GO);
			$cnt=0;
			$arrayGORow = array();
			while($row_g_opt = $dbcon -> fetch_array($RS_GO)){
				$arrayGORow[$cnt] = $row_g_opt["service_name_en"];
				$cnt++;
			}
		}

		//보험사정보
		$SQL_INS  = " SELECT subject, ins_name_en, imgfile2 ";
		$SQL_INS .= ", (SELECT file_realname FROM tbl_file WHERE bc_id = 'ins_list' AND seq = '".$row_plan["ins_cd"]."' ORDER BY idx desc LIMIT 0,1) as signimg ";
		$SQL_INS .= " FROM tbl_board_ins_list WHERE seq = '".$row_plan["ins_cd"]."' "; //사인이미지 수정
		$RS_INS = $dbcon -> query($SQL_INS);
		$row_ins = $dbcon -> fetch_array($RS_INS);
		$arr_img_info = setFileName($row_ins["imgfile2"])[0];
	    $row_ins["imgfile2"] = $arr_img_info[1];

		if ($chk_lang=="E"){
		$url = "./pdf_join_certification_flying_table_en.php";
		}else{
		$url = "./pdf_join_certification_flying_table.php";
		}

		$data = array(
		'orderno' => $orderno		// 주문번호
		, 'seq' => $join_seq			// 가입순번
		);

		$options = array(
			'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$chHtml = @file_get_contents($url, false, $context);
        $error_level = error_reporting();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

		//========================================
		//회원정보 처리
		//========================================
		$USER_NAME	 = all_seed_dec($row["o_name"]);
		$USER_ENAME	 = all_seed_dec($row["o_name_en"]);
		
		$param["name"] = $USER_NAME;

		$USER_BIRTH = substr(all_seed_dec($row["o_isdn1"]),2,6)."-".substr(all_seed_dec($row["o_isdn2"]),0,1)."******";
		$USER_EMAIL = all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"]);

		if($row_group){ //단체가입 여부에 따른 계약자명 처리
            $CONTRACTOR_KR = all_seed_dec($row_group["o_name"]);
            $CONTRACTOR_EN = all_seed_dec($row_group["o_name_en"]);
        } else {
            $CONTRACTOR_KR = all_seed_dec($row_r["o_name"]);
            $CONTRACTOR_EN = all_seed_dec($row_r["o_name_en"]);
        }

		if($row_r["chk_p"] == "Y") {
			$INS_PERIOD = $row_r["s_date"]." ".$row_r["s_date_time"].":00 ~ ".$row_r["e_date"]." ".$row_r["e_date_time"].":00";
		} else if($row_r["chk_p"] == "N") {
			$INS_PERIOD = $row_r["s_date"]." ~ ".$row_r["e_date"];
		}

		if ($chk_lang == "E") {		//가격단위
			$won = "won";
			$language = "영문";
			$name = $USER_ENAME;
		} else {
			$won = "원";
			$language = "국문";
			$name = $USER_NAME;
		}

		if ($chk_lang != "E") {   //계약자명 처리
			$CONTRACTORROW = "<tr>
									<th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>계약자명</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . all_seed_dec($row_group["o_name"]) . "</td>
									<th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>증권번호</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . $row_plan["stock_isdn"] . "</td>
								</tr>";
		} else {
			if ($row_r["o_name_en"]) {
				$CONTRACTORROW = "<tr>
									<th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Name of Policy Holder</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . all_seed_dec($row_group["o_name_en"]) . "</td>
									<th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Policy Number</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . $row_plan["stock_isdn"] . "</td>
								</tr>";
			}
		}

		//서비스 내역
		$CHK_SERVICE = "";
		$service_img = "";
		if($certType != "I") {
			if ($row_r["chk_service"] != "N" && $row_r["chk_service"] != "") {
				$sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '" . $row_r["plan_cd"] . "' ";
				$RS_PLAN_SEQ = $dbcon->query($sql);
				$ARR_PLAN_ROW = array();
				while ($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
					if ($row_s["k_amount"]) {
						if ($chk_lang == "E") {
							$ARR_PLAN_ROW[] = "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["e_name"] . "</th>
							<td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["e_amount"] . "</td></tr>";
						} else {
							$ARR_PLAN_ROW[] = "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["k_name"] . "</th>
							<td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["k_amount"] . "</td></tr>";
						}
					}
				}
				if ($chk_lang == "E") {
					//영문 인슈플러스
					$CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 20px 0;'>Certification of FlyingDoctors Service</h5>";
					$CHK_TABLE = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
						<colgroup><col width='50%' /><col width='*' /></colgroup>
						<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>
						<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>
						</tr></thead><tbody>";
					$service_img_lang="<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>
							The above customer confirms that they have joined to a 24-hour overseas emergency transportation service.<br />
							Please refer to the service guide for details.<br />
							Flying Doctors is an overseas emergency transportation service brand on Bizinsight, Bizinsight provides services <br />
							In accordance with the above collateral and compensation limits</p>
						<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/html/images/[FLYIMG]\" align=\"absmiddle\"></p>";
				} else {
					//국문 인슈플러스
					$CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:10px 0 5px 0;'>플라잉닥터스 서비스 가입 증명서</h5>";
					$CHK_TABLE = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
						<colgroup><col width='50%' /><col width='*' /></colgroup>
						<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장내역</th>
						<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장한도</th>
						</tr></thead><tbody>";
					$service_img_lang = "<p style=\"text-align:right; padding:5px 20px;\"><img src=\"[DROOT]/html/images/[FLYIMG]\" align=\"absmiddle\"></p>";
				}

				if ($ARR_PLAN_ROW) {
					foreach ($ARR_PLAN_ROW as $rows) {
						$CHK_TABLE .= $rows;
					}
					$CHK_TABLE .= "</tbody></table>";
					$CHK_SERVICE .= $CHK_TABLE;
				}

				if ($chk_fly_type == "A") {
					$FLYIMG = "korea_assistance_stamp.png";
					$chk_kor_service = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 플라잉닥터스 서비스에 가입되었음을 확인합니다.</h3>
											<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.
											자세한 사항에 대하여는 서비스 안내서를 참조하시기 바랍니다.</p>";
				} else {
					$chk_kor_service = "";
					if ($chk_lang == "E") {
						$FLYIMG = "biz_sign_en.png";
					} else {
						$FLYIMG = "biz_sign.png";
					}
				}
				$service_img = $service_img_lang;
			}
		}

		//보장내역
		$key = 0;
		$G_TABLE = "";
		$ins_sign_img = "";
		$ins_noti = "";
		if ($certType !=  "S") {
			if ($arrayGORow) {
				while ($row_g = $dbcon->fetch_array($RS_G)) {
					if ($chk_lang == "E") {
						if ($row_g["g_amount_certificate"]) {
							$G_LIST .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $arrayGORow[$key] . "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_g["g_amount_certificate"] . "</td></tr>";
						}
					} else {
						if ($row_g["g_amount"]) {
							$G_LIST .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_g["g_name"] . "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_g["g_amount"] . "</td></tr>";
						}
					}
					$key++;
				}
			}
			
			if ($G_LIST) {
				if ($chk_lang == "E") {
					$G_TABLE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;'>Certification of " . $row_ins["ins_name_en"] . "</h5>
									<p style='margin:8px 0 5px 0;font-size:12px;font-weight:400;color:#333;'>
									Above subscriber is certified to insured of <span style='font-weight:400;color:red;'>" . $row_ins["ins_name_en"] . ".</span><br/>
									Please refer to the KOREA ASSISTANCE provides services based on the following collateral and compensation limits.<br/><br/>
									Coverage: If mentioned, this coverage includes<br/>
									the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy<br/>
									</p>
									<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
									<colgroup><col width='50%' /><col width='*' /></colgroup>
									<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>
									<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>
									</tr></thead><tbody>";
					$ins_sign_img = "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'></p>
									<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>This insurance contract is a group insurance which KOREA ASSISTANCE is policy holder and pays premium.</p>
									<div style=\"font-size:12px;color:#333;\">■ Notice</div>
									<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Death Collateral does not apply to those under 15-years-old.</div>
									<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Deductible : If not mentioned, refer to insurance policy wording.</div>
									<p style=\"text-align:right; padding:10px 20px;\"><img src='[DROOT]/_data/board/ins_list/[SIGNIMG]' align='absmiddle'></p>";
				} else {
					$G_TABLE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 32px;border-radius: 8px;margin:20px 0 20px 0;'>보험사 국문명 가입증명서</h5>
									<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
									<colgroup><col width='50%' /><col width='*' /></colgroup>
									<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장내역</th>
									<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장한도</th>
									</tr></thead><tbody>";
					$ins_sign_img = "<p style=\"text-align:right; padding:5px 20px;\"><img src='[DROOT]/_data/board/ins_list/[SIGNIMG]' align='absmiddle'></p>";
					if ($chk_fly_type == "A") {
						$ins_noti = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>
											상기 고객을 피보험자로 하여 아래와 같이 <span style=\"color: red;\">" . $row_ins["subject"] . "</span>에 가입되었음을 확인합니다.</h3>
											<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>
											보험계약의 자세한 사항에 대하여는 <span style=\"color: red;\">" . $row_ins["subject"] . "</span> 약관을 참조하시기 바랍니다. <br/>
											코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.<br/>
											이 보험계약은 보험계약자 및 상품가격 납부자가 코리아어시스턴스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</P>
											
											
											<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>유의사항</h3>
											<ul style='text-align: left; margin: 0px 0 0 20px;padding: 0;line-height: 1.75;color: #333; font-size: 13px;'>
												<li>만 15세 미만은 상해사망 / 질병사망 담보가 적용되지 않습니다. (상법 732조)</li>
												<li>해외의료실비
													<dl style='margin:0;padding:0 0 0 15px;'>
														<dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 해외의료기관에서 치료를 받은 경우 가입한도 내 실제 부담한 의료비를 보상합니다.</dd>
													</dl>
												</li>
												<li>국내의료실비(급여/비급여)
													<ol style='margin:0;padding:0 0 0 15px;'>
														<li>급여
															<dl style='margin:0;padding:0 0 0 15px;'>
																<dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />
																	&nbsp&nbsp<b>(입원 : 의료급여 중 본인이 부담한 의료비의 80%보상 / 통원 및 처방조제 : 병원규모별 1~2만원과 보장대상 의료비의 20%중 큰 금액을 차감한 금액 보상)</b></dd>
															</dl>
														</li>
														<li>비급여 (3대 비급여 제외)
															<dl style='margin:0;padding:0 0 0 15px;'>
																<dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 비급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />
																	&nbsp&nbsp<b>(입원 : 비의료급여 중 본인이 부담한 의료비의 70%보상 / 통원 및 처방조제 : 병원규모별 3만원과 보장대상 의료비의 30%중 큰 금액을 차감한 금액 보상)</b></dd>
															</dl>
														</li>
													</ol>
												</li>
												<li>휴대품 파손/도난
													<dl style='margin:0;padding:0 0 0 15px;'>
														<dd>- 여행중 휴대한 물품 파손, 도난인 경우 사용기간을 감안한 감가상각한금액, 파손의 경우 수리비용 보상<br />&nbsp&nbsp(휴대물품당 1개/1쌍당 20만원, 자기부담금 1만원. 현금, 신용카드, 여권은 보상되지 않음)</dd>
													</dl>
												</li>
											</ul>";
					} else {
						$ins_noti = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 <span style=\"color: red;\">24시간 해외 긴급이후송 서비스</span>에 가입되었음을 확인합니다.</h3>
										<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>자세한 사항에 대하여는 <span style=\"color: red;\">서비스 안내서</span>를 참조하시기 바랍니다.
										플라잉닥터스는 비즈인사이트의 해외긴급이후송 서비스 브랜드이며,<br /> 비즈인사이트는 위의 담보내용과 보상한도액에 의거하여 서비스를 제공해 드립니다.</p>";
					}
				}
				$G_TABLE .= $G_LIST . "</tbody></table>";
			}
		}

		if ($chk_fly_type == "A") {
			$chk_fly_type_name = "코리아어시스턴스";
			$FOOTER = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='background:#29354c; padding:20px;'>
														<tr valign='middle'><td style='text-align:left;'><img src='[DROOT]/html/images/footer-logo1.png' align='absmiddle'></td>
														<td style='text-align:right;color:#fff;'>https://koreaassistance.co.kr<br>
														<strong style='font-size:14px;'>Tel: +82 2 360 2545</strong><br>B1, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea</td></tr></table>";
		} else {
			$chk_fly_type_name = "플라잉닥터스";
			$FOOTER = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='background:#29354c; padding:10px 20px;'>
														<tr valign='middle'><td style='text-align:left;'><img src='[DROOT]/html/images/footer-logo2.png' align='absmiddle'></td>
														<td style='text-align:right;color:#fff;'>https://flyingdoctors.co.kr <br>
														<strong style='font-size:14px;'>Tel: +82 2 360 2525</strong><br>F8, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea</td></tr></table>";
		}
		
		$chHtml	= str_replace("[USER_NAME]",$USER_NAME,$chHtml);
		$chHtml	= str_replace("[USER_ENAME]",$USER_ENAME,$chHtml);
		$chHtml	= str_replace("[CONTRACTOR_KR]",$CONTRACTOR_KR,$chHtml);
		$chHtml	= str_replace("[CONTRACTOR_EN]",$CONTRACTOR_EN,$chHtml);
		$chHtml	= str_replace("[USER_BIRTH]",$USER_BIRTH,$chHtml);
		$chHtml	= str_replace("[USER_PHONE]",all_seed_dec($row["o_phone"]),$chHtml);
		$chHtml	= str_replace("[USER_EMAIL]",$USER_EMAIL,$chHtml);
		$chHtml	= str_replace("[PLAN_NAME]",$row_r["plan_name"],$chHtml);
		$chHtml	= str_replace("[PURPOSE]",$row_r["purpose"],$chHtml);
		$chHtml	= str_replace("[ENG_PLAN_NAME]",$row_product["ext9"],$chHtml); //영문 플랜명(국문하고 다름)
		$chHtml	= str_replace("[SERVICE_TYPE]",$Arr_txt_plus[$row_r["chk_service"]],$chHtml);
		$chHtml	= str_replace("[ORDERNO]",$row_r["orderno"],$chHtml);
		$chHtml	= str_replace("[USER_TYPE]",$row_r["pr_name"],$chHtml);
		$chHtml	= str_replace("[PR_AMOUNT]",number_format($row["t_amount"]).$won,$chHtml);
		$chHtml	= str_replace("[USER_JNAME]",all_seed_dec($row["o_name"]),$chHtml);		//피보험자
		$chHtml	= str_replace("[USER_JENAME]",all_seed_dec($row["o_name_en"]),$chHtml);		//피보험자
		$chHtml	= str_replace("[STOCK_NO]",$row_plan["stock_isdn"],$chHtml);		//증권번호
		$chHtml	= str_replace("[INS_PERIOD]",$INS_PERIOD,$chHtml);			// 보험기간
		$chHtml	= str_replace("[NATION_NAME]",$row_r["join_nation_name"],$chHtml);		//해외체류국가
		$chHtml	= str_replace("[CHK_SERVICE]", $CHK_SERVICE, $chHtml);   //서비스내역
		$chHtml	= str_replace("[G_LIST]", $G_TABLE, $chHtml);			 //보험담보내용
		$chHtml	= str_replace("[INS_NAME]",$row_ins["subject"],$chHtml);	
		$chHtml	= str_replace("[CONTRACTOR_ROW]",$CONTRACTORROW,$chHtml);	//계약자명
		$chHtml	= str_replace("[INS_NAME_EN]",$row_ins["ins_name_en"],$chHtml);	//보험사 영문명
		$chHtml	= str_replace("[SERVICEIMG]", $service_img, $chHtml);	//공급업자 서명
		$chHtml	= str_replace("[INSSIGNIMG]", $ins_sign_img, $chHtml); //사인이미지	
		if($chk_lang=="E"){
			$chHtml	= str_replace("[SIGNIMG]",$row_ins["imgfile2"],$chHtml); //사인이미지
		} else {
			$chHtml	= str_replace("[SIGNIMG]",$row_ins["signimg"],$chHtml); //사인이미지
		}	
		$chHtml	= str_replace("[FLYIMG]",$FLYIMG,$chHtml);	//공급업자 서명
		$chHtml	= str_replace("[CHK_KOR_SERVICE]", $chk_kor_service, $chHtml);
		$chHtml	= str_replace("[INS_NOTI]", $ins_noti, $chHtml);
		$chHtml	= str_replace("[CHK_FLY_TYPE_NAME]", $chk_fly_type_name, $chHtml);	//상단 이름 구분
		$chHtml	= str_replace("[FOOTER]",$FOOTER,$chHtml);	//하단 문구
		$chHtml	= str_replace("[DROOT]",$_SERVER["DOCUMENT_ROOT"],$chHtml);							//이미지를 위한 루트경로

        //dompdf 버전 문제로 인한 html 소스 공백 제거
        $html = preg_replace('/\r\n|\r|\n/','',$chHtml);
		//echo $html;exit;

		$pdf_filename = "플라잉닥터스_가입증명서_".$language."_".$orderno."_"."".$name.".pdf";			//파일명처리
		//html 코드를 pdf로 변환 (변경하면 안됨)
		
		$dompdf = new \DOMPDF();
		$webRoot = $_SERVER["DOCUMENT_ROOT"];
		$dompdf->set_paper( 'A4', 'portrait' );
		//$dompdf->set_option('enable_html5_parser', TRUE);
		$dompdf->load_html($html);
		$dompdf->render();
		//$dompdf->stream($pdf_filename); //저장되는 파일 이름을 설정한다.
		$output = $dompdf->output();
		$file_url = $_SERVER["DOCUMENT_ROOT"]."/up_file/".$pdf_filename;
		file_put_contents($file_url, $output);
		$pdf_url = "http://".$_SERVER["HTTP_HOST"]."/up_file/".$pdf_filename;
		error_reporting($error_level);
		if($chk_fly_type=="A") {
			mailJoinSend2($param,$USER_EMAIL,$pdf_url,$filename); //이메일은 자동으로 status 결과값이 나옵니다.
		} else {
			mailBizJoinSend($param,$USER_EMAIL,$pdf_url,$filename); //플라잉닥터스 가입증명서 구분값이 비즈인사이트인 경우
		}
		$result = "1";
		
		$send_log = array();
		$send_log["manager_yn"] = "Y";
		$send_log["gubun"] = "E";
		$send_log["memo"] = "가입증명서 재발행";
		$send_log["orderno"] = $orderno;
		insSendLog($send_log); //func.alrimTalk.php 메소드 존재
	}
?>
{"result":"<?=$result;?>"}