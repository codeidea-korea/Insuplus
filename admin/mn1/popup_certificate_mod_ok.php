<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_util/dompdf/dompdf_config.inc.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$result = 0;
	if($mode == "alrimtalk") { //알림톡
		
		$result = "1";
		
		$SQL  = " SELECT j.o_name, j.chk_eng_passport ";
		$SQL .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service";
		$SQL .= " , o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
		$SQL .= " , o.purpose, o.t_amount, o.ins_amount, o.service_amount, o.purpose ";
		$SQL .= " FROM tbl_order_list_join j INNER JOIN tbl_order_list o ON j.orderno = o.orderno WHERE j.seq = '".$join_seq."' AND o.order_step = '2' ";
		$RS = $dbcon->query($SQL);
		
		$row = $dbcon->fetch_array($RS);
		
		$name = "";
		if($chk_lang == "K") { //영문명이 없는 경우 등록한다.
			$name = $o_name;
		} else if($chk_lang == "E") {
			$name = $o_name_en;
			
			if($row["chk_eng_passport"] != "Y") {
				$SQL_UP  = " UPDATE tbl_order_list_join SET chk_eng_passport ='Y', o_name_en = '".all_seed_enc($o_name_en)."' ";
				$SQL_UP .= " WHERE seq = '".$join_seq."' ";
				
				$result = $dbcon->query($SQL_UP);
			}
		}
		
		
		
		$param = array();
		$param["name"] = $name;
		$param["pr_name"] = $row["pr_name"]." ".$row["ins_name"]." ".$row["plan_name"];
		if($row["chk_service"] == "A" || $row["chk_service"]) {
			$param["pr_name"] .= " ".$Arr_txt_plus[$row["chk_service"]];
		}
		if($row["chk_p"] == "Y") { //단기
			$s_date = $row["s_date"]." ".$row["s_date_time"]."시";
			$e_date = $row["e_date"]." ".$row["e_date_time"]."시";
		} else if($row["chk_p"] == "N") { //장기
			$s_date = $row["s_date"];
			$e_date = $row["e_date"];
		}
		$param["period"] = $s_date." ~ ".$e_date;
		$param["purpose"] = $row["purpose"];
		$param["amount"] = $row["ins_amount"]+$row["service_amount"];
		$param["t_amount"] = $row["t_amount"];
		$param["orderno"] = $orderno;
		
		kakaoCertificateReissue($param, $mobile); //알림톡 전송
		
	} else if($mode == "email") { //이메일
		
		$SQL  = " SELECT j.o_name, j.chk_eng_passport ";
		$SQL .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service";
		$SQL .= " , o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
		$SQL .= " , o.purpose, o.t_amount, o.ins_amount, o.service_amount, o.purpose ";
		$SQL .= " ,ins_file_cd, service_file_cd ";
		$SQL .= " FROM tbl_order_list_join j INNER JOIN tbl_order_list o ON j.orderno = o.orderno WHERE j.seq = '".$join_seq."' AND o.order_step = '2' ";
		$RS = $dbcon->query($SQL);
		
		$row = $dbcon->fetch_array($RS);
		
		$param = array();
		$param["name"] = $o_name;
		
		$param["pr_name"] = $row["pr_name"]." ".$row["ins_name"]." ".$row["plan_name"];
		if($row["chk_service"] == "A" || $row["chk_service"]) {
			$param["pr_name"] .= " ".$Arr_txt_plus[$row["chk_service"]];
		}
		
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
		$email = $email1."@".$email2;
		
		// 가입보험내역 검색
		$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' )";
//		echo $SQL_V;
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
		$SQL_U = "update tbl_order_list_join set o_name_en='".all_seed_enc($o_name_en)."' where seq=".$join_seq." and orderno='".$orderno."' ";
		$RS = $dbcon -> query($SQL_U);
		}

		// 가입자 본인내역 검색
		$SQL = "select * from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' ";
		$RS = $dbcon -> query($SQL);
		$row = $dbcon -> fetch_array($RS);

		//계약자 정보 검색
		$SQL_CONT = "select * from tbl_order_list_join tolj 
		left join tbl_order_list tol on (tolj.orderno = tol.orderno)
		where tolj.orderno = '".$orderno."'
		order by tolj.seq ASC limit 1";
		$RS_CONT = $dbcon -> query($SQL_CONT);
		$row_cont_info = $dbcon -> fetch_array($RS_CONT);

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

		if ($chk_lang == "E") {
			$url = "./pdf_join_certification_table_en.php";
		} else {
			$url = "./pdf_join_certification_table.php";
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

		$USER_BIRTH = substr(all_seed_dec($row["o_isdn1"]),2,6)."-".substr(all_seed_dec($row["o_isdn2"]),0,1)."******";
		$USER_EMAIL = all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"]);

        if($row_group){ //단체가입 여부에 따른 계약자명 처리
            $CONTRACTOR_KR = all_seed_dec($row_group["o_name"]);
            $CONTRACTOR_EN = all_seed_dec($row_group["o_name_en"]);
        } else {
            $CONTRACTOR_KR = all_seed_dec($row_cont_info["o_name"]);
            $CONTRACTOR_EN = all_seed_dec($row_cont_info["o_name_en"]);
        }

		if($row_r["chk_p"] == "Y") {
			$INS_PERIOD = $row_r["s_date"]." ".$row_r["s_date_time"].":00 ~ ".$row_r["e_date"]." ".$row_r["e_date_time"].":00";
		} else if($row_r["chk_p"] == "N") {
			$INS_PERIOD = $row_r["s_date"]." ~ ".$row_r["e_date"];
		}

		//인슈플러스 인경우
		$CHK_SERVICE = "";
		$CHK_SERVICE_EN = "";
		if ($certType != "I") {
			if ($row_r["chk_service"] != "N" && $row_r["chk_service"] != "") {
				$sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '" . $row_r["plan_cd"] . "' ";
				$RS_PLAN_SEQ = $dbcon->query($sql);
				$ARR_PLAN_ROW_KR = array();
				$ARR_PLAN_ROW_EN = array();
				while ($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
					if ($row_s["k_amount"]) {
						$ARR_PLAN_ROW_KR[] = "<tr><th width='350px' style=\"background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" . $row_s["k_name"] . "</th>
								<td style=\"background-color: #ffffff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" . $row_s["k_amount"] . "</td></tr>";
						$ARR_PLAN_ROW_EN[] = "<tr><th width='350px' style=\"background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" . $row_s["e_name"] . "</th>
								<td style=\"background-color: #ffffff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" . $row_s["e_amount"] . "</td></tr>";
					}
				}

				if ($chk_lang != "E") {
					//국문 인슈플러스
					$CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0 0 12px 0;'>인슈플러스 서비스 가입 증명서</h5>";
					$CHK_TABLE_KR = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>
											<colgroup><col width='20%'><col width='60%'></colgroup>
											<thead><tr><th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">보장내역</th>
											<th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">보장한도</th>
											</tr></thead><tbody>";
					foreach ($ARR_PLAN_ROW_KR as $rows) {
						$CHK_TABLE_KR .= $rows;
					}
					$CHK_TABLE_KR .= "</tbody></table>";
					if ($ARR_PLAN_ROW_KR) {
						$CHK_SERVICE .= $CHK_TABLE_KR;
					}
					$CHK_SERVICE .=	"<h3 style='font-size:15px;font-weight: 400; font-family:NanumGothic;line-height: 1.5;padding:0'>상기 가입자가 인슈플러스 서비스에 가입되었음을 증명합니다.</h3>
											<ul style='text-align: left; padding:10px 20px;line-height: 1.5;color: #333; font-size: 13px;'>
												<li>이 상품의 구매는 인슈플러스 서비스 약관 및 해당 보험약관에 동의하였음을 의미합니다.</li>
												<li>이 상품계약의 자세한 사항은 인슈플러스 서비스 약관 및 해당 보험약관을 참조하시기 바랍니다.</li>
												<li>국가 혹은 지역별 코리아 어시스턴스 제휴병원은 상황에 따라 수시로 변경될 수 있습니다.</li>
												<li>이로 인해 병원비 대신지불 서비스가 제공되지 않는 경우 고객이 치료비 납입 후, 보상 청구 하여야 합니다.</li>
												<li>이 상품은 코리아 어시스턴스에서 서비스를 제공하며, 국내 보험사에서 보험담보를 보장합니다.</li>
												<li>코리아 어시스턴스는 하기 보험가입증명서의 보험가입금액에 의거하여 서비스를 제공 합니다.</li>
												<li>해외의료비를 보장하는 복수의 보험계약에 가입되어 있음이 확인된 경우 병원비 대신지불 서비스가 제한됩니다.</li>
											</ul>
											<table style='width:100%; text-align:right; padding:0 0 20px 0;'>
											<tr><td><img src='[DROOT]/html/images/korea_assistance_stamp.png' align='absmiddle'></td></tr></table>";
				} else {
					//영문 인슈플러스
					$CHK_SERVICE_EN = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0 0 12px 0;'>Certificate of INSUPLUS Service</h5>";
					$CHK_TABLE_EN = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>
												<colgroup><col width='40%'><col width='60%'></colgroup>
												<thead><tr><th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">Coverage</th>
												<th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">Coverage Limit</th></tr>
												</thead><tbody>";
					foreach ($ARR_PLAN_ROW_EN as $rows) {
						$CHK_TABLE_EN .= $rows;
					}
					$CHK_TABLE_EN .= "</tbody></table>";
					if ($ARR_PLAN_ROW_EN) {
						$CHK_SERVICE_EN .= $CHK_TABLE_EN;
					}
					$CHK_SERVICE_EN .= "<h3 style='font-size:15px;font-weight: 400; font-family: NanumGothic;line-height: 1.5;padding:0'>Above subscriber is certified INSUPLUS Service</h3>
												<p>
												By purchasing this item, you agree to the INSUPLUS Terms of Service and applicable insurance Terms of Conditions.<br/>
												Please refer to INSUPLUS Terms of Service and applicable insurance Terms and Conditions for details of this contract.
												</p>
											<table style='width:100%; text-align:right; padding:0 0 20px 0;'>
											<tr><td><img src='[DROOT]/html/images/korea_assistance_stamp.png' align='absmiddle'></td></tr></table>";
				}
			}
		}

		//보장내역
		$key = 0;
		$G_TABLE = "";
		if ($certType != "S") {
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
					$G_TABLE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0px 0px 0px;'>Certificate of [INS_NAME_EN]</h5>
									<h3 style='font-size:13px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:5px 0 5px 0;'>Above subscriber is certified to insured of <span style='color:#dc3347'>[INS_NAME_EN]</span><br/>
									Please refer to the KOREA ASSISTANCE provides services based on the following collateral and compensation limits.<br/><br/>
									Coverage: If mentioned, this coverage includes the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy</h3>
									<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
									<colgroup><col width='50%' /><col width='*' /></colgroup><thead>
										<tr><th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage</th>
											<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th></tr>
									</thead><tbody>";
					$G_TABLE .= $G_LIST . "</tbody></table>";
					$G_TABLE .= "<p style='font-size:12px;margin:8px 0 7px 0;color:#333;'>This insurance contract is a group insurance which KOREA ASSISTANCE is policy holder and pays premium.</p>					
									<h3 style='font-size:13px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>■ Notice</h3>
									<ul style='text-align: left; margin: 0px 0px 0px 20px;padding: 0;line-height: 1.75;color: #333; font-size: 11px;'>
										<li>Death Collateral does not apply to those under 15-years-old.</li>
										<li>Deductible : If not mentioned, refer to insurance policy wording.</li>
									</ul>
									<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/[SIGNIMG]\" align=\"absmiddle\"><p>";
				} else {
					$G_TABLE = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:12px 0 5px 0;'>상기 고객을 피보험자로 하여 아래와 같이 <span style='color:#dc3347'>[INS_NAME]</span>에 가입되었음을 확인 합니다.</h3>
									<p style='font-size:12px;margin:0 0 30px 0;color:#333;'>보험계약의 자세한 사항에 대하여는 <span style='color:#dc3347'>[INS_NAME]</span> 약관을 참조하시기 바랍니다.<br> 코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.</p>
									<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
									<colgroup><col width='50%' /><col width='*' /></colgroup><thead><tr>
									<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>담보내용</th>
									<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보험가입금액 / 보상한도액</th></tr></thead><tbody>";
					$G_TABLE .= $G_LIST . "</tbody></table>";
					$G_TABLE .= "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>이 보험계약은 보험계약자 및 보험료 납부자가 코리아 어시스턴스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</p>
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
											</ul>
											<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/[SIGNIMG]\" align=\"absmiddle\"></p>";
				}
			}
		}

		if ($chk_lang=="E"){		//가격단위
			$won = "won";
			$language = "영문";
			$name = $USER_ENAME;
		}else{
			$won = "원";
			$language = "국문";
			$name = $USER_NAME;
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
		$chHtml	= str_replace("[CHK_SERVICE]",$CHK_SERVICE,$chHtml);   //인슈서비스인 경우 노출
		$chHtml	= str_replace("[CHK_SERVICE_EN]",$CHK_SERVICE_EN,$chHtml);   //인슈서비스인 경우 노출
		$chHtml	= str_replace("[G_LIST]",$G_TABLE,$chHtml);							//보험담보내용
		$chHtml	= str_replace("[INS_NAME]",$row_ins["subject"],$chHtml);	
		$chHtml	= str_replace("[INS_NAME_EN]",$row_ins["ins_name_en"],$chHtml);	//보험담보내용
		$chHtml	= str_replace("[INS_USER_NUM]",$row["ins_user_num"],$chHtml); //단체가입 ID
		if($chk_lang=="E"){
            $chHtml	= str_replace("[SIGNIMG]",$row_ins["imgfile2"],$chHtml); //사인이미지
		} else {
            $chHtml	= str_replace("[SIGNIMG]",$row_ins["signimg"],$chHtml); //사인이미지
		}

		$chHtml	= str_replace("[DROOT]",$_SERVER["DOCUMENT_ROOT"],$chHtml);							//이미지를 위한 루트경로

        //dompdf 버전 문제로 인한 html 소스 공백 제거
        $html = preg_replace('/\r\n|\r|\n/','',$chHtml);
		//echo $html;

		$pdf_filename = "인슈플러스가입증명서_".$language."_".$orderno."_"."".$name.".pdf";			//파일명처리
		//html 코드를 pdf로 변환 (변경하면 안됨)
		
		$dompdf = new \DOMPDF();
		$webRoot = $_SERVER["DOCUMENT_ROOT"];
		$dompdf->set_paper( 'A4', 'portrait' );
		$dompdf->load_html($html);
		$dompdf->render();
		//$dompdf->stream($pdf_filename); //저장되는 파일 이름을 설정한다.
		$output = $dompdf->output();
		$file_url = $_SERVER["DOCUMENT_ROOT"]."/up_file/".$pdf_filename;
		file_put_contents($file_url, $output);
		$pdf_url = "http://".$_SERVER["HTTP_HOST"]."/up_file/".$pdf_filename;
		error_reporting($error_level);
		mailJoinSend2($param,$email,$pdf_url,$filename); //이메일은 자동으로 status 결과값이 나옵니다.
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