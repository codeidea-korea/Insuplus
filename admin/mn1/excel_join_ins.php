<?

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
header("Content-Type: text/html; charset=UTF-8");
// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/KISA_SEED_CBC.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.Array.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.session.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.DB.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.global.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.plan.php";


if($_GET["mode"] == "excel" && getLen($ss_u_idx) > 0) {
	$dbcon = new dbcon;
	//	$sms_dbcon = new sms_dbcon;
	$dbcon -> dbcon_open(0);
	@mysqli_query("set names utf8");
	$SC_Rows = getSiteConfig();
	extract($SC_Rows);
	unset($SC_Rows);
	
	// DB별개 연결
	$dbconn = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_database_name) or die("데이터베이스 연결에 실패하였습니다.");
	$status = mysqli_select_db($dbconn,$mysql_database_name);
	if (!$status) {
		error("DB_CONNECT_ERROR");
		exit;
	}
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel/Classes/PHPExcel.php";
	$objPHPExcel = new PHPExcel();
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
	->setLastModifiedBy("Maarten Balliauw")
	->setTitle("Office 2007 XLSX Test Document")
	->setSubject("Office 2007 XLSX Test Document")
	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
	->setKeywords("office 2007 openxml php")
	->setCategory("Test result file");
	
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$bc_id = "charge";
	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$pr_name				= REQSTR($_GET["pr_name"], "");
	$ins_name				= REQSTR($_GET["ins_name"], "");
	$plan_name				= REQSTR($_GET["plan_name"], "");
	$chk_service				= REQSTR($_GET["chk_service"], "");
	$join_status				= REQSTR($_GET["join_status"], "");

	$search							= REQSTR($_GET["$earch"], "");
	$search_text					= REQSTR($_GET["search_text"], "");

	$search_orderby				= REQSTR($_GET["search_orderby"], "");
	$search_sort					= REQSTR($_GET["search_sort"], "");

	$search_date_txt					= REQSTR($_GET["search_date_txt"], "");

	if($search_date_txt == "A.writedate"){
		$search_date_txt = "tol_writedate";
	} else if($search_date_txt == "A.s_date"){
		$search_date_txt = "tol_s_date";
	} else if($search_date_txt == "A.e_date"){
		$search_date_txt = "tol_e_date";
	} else {
		$search_date_txt = "tolj_cancle_date";
	}
	$search_date_s				= REQSTR($_GET["search_date_s"], "");
	$search_date_e				= REQSTR($_GET["search_date_e"], "");


	if ( strlen($pr_name) > 0 ) $query_where .= " and a.tol_pr_name = '".$pr_name."' ";
	if ( strlen($ins_name) > 0 ) $query_where .= " and a.tol_ins_name = '".$ins_name."' ";
	if ( strlen($plan_name) > 0 ) $query_where .= " and a.tol_plan_name = '".$plan_name."' ";
	if ( strlen($chk_service) > 0 ) $query_where .= " and a.tol_chk_service = '".$chk_service."' ";
	if ( strlen($join_status) > 0 ) $query_where .= " and a.tolj_join_status = '".$join_status."' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and a.tolj_orderno in (select orderno from tbl_order_list_join where ".$search." ='".all_seed_enc($search_text)."' )  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date_txt." >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date_txt." <= '".$search_date_e." 23:59:59' ";

	$orderby = " ORDER BY tol_seq DESC ";
	$ArrListRs = array();
	$SQL = " SELECT COUNT(a.tol_seq)
	FROM (
		SELECT
			tol.seq AS tol_seq, 
			tol.writedate AS tol_writedate, 
			tol.join_ch AS tol_join_ch, 
			tol.ins_name AS tol_ins_name,
			tol.pr_name AS tol_pr_name, 
			tol.plan_name AS tol_plan_name, 
			tol.plan_cd AS tol_plan_cd,
			tolj.o_name AS tolj_o_name,
			tolj.o_isdn1 AS tolj_o_isdn1,
			tolj.o_isdn2 AS tolj_o_isdn2, 
			tolj.cancle_amount AS tolj_cancle_amount, 
			tol.s_date AS tol_s_date,
			tol.s_date_time AS tol_s_date_time, 
			tol.e_date AS tol_e_date, 
			tol.e_date_time AS tol_e_date_time,
			tol.ins_period AS tol_ins_period, 
			tol.chk_p AS tol_chk_p, 
			tol.join_nation_name AS tol_join_nation_name,
			tol.sale_gubun AS tol_sale_gubun,
			tol.cp_cd AS tol_cp_cd,
			tol.recommend_cd AS tol_recommend_cd,
			tol.o_email1 AS tol_o_email1, 
			tol.o_email2 AS tol_o_email2, 
			tolj.orderno AS tolj_orderno, 
			tolj.join_status AS tolj_join_status,
			tol.order_step AS tol_order_step, 
			tolj.cancle_date AS tolj_cancle_date, 
			tol.pg_isdn AS tol_pg_isdn,
			tol.orderno AS tol_orderno,
			tol.chk_service AS tol_chk_service,
			tol.join_cnt AS tol_join_cnt,
			tolj.join_amount AS tolj_join_amount,
			tolj.join_service AS tolj_join_service,
			tolj.vat_amount AS tolj_vat_amount,
			tolj.s_amount AS tolj_s_amount,
			tolj.t_amount AS tolj_t_amount,
			tolj.cancle_vat_amount AS tolj_cancle_vat_amount
		FROM  tbl_order_list tol INNER JOIN tbl_order_list_join tolj ON (tol.orderno=tolj.orderno)
		) a
	LEFT OUTER JOIN tbl_board_coupon_history tbch ON (a.tol_cp_cd=tbch.seq)
	LEFT OUTER JOIN tbl_board_event tbe ON (tbch.event_seq=tbe.seq)
	LEFT OUTER JOIN tbl_board_recommend_code tbrc ON (a.tol_recommend_cd=tbrc.seq)
	WHERE 1=1 ";
	$query_where .= " AND tol_ins_name ="." '".$_SESSION["ss_u_name"]."' ";
	$ArrListRs[0] = $dbcon -> getCount($SQL.$query_where.$orderby);
	

	$SQL = " SELECT a.*, tbe.coupon_name AS tbe_coupon_name, tbrc.recommendation_code AS tbrc_recom_name, tbp.partnership_name AS join_ch_name
	FROM (
		SELECT
			tol.seq AS tol_seq, 
			tol.writedate AS tol_writedate, 
			tol.join_ch AS tol_join_ch, 
			tol.ins_name AS tol_ins_name,
			tol.pr_name AS tol_pr_name, 
			tol.plan_name AS tol_plan_name, 
			tol.plan_cd AS tol_plan_cd,
			tolj.o_name AS tolj_o_name,
			tolj.o_isdn1 AS tolj_o_isdn1,
			tolj.o_isdn2 AS tolj_o_isdn2, 
			tolj.cancle_amount AS tolj_cancle_amount, 
			tol.s_date AS tol_s_date,
			tol.s_date_time AS tol_s_date_time, 
			tol.e_date AS tol_e_date, 
			tol.e_date_time AS tol_e_date_time,
			tol.ins_period AS tol_ins_period, 
			tol.chk_p AS tol_chk_p, 
			tol.join_nation_name AS tol_join_nation_name,
			tol.sale_gubun AS tol_sale_gubun,
			tol.cp_cd AS tol_cp_cd,
			tol.recommend_cd AS tol_recommend_cd,
			tol.o_email1 AS tol_o_email1, 
			tol.o_email2 AS tol_o_email2, 
			tolj.orderno AS tolj_orderno, 
			tolj.join_status AS tolj_join_status,
			tol.order_step AS tol_order_step, 
			tolj.cancle_date AS tolj_cancle_date, 
			tol.pg_isdn AS tol_pg_isdn,
			tol.orderno AS tol_orderno,
			tol.chk_service AS tol_chk_service,
			tol.join_cnt AS tol_join_cnt,
			tolj.join_amount AS tolj_join_amount,
			tolj.join_service AS tolj_join_service,
			tolj.vat_amount AS tolj_vat_amount,
			tolj.s_amount AS tolj_s_amount,
			tolj.t_amount AS tolj_t_amount,
			tolj.cancle_vat_amount AS tolj_cancle_vat_amount
		FROM  tbl_order_list tol
		INNER JOIN tbl_order_list_join tolj ON (tol.orderno=tolj.orderno)
		) a
	LEFT OUTER JOIN tbl_board_coupon_history tbch ON (a.tol_cp_cd=tbch.seq)
	LEFT OUTER JOIN tbl_board_event tbe ON (tbch.event_seq=tbe.seq)
	LEFT OUTER JOIN tbl_board_recommend_code tbrc ON (a.tol_recommend_cd=tbrc.seq)
	LEFT OUTER JOIN tbl_board_partner tbp ON (a.tol_join_ch= tbp.seq)
	WHERE 1=1 ";
	 
	$query_where .= " AND tol_ins_name ="." '".$_SESSION["ss_u_name"]."' ";
	$SQL .= $query_where.$orderby;
	
	$result = $dbcon -> query($SQL);
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', '결제일')
	->setCellValue('B1', '채널')
	->setCellValue('C1', '보험사')
	->setCellValue('D1', '상품명')
	->setCellValue('E1', '플랜명')
	->setCellValue('F1', '플랜코드')
	->setCellValue('G1', '이름')
	->setCellValue('H1', '주민번호')
	->setCellValue('I1', '나이')
	->setCellValue('J1', '생년월일')
	->setCellValue('K1', '성별')
	->setCellValue('L1', '보험 시작일')
	->setCellValue('M1', '보험 종료일')
	->setCellValue('N1', '보험기간')
	->setCellValue('O1', '출국 국가')
	->setCellValue('P1', '이메일')
	->setCellValue('Q1', '주문번호')
	->setCellValue('R1', 'TID')
	->setCellValue('S1', '가입상태')
	->setCellValue('T1', '결제상태')
	->setCellValue('U1', '취소일')
	->setCellValue('V1', '보험료')
	->setCellValue('W1', '할인금액')
	->setCellValue('X1', '결제금액')
	->setCellValue('Y1', '추천인코드')
	->setCellValue('Z1', '쿠폰코드');
	
	//셀 항목 스타일
	$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("F00000");;
	
	$p =1;
	if($ArrListRs[0] > 0) {
		while($ListRs = $dbcon -> fetch_array($result)) {
				$ListRs["tolj_o_name"] = all_seed_dec($ListRs["tolj_o_name"]);
				$ListRs["tolj_o_isdn1"] = all_seed_dec($ListRs["tolj_o_isdn1"]);
				$ListRs["tolj_o_isdn2"] = all_seed_dec($ListRs["tolj_o_isdn2"]);
				$ListRs["birth_date"] = substr($ListRs["tolj_o_isdn1"],2,6);
				$ListRs["tol_o_email1"] = all_seed_dec($ListRs["tol_o_email1"]);
				$ListRs["tol_o_email2"] = all_seed_dec($ListRs["tol_o_email2"]);
				
				$gender = substr($ListRs["tolj_o_isdn2"],0,1)%2;
				$gender_name = $gender == "1" ? "남":"여";
				
				$ListRs["charge_type"] = $charge_type_arr[$ListRs["charge_type"]];
				$ListRs["charge_status"] = $charge_status_arr[$ListRs["charge_status"]];

				if($ListRs["tol_chk_p"] == "Y") {
					$ListRs["tol_s_date"] = $ListRs["tol_s_date"]." ".$ListRs["tol_s_date_time"]."시";
					$ListRs["tol_e_date"] = $ListRs["tol_e_date"]." ".$ListRs["tol_e_date_time"]."시";
				} else if($ListRs["tol_chk_p"] == "N") {
					$ListRs["tol_s_date"] = $ListRs["tol_s_date"];
					$ListRs["tol_e_date"] = $ListRs["tol_e_date"];
				}

				$planCd = $ListRs["tol_plan_cd"];
				$age = fn_ins_age($ListRs["tolj_o_isdn1"]);
				$SQL2 = "SELECT plan_txt FROM tbl_board_plan_amount1 WHERE plan_cd ='".$planCd."' AND age = '".$age."' LIMIT 1";
				$resultPlanTxt = $dbcon -> query($SQL2);
				$strPlanTxt = mysqli_fetch_row($resultPlanTxt);
				
				extract($ListRs);
				unset($ListRs);
				$p++;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit("A{$p}", $tol_writedate, 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("B{$p}", $join_ch_name, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("C{$p}", $tol_ins_name,	 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("D{$p}", $tol_pr_name, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("E{$p}", $tol_plan_name, 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("F{$p}", $strPlanTxt[0], 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("G{$p}", $tolj_o_name, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("H{$p}", $birth_date."-".$tolj_o_isdn2, 							PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("I{$p}", fn_ins_age($tolj_o_isdn1),								PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("J{$p}", $tolj_o_isdn1, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("K{$p}", $gender_name, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("L{$p}", $tol_s_date,							 				PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("M{$p}", $tol_e_date,							 				PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("N{$p}", $tol_ins_period.$arr_chk_p_gubun[$tol_chk_p], 			PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("O{$p}", $tol_join_nation_name, 									PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("P{$p}", $tol_o_email1."@".$tol_o_email2, 						PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("Q{$p}", $tolj_orderno, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("R{$p}", $tol_pg_isdn, 											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("S{$p}", $arr_join_step[$tolj_join_status], 						PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("T{$p}", $arr_ord_step[$tol_order_step], 						PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("U{$p}", $tolj_cancle_date, 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("V{$p}", $tolj_join_amount+$tolj_join_service, 					PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("W{$p}", $tolj_s_amount, 										PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("X{$p}", $tolj_t_amount, 										PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("Y{$p}", $tbrc_recom_name, 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("Z{$p}", $tbe_coupon_name, 										PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("V{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("W{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("X{$p}")->getNumberFormat()->setFormatCode('#,##0');
		}
		
		unset($ListRs);
		unset($ArrListRs);
	}
	
	//$objPHPExcel->getActiveSheet()->setTitle('Simple');
	$objPHPExcel->setActiveSheetIndex(0);
	$file = "insuplus_가입자_".date("Ymd").".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$file.'');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	
	$dbcon -> dbcon_close();
}
exit;
?>