<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
	//include_once $_SERVER["DOCUMENT_ROOT"]."/_util/dompdf/dompdf_config.inc.php";
	require_once("./dompdf/dompdf_config.inc.php");
	
	$compare_seq = "";
	$PR_SEQ = $_POST["PR_SEQ"];
	$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보

	//단체가입 내역 조회
	$SQL = "select * from tbl_order_group_join_list where group_join_id = '".$_POST["GROUP_SEQ"]."'";
	$RS = $dbcon -> query($SQL);
	if (!$RS){
		echo "<script>alert('해당 단체 가입내역이 없습니다.');</script>";
		exit;
	}
	$row_group_info = $dbcon -> fetch_array($RS);

	$str = ["ins_plan_cd1", "ins_plan_cd2", "ins_plan_cd3"];
	$arr_compare = array();
	for($i=0; $i<3; $i++) {
		if($row_group_info[$str[$i]]) {
			if($compare_seq) $compare_seq .= ",";
			$compare_seq .= $row_group_info[$str[$i]];
			$arr_compare[] = $row_group_info[$str[$i]];
		}
	}
	$title = $PR_INFO["subject"];
	$name = all_seed_dec($row_group_info["o_name"]);
	$phone = all_seed_dec($row_group_info["o_phone"]);
	$email = all_seed_dec($row_group_info["o_email"]);
	$file_path = $row_group_info["listfile"];
	$arr_plan_amount = [$row_group_info["plan_total_ins_amount1"], $row_group_info["plan_total_ins_amount2"], $row_group_info["plan_total_ins_amount3"]];
	$group_join_cnt = $row_group_info["group_join_cnt"];
	$GROUP_INFO_TABLE["chk_p"] = $chk_p;
	$purpose = $row_group_info["purpose"];

	$originalDate = $row_group_info["regdate"];
	//original date is in format YYYY-mm-dd
	$timestamp = strtotime($originalDate); 
	$regdate = date("Y년 m월 d일", $timestamp );

	if($row_group_info["group_join_type"] == "B2C"){
		$group_join_type = "B2C";
		$birthdate = $row_group_info["birthdate"];
	} else {
		$group_join_type = "B2B";
		$biz_num = $row_group_info["biz_num"];
		$issue_num = $row_group_info["issue_num"];
	}
	$amount = [$row_group_info["plan_total_ins_amount1"], $row_group_info["plan_total_ins_amount2"], $row_group_info["plan_total_ins_amount3"]];

	//선택 플랜 리스트
	$arr_plan_list = selGroupPlanList($PR_SEQ, $compare_seq, $chk_p);

	//상품에 대한 인슈플러스 항목 리스트
	if($PR_INFO["ext5"]) {
		$arr_insuplus = selInsuplusList($PR_INFO["ext5"]);
	}

	//상품에 대한 보장내역 검색
    if($PR_INFO["ext4"]){
	    $arr_guarantee = selGuaranteeList($PR_INFO["ext4"]);
    }

	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
	include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
	if ($row_group_info["listfile"]){
		//파일 타입 설정 (확자자에 따른 구분)
		$inputFileType = 'Excel2007';
		if($file_info[1] == "xls") {
			$inputFileType = 'Excel5';
		}
		//==================================================================
		//  PHPEXCEL 용 로더
		//==================================================================
		//엑셀리더 초기화
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
		$objReader->setReadDataOnly(true);
		//범위 지정(위에 작성한 범위필터 적용)
		//	$objReader->setReadFilter($filterSubset);
		//업로드된 엑셀 파일 읽기
		//	$objPHPExcel = $objReader->load($url);
		$objPHPExcel = PHPExcel_IOFactory::load($file_path);
		//첫번째 시트로 고정
		$objPHPExcel->setActiveSheetIndex(0);
		//고정된 시트 로드
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$cellIterator = $objWorksheet->getRowIterator()->current()->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells( true );
		$maxRow = $objWorksheet->getHighestRow();
		$GROUP_INFO_TABLE = array();
		$PLAN_DATA = array();
		
		for($i=2;$i<=$maxRow;$i++) {
            $nullCheck = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
            if($nullCheck != '' && strlen($nullCheck) > 1) {
                $GROUP_INFO_TABLE["birth"][$i-2] = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                $GROUP_INFO_TABLE["gender"][$i-2] = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
                $GROUP_INFO_TABLE["s_date"][$i-2] = str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue());
                $GROUP_INFO_TABLE["e_date"][$i-2] = str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue());
                $GROUP_INFO_TABLE["age"][$i-2] = fn_ins_age(date("Y-m-d",strtotime($GROUP_INFO_TABLE["birth"][$i-2]))); //보험나이
                if($chk_p == "Y") { //단기
                    $t_s_date = $GROUP_INFO_TABLE["s_date"][$i-2];
                    $t_e_date = $GROUP_INFO_TABLE["e_date"][$i-2];
                    $arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p);
                    if($arr_period["day"] > 90) {
                        alert_page("가입기간은 90일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                        exit;
                    }
                    $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                    $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
                } else { //장기
                    $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"][$i-2]);
                    $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"][$i-2]);
                    $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                    if($arr_period["day"] > 365) {
                        alert_page("가입기간은 365일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                        exit;
                    }
                    $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                    $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
                }
            }
		}
		$group_amount_list = getGroupAmountList($arr_compare, $GROUP_INFO_TABLE);
	}

	$sdate = $GROUP_INFO_TABLE["s_date"];
	$edate =  $GROUP_INFO_TABLE["e_date"];
	
	function dn_date_sort($a, $b) {
		return strtotime($a) -strtotime($b);
	}
	function up_date_sort($a, $b) {
		return strtotime($b) -strtotime($a);
	}

	usort($sdate, "dn_date_sort");
	usort($edate, "up_date_sort");

	$period = $sdate[0].":00"." ~ ".$edate[0].":00"; //전체 가입 기간


	$url = "./pdf_group_join_certification_table.php";
	$data = array(
	'seq' => $_POST["GROUP_SEQ"]			// 가입순번
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

	$key = 0;
	$insListHead = "";
	$insListHead .= "<thead><tr>
    <th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">번호</th>
	<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">생년월일</th>
	<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">성별</th>
	<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center; width: 100px;\">게시일</th>
	<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center; width: 100px;\">종료일</th>";
	foreach($arr_plan_list as $plan_info){
		$insListHead .= "<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center; width: 30px;\">".print_ins($plan_info["ins_cd"])." ".$Arr_plan_cd[$plan_info["plan_cd"]]."</th>";
	}
	$insListHead .= "</tr></thead>";
    
	$insListbody = "";
    $insListbody .= "<tbody>";
	if(count($arr_insuplus) > 0 ) {
		
		for($i=0; $i < count($GROUP_INFO_TABLE["birth"]);$i++){
			$insListbody .= "<tr>";
			$insListbody .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">".($i+1)."</td>";							
			$insListbody .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">".$GROUP_INFO_TABLE["birth"][$i]."</td>";
			$insListbody .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">".$GROUP_INFO_TABLE["gender"][$i]."</td>";
			$insListbody .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">".$GROUP_INFO_TABLE["s_date"][$i].":00</td>";
			$insListbody .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: center;\">".$GROUP_INFO_TABLE["e_date"][$i].":00</td>";
			foreach($group_amount_list[$i] as $person_amount){
				$insListbody .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 3px; text-align: right; width: 30px;\">".$person_amount."원</td>";
			}
			$insListbody .= "</tr>";
		}
	}
	$insListbody .= "</tbody>";

	$totalins = "";
    $totalins .= "<thead><tr>
	<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: center;\"> - </th>
	";
	foreach($arr_plan_list as $plan_info){
		$totalins .= "<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: center;\">".print_ins($plan_info["ins_cd"]).$Arr_plan_cd[$plan_info["plan_cd"]]."</th>";
	}
	$totalins .= "</tr></thead>";
	if(count($arr_insuplus) > 0 ) {
		$totalins .= '<tr><td style="background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: center;">상품가격</td>';
		foreach($arr_plan_amount as $plan_amount){
            if($plan_amount){
                $totalins .= '<td style="background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: right;">'.$plan_amount.'원</td>';
            }
		}
		$totalins .= '</tr>';
	}
	$totalins .= "</table>";

	$guarantelist = '';
	$guarantelist .= "<span style=\"font-size: 16px;\">3. 보장내역</span>
				<table width=\"100%\" cellspacing='0' cellpadding='0' style=\"-webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; border-bottom: solid 2px #595959; border-top: solid 2px #595959; color: #000; margin: 5px; width: 100%;\">
				<thead><tr><th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: center;\">보장항목</th>";
	foreach($arr_plan_list as $plan_info){
		$guarantelist .= "<th style=\"background-color: #f6f6f6; border-bottom: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: center;\">".print_ins($plan_info["ins_cd"]).$Arr_plan_cd[$plan_info["plan_cd"]]."</th>";
	} 
	$guarantelist .= "</tr></thead><tbody style=\"text-align:center;\">";
	if(count($arr_insuplus) > 0 ) {
		foreach($arr_guarantee as $guarantee){
			$guarantelist .= "<tr><td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: left;\">".$guarantee["service_name"]."</td>";
				for($k=0;$k<count($arr_plan_list);$k++){
					$guarantelist .= "<td style=\"background-color: #ffffff; border-bottom: 1px solid #d6d6d6; border-left: 1px solid #d6d6d6; font-size: 14px; font-weight: 400; padding: 5px 15px; text-align: left;\">".fn_plan_gua($arr_plan_list[$k]["seq"],$guarantee["idx"])."</td>";
				}
				$guarantelist .= "</tr>";
		}
	}
	$guarantelist .= "</tbody></table>";

	$chHtml	= str_replace("[NAME]",$name,$chHtml);						//대표자명
	$chHtml	= str_replace("[PHONE]",$phone,$chHtml);					//연락처
	$chHtml	= str_replace("[EMAIL]",$email,$chHtml);					//이메일
	$chHtml	= str_replace("[BIRTHDATE]",$birthdate,$chHtml);			//생년월일
	$chHtml	= str_replace("[GROUPJOINCNT]",$group_join_cnt,$chHtml);	//가입인원
	$chHtml	= str_replace("[TITLE]",$title,$chHtml);	//상품명
	$chHtml	= str_replace("[PORPOSE]",$purpose,$chHtml);	//출국목적
	$chHtml	= str_replace("[PERIOD]",$period,$chHtml);	//가입기간
	$chHtml	= str_replace("[REGDATE]",$regdate,$chHtml);	//가입일
	$chHtml	= str_replace("[INSLISTHEAD]",$insListHead,$chHtml);	//상품가격견적
	$chHtml	= str_replace("[INSLISTBODY]",$insListbody,$chHtml);	//상품가격견적
	$chHtml	= str_replace("[TOTALINS]",$totalins,$chHtml);	//상품가격
	$chHtml	= str_replace("[GUARANTELIST]",$guarantelist,$chHtml);	//보장내역
	$chHtml	= str_replace("[DROOT]",$_SERVER["DOCUMENT_ROOT"],$chHtml);							//이미지를 위한 루트경로
	
	//dompdf 버전 문제로 인한 html 소스 공백 제거	
	$html = preg_replace('/>\s+</', '><', $chHtml);
	//echo $html; exit;
	if($action == 'show') {
        echo trim($html);exit;
    }
	$pdf_filename = "인슈플러스견적서_".$name.".pdf";			//파일명처리
	//html 코드를 pdf로 변환 (변경하면 안됨)
	$dompdf = new \DOMPDF();
	$webRoot = $_SERVER["DOCUMENT_ROOT"];
	//$dompdf->set_paper( 'A4', 'landscape' );
	$dompdf->set_paper( 'A4', 'portrait' );
	//$dompdf->set_paper( 'A3', 'landscape' );
	$dompdf->load_html($html);
	$dompdf->render();
	$dompdf->stream($pdf_filename); //저장되는 파일 이름을 설정한다.
	$output = $dompdf->output();
	error_reporting($error_level);
?>
