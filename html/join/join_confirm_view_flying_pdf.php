<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
//include_once $_SERVER["DOCUMENT_ROOT"]."/_util/dompdf/dompdf_config.inc.php";
require_once("./dompdf/dompdf_config.inc.php");

		// 가입가입내역 검색
		$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' )";
		$RS_V = $dbcon -> query($SQL_V);
		if (!$RS_V){
			echo "<script>alert('해당 가입내역이 없습니다.');</script>";
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

		$USER_BIRTH = substr(all_seed_dec($row["o_isdn1"]),2,6)."-".substr(all_seed_dec($row["o_isdn2"]),0,1)."******";
		$USER_EMAIL = all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"]);

        if($row_group){ //단테가입 여부에 따른 계약자명 처리
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

		//서비스 내역
		$CHK_SERVICE = "";
		$CHK_SERVICE_EN = "";
		if($row_r["chk_service"] != "N" && $row_r["chk_service"] != "" && $chk_type == "A") {
			$sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '".$row_r["plan_cd"]."' ";
			$RS_PLAN_SEQ = $dbcon->query($sql);
			$ARR_PLAN_ROW = array();
			while($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
				if($row_s["k_amount"]) {
					if($chk_lang=="E"){
						$ARR_PLAN_ROW[] = "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_s["e_name"]."</th>
						<td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_s["e_amount"]."</td></tr>";
					} else {
						$ARR_PLAN_ROW[] = "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_s["k_name"]."</th>
						<td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_s["k_amount"]."</td></tr>";
					}
				}
				
			}

			if($chk_lang=="E"){
				//영문 인슈플러스
				$CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 20px 0;'>Certification of FlyingDoctors Service</h5>";
					$CHK_TABLE = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
					<colgroup><col width='50%' /><col width='*' /></colgroup>
					<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>
					<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>
					</tr></thead><tbody>";
			} else {
				//국문 인슈플러스
				$CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 20px 0;'>플라잉닥터스 서비스 가입 증명서</h5>";
				$CHK_TABLE = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
					<colgroup><col width='50%' /><col width='*' /></colgroup>
					<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장내역</th>
					<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장한도</th>
					</tr></thead><tbody>";
			}
			
			if($ARR_PLAN_ROW) {
				foreach($ARR_PLAN_ROW as $rows) {
					$CHK_TABLE .= $rows;
				}
				$CHK_TABLE .= "</tbody></table>";
				$CHK_SERVICE .= $CHK_TABLE;
			}
		}

		$key = 0;
		$G_TABLE = "";
		if($arrayGORow){
			while($row_g = $dbcon -> fetch_array($RS_G)){
				if ($chk_lang=="E"){
					if($row_g["g_amount_certificate"]){
						$G_LIST .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$arrayGORow[$key]."</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_g["g_amount_certificate"]."</td></tr>";
					}
				}else{
					if($row_g["g_amount"]){
						$G_LIST .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_g["g_name"]."</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>".$row_g["g_amount"]."</td></tr>";
					}
				}
				$key++;
			}
		}
		//보장내역
		if ($G_LIST){
			if($chk_lang=="E"){
				$G_TABLE ="<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;'>Certification of ".$row_ins["ins_name_en"]."</h5>
				<p style='margin:8px 0 5px 0;font-size:12px;font-weight:400;color:#333;'>
					Above subscriber is certified to insured of <span style='font-weight:400;color:red;'>".$row_ins["ins_name_en"].".</span><br/>
					Please refer to the KOREA ASSISTANCE provides services based on the following collateral and compensation limits.<br/><br/>
					Coverage: If mentioned, this coverage includes<br/>
					the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy<br/>
				</p>
				<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
				<colgroup><col width='50%' /><col width='*' /></colgroup>
				<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>
				<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>
				</tr></thead><tbody>";
			} else {
				$G_TABLE ="<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 32px;border-radius: 8px;margin:20px 0 20px 0;'>보험사 국문명 가입증명서</h5>
				<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
				<colgroup><col width='50%' /><col width='*' /></colgroup>
				<thead>	<tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장내역</th>
				<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장한도</th>
				</tr></thead><tbody>";
			}
			$G_TABLE .= $G_LIST."</tbody></table>";
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

        if($row_group){	//단체가입 여부에 따른 계약자 정보 처리 (어드민에서만 단체가입 시 고객ID를 표시함)
			if ($chk_lang!="E") {   //계약자명 처리
				$CONTRACTORROW = "<tr>
									<th width='145px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>계약자명</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$CONTRACTOR_KR."</td>
									<th width='115px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>고객번호</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$row["ins_user_num"]."</td>
								</tr>";
			} else {
				if($row_group["o_name_en"]) {
				$CONTRACTORROW = "<tr>
									<th width='145px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Name of Policy Holder</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$CONTRACTOR_EN."</td>
									<th width='115px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Client ID</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$row["ins_user_num"]."</td>
								</tr>";
				}
			}
		} else {// 단체가입이 아닌 경우 증권번호를 출력함
			if ($chk_lang!="E") {   //계약자명 처리
				$CONTRACTORROW = "<tr>
									<th width='145px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>계약자명</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$CONTRACTOR_KR."</td>
									<th width='115px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>증권번호</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$row_plan["stock_isdn"]."</td>
								</tr>";
			} else {
				if($row_cont_info["o_name_en"]) {
				$CONTRACTORROW = "<tr>
									<th width='145px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Name of Policy Holder</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$CONTRACTOR_EN."</td>
									<th width='115px' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Policy Number</th>
									<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>".$row_plan["stock_isdn"]."</td>
								</tr>";
				}
			}
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
		$chHtml	= str_replace("[CHK_SERVICE]",$CHK_SERVICE,$chHtml);   //서비스내역
		$chHtml	= str_replace("[G_LIST]",$G_TABLE,$chHtml);							//보험담보내용
		$chHtml	= str_replace("[INS_NAME]",$row_ins["subject"],$chHtml);	
		$chHtml	= str_replace("[CONTRACTOR_ROW]",$CONTRACTORROW,$chHtml);	//계약자명
		$chHtml	= str_replace("[INS_NAME_EN]",$row_ins["ins_name_en"],$chHtml);	//보험사 영문명
		if($chk_lang=="E"){
            if($row_ins["imgfile2"]){
			    $chHtml	= str_replace("[SIGNIMG]",$row_ins["imgfile2"],$chHtml); //사인이미지
            }
		} else {
            if($row_ins["signimg"]) {
			    $chHtml	= str_replace("[SIGNIMG]",$row_ins["signimg"],$chHtml); //사인이미지
            }
		}
		$chHtml	= str_replace("[DROOT]",$_SERVER["DOCUMENT_ROOT"],$chHtml);							//이미지를 위한 루트경로

        //dompdf 버전 문제로 인한 html 소스 공백 제거
        $html = preg_replace('/\r\n|\r|\n/','',$chHtml);
//		echo $html;

		$pdf_filename = "플라잉닥터스_가입증명서_".$language."_".$orderno."_"."".$name.".pdf";			//파일명처리
        //html 코드를 pdf로 변환 (변경하면 안됨)
        $dompdf = new \DOMPDF();
		$webRoot = $_SERVER["DOCUMENT_ROOT"];
        $dompdf->set_paper( 'A4', 'portrait' );
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream($pdf_filename); //저장되는 파일 이름을 설정한다.
		$output = $dompdf->output();
        error_reporting($error_level);
?>